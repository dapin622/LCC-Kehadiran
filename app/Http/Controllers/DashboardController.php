<?php

namespace App\Http\Controllers;

use App\Models\Member;

use App\Models\School;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getMembers()
    {
        $members = Member::with('school','class')->get();
        return response()->json(['data' => $members]);
    }

    public function index()
    {
    $total = Member::count();
    
    $hadir = $total;        
    $tidakHadir = 0;
    $schools = School::withCount('members')->get();

    return view('admin.dashboard', compact('schools','total','hadir','tidakHadir'));
    }
}