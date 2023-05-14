<?php

namespace App\Http\Controllers\classsupervisor;

use App\Http\Controllers\Controller;
use App\Models\ClassSupervisor;
use App\Models\Programme;
use App\Models\ReasonForLeave;
use App\Models\Student;
use App\Models\StudentLeave;
use App\Models\SupervisorRecommendation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClassSupervisorController extends Controller
{

    public function getSupervisorProgramme()
    {
        return ClassSupervisor::where('user_id', session('id'))->with('programmes')->first()->programmes;
    }

    public function dashboard()
    {

        $program = $this->getSupervisorProgramme();

        $title = 'Clas Supervisor';
        $role = 'Supervisor';
        $page = $program->program_code;

        $students = Student::where('program_id', $program->id)->count();

        $permissions = StudentLeave::join('reasons_for_leave', 'reasons_for_leave.id', '=', 'student_leaves.reason_id')
            ->join('students', 'reasons_for_leave.student_id', '=', 'students.id')
            ->join('reason_types', 'reasons_for_leave.reason_type_id', '=', 'reason_types.id')
            ->join('programmes', 'students.program_id', '=', 'programmes.id')
            ->where('students.program_id', '=', $program->id)
            ->where('student_leaves.status', '=', 0)
            ->count();

        $dashboards = [
            ['route' => '#', 'title' => 'Students', 'bg' => 'primary', 'text' => 'white', 'total' => $students],
            ['route' => '/class-supervisor/student-requests', 'title' => 'Permission Requests', 'bg' => 'white', 'text' => 'primary', 'total' => $permissions],
        ];

        return view('NiceAdmin.blank', compact('dashboards', 'title', 'page', 'role'));
    }

    public function studentRequests()
    {
        $title = 'Student Requests';
        $page = 'Remarks';
        $role = 'Supervisor';

        $program = $this->getSupervisorProgramme();

        $requests = StudentLeave::select('firstname', 'middlename', 'lastname', 'medical_reason', 'social_reason', 'departure_date', 'return_date', 'place_of_visit', 'level', 'attachment', 'student_leaves.created_at', 'student_leaves.id', 'reasons_for_leave.id as reason_id')
            ->join('reasons_for_leave', 'reasons_for_leave.id', '=', 'student_leaves.reason_id')
            ->join('students', 'reasons_for_leave.student_id', '=', 'students.id')
            ->join('reason_types', 'reasons_for_leave.reason_type_id', '=', 'reason_types.id')
            ->join('programmes', 'students.program_id', '=', 'programmes.id')
            ->join('education_levels', 'education_levels.id', '=', 'students.level_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('student_leaves.status', '=', 0)
            ->where('students.program_id', '=', $program->id)
            ->get()
            ->toArray();

        // dd($requests);

        return view('NiceAdmin.class-supervisor.recommend', compact('title', 'page', 'role', 'requests'));
    }

    public function remark_request(Request $request)
    {
        $inputs = $request->validate([
            'remarks' => 'required',
        ]);

        $remark = SupervisorRecommendation::create([
            's_remarks' => $inputs['remarks'],
            'user_id' => session('id'),
            'leave_id' => $request->input('leaveId')
        ]);



        if (!$remark)
            return redirect()->back()->withInput()->with('error', 'Failed to remark');

        $leave = StudentLeave::find($request->input('leaveId'));

        if ($leave) {
            $leave->status = 1;
            $leave->save();
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

    public function showSupervisors()
    {
        $title = 'Supervisors';
        $role = 'Admin';
        $page = 'class supervisors';
        $programmes = Programme::all();
        $supervisors = ClassSupervisor::with('programmes')->with('users')->get();

        return view('NiceAdmin.admin.supervisors', compact('supervisors', 'title', 'page', 'role', 'programmes'));
    }

    public function storeSupervisor(Request $request)
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
                'program' => 'required'
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
                'role_id' => 3
            ]);

            // create a new Supervisor program record
            ClassSupervisor::create([
                'user_id' => $user->id,
                'program_id' => $inputs['program'],
            ]);

            // commit the transaction if all queries succeed
            DB::commit();

            // redirect to a success page
            return redirect()->back()->with('success', 'Supervisor saved successfully!');
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
