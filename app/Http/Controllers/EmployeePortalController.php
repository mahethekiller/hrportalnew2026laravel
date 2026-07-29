<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Document;
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
        $announcements = Announcement::orderBy('announcement_id', 'desc')->take(3)->get();
        $meetings = Meeting::where('employee_id', $employeeId)->orderBy('meeting_id', 'desc')->take(3)->get();

        return view('my_portal.index', compact('leaves', 'payslips', 'announcements', 'meetings'));
    }

    /**
     * My Leaves & Requests
     */
    public function leaves(): View
    {
        $employeeId = $this->getEmployeeId();
        $leaves = EmployeeLeave::where('employee_id', $employeeId)->orderBy('leave_id', 'desc')->paginate(10);

        return view('my_portal.leaves', compact('leaves'));
    }

    /**
     * My Attendance & Clock Logs
     */
    public function attendance(): View
    {
        return view('my_portal.attendance');
    }

    /**
     * My Payslips
     */
    public function payslips(): View
    {
        $employeeId = $this->getEmployeeId();
        $payslips = MakePayment::where('employee_id', $employeeId)->orderBy('make_payment_id', 'desc')->paginate(10);

        return view('my_portal.payslips', compact('payslips'));
    }

    /**
     * Performance Feedback / Self Rating Form
     */
    public function performanceFeedback(): View
    {
        $forms = FeedbackForm::with('questions')->get();
        $myAnswers = FeedbackAnswer::where('employee_id', $this->getEmployeeId())
            ->orWhere('user_id', auth()->id())
            ->get()
            ->keyBy('question_id');

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
        $documents = Document::where('company_id', $this->getCompanyId())->latest()->get();
        return view('my_portal.benefits', compact('documents'));
    }

    /**
     * Employee Candidate Referrals
     */
    public function referrals(): View
    {
        $employeeId = $this->getEmployeeId();
        $referrals = Referral::where('employee_id', $employeeId)->orderBy('referral_id', 'desc')->get();
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
        $meetings = Meeting::where('company_id', $this->getCompanyId())->orderBy('meeting_id', 'desc')->get();
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
        $claims = EmployeeTravel::where('employee_id', $this->getEmployeeId())->orderBy('travel_id', 'desc')->get();
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
        $docs = IncomeDocument::where('employee_id', $this->getEmployeeId())->orderBy('document_id', 'desc')->get();
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
}
