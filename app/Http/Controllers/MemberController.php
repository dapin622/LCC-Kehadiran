<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\School;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with('school')->get();
        $schools = School::all();
        $teams = Member::select('team_name')->distinct()->pluck('team_name'); 
        return view('admin.member', compact('members', 'schools', 'teams'));    
    }
    
    // public function create()
    // {
    //     $members = Member::with('school')->get();
    //     $schools = School::all();
    //     $teams = Member::select('team_name')->distinct()->pluck('team_name'); 
    //     return view('admin.member', compact('members', 'schools', 'teams'));
    // }

    public function store(Request $request)
    {
       $request->validate([
        'name' => 'required|string|max:255',
        'nisn' => 'required|string|max:20|unique:members',
        'gender' => 'required',
        'school_id' => 'required|exists:schools,id',
        'team' => 'required|string|max:100',
        'class_name' => 'required|string|max:50',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ],[
            'nisn.unique' => 'NISN sudah ada.', 
            'nisn.required' => 'NISN wajib diisi.',
    ]);

        $member = new Member();
        $member->name = $request->name;
        $member->nisn = $request->nisn;
        $member->gender = $request->gender;
        $member->school_id = $request->school_id;
        $member->team_name = $request->team;
        $member->class_name = $request->class_name;
        $member->qr_code = $request->qr_code ?? Str::uuid();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/foto'), $filename);
            $member->photo = $filename;
        }

        $member->save();

        $qrCodeSvg = (string) QrCode::size(50)->generate($member->qr_code);
        // $qrCodePng = base64_encode(QrCode::format('png')->size(100)->generate($member->qr_code));

        if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'nisn' => $member->nisn,      
                'gender' => $member->gender,
                'school_id'   => $member->school_id,
                'school_name' => $member->school->name ?? '-',
                'region' => $member->school->region ?? '-',
                'team_name' => $member->team_name,
                'class_name' => $member->class_name,
                'photo' => $member->photo,
                'qr_code' => $member->qr_code,
                'qr_code_svg' => $qrCodeSvg,
            ],
        ]);
    }

        return redirect()->route('admin.member')->with('success', 'Member berhasil ditambahkan!');

    }   

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:members,nisn,'.$id,
            'gender' => 'required',
            'school_id' => 'required|exists:schools,id',
            'team' => 'required|string|max:100',
            'class_name' => 'required|string|max:50',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ],[
            'nisn.unique' => 'NISN sudah ada.', 
            'nisn.required' => 'NISN wajib diisi.',
        ]);

        $member->update([
            'name' => $request->name,
            'nisn' => $request->nisn,
            'gender' => $request->gender,
            'school_id' => $request->school_id,
            'team_name' => $request->team,
            'class_name' => $request->class_name,
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/foto'), $filename);
            $member->photo = $filename;
            $member->save();
        }

        $qrCodeSvg = (string) QrCode::size(50)->generate($member->qr_code);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'member' => [
                    'id' => $member->id,
                    'name' => $member->name,
                    'nisn' => $member->nisn,      
                    'gender' => $member->gender,
                    'school_id'   => $member->school_id,           
                    'school_name' => $member->school->name ?? '-',
                    'region' => $member->school->region ?? '-',
                    'team_name' => $member->team_name,
                    'class_name' => $member->class_name,
                    'photo' => $member->photo,
                    'qr_code' => $member->qr_code,
                    'qr_code_svg' => $qrCodeSvg,
                ],
            ]);
        }

        return redirect()->route('admin.member')->with('success', 'Siswa berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
            $member = Member::findOrFail($id);
            $member->delete();

            if ($member->photo && file_exists(public_path('uploads/foto/'.$member->photo))) {
                unlink(public_path('uploads/foto/'.$member->photo));
            }
            if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil dihapus!'
            ]);
        }
            return redirect()->route('admin.member')->with('success', 'Siswa berhasil dihapus');
    }

}
