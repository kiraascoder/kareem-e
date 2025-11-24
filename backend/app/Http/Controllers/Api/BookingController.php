<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Season;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Event;

class BookingController extends Controller
{
        public function approve(Booking $booking)
    {
        if ($booking->status === Booking::STATUS['APPROVED']) {
            return response()->json([
                'success' => true,
                'message' => 'Booking sudah disetujui sebelumnya.',
                'data'    => $booking->load('event'),
            ]);
        }
        $tanggalEvent   = $booking->tanggal_event;
        $tanggalBooking = $booking->tanggal_booking ?? now();
    
        $season = $booking->season ?: \App\Models\Season::forDate($tanggalEvent);
    
        if (!$booking->event) {
            $kodeEvent = 'EV-' . now()->format('Ymd-His') . '-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(4));
    
            $event = Event::create([
                'kode_event'      => $kodeEvent,
                'booking_id'      => $booking->id,
                'client_id'       => $booking->client_id,
                'season_id'       => $season ? $season->id : null,
                'nama_event'      => $booking->nama_event,
                'jenis_event'     => $booking->jenis_event,
                'tanggal_event'   => $tanggalEvent,
                'tanggal_booking' => $tanggalBooking,
                'jumlah_peserta'  => $booking->jumlah_peserta,
                'harga_dasar'     => $booking->harga_dasar,
                'status'          => Event::STATUS['SCHEDULED'],
                'catatan'         => null,
            ]);
        } else {
            $event = $booking->event;
        }
    
       
        $booking->status = Booking::STATUS['APPROVED'];
        $booking->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil disetujui dan event dibuat/diperbarui.',
            'data'    => [
                'booking' => $booking->fresh()->load('season'),
                'event'   => $event->load('season'),
            ],
        ]);
    }
    
    public function reject(Booking $booking)
    {
        if ($booking->status === Booking::STATUS['REJECTED']) {
            return response()->json([
                'success' => true,
                'message' => 'Booking sudah ditolak sebelumnya.',
                'data'    => $booking,
            ]);
        }
    
        $booking->status = Booking::STATUS['REJECTED'];
        $booking->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil ditolak.',
            'data'    => $booking,
        ]);
    }


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
