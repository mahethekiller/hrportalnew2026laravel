@extends('layouts.app')

@section('title', 'API Documentation V1')
@section('page_title', 'REST API Reference & OpenAPI Docs')

@section('content')
<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <h2 class="headline-lg text-body-emphasis mb-1">REST API V1 Documentation</h2>
        <p class="text-body-secondary small mb-0">Interactive OpenAPI 3.0 specification & Postman collections for Employee & HR APIs.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="{{ asset('docs/v1/swagger.json') }}" target="_blank" class="btn btn-light-primary btn-sm me-2">
            <i class="fa-solid fa-code me-1"></i>OpenAPI JSON
        </a>
        <a href="{{ asset('docs/v1/postman_collection.json') }}" download class="btn btn-light-success btn-sm">
            <i class="fa-solid fa-download me-1"></i>Postman Collection
        </a>
    </div>
</div>

<!-- API Overview Card -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card card-hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="btn btn-light-primary p-3 rounded-circle">
                        <i class="fa-solid fa-plug fs-3"></i>
                    </div>
                    <div>
                        <h4 class="card-title fs-6 mb-1">Base Endpoint URL</h4>
                        <code class="text-primary fw-bold">/api/v1/employees</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="btn btn-light-success p-3 rounded-circle">
                        <i class="fa-solid fa-shield-halved fs-3"></i>
                    </div>
                    <div>
                        <h4 class="card-title fs-6 mb-1">Authentication</h4>
                        <span class="badge badge-light-success">Sanctum Bearer Token</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="btn btn-light-info p-3 rounded-circle">
                        <i class="fa-solid fa-file-code fs-3"></i>
                    </div>
                    <div>
                        <h4 class="card-title fs-6 mb-1">Response Format</h4>
                        <span class="badge badge-light-primary">JSON (ISO-8601)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Endpoint Reference Table -->
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Employee API Endpoints Reference</h3>
        <span class="label-sm">REST API V1</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>HTTP Method</th>
                        <th>Endpoint Route</th>
                        <th>Description</th>
                        <th>Query Params / Payload</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge bg-success">GET</span></td>
                        <td><code>/api/v1/employees</code></td>
                        <td>Get paginated list of employees with search and filters</td>
                        <td><code>search</code>, <code>department_id</code>, <code>status</code>, <code>page</code>, <code>per_page</code></td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-primary">POST</span></td>
                        <td><code>/api/v1/employees</code></td>
                        <td>Create new employee record and linked User account</td>
                        <td><code>first_name</code>, <code>last_name</code>, <code>employee_id</code>, <code>email</code>, <code>password</code>, ...</td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-success">GET</span></td>
                        <td><code>/api/v1/employees/{id}</code></td>
                        <td>Get specific employee profile details</td>
                        <td><code>id</code> (URL parameter)</td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-warning text-dark">PUT / PATCH</span></td>
                        <td><code>/api/v1/employees/{id}</code></td>
                        <td>Update an existing employee record</td>
                        <td>Payload with modified fields</td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-danger">DELETE</span></td>
                        <td><code>/api/v1/employees/{id}</code></td>
                        <td>Delete employee record from system</td>
                        <td><code>id</code> (URL parameter)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
