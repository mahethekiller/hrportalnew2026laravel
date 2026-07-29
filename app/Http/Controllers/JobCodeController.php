<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\JobCodeRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobCodeController extends Controller
{
    public function __construct(
        protected JobCodeRepository $jobCodeRepository
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status']);
        $jobCodes = $this->jobCodeRepository->getPaginated($filters);

        return view('recruitment.job_codes', compact('jobCodes', 'filters'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'job_code' => ['required', 'string', 'max:100'],
            'position' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'integer'],
        ]);

        $data = $request->only(['job_code', 'position', 'status']);
        $data['added_by'] = Auth::user()->name ?? 'HR Manager';

        $code = $this->jobCodeRepository->create($data);

        return redirect()->route('recruitment-job-codes.index')
            ->with('success', 'Job Code Tag "' . $code->job_code . '" created successfully.');
    }

    public function update(Request $request, \App\Models\JobCode $jobCode): RedirectResponse
    {
        $request->validate([
            'job_code' => ['required', 'string', 'max:100'],
            'position' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $data = $request->only(['job_code', 'position', 'status']);
        $data['updated_by'] = Auth::user()->name ?? 'HR Manager';

        $this->jobCodeRepository->update($jobCode, $data);

        return redirect()->route('recruitment-job-codes.index')
            ->with('success', 'Job Code Tag "' . $jobCode->job_code . '" updated successfully.');
    }
}
