<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Document;
use App\Models\EmpTodayAttendance;
use App\Models\Employee;
use App\Models\EmployeeDataUpdate;
use App\Models\EmployeeLeave;
use App\Models\EmployeeResignation;
use App\Models\EmployeeTravel;
use App\Models\FeedbackAnswer;
use App\Models\FeedbackForm;
use App\Models\IncomeDocument;
use App\Models\JobPost;
use App\Models\MakePayment;
use App\Models\Meeting;
use App\Models\Referral;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class EmployeePortalController extends Controller
{
    protected function getEmployeeId(): int
    {
        return (int) (auth()->user()?->employee_id ?? auth()->id() ?? 1);
    }

    protected function getCompanyId(): int
    {
        return (int) (auth()->user()?->company_id ?? 1);
    }

    /**
     * ESS Dashboard Overview
     */
    public function index(): View
    {
        $employeeId = $this->getEmployeeId();

        $leaves = EmployeeLeave::where('employee_id', $employeeId)->latest()->take(5)->get();
        $payslips = MakePayment::where('employee_id', $employeeId)->latest()->take(3)->get();
        $announcementKey = (new Announcement)->getKeyName();
        $announcements = Announcement::orderBy($announcementKey, 'desc')->take(3)->get();
        $meetingKey = (new Meeting)->getKeyName();
        $meetings = Meeting::where('employee_id', $employeeId)->orderBy($meetingKey, 'desc')->take(3)->get();

        return view('my_portal.index', compact('leaves', 'payslips', 'announcements', 'meetings'));
    }

    /**
     * My Leaves & Requests
     */
    public function leaves(): View
    {
        $employeeId = $this->getEmployeeId();
        $keyName = (new EmployeeLeave)->getKeyName();
        $leaves = EmployeeLeave::where('employee_id', $employeeId)->orderBy($keyName, 'desc')->paginate(10);

        return view('my_portal.leaves', compact('leaves'));
    }

    public function storeLeave(Request $request): RedirectResponse
    {
        $request->validate([
            'leave_type_id' => 'required|integer',
            'from_date' => 'required|string',
            'to_date' => 'required|string',
            'reason' => 'required|string',
        ]);

        $table = (new EmployeeLeave)->getTable();
        $data = [
            'company_id' => $this->getCompanyId(),
            'employee_id' => $this->getEmployeeId(),
            'reason' => $request->reason,
            'status' => 1,
        ];

        if (Schema::hasColumn($table, 'leave_type_id')) $data['leave_type_id'] = $request->leave_type_id;
        if (Schema::hasColumn($table, 'from_date')) $data['from_date'] = $request->from_date;
        if (Schema::hasColumn($table, 'to_date')) $data['to_date'] = $request->to_date;
        if (Schema::hasColumn($table, 'start_date')) $data['start_date'] = $request->from_date;
        if (Schema::hasColumn($table, 'end_date')) $data['end_date'] = $request->to_date;
        if (Schema::hasColumn($table, 'applied_on')) $data['applied_on'] = date('Y-m-d H:i:s');
        if (Schema::hasColumn($table, 'manager_id')) $data['manager_id'] = auth()->user()?->manager_id ?? 1;
        if (Schema::hasColumn($table, 'created_at')) $data['created_at'] = date('Y-m-d H:i:s');

        EmployeeLeave::create($data);

        return redirect()->back()->with('success', 'Leave application submitted successfully!');
    }

    /**
     * My Attendance & Clock Logs
     */
    public function attendance(Request $request): View
    {
        $user = auth()->user();
        $employee = null;

        if ($user) {
            $employee = Employee::where('user_id', $user->id)
                ->orWhere('employee_id', $user->employee_id ?? '')
                ->orWhere('email', $user->email ?? '')
                ->first();
        }

        $cardNo = $employee?->card_no ?? $user?->card_no;
        $employeeCode = $employee?->employee_id ?? $user?->employee_id;
        $userId = $employee?->user_id ?? $user?->id;

        $query = EmpTodayAttendance::query();

        $query->where(function ($q) use ($cardNo, $employeeCode, $userId) {
            $matched = false;

            if (!empty($cardNo) && $cardNo !== '0') {
                $q->orWhere('card_no', $cardNo)->orWhere('badgenumber', $cardNo);
                $matched = true;
            }

            if (!empty($employeeCode) && $employeeCode !== '0') {
                $q->orWhere('card_no', $employeeCode)->orWhere('badgenumber', $employeeCode);
                $digits = preg_replace('/[^0-9]/', '', (string)$employeeCode);
                if (!empty($digits)) {
                    $q->orWhere('card_no', $digits)->orWhere('badgenumber', $digits);
                    $q->orWhere('card_no', 'like', '%' . $digits)->orWhere('badgenumber', 'like', '%' . $digits);
                }
                $matched = true;
            }

            if (!empty($userId)) {
                $q->orWhere('card_no', $userId)->orWhere('badgenumber', $userId);
                $matched = true;
            }

            if (!$matched) {
                $q->where('card_no', '___NO_CARD___');
            }
        });

        // Apply Date Range Filters
        if ($request->filled('from_date')) {
            $query->where('punch_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('punch_date', '<=', $request->to_date);
        }

        // Apply Status Filter
        if ($request->filled('status')) {
            if ($request->status === 'present') {
                $query->where(function ($sq) {
                    $sq->whereNotNull('check_in_time')
                       ->orWhereNotNull('check_in_datetime');
                });
            } elseif ($request->status === 'absent') {
                $query->whereNull('check_in_time')
                      ->whereNull('check_in_datetime');
            }
        }

        $attendanceLogs = $query->orderBy('punch_date', 'desc')->orderBy('id', 'desc')->paginate(15)->appends($request->all());

        return view('my_portal.attendance', compact('attendanceLogs', 'employee'));
    }

    /**
     * My Payslips
     */
    public function payslips(): View
    {
        $employeeId = $this->getEmployeeId();
        $keyName = (new MakePayment)->getKeyName();
        $payslips = MakePayment::where('employee_id', $employeeId)->orderBy($keyName, 'desc')->paginate(10);

        return view('my_portal.payslips', compact('payslips'));
    }

    /**
     * Performance Feedback / Self Rating Form
     */
    public function performanceFeedback(): View
    {
        $forms = FeedbackForm::with('questions')->get();
        $table = (new FeedbackAnswer)->getTable();
        $userId = auth()->id() ?? $this->getEmployeeId();

        $query = FeedbackAnswer::query();
        if (Schema::hasColumn($table, 'employee_id') && Schema::hasColumn($table, 'user_id')) {
            $query->where('employee_id', $this->getEmployeeId())->orWhere('user_id', $userId);
        } elseif (Schema::hasColumn($table, 'employee_id')) {
            $query->where('employee_id', $this->getEmployeeId());
        } elseif (Schema::hasColumn($table, 'user_id')) {
            $query->where('user_id', $userId);
        }

        $myAnswers = $query->get()->keyBy('question_id');

        return view('my_portal.performance_feedback', compact('forms', 'myAnswers'));
    }

    public function storePerformanceFeedback(Request $request): RedirectResponse
    {
        $request->validate([
            'ratings' => 'required|array',
            'answers' => 'required|array',
        ]);

        $employeeId = $this->getEmployeeId();
        $userId = auth()->id() ?? $employeeId;
        $table = (new FeedbackAnswer)->getTable();

        foreach ($request->ratings as $questionId => $rating) {
            $payload = [
                'form_id' => $request->form_id ?? 1,
                'rating' => (int) $rating,
                'answer' => $request->answers[$questionId] ?? '',
            ];

            if (Schema::hasColumn($table, 'employee_id')) {
                $payload['employee_id'] = $employeeId;
            }
            if (Schema::hasColumn($table, 'user_id')) {
                $payload['user_id'] = $userId;
            }
            if (Schema::hasColumn($table, 'feedback')) {
                $payload['feedback'] = $request->answers[$questionId] ?? '';
            }
            if (Schema::hasColumn($table, 'created_at')) {
                $payload['created_at'] = date('d-m-Y H:i:s');
            }
            if (Schema::hasColumn($table, 'added_date')) {
                $payload['added_date'] = date('Y-m-d H:i:s');
            }
            if (Schema::hasColumn($table, 'show_status')) {
                $payload['show_status'] = 1;
            }

            $lookup = ['question_id' => $questionId];
            if (Schema::hasColumn($table, 'employee_id')) {
                $lookup['employee_id'] = $employeeId;
            } else {
                $lookup['user_id'] = $userId;
            }

            FeedbackAnswer::updateOrCreate($lookup, $payload);
        }

        return redirect()->back()->with('success', 'Your performance self-rating & feedback has been saved successfully!');
    }

    /**
     * Corporate Benefits & Policies Page
     */
    public function benefits(): View
    {
        $table = (new Document)->getTable();
        $keyName = (new Document)->getKeyName();

        $query = Document::where('company_id', $this->getCompanyId());

        if (Schema::hasColumn($table, 'active')) {
            $query->where('active', 1);
        }

        if (Schema::hasColumn($table, 'created_at')) {
            $query->orderBy('created_at', 'desc');
        } elseif (Schema::hasColumn($table, 'added_date')) {
            $query->orderBy('added_date', 'desc');
        } else {
            $query->orderBy($keyName, 'desc');
        }

        $documents = $query->get();
        return view('my_portal.benefits', compact('documents'));
    }

    /**
     * Employee Candidate Referrals
     */
    public function referrals(): View
    {
        $employeeId = $this->getEmployeeId();
        $refKey = (new Referral)->getKeyName();
        $referrals = Referral::where('employee_id', $employeeId)->orderBy($refKey, 'desc')->get();
        $openJobs = JobPost::latest()->get();

        return view('my_portal.referrals', compact('referrals', 'openJobs'));
    }

    public function storeReferral(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:50',
            'job_id' => 'required|integer',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $resumePath = '';
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/referrals'), $fileName);
            $resumePath = 'uploads/referrals/' . $fileName;
        }

        $table = (new Referral)->getTable();
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'resume' => $resumePath,
            'status' => 'Pending',
        ];

        if (Schema::hasColumn($table, 'company_id')) $data['company_id'] = $this->getCompanyId();
        if (Schema::hasColumn($table, 'employee_id')) $data['employee_id'] = $this->getEmployeeId();
        if (Schema::hasColumn($table, 'job_id')) $data['job_id'] = $request->job_id;
        if (Schema::hasColumn($table, 'contact_number')) $data['contact_number'] = $request->contact_number;
        if (Schema::hasColumn($table, 'contact_no')) $data['contact_no'] = $request->contact_number;
        if (Schema::hasColumn($table, 'assigned_to')) $data['assigned_to'] = 0;
        if (Schema::hasColumn($table, 'added_by')) $data['added_by'] = $this->getEmployeeId();
        if (Schema::hasColumn($table, 'description')) $data['description'] = 'Candidate Referral';
        if (Schema::hasColumn($table, 'remarks')) $data['remarks'] = '';

        Referral::create($data);

        return redirect()->back()->with('success', 'Candidate referral submitted successfully!');
    }

    /**
     * Book Conference Room / Meetings
     */
    public function meetings(): View
    {
        $meetingKey = (new Meeting)->getKeyName();
        $meetings = Meeting::where('company_id', $this->getCompanyId())->orderBy($meetingKey, 'desc')->get();
        return view('my_portal.meetings', compact('meetings'));
    }

    public function storeMeeting(Request $request): RedirectResponse
    {
        $request->validate([
            'meeting_title' => 'required|string|max:255',
            'meeting_date' => 'required|string',
            'meeting_time' => 'required|string',
            'room_name' => 'required|string|max:100',
            'note' => 'nullable|string',
        ]);

        $table = (new Meeting)->getTable();
        $data = [
            'meeting_title' => $request->meeting_title,
            'meeting_date' => $request->meeting_date,
            'meeting_time' => $request->meeting_time,
        ];

        if (Schema::hasColumn($table, 'company_id')) $data['company_id'] = $this->getCompanyId();
        if (Schema::hasColumn($table, 'employee_id')) $data['employee_id'] = $this->getEmployeeId();
        if (Schema::hasColumn($table, 'room_name')) $data['room_name'] = $request->room_name;
        if (Schema::hasColumn($table, 'note')) $data['note'] = $request->note ?? '';
        if (Schema::hasColumn($table, 'meeting_note')) $data['meeting_note'] = $request->note ?? '';

        Meeting::create($data);

        return redirect()->back()->with('success', 'Conference room booked successfully!');
    }

    /**
     * Conveyance & Travel Claims
     */
    public function conveyance(): View
    {
        $travelKey = (new EmployeeTravel)->getKeyName();
        $claims = EmployeeTravel::where('employee_id', $this->getEmployeeId())->orderBy($travelKey, 'desc')->get();
        return view('my_portal.conveyance', compact('claims'));
    }

    public function storeConveyance(Request $request): RedirectResponse
    {
        $request->validate([
            'travel_type' => 'required|string|max:100',
            'visit_place' => 'required|string|max:255',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'expected_budget' => 'required|numeric',
            'description' => 'required|string',
        ]);

        $table = (new EmployeeTravel)->getTable();
        $data = [
            'company_id' => $this->getCompanyId(),
            'employee_id' => $this->getEmployeeId(),
            'visit_place' => $request->visit_place,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'expected_budget' => (string) $request->expected_budget,
            'actual_budget' => (string) $request->expected_budget,
            'description' => $request->description,
            'status' => 0,
        ];

        if (Schema::hasColumn($table, 'travel_type')) $data['travel_type'] = $request->travel_type;
        if (Schema::hasColumn($table, 'visit_purpose')) $data['visit_purpose'] = $request->travel_type;
        if (Schema::hasColumn($table, 'travel_mode')) $data['travel_mode'] = 1;
        if (Schema::hasColumn($table, 'arrangement_type')) $data['arrangement_type'] = 1;
        if (Schema::hasColumn($table, 'cost')) $data['cost'] = (string) $request->expected_budget;
        if (Schema::hasColumn($table, 'date')) $data['date'] = date('Y-m-d');
        if (Schema::hasColumn($table, 'added_by')) $data['added_by'] = $this->getEmployeeId();

        EmployeeTravel::create($data);

        return redirect()->back()->with('success', 'Conveyance & travel claim submitted successfully!');
    }

    /**
     * Upload Income & Tax Documents
     */
    public function taxDocuments(): View
    {
        $doc = new IncomeDocument;
        $table = $doc->getTable();
        $keyName = $doc->getKeyName();

        $query = IncomeDocument::query();

        if (Schema::hasColumn($table, 'employee_id')) {
            $query->where('employee_id', $this->getEmployeeId());
        } elseif (Schema::hasColumn($table, 'added_by')) {
            $query->where('added_by', $this->getEmployeeId());
        } elseif (Schema::hasColumn($table, 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $docs = $query->orderBy($keyName, 'desc')->get();
        return view('my_portal.tax_documents', compact('docs'));
    }

    public function storeTaxDocument(Request $request): RedirectResponse
    {
        $request->validate([
            'document_type' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,jpg,png,zip|max:5120',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/tax_documents'), $fileName);

        $table = (new IncomeDocument)->getTable();
        $data = [
            'file' => 'uploads/tax_documents/' . $fileName,
            'financial_year' => date('Y') . '-' . (date('Y') + 1),
        ];

        if (Schema::hasColumn($table, 'company_id')) $data['company_id'] = $this->getCompanyId();
        if (Schema::hasColumn($table, 'employee_id')) $data['employee_id'] = $this->getEmployeeId();
        if (Schema::hasColumn($table, 'doc_type')) $data['doc_type'] = $request->document_type;
        if (Schema::hasColumn($table, 'document_type')) $data['document_type'] = $request->document_type;
        if (Schema::hasColumn($table, 'title')) $data['title'] = $request->title;
        if (Schema::hasColumn($table, 'description')) $data['description'] = $request->description ?? '';
        if (Schema::hasColumn($table, 'file_name')) $data['file_name'] = 'uploads/tax_documents/' . $fileName;
        if (Schema::hasColumn($table, 'file_size')) $data['file_size'] = round($file->getSize() / 1024, 2) . ' KB';
        if (Schema::hasColumn($table, 'status')) $data['status'] = 'Submitted';
        if (Schema::hasColumn($table, 'added_by')) $data['added_by'] = $this->getEmployeeId();
        if (Schema::hasColumn($table, 'added_date')) $data['added_date'] = date('d-m-Y H:i:s');

        IncomeDocument::create($data);

        return redirect()->back()->with('success', 'Income tax document uploaded successfully!');
    }

    /**
     * Resignation Notice
     */
    public function resignation(): View
    {
        $resignation = EmployeeResignation::where('employee_id', $this->getEmployeeId())->first();
        return view('my_portal.resignation', compact('resignation'));
    }

    public function storeResignation(Request $request): RedirectResponse
    {
        $request->validate([
            'notice_date' => 'required|string',
            'resignation_date' => 'required|string',
            'reason' => 'required|string',
        ]);

        $table = (new EmployeeResignation)->getTable();
        $data = [
            'company_id' => $this->getCompanyId(),
            'notice_date' => $request->notice_date,
            'resignation_date' => $request->resignation_date,
            'reason' => $request->reason,
            'status' => 'Under Review',
        ];

        if (Schema::hasColumn($table, 'manager_id')) $data['manager_id'] = 1;
        if (Schema::hasColumn($table, 'requested_notice')) $data['requested_notice'] = '30 Days';
        if (Schema::hasColumn($table, 'manager_comment')) $data['manager_comment'] = '';
        if (Schema::hasColumn($table, 'it_comment')) $data['it_comment'] = '';
        if (Schema::hasColumn($table, 'account_comment')) $data['account_comment'] = '';
        if (Schema::hasColumn($table, 'hr_comment')) $data['hr_comment'] = '';
        if (Schema::hasColumn($table, 'coo_comment')) $data['coo_comment'] = '';
        if (Schema::hasColumn($table, 'sage_comment')) $data['sage_comment'] = '';
        if (Schema::hasColumn($table, 'login_comment')) $data['login_comment'] = '';
        if (Schema::hasColumn($table, 'it_person')) $data['it_person'] = '';
        if (Schema::hasColumn($table, 'account_per')) $data['account_per'] = '';
        if (Schema::hasColumn($table, 'hr_person')) $data['hr_person'] = '';
        if (Schema::hasColumn($table, 'manager_person')) $data['manager_person'] = '';
        if (Schema::hasColumn($table, 'sage_person')) $data['sage_person'] = '';
        if (Schema::hasColumn($table, 'login_person')) $data['login_person'] = '';
        if (Schema::hasColumn($table, 'employee_accept')) $data['employee_accept'] = 'Pending';
        if (Schema::hasColumn($table, 'comments')) $data['comments'] = '';
        if (Schema::hasColumn($table, 'added_by')) $data['added_by'] = $this->getEmployeeId();

        EmployeeResignation::updateOrCreate(
            ['employee_id' => $this->getEmployeeId()],
            $data
        );

        return redirect()->back()->with('success', 'Resignation notice submitted successfully.');
    }

    /**
     * ESS profile edit form
     */
    public function editProfile(): View
    {
        $employee = Employee::where('user_id', auth()->id())->firstOrFail();
        $pendingUpdate = EmployeeDataUpdate::where('user_id', auth()->id())->where('acceptance', 0)->first();
        return view('my_portal.profile_update', compact('employee', 'pendingUpdate'));
    }

    /**
     * Store/update ESS profile update request
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email_personal' => 'nullable|email|max:255',
            'contact_no' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'mother_tongue' => 'nullable|string|max:100',
            'place_of_birth' => 'nullable|string|max:255',
            'blood_group' => 'nullable|string|max:10',
            'marital_status' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'aadhar_no' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'address_com' => 'nullable|string',
            // family
            'father_name' => 'nullable|string|max:255',
            'father_mobile' => 'nullable|string|max:20',
            'father_gender' => 'nullable|string|max:10',
            'father_occupation' => 'nullable|string|max:255',
            'father_address' => 'nullable|string',
            'mother_name' => 'nullable|string|max:255',
            'mother_mobile' => 'nullable|string|max:20',
            'mother_gender' => 'nullable|string|max:10',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_address' => 'nullable|string',
            'brother_name' => 'nullable|string|max:255',
            'brother_mobile' => 'nullable|string|max:20',
            'brother_gender' => 'nullable|string|max:10',
            'brother_occupation' => 'nullable|string|max:255',
            'brother_address' => 'nullable|string',
            'sister_name' => 'nullable|string|max:255',
            'sister_mobile' => 'nullable|string|max:20',
            'sister_gender' => 'nullable|string|max:10',
            'sister_occupation' => 'nullable|string|max:255',
            'sister_address' => 'nullable|string',
            'spouse_name' => 'nullable|string|max:255',
            'spouse_mobile' => 'nullable|string|max:20',
            'spouse_gender' => 'nullable|string|max:10',
            'spouse_occupation' => 'nullable|string|max:255',
            'spouse_address' => 'nullable|string',
            'child1_name' => 'nullable|string|max:255',
            'child1_mobile' => 'nullable|string|max:20',
            'child1_gender' => 'nullable|string|max:10',
            'child1_occupation' => 'nullable|string|max:255',
            'child1_address' => 'nullable|string',
            'child2_name' => 'nullable|string|max:255',
            'child2_mobile' => 'nullable|string|max:20',
            'child2_gender' => 'nullable|string|max:10',
            'child2_occupation' => 'nullable|string|max:255',
            'child2_address' => 'nullable|string',
            // emergency
            'emergency_contact_relation' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_gender' => 'nullable|string|max:10',
            'emergency_contact_mobile' => 'nullable|string|max:20',
            'emergency_contact_occupation' => 'nullable|string|max:255',
            'emergency_contact_address' => 'nullable|string',
            // other info
            'official_contact_no' => 'nullable|string|max:20',
            'vehicle_type' => 'nullable|string|max:50',
            'vehicle_no' => 'nullable|string|max:50',
            'paytm_no' => 'nullable|string|max:20',
            'skype_id' => 'nullable|string|max:100',
            'health_ins_opted' => 'nullable|string|max:10',
            'pf_opted' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['email'] = auth()->user()->email;
        $validated['added_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $validated['added_date'] = date('Y-m-d H:i:s');
        $validated['updated_date'] = date('Y-m-d H:i:s');
        $validated['acceptance'] = 0;
        $validated['emp_updated_dets'] = 0;

        // Generate safe defaults dynamically from the model's fillable attributes to avoid MySQL constraint errors
        $fillable = (new EmployeeDataUpdate)->getFillable();
        $defaults = [];
        foreach ($fillable as $field) {
            if (in_array($field, ['user_id', 'added_by', 'updated_by', 'acceptance', 'emp_updated_dets', 'acceptance_basic', 'acceptance_father', 'acceptance_mother', 'acceptance_emer', 'acceptance_bro', 'acceptance_sis', 'acceptance_c1', 'acceptance_c2', 'acceptance_social', 'acceptance_spouse'])) {
                $defaults[$field] = 0;
            } elseif ($field === 'date_of_birth' || $field === 'date_of_birth_doc') {
                $defaults[$field] = '1970-01-01';
            } elseif ($field === 'added_date' || $field === 'updated_date' || $field === 'acceptance_date') {
                $defaults[$field] = date('Y-m-d H:i:s');
            } else {
                $defaults[$field] = '';
            }
        }

        $saveData = array_merge($defaults, array_filter($validated, function($val) {
            return $val !== null;
        }));

        EmployeeDataUpdate::updateOrCreate(
            ['user_id' => auth()->id(), 'acceptance' => 0],
            $saveData
        );

        return redirect()->route('my-portal.profile-update')->with('success', 'Profile update request submitted successfully. Awaiting HR approval.');
    }

    /**
     * Public onboarding form for new hires
     */
    public function onboardingForm($token): View
    {
        $employee = Employee::whereRaw('MD5(user_id) = ?', [$token])->first();
        if (!$employee) {
            abort(404, 'Invalid onboarding token link.');
        }
        $pendingUpdate = EmployeeDataUpdate::where('user_id', $employee->user_id)->where('acceptance', 0)->first();
        return view('my_portal.onboarding', compact('employee', 'pendingUpdate', 'token'));
    }

    /**
     * Store public onboarding details
     */
    public function storeOnboarding(Request $request, $token): RedirectResponse
    {
        $employee = Employee::whereRaw('MD5(user_id) = ?', [$token])->first();
        if (!$employee) {
            abort(404, 'Invalid onboarding token link.');
        }

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email_personal' => 'nullable|email|max:255',
            'contact_no' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'mother_tongue' => 'nullable|string|max:100',
            'place_of_birth' => 'nullable|string|max:255',
            'blood_group' => 'nullable|string|max:10',
            'marital_status' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'aadhar_no' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'address_com' => 'nullable|string',
            // family
            'father_name' => 'nullable|string|max:255',
            'father_mobile' => 'nullable|string|max:20',
            'father_gender' => 'nullable|string|max:10',
            'father_occupation' => 'nullable|string|max:255',
            'father_address' => 'nullable|string',
            'mother_name' => 'nullable|string|max:255',
            'mother_mobile' => 'nullable|string|max:20',
            'mother_gender' => 'nullable|string|max:10',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_address' => 'nullable|string',
            'brother_name' => 'nullable|string|max:255',
            'brother_mobile' => 'nullable|string|max:20',
            'brother_gender' => 'nullable|string|max:10',
            'brother_occupation' => 'nullable|string|max:255',
            'brother_address' => 'nullable|string',
            'sister_name' => 'nullable|string|max:255',
            'sister_mobile' => 'nullable|string|max:20',
            'sister_gender' => 'nullable|string|max:10',
            'sister_occupation' => 'nullable|string|max:255',
            'sister_address' => 'nullable|string',
            'spouse_name' => 'nullable|string|max:255',
            'spouse_mobile' => 'nullable|string|max:20',
            'spouse_gender' => 'nullable|string|max:10',
            'spouse_occupation' => 'nullable|string|max:255',
            'spouse_address' => 'nullable|string',
            'child1_name' => 'nullable|string|max:255',
            'child1_mobile' => 'nullable|string|max:20',
            'child1_gender' => 'nullable|string|max:10',
            'child1_occupation' => 'nullable|string|max:255',
            'child1_address' => 'nullable|string',
            'child2_name' => 'nullable|string|max:255',
            'child2_mobile' => 'nullable|string|max:20',
            'child2_gender' => 'nullable|string|max:10',
            'child2_occupation' => 'nullable|string|max:255',
            'child2_address' => 'nullable|string',
            // emergency
            'emergency_contact_relation' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_gender' => 'nullable|string|max:10',
            'emergency_contact_mobile' => 'nullable|string|max:20',
            'emergency_contact_occupation' => 'nullable|string|max:255',
            'emergency_contact_address' => 'nullable|string',
            // other info
            'official_contact_no' => 'nullable|string|max:20',
            'vehicle_type' => 'nullable|string|max:50',
            'vehicle_no' => 'nullable|string|max:50',
            'paytm_no' => 'nullable|string|max:20',
            'skype_id' => 'nullable|string|max:100',
            'health_ins_opted' => 'nullable|string|max:10',
            'pf_opted' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
        ]);

        $validated['user_id'] = $employee->user_id;
        $validated['email'] = $employee->email;
        $validated['added_by'] = $employee->user_id;
        $validated['updated_by'] = $employee->user_id;
        $validated['added_date'] = date('Y-m-d H:i:s');
        $validated['updated_date'] = date('Y-m-d H:i:s');
        $validated['acceptance'] = 0;
        $validated['emp_updated_dets'] = 0;

        // Generate safe defaults dynamically from the model's fillable attributes to avoid MySQL constraint errors
        $fillable = (new EmployeeDataUpdate)->getFillable();
        $defaults = [];
        foreach ($fillable as $field) {
            if (in_array($field, ['user_id', 'added_by', 'updated_by', 'acceptance', 'emp_updated_dets', 'acceptance_basic', 'acceptance_father', 'acceptance_mother', 'acceptance_emer', 'acceptance_bro', 'acceptance_sis', 'acceptance_c1', 'acceptance_c2', 'acceptance_social', 'acceptance_spouse'])) {
                $defaults[$field] = 0;
            } elseif ($field === 'date_of_birth' || $field === 'date_of_birth_doc') {
                $defaults[$field] = '1970-01-01';
            } elseif ($field === 'added_date' || $field === 'updated_date' || $field === 'acceptance_date') {
                $defaults[$field] = date('Y-m-d H:i:s');
            } else {
                $defaults[$field] = '';
            }
        }

        $saveData = array_merge($defaults, array_filter($validated, function($val) {
            return $val !== null;
        }));

        EmployeeDataUpdate::updateOrCreate(
            ['user_id' => $employee->user_id, 'acceptance' => 0],
            $saveData
        );

        return redirect()->back()->with('success', 'Onboarding details submitted successfully. HR will review and activate your profile.');
    }
}
