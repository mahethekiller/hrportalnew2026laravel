@extends('layouts.app')

@section('title', 'Super Admin Dashboard')
@section('page_title', 'System Administrator Overview')

@section('content')

<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <h2 class="headline-lg text-body-emphasis mb-1">Welcome Back, {{ auth()->user()->first_name ?? auth()->user()->name ?? 'Administrator' }} 🛡️</h2>
        <p class="text-body-secondary small mb-0">System configuration, global metrics, and application deployment workstation.</p>
    </div>
</div>

<!-- Organizational Stat Counters -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">Registered Companies</span>
                    <h3 class="fw-bolder text-gray-900 mb-0 mt-1">{{ $totalCompanies }}</h3>
                </div>
                <div class="bg-light-primary text-primary p-3 rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-hotel fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">Departments</span>
                    <h3 class="fw-bolder text-gray-900 mb-0 mt-1">{{ $totalDepartments }}</h3>
                </div>
                <div class="bg-light-success text-success p-3 rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-network-wired fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">Designations</span>
                    <h3 class="fw-bolder text-gray-900 mb-0 mt-1">{{ $totalDesignations }}</h3>
                </div>
                <div class="bg-light-warning text-warning p-3 rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-briefcase fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-9 fw-semibold d-block">Office Locations</span>
                    <h3 class="fw-bolder text-gray-900 mb-0 mt-1">{{ $totalLocations }}</h3>
                </div>
                <div class="bg-light-info text-info p-3 rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-map-pin fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        
        <!-- Portal Configuration Status Card -->
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-header border-0 bg-transparent pt-4 pb-0">
                <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-sliders me-2 text-primary"></i> Active Brand Parameters</h5>
                <p class="text-muted fs-9 mb-0">These values dictate the portal identity loaded system-wide.</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 fs-8">
                        <tbody>
                            <tr>
                                <td class="fw-bold text-gray-800" style="width: 35%;">Portal Brand Name</td>
                                <td class="text-muted">{{ $systemSetting->application_name ?? 'Not Configured' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-gray-800">Support Desk Email</td>
                                <td class="text-muted">{{ $systemSetting->support_email ?? 'Not Configured' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-gray-800">System Timezone</td>
                                <td class="text-muted"><span class="badge bg-light-primary text-primary">{{ $systemSetting->system_timezone ?? 'Asia/Kolkata' }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-gray-800">Default Currency</td>
                                <td class="text-muted">{{ $systemSetting->default_currency ?? 'INR' }} ({{ $systemSetting->default_currency_symbol ?? '₹' }})</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-gray-800">Footer Copyright Label</td>
                                <td class="text-muted">{{ $systemSetting->footer_text ?? 'Not Configured' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- System Modules Check list -->
        <div class="card border-0 shadow-sm rounded-3 bg-white">
            <div class="card-header border-0 bg-transparent pt-4 pb-0">
                <h5 class="fw-bold text-gray-900 fs-6 mb-0"><i class="fa-solid fa-cubes me-2 text-info"></i> Portal Modules Operational Status</h5>
                <p class="text-muted fs-9 mb-0">Configure these flags inside System Settings to toggle sidebar modules.</p>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @php
                        $modules = [
                            'Recruitment Pipeline' => $systemSetting->module_recruitment ?? 0,
                            'Travel Management' => $systemSetting->module_travel ?? 0,
                            'Performance Appraisals' => $systemSetting->module_performance ?? 0,
                            'Assets & Inventory' => $systemSetting->module_assets ?? 0,
                            'Training Sessions' => $systemSetting->module_training ?? 0,
                        ];
                    @endphp

                    @foreach($modules as $mName => $mStatus)
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 d-flex align-items-center justify-content-between bg-light bg-opacity-50">
                                <span class="fw-bold text-gray-800 fs-8">{{ $mName }}</span>
                                @if($mStatus)
                                    <span class="badge bg-light-success text-success fs-9"><i class="fa-solid fa-circle-check me-1"></i> Active</span>
                                @else
                                    <span class="badge bg-light-secondary text-secondary fs-9"><i class="fa-solid fa-circle-xmark me-1"></i> Disabled</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column Sidebar -->
    <div class="col-lg-4">
        
        <!-- Admin Settings Quick Panel -->
        <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
            <div class="card-header border-0 bg-transparent pt-4 pb-0">
                <h5 class="fw-bold text-gray-900 fs-7 mb-0"><i class="fa-solid fa-gears text-warning me-2"></i> System Controls</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('system-settings.index') }}" class="btn btn-light-primary text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-sliders me-2"></i> Update Global Branding
                    </a>
                    <a href="{{ route('user-roles.index') }}" class="btn btn-light-primary text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-user-shield me-2"></i> Access Rules & Spatie Roles
                    </a>
                    <a href="{{ url('/api-docs') }}" class="btn btn-light-primary text-start fs-8 fw-semibold py-2">
                        <i class="fa-solid fa-code me-2"></i> View System API Docs
                    </a>
                </div>
            </div>
        </div>

        @include('dashboard.partials.sidebar_widgets')
    </div>
</div>

@endsection
