<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;

class ReportApiController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    public function summary(): JsonResponse
    {
        $overview = $this->reportService->getExecutiveOverviewStats();

        return response()->json([
            'message' => 'Executive HR portal statistics fetched successfully.',
            'data' => $overview,
        ]);
    }
}
