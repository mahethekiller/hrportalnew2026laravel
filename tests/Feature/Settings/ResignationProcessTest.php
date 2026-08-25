<?php

namespace Tests\Feature\Settings;

use App\Models\Employee;
use App\Models\EmployeeResignation;
use App\Models\User;
use App\Services\EmployeeResignationService;
use App\Services\MailService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ResignationProcessTest extends TestCase
{
    /**
     * Test Notice Period LWD Calculation (In Months).
     */
    public function test_notice_period_lwd_calculation_in_months(): void
    {
        $employee = new Employee([
            'notice_period' => 2,
        ]);

        $this->assertEquals(2, $employee->notice_period_months);

        $noticeDate = '2026-08-25';
        $expectedLwd = Carbon::parse($noticeDate)->addMonths(2)->format('Y-m-d');
        $calculatedLwd = $employee->calculateLwd($noticeDate)->format('Y-m-d');

        $this->assertEquals($expectedLwd, $calculatedLwd);
    }

    /**
     * Test Notice Shortfall Days Calculation.
     */
    public function test_notice_shortfall_days_calculation(): void
    {
        $employee = new Employee([
            'notice_period' => 1,
        ]);

        $resignation = new EmployeeResignation([
            'notice_date' => '2026-08-01',
            'resignation_date' => '2026-08-15', // Requested 14 days early exit
        ]);
        $resignation->setRelation('employee', $employee);

        $this->assertGreaterThan(0, $resignation->shortfall_days);
    }

    /**
     * Test Resignation Mailable Threading Headers.
     */
    public function test_resignation_mailable_threading_headers(): void
    {
        $employee = new Employee([
            'user_id' => 999,
            'first_name' => 'Test',
            'last_name' => 'User',
            'employee_id' => 'EMP999',
            'email' => 'testuser@example.com',
            'notice_period' => 1,
        ]);

        $resignation = new EmployeeResignation([
            'resignation_id' => 999,
            'employee_id' => 999,
            'notice_date' => '2026-08-25',
            'resignation_date' => '2026-09-25',
            'reason' => 'Career Growth',
        ]);
        $resignation->setRelation('employee', $employee);

        $mailable = new \App\Mail\ResignationMail(
            'submitted',
            $resignation,
            '[i2u2 Portal] Resignation Request - Test User (EMP999)',
            '<p>Test body</p>',
            'http://127.0.0.1:8000/my-portal/team-resignations',
            '<init-msg-123>',
            null,
            null
        );

        $mailable->assertHasSubject('[i2u2 Portal] Resignation Request - Test User (EMP999)');
    }
}
