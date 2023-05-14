<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\AwaySession;
use App\Models\DofRecommendation;
use App\Models\DosRecommendation;
use App\Models\EducationLevel;
use App\Models\Programme;
use App\Models\ReasonForLeave;
use App\Models\ReasonType;
use App\Models\SessionType;
use App\Models\Student;
use App\Models\StudentLeave;
use App\Models\SupervisorRecommendation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function dashboard()
    {
        $title = 'Dashboard';
        $role = 'Student';
        $page = 'dashboard';

        $dashboards = [
            ['route' => '/student/permissions', 'title' => 'Permissions', 'bg' => 'primary', 'text' => 'white', 'total' => '4'],
            ['route' => '/student/users', 'title' => 'Users', 'bg' => 'white', 'text' => 'primary', 'total' => '19'],
            ['route' => '/student/admins', 'title' => 'Admins', 'bg' => 'secondary', 'text' => 'white', 'total' => '120'],
            ['route' => '/student/logged', 'title' => 'Logged', 'bg' => 'dark', 'text' => 'white', 'total' => '270'],
        ];

        return view('NiceAdmin.blank', compact('dashboards', 'title', 'page', 'role'));
    }

    public function showStudents()
    {
        $title = 'Students';
        $page = 'students';
        $role = 'admin';
        $programmes = Programme::all();
        $levels = EducationLevel::all();
        $years = [
            ['name' => 'FIRST YEAR'],
            ['name' => 'SECOND YEAR'],
            ['name' => 'THIRD YEAR']
        ];
        $students = Student::with('users')->with('programmes')->with('levels')->get();
        return view('NiceAdmin.admin.students', compact('title', 'page', 'role', 'students', 'programmes', 'levels', 'years'));
    }

    public function storeStudent(Request $request)
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
                'reg' => 'required|string|unique:students,reg_no',
                'program' => 'required',
                'level' => 'required',
                'year' => 'required'
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
                'role_id' => 2
            ]);

            // create a new Student program record
            Student::create([
                'user_id' => $user->id,
                'reg_no' => $inputs['reg'],
                'level_id' => $inputs['level'],
                'year_of_study' => $inputs['year'],
                'program_id' => $inputs['program'],
            ]);

            // commit the transaction if all queries succeed
            DB::commit();

            // redirect to a success page
            return redirect()->back()->with('success', 'Student saved successfully!');
        } catch (\Exception $e) {
            // rollback the transaction if any query fails
            DB::rollback();

            // log the error
            logger()->error($e);

            // redirect to an error page
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function requestForm()
    {
        $title = 'Permission Request';
        $role = 'Student';
        $page = 'request leave permission';
        $reasons = ReasonType::all();
        $sessions = SessionType::all();

        $permissionExist = Student::where('user_id', session('id'))->with('reasons')->first()->toArray();

        // dd($permissionExist['reasons']);

        $student = Student::with('users')
            ->with('levels')
            ->with('programmes')
            ->join('programmes', 'programmes.id', '=', 'students.program_id')
            ->join('departments', 'departments.id', '=', 'programmes.dept_id')
            ->join('faculties', 'faculties.id', '=', 'departments.faculty_id')
            ->where('students.user_id', '=', session('id'))
            ->first();
        // dd($sessions);
        return view(
            'NiceAdmin.student.permission-request',
            compact('title', 'role', 'page', 'reasons', 'student', 'sessions', 'permissionExist')
        );
    }

    public function makeRequest(Request $request)
    {
        DB::beginTransaction();

        $inputs = $request->validate([
            'reason' => 'required',
            'marital' => 'required|string',
            'place' => 'required|string',
            'address' => 'required|string',
            'departure' => 'required|date|after_or_equal:today',
            'return' => 'required|date|after_or_equal:departure',
            'session_type.*' => 'required|integer',
            'lecturer.*' => 'required|string',
            'subject.*' => 'required|string',
            'commence.*' => 'required|date|after_or_equal:departure',
            'medical' => 'required_if:reason,1,3',
            'social' => 'required_if:reason,2,3',
            'attachment' => 'required_if:reason,1,3|file|mimes:pdf,doc,docx|max:20480',
        ], [
            'reason.required' => 'Please select a reason for your leave request.',
            'marital.required' => 'Please select your marital status.',
            'place.required' => 'Please enter the place of visit.',
            'address.required' => 'Please enter your address.',
            'departure.required' => 'Please select the departure date.',
            'departure.after_or_equal' => 'The departure date must be today or a later date.',
            'return.required' => 'Please select the return date.',
            'return.after_or_equal' => 'The return date must be after the departure date.',
            'session_type.*.required' => 'Please select a session type.',
            'session_type.*.integer' => 'The session type must be an integer.',
            'lecturer.*.required' => 'Please enter the lecturer name.',
            'subject.*.required' => 'Please enter the subject name.',
            'commence.*.required' => 'Please select the session commencement date.',
            'commence.*.after_or_equal' => 'The session commencement date must be after or the same as the departure date.',
            'medical.required_if' => 'Please provide a medical reason for your leave request.',
            'social.required_if' => 'Please provide a social reason for your leave request.',
            'attachment.required_if' => 'Please attach a supporting document for your medical reason.',
            'attachment.file' => 'The attachment must be a file.',
            'attachment.mimes' => 'The attachment must be a PDF, DOC, or DOCX file.',
            'attachment.max' => 'The attachment size must not exceed 20MB.',
        ]);

        try {

            $studentId = Student::where('user_id', session('id'))->first()->id;

            $attachmentPath = null;

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');

                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('attachments'), $filename);

                $attachmentPath = 'attachments/' . $filename;
            }

            $reasons = ReasonForLeave::create([
                'medical_reason' => $inputs['medical'] ?? null,
                'social_reason' => $inputs['social'] ?? null,
                'attachment' => $attachmentPath,
                'reason_type_id' => $inputs['reason'],
                'student_id' => $studentId,
            ]);

            $sessionTypes = $inputs['session_type'];
            $subjects = $inputs['subject'];
            $lecturers = $inputs['lecturer'];
            $commenceDates = $inputs['commence'];

            foreach ($sessionTypes as $index => $sessionType) {
                AwaySession::create([
                    'session_type_id' => $sessionType,
                    'subject' => $subjects[$index],
                    'lecturer' => $lecturers[$index],
                    'commence_date' => $commenceDates[$index],
                    'reason_id' => $reasons->id
                ]);
            }

            StudentLeave::create([
                'marital_status' => $inputs['marital'],
                'departure_date' => $inputs['departure'],
                'return_date' => $inputs['return'],
                'place_of_visit' => $inputs['place'],
                'reason_id' => $reasons->id
            ]);

            DB::commit();

            return redirect()->back()->withInput()->with('success', 'Request sent successfully!');
        } catch (\Exception $e) {
            DB::rollback();

            logger()->error($e);

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function permission_progress()
    {
        $title = 'Permission Progress';
        $page = 'Permission progress';
        $role = 'student';

        $progress = [];

        $studentId = Student::where('user_id', session('id'))->first()->id;

        try {
            $reason_id = ReasonForLeave::where('student_id', $studentId)->first()->id;

            $student_leave = StudentLeave::where('reason_id', $reason_id)->first();

            $supervisor_recommendation = SupervisorRecommendation::where('leave_id', $student_leave->id)->first();

            $dof_recommendation = DofRecommendation::where('s_remark_id', $supervisor_recommendation->id)->first();

            $dos_recommendations = DosRecommendation::where('dof_remarks_id', $dof_recommendation->id)->first();

            if ($student_leave->status == 0) {
                $progress = [
                    [
                        'bg' => 'white',
                        'title' => 'Pending...',
                        'title-color' => 'danger',
                        'subtitle' => 'waiting for class supervisor approval',
                        'subtitle-color' => 'success'
                    ]
                ];
            }

            if ($supervisor_recommendation->status == 0) {
                $progress = [
                    [
                        'bg' => 'warning',
                        'title' => 'On progress...',
                        'title-color' => 'white',
                        'subtitle' => 'Waiting for dean of faculty approval',
                        'subtitle-color' => 'secondary'
                    ]
                ];
            }

            if ($dof_recommendation->status == 0) {
                $progress = [
                    [
                        'bg' => 'dark',
                        'title' => 'On progress...',
                        'title-color' => 'white',
                        'subtitle' => 'Waiting for dean of school approval',
                        'subtitle-color' => 'warning'
                    ]
                ];
            }

            if ($dos_recommendations->status == 0) {
                $progress = [
                    [
                        'bg' => 'danger',
                        'title' => 'REJECTED',
                        'title-color' => 'white fw-bolder',
                        'subtitle' => 'Sorry!, your permission request has been rejected!',
                        'subtitle-color' => 'white fw-bolder'
                    ]
                ];
            }

            if ($dos_recommendations->status == 1) {
                $progress = [
                    [
                        'bg' => 'white',
                        'title' => 'ACCEPTED',
                        'title-color' => 'success fw-bolder',
                        'subtitle' => 'Congratulations!, your permission request has been accepted!',
                        'subtitle-color' => 'success fw-bolder',
                        'report' => 'View report',
                    ]
                ];
            }

            return view('NiceAdmin.student.permission-progress', compact('title', 'page', 'role', 'progress'));
        } catch (\Exception $e) {

            $progress = [
                [
                    'bg' => 'white',
                    'title' => 'No Permission Requested',
                    'title-color' => 'success fw-bolder',
                    'subtitle' => 'You have got no request',
                    'subtitle-color' => 'warning fw-bolder'
                ]
            ];

            return view('NiceAdmin.student.permission-progress', compact('title', 'page', 'role', 'progress'));
        }
    }
}
