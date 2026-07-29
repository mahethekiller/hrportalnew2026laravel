<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobPostRequest;
use App\Http\Resources\JobPostResource;
use App\Services\JobPostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobPostApiController extends Controller
{
    public function __construct(
        protected JobPostService $jobPostService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'status']);
        $perPage = (int) $request->get('per_page', 15);
        $jobs = $this->jobPostService->getJobPostsPaginated($filters, $perPage);

        return JobPostResource::collection($jobs);
    }

    public function show(int $id): JsonResponse
    {
        $job = $this->jobPostService->getJobPostById($id);
        if (!$job) {
            return response()->json(['message' => 'Job opening requisition record not found.'], 404);
        }

        return response()->json(['data' => new JobPostResource($job)]);
    }

    public function store(StoreJobPostRequest $request): JsonResponse
    {
        $job = $this->jobPostService->createJobPost($request->validated());

        return response()->json([
            'message' => 'Job opening requisition published successfully.',
            'data' => new JobPostResource($job),
        ], 201);
    }
}
