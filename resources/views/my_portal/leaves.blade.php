@extends('layouts.app')

@section('title', 'My Time Off & Leaves')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fs-9 fw-semibold">
                    <i class="fa-solid fa-user-clock me-1"></i> Self-Service Portal
                </span>
                <span class="text-body-tertiary fs-9">•</span>
                <span class="text-body-secondary fs-8 fw-medium">Leave Management</span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-body-emphasis">My Time Off & Leaves</h1>
            <p class="text-body-secondary fs-7 mb-0">Plan, submit, and track the status of your personal time-off requests and leave history.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-primary btn-sm fw-semibold shadow-sm px-3 py-2 transition-all hover-lift" data-bs-toggle="modal" data-bs-target="#leaveModal">
                <i class="fa-solid fa-plus me-1.5"></i> Apply for Time Off
            </button>
        </div>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="fa-solid fa-circle-exclamation fs-5 text-danger"></i>
            <div class="flex-grow-1 fs-8">
                <strong>Attention required:</strong> {{ $errors->first() }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Metric KPI Cards (Airy & Elevated: DENSITY: 3, MOTION: 4, VARIANCE: 6) -->
    <div class="row g-3 mb-4">
        <!-- Total Requests -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Total Requests</span>
                        <div class="stat-icon-wrapper rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-calendar-days fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-body-emphasis mb-1">{{ $stats['total'] ?? $leaves->total() }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">All-time applications submitted</p>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Approved Time Off</span>
                        <div class="stat-icon-wrapper rounded-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-circle-check fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-success mb-1">{{ $stats['approved'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Confirmed & granted requests</p>
                </div>
            </div>
        </div>

        <!-- Pending Approval -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Pending Approval</span>
                        <div class="stat-icon-wrapper rounded-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-hourglass-half fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-warning mb-1">{{ $stats['pending'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Awaiting manager review</p>
                </div>
            </div>
        </div>

        <!-- Rejected / Cancelled -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-body transition-all hover-lift">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-8 fw-semibold text-body-secondary">Declined / Revoked</span>
                        <div class="stat-icon-wrapper rounded-2 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-circle-xmark fs-7"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-body-secondary mb-1">{{ $stats['rejected'] ?? 0 }}</h3>
                    <p class="fs-9 text-body-secondary mb-0">Unapproved or cancelled</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications Table Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-body">
        <!-- Card Header with Filter Controls -->
        <div class="card-header bg-transparent border-bottom py-3 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-list-check text-primary fs-6"></i>
                    <h5 class="mb-0 fw-bold text-body-emphasis">Leave Application History</h5>
                    <span class="badge bg-body-tertiary text-body-secondary border rounded-pill px-2.5 fs-9 fw-normal ms-1">
                        {{ $leaves->total() }} Total
                    </span>
                </div>

                <!-- Table Quick Filters -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="btn-group btn-group-sm" role="group" id="statusFilterTabs">
                        <button type="button" class="btn btn-outline-secondary active fw-semibold" data-status-filter="all">All</button>
                        <button type="button" class="btn btn-outline-secondary fw-semibold" data-status-filter="approved">Approved</button>
                        <button type="button" class="btn btn-outline-secondary fw-semibold" data-status-filter="pending">Pending</button>
                        <button type="button" class="btn btn-outline-secondary fw-semibold" data-status-filter="rejected">Rejected</button>
                    </div>
                    <div class="input-group input-group-sm" style="width: 220px;">
                        <span class="input-group-text bg-body border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass fs-9"></i></span>
                        <input type="text" id="leaveTableSearch" class="form-control bg-body border-start-0 fs-8" placeholder="Search leaves...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-8" id="leavesTable">
                    <thead class="table-light border-bottom">
                        <tr>
                            <!-- Rule 8: Action Buttons in Column 1 -->
                            <th class="ps-4" style="width: 90px;">Actions</th>
                            <th style="width: 110px;">Leave Ref</th>
                            <th style="width: 180px;">Category</th>
                            <th style="width: 210px;">Date Range</th>
                            <th style="width: 100px;">Duration</th>
                            <th>Reason</th>
                            <th style="width: 140px;">Applied Date</th>
                            <th class="pe-4 text-end" style="width: 130px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            @php
                                $leaveId = $leave->leave_id ?? $leave->id;
                                $statusVal = (int) $leave->status;
                                $isApproved = in_array($leave->status, [2, 'Approved', '2'], true);
                                $isRejected = in_array($leave->status, [3, 'Rejected', '3'], true);
                                $isPending = in_array($leave->status, [1, 'Pending', '1'], true) || (!$isApproved && !isRejected);

                                $statusClass = $isApproved ? 'approved' : ($isRejected ? 'rejected' : 'pending');

                                $fromDate = \Carbon\Carbon::parse($leave->from_date);
                                $toDate = \Carbon\Carbon::parse($leave->to_date);
                                $dayDiff = $fromDate->diffInDays($toDate) + 1;
                                
                                $typeName = \App\Models\LeaveApplication::LEAVE_TYPES[(int) $leave->leave_type_id] ?? 'Leave';
                            @endphp
                            <tr class="leave-row transition-colors" data-status="{{ $statusClass }}">
                                <!-- Rule 8: Column 1 Icon-Only Action Buttons with explicit gap -->
                                <td class="ps-4">
                                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary px-2.5 rounded-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewLeaveModal{{ $leaveId }}" 
                                                title="View Application Details">
                                            <i class="fa-solid fa-eye fs-8"></i>
                                        </button>
                                    </div>
                                </td>

                                <!-- Leave Ref -->
                                <td>
                                    <span class="badge bg-body border text-body-emphasis font-monospace fs-9 fw-semibold px-2 py-1">
                                        #LEV-{{ str_pad($leaveId, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                <!-- Category -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; min-width: 24px;">
                                            <i class="fa-solid fa-suitcase fs-9"></i>
                                        </div>
                                        <span class="fw-semibold text-body-emphasis">{{ $typeName }}</span>
                                    </div>
                                </td>

                                <!-- Date Range -->
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center gap-1.5 text-body-emphasis fw-medium">
                                            <i class="fa-regular fa-calendar text-body-secondary fs-9"></i>
                                            <span>{{ $fromDate->format('d M, Y') }}</span>
                                            <i class="fa-solid fa-arrow-right text-body-tertiary fs-9 mx-0.5"></i>
                                            <span>{{ $toDate->format('d M, Y') }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Duration -->
                                <td>
                                    <span class="badge bg-body-tertiary text-body border rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                        {{ $dayDiff }} {{ Str::plural('Day', $dayDiff) }}
                                    </span>
                                </td>

                                <!-- Reason -->
                                <td>
                                    <span class="text-body-secondary" title="{{ $leave->reason }}">
                                        {{ Str::limit($leave->reason, 42, '...') }}
                                    </span>
                                </td>

                                <!-- Applied Date -->
                                <td>
                                    <span class="text-body-secondary fs-9">
                                        {{ !empty($leave->applied_on) ? \Carbon\Carbon::parse($leave->applied_on)->format('d M, Y h:i A') : ($leave->created_at ? \Carbon\Carbon::parse($leave->created_at)->format('d M, Y') : '-') }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="pe-4 text-end">
                                    @if($isApproved)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                            <i class="fa-solid fa-circle-check me-1"></i> Approved
                                        </span>
                                    @elseif($isRejected)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                            <i class="fa-solid fa-circle-xmark me-1"></i> Rejected
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 fs-9 fw-semibold">
                                            <i class="fa-solid fa-clock me-1"></i> Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Detail Modal for each application -->
                            <div class="modal fade" id="viewLeaveModal{{ $leaveId }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-bottom py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="fa-solid fa-calendar-check fs-7"></i>
                                                </div>
                                                <div>
                                                    <h5 class="modal-title fw-bold text-body-emphasis mb-0">Leave Details #LEV-{{ str_pad($leaveId, 4, '0', STR_PAD_LEFT) }}</h5>
                                                    <span class="fs-9 text-body-secondary">{{ $typeName }} Application</span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 fs-8">
                                            <!-- Status Banner inside Modal -->
                                            <div class="p-3 rounded-3 mb-3 d-flex align-items-center justify-content-between {{ $isApproved ? 'bg-success-subtle border border-success-subtle text-success' : ($isRejected ? 'bg-danger-subtle border border-danger-subtle text-danger' : 'bg-warning-subtle border border-warning-subtle text-warning') }}">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fa-solid {{ $isApproved ? 'fa-circle-check' : ($isRejected ? 'fa-circle-xmark' : 'fa-hourglass-half') }} fs-6"></i>
                                                    <span class="fw-bold">Current Status: {{ $isApproved ? 'Approved' : ($isRejected ? 'Rejected' : 'Pending Approval') }}</span>
                                                </div>
                                                <span class="badge bg-body text-body-emphasis border fs-9 font-monospace">
                                                    {{ $dayDiff }} {{ Str::plural('Day', $dayDiff) }}
                                                </span>
                                            </div>

                                            <div class="row g-3 mb-3">
                                                <div class="col-6">
                                                    <label class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-1">From Date</label>
                                                    <div class="fw-bold text-body-emphasis fs-8">
                                                        <i class="fa-regular fa-calendar me-1 text-primary"></i> {{ $fromDate->format('d M, Y (D)') }}
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-1">To Date</label>
                                                    <div class="fw-bold text-body-emphasis fs-8">
                                                        <i class="fa-regular fa-calendar me-1 text-primary"></i> {{ $toDate->format('d M, Y (D)') }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-1">Reason for Leave</label>
                                                <div class="p-3 rounded-2 bg-body-tertiary border text-body-emphasis line-height-base">
                                                    {{ $leave->reason ?: 'No description provided.' }}
                                                </div>
                                            </div>

                                            @if(!empty($leave->remarks))
                                                <div class="mb-3">
                                                    <label class="text-body-secondary fs-9 fw-semibold text-uppercase d-block mb-1">Approver Remarks</label>
                                                    <div class="p-3 rounded-2 bg-body-secondary border text-body-emphasis">
                                                        {{ $leave->remarks }}
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="d-flex align-items-center justify-content-between pt-2 border-top fs-9 text-body-secondary">
                                                <span>Submitted on:</span>
                                                <span class="fw-medium text-body">
                                                    {{ !empty($leave->applied_on) ? \Carbon\Carbon::parse($leave->applied_on)->format('d M, Y h:i A') : ($leave->created_at ? \Carbon\Carbon::parse($leave->created_at)->format('d M, Y') : '-') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top py-2 px-4">
                                            <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="8" class="text-center py-5 text-body-secondary">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-4">
                                        <div class="rounded-circle bg-body-tertiary p-3 mb-3 border text-body-secondary opacity-75">
                                            <i class="fa-regular fa-calendar-xmark fs-2"></i>
                                        </div>
                                        <h6 class="fw-bold text-body-emphasis mb-1">No Leave Applications Found</h6>
                                        <p class="fs-8 text-body-secondary mb-3">You haven't submitted any time-off requests yet.</p>
                                        <button type="button" class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#leaveModal">
                                            <i class="fa-solid fa-plus me-1"></i> Apply for Time Off
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            @if($leaves->hasPages())
                <div class="card-footer bg-transparent border-top py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span class="fs-9 text-body-secondary">
                        Showing {{ $leaves->firstItem() }} to {{ $leaves->lastItem() }} of {{ $leaves->total() }} applications
                    </span>
                    <div>
                        {{ $leaves->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Apply for Time Off (Rule 9, 10, 11, 12 Compliant) -->
<div class="modal fade" id="leaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="fa-solid fa-plane-departure fs-6"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-body-emphasis mb-0">Apply for Time Off</h5>
                        <p class="fs-9 text-body-secondary mb-0">Submit a leave request for managerial approval</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('my-portal.leaves.store') }}" method="POST" id="leaveApplicationForm">
                @csrf
                <div class="modal-body p-4 fs-8">
                    <!-- Rule 10: In-modal error alerts -->
                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-3 py-2 px-3 fs-8 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <div>{{ $errors->first() }}</div>
                        </div>
                    @endif

                    <!-- Leave Category (Rule 9: Searchable Select) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-body-emphasis fs-8 mb-1">
                            Leave Category <span class="text-danger">*</span>
                        </label>
                        <select name="leave_type_id" id="leave_type_select" class="form-select fs-8 select-search" required>
                            <option value="">-- Select Leave Category --</option>
                            <option value="1" {{ old('leave_type_id') == '1' ? 'selected' : '' }}>Casual Leave (CL)</option>
                            <option value="2" {{ old('leave_type_id') == '2' ? 'selected' : '' }}>Earned Leave (EL)</option>
                            <option value="3" {{ old('leave_type_id') == '3' ? 'selected' : '' }}>Restricted Holiday (RH)</option>
                            <option value="11" {{ old('leave_type_id') == '11' ? 'selected' : '' }}>Leave Without Pay (LWP)</option>
                            <option value="4" {{ old('leave_type_id') == '4' ? 'selected' : '' }}>Compensatory Off (COMP OFF)</option>
                            <option value="5" {{ old('leave_type_id') == '5' ? 'selected' : '' }}>Maternity Leave</option>
                            <option value="6" {{ old('leave_type_id') == '6' ? 'selected' : '' }}>Paternity Leave</option>
                            <option value="7" {{ old('leave_type_id') == '7' ? 'selected' : '' }}>Marriage Leave</option>
                            <option value="8" {{ old('leave_type_id') == '8' ? 'selected' : '' }}>Work From Home (WFH)</option>
                            <option value="9" {{ old('leave_type_id') == '9' ? 'selected' : '' }}>Covid / Medical Leave</option>
                            <option value="10" {{ old('leave_type_id') == '10' ? 'selected' : '' }}>Bereavement Leave</option>
                        </select>
                    </div>

                    <!-- Date Range Selection with Real-time Duration Counter -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-body-emphasis fs-8 mb-1">
                                From Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   name="from_date" 
                                   id="leave_from_date" 
                                   class="form-control fs-8" 
                                   value="{{ old('from_date', date('Y-m-d')) }}" 
                                   required 
                                   onchange="calculateLeaveDuration()">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-body-emphasis fs-8 mb-1">
                                To Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   name="to_date" 
                                   id="leave_to_date" 
                                   class="form-control fs-8" 
                                   value="{{ old('to_date', date('Y-m-d')) }}" 
                                   required 
                                   onchange="calculateLeaveDuration()">
                        </div>
                    </div>

                    <!-- Live Duration Feedback Pill -->
                    <div class="mb-3 p-2.5 rounded-2 bg-body-tertiary border d-flex align-items-center justify-content-between fs-8">
                        <div class="d-flex align-items-center gap-2 text-body-secondary">
                            <i class="fa-solid fa-stopwatch text-primary"></i>
                            <span>Calculated Duration:</span>
                        </div>
                        <span id="leaveDurationBadge" class="badge bg-primary text-white font-monospace fs-9 py-1 px-2.5 rounded-pill">
                            1 Day
                        </span>
                    </div>

                    <!-- Reason Input -->
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-semibold text-body-emphasis fs-8 mb-0">
                                Reason for Time Off <span class="text-danger">*</span>
                            </label>
                            <span class="fs-9 text-body-secondary" id="reasonCharCount">0 / 250</span>
                        </div>
                        <textarea name="reason" 
                                  id="leave_reason_input" 
                                  class="form-control fs-8" 
                                  rows="3" 
                                  maxlength="250"
                                  placeholder="Please provide details regarding your leave request..." 
                                  required 
                                  onkeyup="updateReasonCount(this)">{{ old('reason') }}</textarea>
                    </div>
                </div>

                <!-- Rule 12: Mandatory Form Submit Disabling & Loading Spinner -->
                <div class="modal-footer border-top py-2.5 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 submit-loader" onclick="submitWithLoader(this)">
                        <i class="fa-solid fa-paper-plane me-1"></i> Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.375rem 0.75rem rgba(0, 0, 0, 0.08) !important;
    }
    .transition-colors {
        transition: background-color 0.15s ease-in-out;
    }
    #leavesTable th {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        font-size: 0.75rem;
    }
</style>
@endpush

@push('js')
<script>
    // Live Duration Calculator
    function calculateLeaveDuration() {
        const fromInput = document.getElementById('leave_from_date');
        const toInput = document.getElementById('leave_to_date');
        const badge = document.getElementById('leaveDurationBadge');

        if (!fromInput || !toInput || !badge) return;

        const fromVal = fromInput.value;
        const toVal = toInput.value;

        if (fromVal && toVal) {
            const start = new Date(fromVal);
            const end = new Date(toVal);

            if (end < start) {
                badge.className = 'badge bg-danger text-white font-monospace fs-9 py-1 px-2.5 rounded-pill';
                badge.textContent = 'Invalid: To Date is before From Date';
                return;
            }

            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

            badge.className = 'badge bg-primary text-white font-monospace fs-9 py-1 px-2.5 rounded-pill';
            badge.textContent = diffDays + (diffDays === 1 ? ' Day' : ' Days');
        }
    }

    function updateReasonCount(el) {
        const counter = document.getElementById('reasonCharCount');
        if (counter && el) {
            counter.textContent = el.value.length + ' / 250';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initial duration calc
        calculateLeaveDuration();

        // Initial character count
        const reasonInput = document.getElementById('leave_reason_input');
        if (reasonInput) updateReasonCount(reasonInput);

        // Client-side quick filter tabs
        const filterTabs = document.querySelectorAll('#statusFilterTabs button');
        const rows = document.querySelectorAll('.leave-row');

        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                filterTabs.forEach(t => t.classList.remove('active', 'btn-primary'));
                filterTabs.forEach(t => t.classList.add('btn-outline-secondary'));
                this.classList.remove('btn-outline-secondary');
                this.classList.add('active', 'btn-primary');

                const filterVal = this.getAttribute('data-status-filter');
                rows.forEach(row => {
                    const rowStatus = row.getAttribute('data-status');
                    if (filterVal === 'all' || rowStatus === filterVal) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });

        // Instant search filtering
        const searchInput = document.getElementById('leaveTableSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase().trim();
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }

        // Re-open modal if validation errors exist (Rule 10)
        @if(isset($errors) && $errors->any())
            const leaveModalEl = document.getElementById('leaveModal');
            if (leaveModalEl && typeof bootstrap !== 'undefined') {
                const modalInstance = new bootstrap.Modal(leaveModalEl);
                modalInstance.show();
            }
        @endif
    });
</script>
@endpush
