<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\School;
use App\Models\EventParticipant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total anggota yang SUDAH ABSEN (attended_at tidak null)
        $total = EventParticipant::whereNotNull('attended_at')
            ->distinct('member_id')
            ->count('member_id');

        // Hitung anggota hadir (status hadir ATAU terlambat, sudah absen)
        $hadir = EventParticipant::whereIn('status', ['hadir', 'terlambat'])
            ->whereNotNull('attended_at')
            ->distinct('member_id')
            ->count('member_id');

        // Hitung anggota tidak hadir (status tidak_hadir DAN sudah tercatat di attended_at)
        $tidakHadir = EventParticipant::where('status', 'tidak_hadir')
            ->whereNotNull('attended_at')
            ->distinct('member_id')
            ->count('member_id');

        // Ambil data per sekolah
        $schools = School::withCount([
            'members' => function ($query) {
                // Hanya hitung members yang sudah pernah absen
                $query->whereHas('participants', function($q) {
                    $q->whereNotNull('attended_at');
                });
            }
        ])->get();

        foreach ($schools as $school) {

            $memberIds = Member::where('school_id', $school->id)
                ->whereHas('participants', function($q) {
                    $q->whereNotNull('attended_at');
                })
                ->pluck('id');

            $school->hadir_count = EventParticipant::whereIn('member_id', $memberIds)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->whereNotNull('attended_at')
                ->distinct('member_id')
                ->count('member_id');

            $school->tidak_hadir_count = EventParticipant::whereIn('member_id', $memberIds)
                ->where('status', 'tidak_hadir')
                ->whereNotNull('attended_at')
                ->distinct('member_id')
                ->count('member_id');
        }

        return view('admin.dashboard', compact('total', 'hadir', 'tidakHadir', 'schools'));
    }

    public function getMemberData()
    {
        // Ambil member IDs yang sudah pernah absen (attended_at tidak null)
        $attendedMemberIds = EventParticipant::whereNotNull('attended_at')
            ->distinct()
            ->pluck('member_id')
            ->toArray();

        // Ambil data member berdasarkan IDs yang sudah absen
        $members = Member::with(['school', 'class'])
            ->whereIn('id', $attendedMemberIds)
            ->get()
            ->map(function ($member) {
                return [
                    'name' => $member->name ?? '-',
                    'school' => [
                        'name' => $member->school->name ?? '-',
                        'region' => $member->school->region ?? '-'
                    ],
                    'class' => [
                        'name' => $member->class->name ?? '-'
                    ]
                ];
            });

        return response()->json(['data' => $members]);
    } 
}