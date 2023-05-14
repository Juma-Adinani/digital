<?php

namespace App\Http\Controllers\deanschool;

use App\Http\Controllers\Controller;
use App\Models\DofRecommendation;
use App\Models\DosRecommendation;
use App\Models\ReasonForLeave;
use Illuminate\Http\Request;

class DeanSchoolController extends Controller
{
    public function dashboard()
    {

        $title = 'Student Requests';
        $page = 'Permission requests';
        $role = 'Dean school';

        $permissions = DofRecommendation::where('status', 0)->count();

        $dashboards = [
            ['route' => '/dean_of_school/permission-requests', 'title' => 'Permission Requests', 'bg' => 'white', 'text' => 'primary', 'total' => $permissions],
        ];

        return view('NiceAdmin.blank', compact('dashboards', 'title', 'page', 'role'));
    }

    public function permission_requests()
    {
        $title = 'Dean';
        $page = 'permission requests';
        $role = 'dean';

        $requests = DofRecommendation::select('firstname', 'middlename', 'lastname', 'medical_reason', 'social_reason', 'departure_date', 'return_date', 'place_of_visit', 'level', 'attachment', 'student_leaves.created_at', 'dof_recommendations.id', 'reasons_for_leave.id as reason_id', 'program_code', 'faculty_code')
            ->join('supervisor_recommendations', 'supervisor_recommendations.id', '=', 'dof_recommendations.s_remark_id')
            ->join('student_leaves', 'student_leaves.id', '=', 'supervisor_recommendations.leave_id')
            ->join('reasons_for_leave', 'reasons_for_leave.id', '=', 'student_leaves.reason_id')
            ->join('students', 'reasons_for_leave.student_id', '=', 'students.id')
            ->join('reason_types', 'reasons_for_leave.reason_type_id', '=', 'reason_types.id')
            ->join('programmes', 'students.program_id', '=', 'programmes.id')
            ->join('education_levels', 'education_levels.id', '=', 'students.level_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('departments', 'departments.id', '=', 'programmes.dept_id')
            ->join('faculties', 'faculties.id', '=', 'departments.faculty_id')
            ->where('dof_recommendations.status', '=', 0)
            ->get()
            ->toArray();

        // dd($requests);

        return view('NiceAdmin.dos.recommend', compact('role', 'title', 'page', 'requests'));
    }

    public function remark_request(Request $request)
    {
        $inputs = $request->validate([
            'remarks' => 'required',
            'response' => 'required'
        ]);

        $remark = DosRecommendation::create([
            'dos_remarks' => $inputs['remarks'],
            'user_id' => session('id'),
            'status' => $inputs['response'],
            'dof_remarks_id' => $request->input('dof_remark_id')
        ]);

        if (!$remark)
            return redirect()->back()->withInput()->with('error', 'Failed to remark');

        $dofRecommendation = DofRecommendation::find($request->input('dof_remark_id'));

        if ($dofRecommendation) {
            $dofRecommendation->status = 1;
            $dofRecommendation->save();
        }
        return redirect()->back()->with('success', 'Remarked successfully!');
    }

    public function view_document($id)
    {

        $attachment = ReasonForLeave::find($id);
        if ($attachment == null) return view('NiceAdmin.404');
        // dd($attachment);
        return view('NiceAdmin.class-supervisor.view-doc', compact('attachment'));
    }
}
