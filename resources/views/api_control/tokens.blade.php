@extends('layouts.app')

@section('title', 'API Access Keys Manager')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Header Banner -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-900">API Access Keys Manager</h1>
            <p class="text-muted fs-7 mb-0">Generate, inspect, and revoke API access credentials for developer integrations.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('api.docs') }}" class="btn btn-light-primary btn-sm">
                <i class="fa-solid fa-code me-1"></i> API Documentation
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createTokenModal">
                <i class="fa-solid fa-key me-1"></i> Generate New API Key
            </button>
        </div>
    </div>

    <!-- API Keys Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 gs-4 fs-7">
                    <thead class="table-light text-muted fw-bold text-uppercase fs-9">
                        <tr>
                            <th class="ps-4">App / Service Username</th>
                            <th>Masked API Access Key</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tokens as $tk)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-gray-900"><i class="fa-solid fa-user-gear me-2 text-primary"></i>{{ $tk->username }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-light-secondary font-monospace fs-8">{{ $tk->masked_token }}</span>
                                </td>
                                <td>
                                    <span class="fs-8 text-gray-800">{{ $tk->added_date ?? '--' }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $tk->status_badge_class }}">
                                        {{ ucfirst($tk->status ?? 'Active') }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    @if(strtolower((string)$tk->status) !== 'revoked')
                                        <form method="POST" action="{{ route('api-tokens.revoke', $tk->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light-danger py-1 px-2 fs-8" onclick="return confirm('Revoke this API Access key?')">
                                                <i class="fa-solid fa-ban me-1"></i> Revoke Key
                                            </button>
                                        </form>
                                    @else
                                        <span class="fs-9 text-muted">Revoked</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-key fs-2 mb-2 d-block text-muted"></i>
                                    No API access keys generated yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Generate API Key -->
<div class="modal fade" id="createTokenModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('api-tokens.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-key me-2 text-primary"></i> Generate API Access Key</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold">Service / Application Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control form-control-sm" required placeholder="e.g. MobileApp_Client / PayrollSyncService">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Generate API Key</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
