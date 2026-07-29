<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Trainer;
use App\Models\TrainingSession;
use App\Repositories\TrainingRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TrainingService
{
    public function __construct(
        protected TrainingRepository $trainingRepository
    ) {}

    public function getSessionsPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->trainingRepository->getPaginatedSessions($filters, $perPage);
    }

    public function getSessionById(int $id): ?TrainingSession
    {
        return $this->trainingRepository->findSessionById($id);
    }

    public function getTrainers(): Collection
    {
        return $this->trainingRepository->getTrainers();
    }

    public function getTrainingTypes(): Collection
    {
        return $this->trainingRepository->getTrainingTypes();
    }

    public function getSummaryStats(): array
    {
        return $this->trainingRepository->getSummaryStats();
    }

    public function createSession(array $data): TrainingSession
    {
        return $this->trainingRepository->createSession($data);
    }

    public function createTrainer(array $data): Trainer
    {
        return $this->trainingRepository->createTrainer($data);
    }

    public function updateSessionStatus(TrainingSession $session, int $status, ?string $performance = null): bool
    {
        return $this->trainingRepository->updateSessionStatus($session, $status, $performance);
    }
}
