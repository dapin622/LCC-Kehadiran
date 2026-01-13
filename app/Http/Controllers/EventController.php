<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Event;
use App\Models\School;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $schools = School::all();
        $teams   = Team::all();
        $events = Event::with('school', 'team')->get();
        
        return view('admin.event', compact('events', 'schools', 'teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'school_id' => 'required|exists:schools,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'team_id.unique' => 'Nama tim sudah ada.', 
        ]);

        $event = Event::create([
            'team_id'    => $request->team_id,
            'school_id'  => $request->school_id,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,

            'attendance_token' => $request->attendance_token,
            'attendance_start' => $request->attendance_start,
            'attendance_end' => $request->attendance_end,
            'is_attendance_active' => $request->has('is_attendance_active'),
        ]);

        $event->load(['team', 'school']);

        return response()->json([
            'success' => true,
            'event' => [
                'id' => $event->id,
                'team_id' => $event->team_id,
                'school_id' => $event->school_id,
                'team' => $event->team,
                'school' => $event->school,
                'start_date' => $event->start_date->format('d-m-Y H:i'),
                'end_date'   => $event->end_date->format('d-m-Y H:i'),

                'start_date_input' => $event->start_date->format('Y-m-d\TH:i'),
                'end_date_input'   => $event->end_date->format('Y-m-d\TH:i'),

                'attendance_token' => $event->attendance_token,
                'attendance_start' => optional($event->attendance_start)->format('Y-m-d H:i'),
                'attendance_end' => optional($event->attendance_end)->format('Y-m-d H:i'),
                'is_attendance_active' => $event->is_attendance_active,

                'attendance_start_input' => optional($event->attendance_start)->format('Y-m-d\TH:i'),
                'attendance_end_input' => optional($event->attendance_end)->format('Y-m-d\TH:i'),
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'school_id' => 'required|exists:schools,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $event = Event::findOrFail($id);
        $event->update([
            'team_id'    => $request->team_id,
            'school_id'  => $request->school_id,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,

            'attendance_token' => $request->attendance_token,
            'attendance_start' => $request->attendance_start,
            'attendance_end' => $request->attendance_end,
            'is_attendance_active' => $request->has('is_attendance_active'),

        ]);
        $event->load(['team', 'school']);

        return response()->json([
            'success' => true,
            'event' => [
                'id' => $event->id,
                'team_id' => $event->team_id,
                'school_id' => $event->school_id,
                'team' => $event->team,
                'school' => $event->school,
                'start_date' => $event->start_date->format('d-m-Y H:i'),
                'end_date'   => $event->end_date->format('d-m-Y H:i'),

                'start_date_input' => $event->start_date->format('Y-m-d\TH:i'),
                'end_date_input'   => $event->end_date->format('Y-m-d\TH:i'),

                'attendance_token' => $event->attendance_token,
                'attendance_start' => optional($event->attendance_start)->format('Y-m-d H:i'),
                'attendance_end' => optional($event->attendance_end)->format('Y-m-d H:i'),
                'is_attendance_active' => $event->is_attendance_active,

                'attendance_start_input' => optional($event->attendance_start)->format('Y-m-d\TH:i'),
                'attendance_end_input' => optional($event->attendance_end)->format('Y-m-d\TH:i'),
            ]
        ]);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return redirect()->route('admin.event')->with('success', 'Event berhasil dihapus');
    }

}
