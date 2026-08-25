<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $docType === 'experience' ? 'Experience Certificate' : 'Relieving Letter' }} - {{ $resignation->employee->first_name ?? '' }} {{ $resignation->employee->last_name ?? '' }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 40px;
            font-size: 14px;
            line-height: 1.6;
            background: #ffffff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header-logo {
            max-height: 60px;
            max-width: 200px;
        }
        .doc-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 30px;
            color: #0f172a;
            text-decoration: underline;
        }
        .meta-info {
            margin-bottom: 25px;
        }
        .content-body {
            text-align: justify;
            margin-bottom: 40px;
        }
        .content-body p {
            margin-bottom: 16px;
        }
        .signature-block {
            margin-top: 60px;
        }
        .signature-title {
            font-weight: bold;
            color: #0f172a;
        }
        .footer {
            position: fixed;
            bottom: 30px;
            left: 40px;
            right: 40px;
            border-top: 1px solid #cbd5e1;
            padding-top: 10px;
            font-size: 10px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td>
                <h2>{{ $resignation->employee->company->name ?? 'Company Name' }}</h2>
                <div style="color: #64748b; font-size: 12px;">Official Relieving & Exit Records</div>
            </td>
            <td align="right" valign="top">
                <strong>Date:</strong> {{ date('d M Y') }}
            </td>
        </tr>
    </table>

    <div class="doc-title">
        {{ $docType === 'experience' ? 'Experience & Character Certificate' : 'Relieving Letter' }}
    </div>

    <div class="meta-info">
        <strong>To Whom It May Concern</strong>
    </div>

    <div class="content-body">
        @if($docType === 'experience')
            <p>
                This is to certify that <strong>{{ $resignation->employee->first_name ?? '' }} {{ $resignation->employee->last_name ?? '' }}</strong> (Employee Code: <strong>{{ $resignation->employee->employee_id ?? 'N/A' }}</strong>) was employed with <strong>{{ $resignation->employee->company->name ?? 'the Company' }}</strong>.
            </p>
            <p>
                During their tenure with us, they served with dedication, diligence, and professionalism. We confirm their key employment details as follows:
            </p>
            <ul>
                <li><strong>Designation:</strong> {{ $resignation->employee->designation->designation_name ?? 'Employee' }}</li>
                <li><strong>Department:</strong> {{ $resignation->employee->department->department_name ?? 'General' }}</li>
                <li><strong>Date of Joining:</strong> {{ !empty($resignation->employee->date_of_joining) ? date('d M Y', strtotime($resignation->employee->date_of_joining)) : 'N/A' }}</li>
                <li><strong>Last Working Day:</strong> {{ !empty($resignation->resignation_date) ? date('d M Y', strtotime($resignation->resignation_date)) : date('d M Y') }}</li>
            </ul>
            <p>
                During their service, we found them to be sincere, hard-working, and result-oriented. We wish them every success in all future professional endeavors.
            </p>
        @else
            <p>
                Dear <strong>{{ $resignation->employee->first_name ?? '' }} {{ $resignation->employee->last_name ?? '' }}</strong>,
            </p>
            <p>
                With reference to your resignation notice dated <strong>{{ !empty($resignation->notice_date) ? date('d M Y', strtotime($resignation->notice_date)) : date('d M Y') }}</strong>, we hereby confirm that your resignation has been accepted, and you are relieved from your duties at <strong>{{ $resignation->employee->company->name ?? 'the Company' }}</strong> with effect from the close of business hours on <strong>{{ !empty($resignation->resignation_date) ? date('d M Y', strtotime($resignation->resignation_date)) : date('d M Y') }}</strong>.
            </p>
            <p>
                We confirm that all departmental No-Dues clearances (Reporting Manager, IT Assets, Accounts Settlement, and HR Formalities) have been successfully completed and approved.
            </p>
            <p>
                We thank you for your contributions during your tenure with us and wish you the very best in your future career endeavors.
            </p>
        @endif
    </div>

    <div class="signature-block">
        <div style="margin-bottom: 50px;">Sincerely,</div>
        <div class="signature-title">For {{ $resignation->employee->company->name ?? 'Company' }}</div>
        <br><br>
        <div>_______________________________</div>
        <div style="font-weight: bold; margin-top: 5px;">Authorized Signatory - Human Resources</div>
        <div style="color: #64748b; font-size: 12px;">This is an official system-generated document issued by i2u2 HR Portal.</div>
    </div>

    <div class="footer">
        Confidential Document | Issued by {{ $resignation->employee->company->name ?? 'Company' }} HR Department
    </div>
</body>
</html>
