<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Asset;
use App\Repositories\AssetRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AssetService
{
    public function __construct(
        protected AssetRepository $assetRepository
    ) {}

    public function getAssetsPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->assetRepository->getPaginated($filters, $perPage);
    }

    public function getAssetById(int $id): ?Asset
    {
        return $this->assetRepository->findById($id);
    }

    public function getSummaryStats(): array
    {
        return $this->assetRepository->getSummaryStats();
    }

    public function createAsset(array $data): Asset
    {
        if (empty($data['company_asset_code'])) {
            $data['company_asset_code'] = 'AST-' . strtoupper(substr(uniqid(), -6));
        }

        return $this->assetRepository->create($data);
    }
}
