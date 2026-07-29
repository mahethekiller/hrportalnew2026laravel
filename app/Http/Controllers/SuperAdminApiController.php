<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreApiTokenRequest;
use App\Http\Requests\StoreWebhookRequest;
use App\Models\ApiAccessToken;
use App\Models\WebhookTrigger;
use App\Services\ApiControllerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperAdminApiController extends Controller
{
    public function __construct(
        protected ApiControllerService $apiControllerService
    ) {}

    public function docs(): View
    {
        return view('api_docs');
    }

    public function tokens(): View
    {
        $tokens = $this->apiControllerService->getTokens();

        return view('api_control.tokens', compact('tokens'));
    }

    public function storeToken(StoreApiTokenRequest $request): RedirectResponse
    {
        $token = $this->apiControllerService->createToken($request->validated());

        return redirect()->route('api-tokens.index')
            ->with('success', 'API Access Key generated for username "' . $token->username . '". Key: ' . $token->accessToken);
    }

    public function revokeToken(ApiAccessToken $token): RedirectResponse
    {
        $this->apiControllerService->revokeToken($token);

        return redirect()->route('api-tokens.index')
            ->with('success', 'API Access Key for "' . $token->username . '" revoked successfully.');
    }

    public function webhooks(): View
    {
        $webhooks = $this->apiControllerService->getWebhooks();

        return view('api_control.webhooks', compact('webhooks'));
    }

    public function storeWebhook(StoreWebhookRequest $request): RedirectResponse
    {
        $webhook = $this->apiControllerService->createWebhook($request->validated());

        return redirect()->route('webhooks.index')
            ->with('success', 'Webhook subscription for event "' . $webhook->event_name . '" created successfully.');
    }

    public function toggleWebhook(WebhookTrigger $webhook): RedirectResponse
    {
        $this->apiControllerService->toggleWebhookStatus($webhook);

        return redirect()->route('webhooks.index')
            ->with('success', 'Webhook status toggled successfully.');
    }
}
