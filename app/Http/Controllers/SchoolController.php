<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use App\Exports\SchoolExport;
use Maatwebsite\Excel\Facades\Excel;

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
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:5048',
        ], [
            'name.unique' => 'Nama sekolah sudah ada.', 
            'name.required' => 'Nama sekolah wajib diisi.',
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time().'_'.$file->getClientOriginalName(); 
            $file->move(public_path('uploads/foto'), $filename); 
            $photoPath = $filename; 
        }


        $school = School::create([   
        'name'     => strtoupper($request->name),
        'region'   => strtoupper($request->region),
        'province' => strtoupper($request->province),
        'photo'    => $photoPath,
        ]);
        
        if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'school'  => $school,
            'photo_url' => $photoPath ? asset('uploads/foto/'.$photoPath) : null
        ]);
        }
        
        return redirect()->route('admin.sekolah')->with('success', 'Sekolah berhasil ditambahkan');
    }

        public function update(Request $request, $id)
    {
        $school = School::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:schools,name,'.$id,
            'region' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:5048',
        ]);

        $school->update([
            'name' => strtoupper($request->name),
            'region' => strtoupper($request->region),
            'province' => strtoupper($request->province),
        ]);

        if ($request->hasFile('photo')) {
            if ($school->photo && file_exists(public_path('uploads/foto/'.$school->photo))) {
                unlink(public_path('uploads/foto/'.$school->photo));
            }

            $file = $request->file('photo');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/foto'), $filename);
            $school->photo = $filename;
            $school->save();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'school' => $school,
                'photo_url' => $school->photo ? asset('uploads/foto/'.$school->photo) : null
            ]);
        }

        return redirect()->route('admin.sekolah')->with('success', 'Sekolah berhasil diperbarui!');
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

        if ($school->photo) {
                $photoPath = public_path('uploads/foto/' . $school->photo);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
        }
        
        if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Sekolah berhasil dihapus!'
        ]);
    }
        return redirect()->route('admin.sekolah')->with('success', 'Sekolah berhasil dihapus');
    }

    public function exportExcel()
    {
        return Excel::download(new SchoolExport, 'data-sekolah.xlsx');
    }

  
}
