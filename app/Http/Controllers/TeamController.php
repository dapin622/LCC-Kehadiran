<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:teams,name'
        ], [
            'name.required' => 'Nama tim wajib diisi.',
            'name.unique' => 'Nama tim sudah ada.',
        ]);

        $team = Team::create([
            'name' => strtoupper($request->name)
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'team' => $team
            ]);
        }

        return redirect()->route('admin.event')->with('success', 'Tim berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        $oldName = $team->name;

        $request->validate([
            'name' => 'required|unique:teams,name,' . $id
        ]);

        $team->update([
            'name' => strtoupper($request->name)
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'team' => $team,
                'old_name' => $oldName
            ]);
        }

        return redirect()->route('admin.event')->with('success', 'Tim berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        $team->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tim berhasil dihapus'
            ]);
        }

        return redirect()->route('admin.event')->with('success', 'Tim berhasil dihapus!');
    }
}
