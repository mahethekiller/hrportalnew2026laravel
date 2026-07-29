<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePerformanceAppraisalRequest;
use App\Http\Resources\PerformanceAppraisalResource;
use App\Services\PerformanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PerformanceApiController extends Controller
{
    public function __construct(
        protected PerformanceService $performanceService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'period']);
        $perPage = (int) $request->get('per_page', 15);
        $appraisals = $this->performanceService->getAppraisalsPaginated($filters, $perPage);

        return PerformanceAppraisalResource::collection($appraisals);
    }

    public function show(int $id): JsonResponse
    {
        $appraisal = $this->performanceService->getAppraisalById($id);
        if (!$appraisal) {
            return response()->json(['message' => 'Performance Appraisal record not found.'], 404);
        }

        return response()->json(['data' => new PerformanceAppraisalResource($appraisal)]);
    }

    public function store(StorePerformanceAppraisalRequest $request): JsonResponse
    {
        $appraisal = $this->performanceService->createAppraisal($request->validated());

        return response()->json([
            'message' => 'Performance Appraisal recorded successfully.',
            'data' => new PerformanceAppraisalResource($appraisal),
        ], 201);
    }
}
