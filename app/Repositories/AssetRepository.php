<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Asset;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AssetRepository
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Asset::with(['employee', 'company']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_asset_code', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($eq) use ($search) {
                      $eq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('employee_id', 'like', "%{$search}%");
                  });
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('is_working', $filters['status']);
        }

        return $query->orderBy('assets_id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?Asset
    {
        return Asset::with(['employee', 'company'])->find($id);
    }

    public function create(array $data): Asset
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['is_working'] = $data['is_working'] ?? 1;

        return Asset::create($data);
    }

    public function getSummaryStats(): array
    {
        $total = Asset::count();
        $allocated = Asset::whereNotNull('employee_id')->where('employee_id', '!=', 0)->count();
        $inStock = Asset::where(function ($q) {
            $q->whereNull('employee_id')->orWhere('employee_id', 0);
        })->count();
        $maintenance = Asset::where('is_working', 0)->count();

        return [
            'total_assets' => $total,
            'allocated_count' => $allocated,
            'in_stock_count' => $inStock,
            'maintenance_count' => $maintenance,
        ];
    }
}
