<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRoom;

class ClassRoomController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::all();
        return view('admin.member.class', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:class_rooms,name',
        ], [
            'name.required' => 'Nama kelas wajib diisi.',
            'name.unique' => 'Nama kelas sudah ada.',
        ]);

        $class = ClassRoom::create([
            'name' => strtoupper($request->name),
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'class' => $class
            ]);
        }

        return redirect()->route('admin.member.class')->with('success', 'kelas berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $class = ClassRoom::findOrFail($id);
        $request->validate([
            'name' => 'required|string|unique:class_rooms,name,' . $id,
        ], [
            'name.required' => 'Nama kelas wajib diisi.',
            'name.unique' => 'Nama kelas sudah ada.',
        ]);

        $class->update([
            'name' => strtoupper($request->name),
        ]);
        
        if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'class' => $class
                    ]);
                }     

        return redirect()->route('admin.member.class')->with('success', 'kelas berhasil diperbarui!');
    }

    public function destroy(Request $request,$id)
    {
        $class = ClassRoom::findOrFail($id);
        $class->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil dihapus!'
            ]);
        }

        return redirect()->route('admin.member.class')->with('success', 'kelas berhasil dihapus!');
    }
}
