@extends('layouts.app')

@section('title', 'REST API V1 Developer Specification Suite')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">REST API V1 OpenAPI Specification Suite</h1>
            <p class="text-muted fs-7 mb-0">Interactive developer documentation, live response schema inspection, and test request parameters for enterprise integration.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('api-tokens.index') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-key me-1"></i> API Access Keys
            </a>
            <a href="{{ route('webhooks.index') }}" class="btn btn-light-info btn-sm">
                <i class="fa-solid fa-bolt me-1"></i> Webhooks
            </a>
        </div>
    </div>

    <!-- Interactive API Specification Accordion -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-0 pt-3 bg-light bg-opacity-50">
            <h3 class="card-title fw-bold text-gray-900 fs-6">
                <i class="fa-solid fa-code me-2 text-primary"></i> OpenAPI 3.0 Endpoints Directory
            </h3>
        </div>
        <div class="card-body p-4">
            <div class="accordion" id="apiDocsAccordion">
                
                <!-- Endpoint 1: Employees -->
                <div class="accordion-item border mb-3 rounded overflow-hidden">
                    <h2 class="accordion-header" id="headingEmp">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEmp">
                            <span class="badge bg-success me-3 font-monospace">GET / API / V1 / EMPLOYEES</span>
                            <span class="fw-bold text-gray-900">List Employees Directory</span>
                        </button>
                    </h2>
                    <div id="collapseEmp" class="accordion-collapse collapse" data-bs-parent="#apiDocsAccordion">
                        <div class="accordion-body bg-light bg-opacity-25">
                            <p class="fs-8 text-muted">Returns paginated list of company employee profiles with demographics and departmental metadata.</p>
                            <h6 class="fs-8 fw-bold text-uppercase text-gray-800">Sample Response (200 OK)</h6>
                            <pre class="bg-dark text-success p-3 rounded fs-9 font-monospace">{
  "data": [
    {
      "user_id": 1,
      "employee_id": "EMP-001",
      "first_name": "Antigravity",
      "last_name": "Admin",
      "email": "admin@company.com",
      "department": "Engineering",
      "designation": "Principal Software Architect"
    }
  ],
  "links": { ... },
  "meta": { ... }
}</pre>
                            <div class="mt-2">
                                <span class="fs-9 text-muted fw-bold">cURL Command:</span>
                                <code class="d-block bg-white p-2 border rounded fs-9 text-dark font-monospace">curl -X GET "{{ url('/api/v1/employees') }}" -H "Accept: application/json"</code>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endpoint 2: Leave Applications -->
                <div class="accordion-item border mb-3 rounded overflow-hidden">
                    <h2 class="accordion-header" id="headingLeave">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLeave">
                            <span class="badge bg-primary me-3 font-monospace">POST / API / V1 / LEAVES</span>
                            <span class="fw-bold text-gray-900">Submit Employee Leave Request</span>
                        </button>
                    </h2>
                    <div id="collapseLeave" class="accordion-collapse collapse" data-bs-parent="#apiDocsAccordion">
                        <div class="accordion-body bg-light bg-opacity-25">
                            <p class="fs-8 text-muted">Submits a new employee leave application with date range and reason.</p>
                            <h6 class="fs-8 fw-bold text-uppercase text-gray-800">Request Body Parameters</h6>
                            <pre class="bg-dark text-info p-3 rounded fs-9 font-monospace">{
  "employee_id": 1,
  "leave_type_id": 1,
  "from_date": "2026-08-10",
  "to_date": "2026-08-12",
  "reason": "Annual Paid Leave"
}</pre>
                            <div class="mt-2">
                                <span class="fs-9 text-muted fw-bold">cURL Command:</span>
                                <code class="d-block bg-white p-2 border rounded fs-9 text-dark font-monospace">curl -X POST "{{ url('/api/v1/leaves') }}" -H "Content-Type: application/json" -d '{"employee_id":1,"leave_type_id":1,"from_date":"2026-08-10","to_date":"2026-08-12","reason":"Annual Leave"}'</code>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endpoint 3: Payroll -->
                <div class="accordion-item border mb-3 rounded overflow-hidden">
                    <h2 class="accordion-header" id="headingPayroll">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePayroll">
                            <span class="badge bg-success me-3 font-monospace">GET / API / V1 / PAYROLL</span>
                            <span class="fw-bold text-gray-900">List Monthly Payroll Disbursements</span>
                        </button>
                    </h2>
                    <div id="collapsePayroll" class="accordion-collapse collapse" data-bs-parent="#apiDocsAccordion">
                        <div class="accordion-body bg-light bg-opacity-25">
                            <p class="fs-8 text-muted">Fetches processed monthly employee salary payment records.</p>
                            <div class="mt-2">
                                <span class="fs-9 text-muted fw-bold">cURL Command:</span>
                                <code class="d-block bg-white p-2 border rounded fs-9 text-dark font-monospace">curl -X GET "{{ url('/api/v1/payroll') }}" -H "Accept: application/json"</code>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endpoint 4: Training Sessions -->
                <div class="accordion-item border mb-3 rounded overflow-hidden">
                    <h2 class="accordion-header" id="headingTraining">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTraining">
                            <span class="badge bg-success me-3 font-monospace">GET / API / V1 / TRAINING-SESSIONS</span>
                            <span class="fw-bold text-gray-900">List Training Sessions</span>
                        </button>
                    </h2>
                    <div id="collapseTraining" class="accordion-collapse collapse" data-bs-parent="#apiDocsAccordion">
                        <div class="accordion-body bg-light bg-opacity-25">
                            <p class="fs-8 text-muted">Returns list of employee training courses and progress statuses.</p>
                            <div class="mt-2">
                                <span class="fs-9 text-muted fw-bold">cURL Command:</span>
                                <code class="d-block bg-white p-2 border rounded fs-9 text-dark font-monospace">curl -X GET "{{ url('/api/v1/training-sessions') }}" -H "Accept: application/json"</code>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endpoint 5: System Settings -->
                <div class="accordion-item border rounded overflow-hidden">
                    <h2 class="accordion-header" id="headingSettings">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSettings">
                            <span class="badge bg-warning me-3 font-monospace">PUT / API / V1 / SYSTEM-SETTINGS</span>
                            <span class="fw-bold text-gray-900">Update Global System Settings</span>
                        </button>
                    </h2>
                    <div id="collapseSettings" class="accordion-collapse collapse" data-bs-parent="#apiDocsAccordion">
                        <div class="accordion-body bg-light bg-opacity-25">
                            <p class="fs-8 text-muted">Updates portal application name, currency symbol, and active module flags.</p>
                            <div class="mt-2">
                                <span class="fs-9 text-muted fw-bold">cURL Command:</span>
                                <code class="d-block bg-white p-2 border rounded fs-9 text-dark font-monospace">curl -X PUT "{{ url('/api/v1/system-settings') }}" -H "Content-Type: application/json" -d '{"application_name":"Antigravity Portal","support_email":"admin@company.com"}'</code>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
