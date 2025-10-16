<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Member;

class DashboardController extends Controller
{
    public function getMembers()
    {
        $members = Member::with('school')->get();
        return response()->json(['data' => $members]);
    }

    public function index()
    {
        return view('admin.dashboard'); 
    }
}
