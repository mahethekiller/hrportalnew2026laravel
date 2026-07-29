@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-clock me-2 text-primary"></i> My Attendance & Time Logs</h4>
        <p class="text-muted fs-8 mb-0">View your daily clock-in/out records and monthly working hours.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4 text-center">
        <div class="p-4 bg-light rounded-3 d-inline-block mb-3">
            <i class="fa-solid fa-business-time fa-3x text-primary"></i>
        </div>
        <h5 class="fw-bold text-gray-900">Personal Attendance Matrix</h5>
        <p class="text-muted fs-8 col-md-6 mx-auto">Your daily clock-in timestamps, working shifts, and monthly attendance logs are synchronized with your employee ID.</p>
        <a href="#" class="btn btn-primary btn-sm fw-bold px-4">
            <i class="fa-solid fa-eye me-1"></i> View Full Attendance Logs
        </a>
    </div>
</div>
@endsection
