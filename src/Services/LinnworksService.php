<?php

namespace AdminUI\AdminUILinnworks\Services;

use Illuminate\Support\Str;
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
    public string $locality;
    public string $refreshToken;
    public string $accessToken;
    public array $appData = [];

    public function __construct()
    {
        $this->enabled = config('linnworks.enabled') ?? false;
        $this->appId = config('linnworks.app_id') ?? "";
        $this->appSecret = config('linnworks.app_secret') ?? "";
        $this->refreshToken = config('linnworks.refresh_token') ?? "";
        $this->locality = config('linnworks.locality') ?? "";
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

            if (isset($response['ExpirationDate'])) {

                $expirationDate = $response['ExpirationDate'];
                $time = Carbon::parse($expirationDate)->subMinutes(60 * 24);
                $diff = now()->diffInMinutes($time);

                $locality = Configuration::firstWhere('name', 'linnworks_locality');
                $locality->value = $response['Locality'];
                $locality->save();

                Cache::put($this->cacheKey, $response, $diff);
            } else {
                return null;
            }
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

    public function getLocalityUrl(string $url = ""): string
    {
        $loc = Str::lower($this->locality) ?? "eu";

        return "https://" . $loc . '-ext.linnworks.net/api/' . $url;
    }

    public function fetch(string $method = "get", string $endpoint = "", array $data = [])
    {
        return Http::withHeaders(['Authorization' => $this->getApiToken()])
            ->acceptJson()
            ->{$method}($this->getLocalityUrl($endpoint), $data)
            ->json();
    }
}
