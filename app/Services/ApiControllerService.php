<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ApiAccessToken;
use App\Models\WebhookTrigger;
use App\Repositories\ApiControllerRepository;
use Illuminate\Database\Eloquent\Collection;

class ApiControllerService
{
    public function __construct(
        protected ApiControllerRepository $apiControllerRepository
    ) {}

    public function getTokens(): Collection
    {
        return $this->apiControllerRepository->getTokens();
    }

    public function createToken(array $data): ApiAccessToken
    {
        return $this->apiControllerRepository->createToken($data);
    }

    public function revokeToken(ApiAccessToken $token): bool
    {
        return $this->apiControllerRepository->revokeToken($token);
    }

    public function getWebhooks(): Collection
    {
        return $this->apiControllerRepository->getWebhooks();
    }

    public function createWebhook(array $data): WebhookTrigger
    {
        return $this->apiControllerRepository->createWebhook($data);
    }

    public function toggleWebhookStatus(WebhookTrigger $webhook): bool
    {
        return $this->apiControllerRepository->toggleWebhookStatus($webhook);
    }
}
