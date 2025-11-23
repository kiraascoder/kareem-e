<?php

use Illuminate\Support\Facades\Route;
use App\Services\MlPricingService;
use Illuminate\Http\Request;

Route::get('/ping', function () {
    return response()->json([
        'message' => 'Backend OK',
    ]);
});

Route::post('/test-ml', function (Request $request, MlPricingService $mlService) {
    $payload = [
        'jenis_event'      => 'corporate',
        'tanggal_event'    => '2025-12-01',
        'tanggal_booking'  => '2025-11-20',
        'jumlah_peserta'   => 100,
        'harga_dasar'      => 25000000,
        'season'           => 'high',
    ];

    $result = $mlService->predictPrice($payload);

    return response()->json([
        'from_laravel' => true,
        'payload'      => $payload,
        'ml_result'    => $result,
    ]);
});
