<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Services\MlPricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PriceRecommendation;


class PricingController extends Controller
{

    public function previewPublic(Request $request, MlPricingService $mlService)
    {
        $data = $request->validate([
            'nama_event'      => 'required|string|max:255',
            'jenis_event'     => 'required|string|max:100',
            'tanggal_event'   => 'required|date',
            'tanggal_booking' => 'nullable|date',
            'jumlah_peserta'  => 'required|integer|min:1',
            'harga_dasar'     => 'required|numeric|min:0',
        ]);

        $tanggalEvent = Carbon::parse($data['tanggal_event']);
        $tanggalBooking = isset($data['tanggal_booking'])
            ? Carbon::parse($data['tanggal_booking'])
            : now();

        $leadTime = $tanggalEvent->diffInDays($tanggalBooking);

        $season = Season::forDate($tanggalEvent);
        $seasonKode = $season?->kode ?? null; 
        $payload = [
            'jenis_event'      => $data['jenis_event'],
            'tanggal_event'    => $tanggalEvent->toDateString(),
            'tanggal_booking'  => $tanggalBooking->toDateString(),
            'jumlah_peserta'   => $data['jumlah_peserta'],
            'harga_dasar'      => $data['harga_dasar'],
            'season'           => $seasonKode,
        ];

        try {
            $result = $mlService->predictPrice($payload);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung harga rekomendasi.',
            ], 500);
        }

        return response()->json([
            'success'   => true,
            'input'     => [
                'nama_event'      => $data['nama_event'],
                'jenis_event'     => $data['jenis_event'],
                'tanggal_event'   => $tanggalEvent->toDateString(),
                'tanggal_booking' => $tanggalBooking->toDateString(),
                'jumlah_peserta'  => $data['jumlah_peserta'],
                'harga_dasar'     => $data['harga_dasar'],
                'lead_time'       => $leadTime,
                'season'          => $seasonKode,
            ],
            'ml_result' => $result,
        ]);
    }
    public function applyRecommendation(PriceRecommendation $priceRecommendation)
{
    $priceRecommendation->load('event');

    $event = $priceRecommendation->event;

    if (!$event) {
        return response()->json([
            'success' => false,
            'message' => 'Rekomendasi ini tidak terhubung ke event manapun.',
        ], 400);
    }
    PriceRecommendation::where('event_id', $event->id)
        ->update(['dipakai' => false]);

    $priceRecommendation->dipakai = true;
    $priceRecommendation->save();

    $event->harga_disepakati = $priceRecommendation->harga_rekomendasi;
    $event->save();

    return response()->json([
        'success' => true,
        'message' => 'Rekomendasi harga diterapkan ke event.',
        'event'   => $event->fresh(),
        'recommendation' => $priceRecommendation,
    ]);
}

}
