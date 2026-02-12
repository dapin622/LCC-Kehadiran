<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->member_id) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Data member tidak ditemukan.');
        }

        $member = Member::find($user->member_id);
        if (!$member) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Data member tidak valid.');
        }

        $events = Event::where('team_id', $member->team_id)
            ->where('school_id', $member->school_id)
            ->with([
                'team',
                'school',
                'participants' => function ($q) use ($member) {
                    $q->where('member_id', $member->id);
                }
            ])
            ->orderBy('start_date', 'desc')
            ->get();

        // Auto-create participant jika belum ada
        foreach ($events as $event) {
            if ($event->participants->isEmpty()) {
                EventParticipant::create([
                    'event_id' => $event->id,
                    'member_id' => $member->id,
                    'status' => 'tidak_hadir', 
                    'attended_at' => null,
                ]);
                
                // Reload participants
                $event->load(['participants' => function ($q) use ($member) {
                    $q->where('member_id', $member->id);
                }]);
            }
        }

        return view('user.absensi', compact('events', 'member'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'token'    => 'required',
            'status'   => 'nullable|in:izin',
        ]);

        $user = Auth::user();
        $member = Member::find($user->member_id);

        if (!$member) {
            return back()->with('error', 'Data member tidak ditemukan.');
        }

        $event = Event::findOrFail($request->event_id);

        // Cek apakah absensi sudah ditutup oleh admin
         if (!$event->is_attendance_active) {
        return response()->json([
            'success' => false,
            'message' => 'Absensi sudah ditutup.'
        ]);
        }

        if ($request->token !== $event->attendance_token) {
            return response()->json([
                'success' => false,
                'message' => 'Token absensi salah.'
            ]);
        }

        // Cari atau buat participant
        $participant = EventParticipant::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if (!$participant) {
            $participant = EventParticipant::create([
                'event_id' => $event->id,
                'member_id' => $member->id,
                'status' => 'tidak_hadir',
                'attended_at' => null,
            ]);
        }

        if ($participant->attended_at) {
            return back()->with('error', 'Anda sudah melakukan absensi.');
        }

        
        // Jika user memilih status "izin"
        if ($request->status === 'izin') {
            $participant->update([
                'status' => 'izin',
                'attended_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status izin berhasil dicatat.'
            ]);
        }

        $now = now();
        
        if ($event->attendance_end && $now->gt($event->attendance_end)) {
            $participant->update([
                'status' => 'terlambat',
                'attended_at' => now(),
            ]);

             return response()->json([
                'success' => true,
                'message' => 'Absensi tercatat sebagai terlambat.'
            ]);
        }

        $participant->update([
            'status' => 'hadir',
            'attended_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil, Anda hadir tepat waktu.'
        ]);
    }

    public function detail($eventId)
    {
        $user = Auth::user();
        $member = Member::find($user->member_id);

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Data member tidak ditemukan'
            ], 404);
        }

        $event = Event::findOrFail($eventId);

        // Cek apakah event ini untuk team dan school member
        if ($event->team_id !== $member->team_id || $event->school_id !== $member->school_id) {
            return response()->json([
                'success' => false,
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        $participant = EventParticipant::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        // Tentukan status berdasarkan kondisi
        $status = 'tidak_hadir';
        $attendedTime = null;
        $attendedRaw = null;

        if ($participant && $participant->attended_at) {

            $attendedTime = $participant->attended_at->format('H:i');
            $attendedRaw = $participant->attended_at->toIso8601String();

            if ($participant->status === 'izin') {
                $status = 'izin';
            } 
            else {
                if ($event->attendance_end && $participant->attended_at->gt($event->attendance_end)) {
                    $status = 'terlambat';
                } else {
                    $status = 'hadir';
                }
            }

        } else {

            if ($event->is_attendance_active) {
                $status = 'belum_absen';
            } else {
                $status = 'tidak_hadir';
            }
        }

        $data = [
            'event_name' => $event->team->name ?? 'Event',
            'start_time' => $event->start_date ? $event->start_date->format('d-m-Y H:i') : '-',
            'end_time' => $event->end_date ? $event->end_date->format('d-m-Y H:i') : '-',
            'token' => $event->attendance_token ?? '-',
            'status' => $status,
            'attended_time' => $attendedTime,
            'attended_time_raw' => $attendedRaw,
            'attendance_end_raw' => optional($event->attendance_end)->toIso8601String(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}