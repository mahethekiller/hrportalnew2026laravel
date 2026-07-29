<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Resources\AssetResource;
use App\Services\AssetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AssetApiController extends Controller
{
    public function __construct(
        protected AssetService $assetService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'status']);
        $perPage = (int) $request->get('per_page', 15);
        $assets = $this->assetService->getAssetsPaginated($filters, $perPage);

        return AssetResource::collection($assets);
    }

    public function show(int $id): JsonResponse
    {
        $asset = $this->assetService->getAssetById($id);
        if (!$asset) {
            return response()->json(['message' => 'Asset record not found.'], 404);
        }

        return response()->json(['data' => new AssetResource($asset)]);
    }

    public function store(StoreAssetRequest $request): JsonResponse
    {
        $asset = $this->assetService->createAsset($request->validated());

        return response()->json([
            'message' => 'Asset registered successfully.',
            'data' => new AssetResource($asset),
        ], 201);
    }
}
