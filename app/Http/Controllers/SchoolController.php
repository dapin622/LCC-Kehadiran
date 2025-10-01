<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::all();
        $regions = School::select('region')->distinct()->pluck('region');
        $provinces = School::select('province')->distinct()->pluck('province');
        return view('admin.sekolah', compact('schools','regions','provinces'));

    }

    public function create()
    {
        return view('admin.sekolah');
    }

  public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:schools,name',
            'region' => 'required|string|max:255',
            'province' => 'required|string|max:255',
        ], [
            'name.unique' => 'Nama sekolah sudah ada.', 
            'name.required' => 'Nama sekolah wajib diisi.',
        ]);

        $school = School::create([   
        'name'     => strtoupper($request->name),
        'region'   => strtoupper($request->region),
        'province' => strtoupper($request->province),
        ]);
        
            if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'school'  => $school
            ]);
        }
        
        return redirect()->route('admin.sekolah')->with('success', 'Sekolah berhasil ditambahkan');
    }

    public function filter(Request $request)
    {
        $query = School::query();

        if ($request->region) {
            $query->where('region', $request->region);
        }
        if ($request->province) {
            $query->where('province', $request->province);
        }

        return response()->json($query->get());
    }


    public function destroy(Request $request, $id)
    {
        $school = School::findOrFail($id);
        $school->delete();

        if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Sekolah berhasil dihapus!'
        ]);
    }
        return redirect()->route('admin.sekolah')->with('success', 'Sekolah berhasil dihapus');
    }
  
}
