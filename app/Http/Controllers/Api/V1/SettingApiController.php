<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSystemSettingRequest;
use App\Http\Resources\SystemSettingResource;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;

class SettingApiController extends Controller
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    public function index(): JsonResponse
    {
        $setting = $this->settingService->getSystemSetting();

        return response()->json(['data' => new SystemSettingResource($setting)]);
    }

    public function update(UpdateSystemSettingRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->settingService->updateSystemSetting($data);
        $setting = $this->settingService->getSystemSetting();

        return response()->json([
            'message' => 'Global system settings updated successfully.',
            'data' => new SystemSettingResource($setting),
        ]);
    }
}
