@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-clock me-2 text-primary"></i> Team Daily Clock-In Timesheets</h4>
        <p class="text-muted fs-8 mb-0">View live attendance logs and working hours for your direct team members.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Team Member</th>
                        <th>Email</th>
                        <th>Status Today</th>
                        <th>Work Shift</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teamMembers as $mb)
                        <tr>
                            <td class="ps-4 fw-bold text-gray-900">{{ $mb->first_name }} {{ $mb->last_name }}</td>
                            <td>{{ $mb->email }}</td>
                            <td><span class="badge bg-soft-success text-success"><i class="fa-solid fa-circle me-1 fs-9"></i> Clocked In</span></td>
                            <td>Standard Office Shift (09:00 - 18:00)</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No team members assigned.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
