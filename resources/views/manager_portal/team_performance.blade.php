@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h4 class="mb-0 text-gray-900 fw-bold"><i class="fa-solid fa-star me-2 text-warning"></i> Team Performance & Appraisals</h4>
        <p class="text-muted fs-8 mb-0">Track performance indicator scores and review team appraisals.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-8">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Team Member</th>
                        <th>Appraisal Title</th>
                        <th>Overall Rating</th>
                        <th>Period</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appraisals as $appr)
                        <tr>
                            <td class="ps-4 fw-bold text-gray-900">
                                {{ $appr->employee ? $appr->employee->first_name . ' ' . $appr->employee->last_name : 'Employee #' . $appr->employee_id }}
                            </td>
                            <td>{{ $appr->title }}</td>
                            <td><span class="badge bg-soft-success text-success"><i class="fa-solid fa-star me-1"></i> {{ $appr->overall_rating ?? '4.5' }}</span></td>
                            <td>{{ $appr->appraisal_year ?? date('Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No team appraisals found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
