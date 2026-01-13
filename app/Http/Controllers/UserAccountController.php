<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Models\School;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserAccountController extends Controller
{
    public function index()
    {
        $membersForAdd = Member::doesntHave('user')->get();
        $membersForEdit = Member::all();
        $users   = User::with('member.school')->get();
        $schools = School::all();

        return view('admin.user_account', compact('membersForAdd','membersForEdit', 'users', 'schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id|unique:users,member_id',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
        ]);

        $member = Member::findOrFail($request->member_id);

        $user = User::create([
            'member_id' => $member->id,
            'name'      => $member->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'user',
        ]);

        return response()->json([
            'user' => $user->load('member.school')
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'member_id' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6'
        ]);

        $member = Member::findOrFail($request->member_id);

        $user->member_id = $member->id;
        $user->name      = $member->name;
        $user->email     = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'user' => $user->load('member.school')
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
            
       if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus!'
        ]);
        }
        return redirect()->route('admin.user_account')->with('success', 'Data berhasil dihapus');
    }
}

