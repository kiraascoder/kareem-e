<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MlPricingService
{
    public function predictPrice(array $payload)
    {
        $url = config('services.ml.url') . '/predict-price';

        $response = Http::post($url, $payload);

        if ($response->failed()) {
            throw new \Exception('Gagal menghubungi ML Service');
        }

        return $response->json();
    }
}
