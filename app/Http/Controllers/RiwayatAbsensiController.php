<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatAbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return view('user.riwayat', [
                'member' => null,
                'attendances' => collect(),
                'error' => 'Data member tidak ditemukan'
            ]);
        }

        // Ambil HANYA riwayat yang sudah absen (attended_at tidak null)
        $attendances = EventParticipant::with(['event.team', 'event.school'])
            ->where('member_id', $member->id)
            ->whereNotNull('attended_at') // Hanya yang sudah absen
            ->orderBy('attended_at', 'desc')
            ->paginate(10);

        return view('user.riwayat', [
            'member' => $member,
            'attendances' => $attendances
        ]);
    }
}