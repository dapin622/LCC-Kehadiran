<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return view('user.dashboard', [
                'member' => null,
                'totalAbsensi' => 0,
                'totalHadir' => 0,
                'totalTerlambat' => 0,
                'totalIzin' => 0,
                'error' => 'Data member tidak ditemukan'
            ]);
        }

        // Hitung statistik absensi - HANYA yang sudah absen (attended_at tidak null)
        $totalAbsensi = EventParticipant::where('member_id', $member->id)
            ->whereNotNull('attended_at') // PENTING: Hanya hitung yang sudah absen
            ->count();
            
        $totalHadir = EventParticipant::where('member_id', $member->id)
            ->where('status', 'hadir')
            ->whereNotNull('attended_at') // Tambahkan juga di sini untuk konsistensi
            ->count();
            
        $totalTerlambat = EventParticipant::where('member_id', $member->id)
            ->where('status', 'terlambat')
            ->whereNotNull('attended_at')
            ->count();
            
        $totalIzin = EventParticipant::where('member_id', $member->id)
            ->where('status', 'izin')
            ->whereNotNull('attended_at')
            ->count();

        return view('user.dashboard', [
            'member' => $member,
            'totalAbsensi' => $totalAbsensi,
            'totalHadir' => $totalHadir,
            'totalTerlambat' => $totalTerlambat,
            'totalIzin' => $totalIzin
        ]);
    }
}