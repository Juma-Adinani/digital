<?php

namespace App\Http\Controllers\dean;

use App\Http\Controllers\Controller;
use App\Models\DeanFaculty;
use App\Models\DofRecommendation;
use App\Models\Faculty;
use App\Models\ReasonForLeave;
use App\Models\Student;
use App\Models\SupervisorRecommendation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DeanController extends Controller
{
    public function index()
    {
        return DeanFaculty::where('user_id', session('id'))->first();
    }

    public function dashboard()
    {
        $faculty = $this->index();

        $title = 'Dashboard';
        $page = 'dean dashboard';
        $role = 'dean';

        $students = Student::join('programmes', 'programmes.id', '=', 'students.program_id')
            ->join('departments', 'departments.id', '=', 'programmes.dept_id')
            ->join('faculties', 'faculties.id', '=', 'departments.faculty_id')
            ->where('faculties.id', '=', $faculty->faculty_id)
            ->count();


        $permissions = SupervisorRecommendation::join('student_leaves', 'student_leaves.id', '=', 'supervisor_recommendations.leave_id')
            ->join('reasons_for_leave', 'reasons_for_leave.id', '=', 'student_leaves.reason_id')
            ->join('students', 'reasons_for_leave.student_id', '=', 'students.id')
            ->join('reason_types', 'reasons_for_leave.reason_type_id', '=', 'reason_types.id')
            ->join('programmes', 'students.program_id', '=', 'programmes.id')
            ->join('departments', 'departments.id', '=', 'programmes.dept_id')
            ->join('faculties', 'faculties.id', '=', 'departments.faculty_id')
            ->where('faculties.id', '=', $faculty->faculty_id)
            ->where('supervisor_recommendations.status', '=', 0)
            ->count();

        $dashboards = [
            ['route' => '#', 'title' => 'Students', 'bg' => 'primary', 'text' => 'white', 'total' => $students],
            ['route' => '/dean_of_faculty/permission-requests', 'title' => 'Permission Requests', 'bg' => 'white', 'text' => 'primary', 'total' => $permissions],
        ];

        return view('NiceAdmin.blank', compact('dashboards', 'title', 'page', 'role'));
    }

    public function permission_requests()
    {
        $faculty = $this->index();

        $title = 'Dean, ' . $faculty->faculty_code;
        $page = 'permission requests';
        $role = 'dean';

        $requests = SupervisorRecommendation::select('firstname', 'middlename', 'lastname', 'medical_reason', 'social_reason', 'departure_date', 'return_date', 'place_of_visit', 'level', 'attachment', 'student_leaves.created_at', 'supervisor_recommendations.id', 'reasons_for_leave.id as reason_id')
            ->join('student_leaves', 'student_leaves.id', '=', 'supervisor_recommendations.leave_id')
            ->join('reasons_for_leave', 'reasons_for_leave.id', '=', 'student_leaves.reason_id')
            ->join('students', 'reasons_for_leave.student_id', '=', 'students.id')
            ->join('reason_types', 'reasons_for_leave.reason_type_id', '=', 'reason_types.id')
            ->join('programmes', 'students.program_id', '=', 'programmes.id')
            ->join('education_levels', 'education_levels.id', '=', 'students.level_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('departments', 'departments.id', '=', 'programmes.dept_id')
            ->join('faculties', 'faculties.id', '=', 'departments.faculty_id')
            ->where('faculties.id', '=', $faculty->faculty_id)
            ->where('supervisor_recommendations.status', '=', 0)
            ->get()
            ->toArray();

        // dd($requests);

        return view('NiceAdmin.dof.recommend', compact('role', 'title', 'page', 'requests'));
    }

    public function remark_request(Request $request)
    {
        $inputs = $request->validate([
            'remarks' => 'required',
        ]);

        $remark = DofRecommendation::create([
            'dof_remarks' => $inputs['remarks'],
            'user_id' => session('id'),
            's_remark_id' => $request->input('supervisorecommendationId')
        ]);



        if (!$remark)
            return redirect()->back()->withInput()->with('error', 'Failed to remark');

        $supervisorRecommendation = SupervisorRecommendation::find($request->input('supervisorecommendationId'));

        if ($supervisorRecommendation) {
            $supervisorRecommendation->status = 1;
            $supervisorRecommendation->save();
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

    public function showDeans()
    {
        $title = 'Deans';
        $role = 'Admin';
        $page = 'deans of faculties';
        $faculties = Faculty::all();
        $deans = DeanFaculty::with('faculties')->with('users')->get();

        return view('NiceAdmin.admin.deans', compact('deans', 'title', 'page', 'role', 'faculties'));
    }

    public function storeDean(Request $request)
    {

        // start a transaction
        DB::beginTransaction();

        try {
            $inputs = $request->validate([
                'firstname' => 'required|string',
                'middlename' => 'required|string',
                'lastname' => 'required|string',
                'gender' => 'required|string',
                'email' => 'required|email|unique:users',
                'phone' => 'required|numeric|unique:users',
                'faculty' => 'required'
            ]);

            // create a new user
            $user = User::create([
                'firstname' => $inputs['firstname'],
                'middlename' => $inputs['middlename'],
                'lastname' => $inputs['lastname'],
                'gender' => $inputs['gender'],
                'email' => $inputs['email'],
                'phone' => $inputs['phone'],
                'password' => Hash::make(strtolower($inputs['firstname'] . '123')),
                'role_id' => 4
            ]);

            // create a new dean faculty record
            DeanFaculty::create([
                'user_id' => $user->id,
                'faculty_id' => $inputs['faculty'],
            ]);

            // commit the transaction if all queries succeed
            DB::commit();

            // redirect to a success page
            return redirect()->back()->with('success', 'Dean saved successfully!');
        } catch (\Exception $e) {
            // rollback the transaction if any query fails
            DB::rollback();

            // log the error
            logger()->error($e);

            // redirect to an error page
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
