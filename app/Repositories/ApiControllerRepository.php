<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ApiAccessToken;
use App\Models\WebhookTrigger;
use Illuminate\Database\Eloquent\Collection;

class ApiControllerRepository
{
    public function getTokens(): Collection
    {
        return ApiAccessToken::orderBy('id', 'desc')->get();
    }

    public function createToken(array $data): ApiAccessToken
    {
        $data['status'] = $data['status'] ?? 'active';
        $data['added_date'] = date('Y-m-d H:i:s');
        if (empty($data['accessToken'])) {
            $data['accessToken'] = 'ag_live_' . bin2hex(random_bytes(24));
        }

        return ApiAccessToken::create($data);
    }

    public function revokeToken(ApiAccessToken $token): bool
    {
        return $token->update(['status' => 'revoked']);
    }

    public function getWebhooks(): Collection
    {
        return WebhookTrigger::orderBy('webhook_id', 'desc')->get();
    }

    public function createWebhook(array $data): WebhookTrigger
    {
        $data['status'] = $data['status'] ?? 'active';
        $data['created_at'] = date('Y-m-d H:i:s');
        if (empty($data['secret_key'])) {
            $data['secret_key'] = 'whsec_' . bin2hex(random_bytes(16));
        }

        return WebhookTrigger::create($data);
    }

    public function toggleWebhookStatus(WebhookTrigger $webhook): bool
    {
        $newStatus = strtolower((string)$webhook->status) === 'active' ? 'disabled' : 'active';
        return $webhook->update(['status' => $newStatus]);
    }
}
