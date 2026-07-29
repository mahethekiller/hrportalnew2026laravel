<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Trainer;
use App\Models\TrainingSession;
use App\Models\TrainingType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TrainingRepository
{
    public function getPaginatedSessions(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = TrainingSession::with(['employee', 'trainer', 'trainingType']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('employee', function ($eq) use ($search) {
                    $eq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('trainingType', function ($tq) use ($search) {
                    $tq->where('type', 'like', "%{$search}%");
                })->orWhereHas('trainer', function ($trq) use ($search) {
                    $trq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('training_status', $filters['status']);
        }

        return $query->orderBy('training_id', 'desc')->paginate($perPage);
    }

    public function findSessionById(int $id): ?TrainingSession
    {
        return TrainingSession::with(['employee', 'trainer', 'trainingType'])->find($id);
    }

    public function getTrainers(): Collection
    {
        return Trainer::orderBy('first_name')->get();
    }

    public function getTrainingTypes(): Collection
    {
        return TrainingType::orderBy('type')->get();
    }

    public function createSession(array $data): TrainingSession
    {
        $data['company_id'] = $data['company_id'] ?? 1;
        $data['training_status'] = $data['training_status'] ?? 0;
        $data['created_at'] = date('Y-m-d H:i:s');

        return TrainingSession::create($data);
    }

    public function createTrainer(array $data): Trainer
    {
        $data['company_id'] = $data['company_id'] ?? 1;
        $data['status'] = $data['status'] ?? 'active';
        $data['created_at'] = date('Y-m-d H:i:s');

        return Trainer::create($data);
    }

    public function updateSessionStatus(TrainingSession $session, int $status, ?string $performance = null): bool
    {
        $payload = ['training_status' => $status];
        if ($performance !== null) {
            $payload['performance'] = $performance;
        }

        return $session->update($payload);
    }

    public function getSummaryStats(): array
    {
        $all = TrainingSession::all();
        $total = $all->count();
        $pending = $all->where('training_status', 0)->count();
        $inProgress = $all->where('training_status', 1)->count();
        $completed = $all->where('training_status', 2)->count();
        $totalCost = (float) $all->sum('training_cost');

        return [
            'total_sessions' => $total,
            'pending_sessions' => $pending,
            'in_progress_sessions' => $inProgress,
            'completed_sessions' => $completed,
            'total_investment' => '₹' . number_format($totalCost, 2),
        ];
    }
}
