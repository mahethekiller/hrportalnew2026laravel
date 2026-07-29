<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobApplicationRequest;
use App\Http\Resources\JobApplicationResource;
use App\Services\RecruitmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RecruitmentApiController extends Controller
{
    public function __construct(
        protected RecruitmentService $recruitmentService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'status']);
        $perPage = (int) $request->get('per_page', 15);
        $applications = $this->recruitmentService->getApplicationsPaginated($filters, $perPage);

        return JobApplicationResource::collection($applications);
    }

    public function show(int $id): JsonResponse
    {
        $application = $this->recruitmentService->getApplicationById($id);
        if (!$application) {
            return response()->json(['message' => 'Candidate application record not found.'], 404);
        }

        return response()->json(['data' => new JobApplicationResource($application)]);
    }

    public function store(StoreJobApplicationRequest $request): JsonResponse
    {
        $application = $this->recruitmentService->createApplication($request->validated());

        return response()->json([
            'message' => 'Candidate application submitted successfully.',
            'data' => new JobApplicationResource($application),
        ], 201);
    }
}
