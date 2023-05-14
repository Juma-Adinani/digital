<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSupervisor;
use App\Models\DeanFaculty;
use App\Models\Department;
use App\Models\Programme;
use App\Models\Student;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $title = 'Dashboard';
        $role = 'Admin';
        $page = 'dashboard';
        $students = Student::count();
        $programmes = Programme::count();
        $departments = Department::count();
        $deans = DeanFaculty::count();
        $supervisors = ClassSupervisor::count();

        $dashboards = [
            ['route' => '/admin/students', 'title' => 'Students', 'bg' => 'primary', 'text' => 'white', 'total' => $students],
            ['route' => '/admin/supervisors', 'title' => 'Supervisors', 'bg' => 'white', 'text' => 'primary', 'total' => $supervisors],
            ['route' => '/admin/deans', 'title' => 'Dean of Faculty', 'bg' => 'secondary', 'text' => 'white', 'total' => $deans],
            ['route' => '/admin/departments', 'title' => 'Departments', 'bg' => 'success', 'text' => 'white', 'total' => $departments],
            ['route' => '/admin/programmes', 'title' => 'Programmes', 'bg' => 'dark', 'text' => 'white', 'total' => $programmes],
        ];

        return view('NiceAdmin.blank', compact('dashboards', 'title', 'page', 'role'));
    }
}
