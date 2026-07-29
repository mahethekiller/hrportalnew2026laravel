<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrainingSessionRequest;
use App\Http\Resources\TrainingSessionResource;
use App\Services\TrainingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TrainingApiController extends Controller
{
    public function __construct(
        protected TrainingService $trainingService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'status']);
        $perPage = (int) $request->get('per_page', 15);
        $sessions = $this->trainingService->getSessionsPaginated($filters, $perPage);

        return TrainingSessionResource::collection($sessions);
    }

    public function show(int $id): JsonResponse
    {
        $session = $this->trainingService->getSessionById($id);
        if (!$session) {
            return response()->json(['message' => 'Training session record not found.'], 404);
        }

        return response()->json(['data' => new TrainingSessionResource($session)]);
    }

    public function store(StoreTrainingSessionRequest $request): JsonResponse
    {
        $session = $this->trainingService->createSession($request->validated());

        return response()->json([
            'message' => 'Training session scheduled successfully.',
            'data' => new TrainingSessionResource($session),
        ], 201);
    }
}
