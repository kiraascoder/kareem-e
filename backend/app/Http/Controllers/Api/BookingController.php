<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Season;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    public function index(Request $request)
    {
        $bookings = Booking::with(['client', 'season'])
            ->latest()
            ->paginate(10);

        return response()->json($bookings);
    }


    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();

        $tanggalEvent = Carbon::parse($data['tanggal_event']);
        $tanggalBooking = isset($data['tanggal_booking'])
            ? Carbon::parse($data['tanggal_booking'])
            : now();


        $season = Season::forDate($tanggalEvent);

        
        $kodeBooking = 'BK-' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(4));

        $booking = Booking::create([
            'kode_booking'     => $kodeBooking,
            'client_id'        => null, 
            'nama_event'       => $data['nama_event'],
            'jenis_event'      => $data['jenis_event'],
            'tanggal_event'    => $tanggalEvent,
            'tanggal_booking'  => $tanggalBooking,
            'jumlah_peserta'   => $data['jumlah_peserta'],
            'harga_dasar'      => $data['harga_dasar'],
            'season_id'        => $season?->id,
            'status'           => Booking::STATUS['PENDING'],
            'catatan_klien'    => null,
            'catatan_internal' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil disimpan.',
            'data'    => $booking->load(['season']),
        ], 201);
    }

    public function show(Booking $booking)
    {
        $booking->load(['client', 'season', 'priceRecommendations']);

        return response()->json($booking);
    }

 
}
