<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function showDepartments()
    {
        $title = 'Departments';
        $role = 'Admin';
        $page = 'departments';
        $departments = Department::with('faculties')->get();
        $faculties = Faculty::all();
        return view('NiceAdmin.admin.Departments', compact('title', 'role', 'page', 'departments', 'faculties'));
    }

    public function storeDepartment(Request $request)
    {
        $inputs = $request->validate([
            'code' => 'required|string|unique:departments,dept_code',
            'name' => 'required|string|unique:departments,dept_name',
            'dept' => 'required|numeric'
        ]);

        $department = Department::create([
            'dept_code' => $inputs['code'],
            'dept_name' => $inputs['name'],
            'faculty_id' => $inputs['dept']
        ]);

        if (!$department) {
            return redirect()->back()->withInput()->with('errror', 'Failed to store Department');
        }

        return redirect()->back()->with('success', 'Department stored successfully!');
    }
}
