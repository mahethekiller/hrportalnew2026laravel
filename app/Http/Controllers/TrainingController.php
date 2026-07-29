<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrainerRequest;
use App\Http\Requests\StoreTrainingSessionRequest;
use App\Models\Employee;

use App\Models\TrainingSession;
use App\Services\TrainingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainingController extends Controller
{
    public function __construct(
        protected TrainingService $trainingService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status']);
        $sessions = $this->trainingService->getSessionsPaginated($filters);
        $summary = $this->trainingService->getSummaryStats();
        $employees = Employee::orderBy('first_name')->get();
        $trainers = $this->trainingService->getTrainers();
        $trainingTypes = $this->trainingService->getTrainingTypes();

        return view('training.index', compact('sessions', 'summary', 'employees', 'trainers', 'trainingTypes', 'filters'));
    }

    public function trainers(Request $request): View
    {
        $trainers = $this->trainingService->getTrainers();

        return view('training.trainers', compact('trainers'));
    }

    public function storeSession(StoreTrainingSessionRequest $request): RedirectResponse
    {
        $session = $this->trainingService->createSession($request->validated());

        return redirect()->route('training-sessions.index')
            ->with('success', 'Training Session scheduled successfully.');
    }

    public function storeTrainer(StoreTrainerRequest $request): RedirectResponse
    {
        $trainer = $this->trainingService->createTrainer($request->validated());

        return redirect()->route('trainers.index')
            ->with('success', 'Instructor / Trainer "' . $trainer->full_name . '" registered successfully.');
    }

    public function updateStatus(Request $request, TrainingSession $session): RedirectResponse
    {
        $request->validate([
            'training_status' => ['required', 'integer'],
            'performance' => ['nullable', 'string', 'max:255'],
        ]);

        $this->trainingService->updateSessionStatus($session, (int) $request->input('training_status'), $request->input('performance'));

        return redirect()->route('training-sessions.index')
            ->with('success', 'Training session status updated successfully.');
    }
}
