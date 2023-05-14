<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Programme;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    public function showProgrammes()
    {
        $title = 'Programmes';
        $role = 'Admin';
        $page = 'programmes';
        $programmes = Programme::with('departments')->get();
        $departments = Department::all();
        return view('NiceAdmin.admin.programmes', compact('title', 'role', 'page', 'programmes', 'departments'));
    }

    public function storeProgramme(Request $request)
    {
        $inputs = $request->validate([
            'code' => 'required|string|unique:programmes,program_code',
            'name' => 'required|string|unique:programmes,program_name',
            'dept' => 'required|numeric'
        ]);

        $programme = Programme::create([
            'program_code' => $inputs['code'],
            'program_name' => $inputs['name'],
            'dept_id' => $inputs['dept']
        ]);

        if (!$programme) {
            return redirect()->back()->withInput()->with('errror', 'Failed to store programme');
        }

        return redirect()->back()->with('success', 'programme stored successfully!');
    }
}
