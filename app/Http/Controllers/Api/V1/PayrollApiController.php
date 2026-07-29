<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePayrollPaymentRequest;
use App\Http\Resources\PayrollPaymentResource;
use App\Services\PayrollService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PayrollApiController extends Controller
{
    public function __construct(
        protected PayrollService $payrollService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'date', 'status']);
        $perPage = (int) $request->get('per_page', 15);
        $payments = $this->payrollService->getPaymentsPaginated($filters, $perPage);

        return PayrollPaymentResource::collection($payments);
    }

    public function show(int $id): JsonResponse
    {
        $payment = $this->payrollService->getPaymentById($id);
        if (!$payment) {
            return response()->json(['message' => 'Payroll payment record not found.'], 404);
        }

        return response()->json(['data' => new PayrollPaymentResource($payment)]);
    }

    public function store(StorePayrollPaymentRequest $request): JsonResponse
    {
        $payment = $this->payrollService->processPayment($request->validated());

        return response()->json([
            'message' => 'Payroll payment processed successfully.',
            'data' => new PayrollPaymentResource($payment),
        ], 201);
    }
}
