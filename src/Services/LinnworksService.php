<?php

namespace AdminUI\AdminUILinnworks\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use AdminUI\AdminUI\Models\Configuration;

class LinnworksService
{
    public string $baseUrl = "https://api.linnworks.net/api/";
    public string $cacheKey = "linnworks_access_data";
    public bool $enabled = false;
    public string $appId;
    public string $appSecret;
    public string $refreshToken;
    public string $accessToken;
    public array $appData = [];

    public function __construct()
    {
        $this->enabled = config('linnworks.enabled') ?? false;
        $this->appId = config('linnworks.app_id') ?? "";
        $this->appSecret = config('linnworks.app_secret') ?? "";
        $this->refreshToken = config('linnworks.refresh_token') ?? "";
    }

    public function getApiToken()
    {
        $response = Cache::get($this->cacheKey);

        if (empty($response)) {
            $response = Http::acceptJson()->post($this->getUrl('Auth/AuthorizeByApplication'), [
                'ApplicationId' => $this->appId,
                'ApplicationSecret' => $this->appSecret,
                'Token' => $this->refreshToken
            ])->json();

            $expirationDate = $response['ExpirationDate'];
            $time = Carbon::parse($expirationDate)->subMinutes(60 * 24);
            $diff = now()->diffInMinutes($time);

            Cache::put($this->cacheKey, $response, $diff);
        }

        $this->appData = $response;
        $this->accessToken = $response['Token'];

        return $this->accessToken;
    }

    public function isConnected(): bool
    {
        $this->getApiToken();
        return !empty($this->refreshToken);
    }

    public function getUrl(string $relativeUrl): string
    {
        return $this->baseUrl . $relativeUrl;
    }
}
