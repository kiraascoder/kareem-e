<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::with(['client', 'booking', 'season', 'priceRecommendations'])
            ->latest()
            ->paginate(10);

        return response()->json($events);
    }

    public function show(Event $event)
    {
        $event->load(['client', 'booking', 'season', 'priceRecommendations']);

        return response()->json($event);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_event'      => 'required|string|max:255',
            'jenis_event'     => 'required|string|max:100',
            'tanggal_event'   => 'required|date',
            'tanggal_booking' => 'nullable|date',
            'jumlah_peserta'  => 'required|integer|min:1',
            'harga_dasar'     => 'required|numeric|min:0',
            'client_id'       => 'nullable|exists:clients,id',
            'booking_id'      => 'nullable|exists:bookings,id',
        ]);

        $tanggalEvent = Carbon::parse($data['tanggal_event']);
        $tanggalBooking = isset($data['tanggal_booking'])
            ? Carbon::parse($data['tanggal_booking'])
            : now();

        $season = Season::forDate($tanggalEvent);

        $kodeEvent = 'EV-' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(4));

        $event = Event::create([
            'kode_event'      => $kodeEvent,
            'booking_id'      => $data['booking_id'] ?? null,
            'client_id'       => $data['client_id'] ?? null,
            'season_id'       => $season ? $season->id : null,
            'nama_event'      => $data['nama_event'],
            'jenis_event'     => $data['jenis_event'],
            'tanggal_event'   => $tanggalEvent,
            'tanggal_booking' => $tanggalBooking,
            'jumlah_peserta'  => $data['jumlah_peserta'],
            'harga_dasar'     => $data['harga_dasar'],
            'status'          => Event::STATUS['SCHEDULED'],
            'catatan'         => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil dibuat.',
            'data'    => $event->load(['season', 'client', 'booking']),
        ], 201);
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'nama_event'      => 'sometimes|required|string|max:255',
            'jenis_event'     => 'sometimes|required|string|max:100',
            'tanggal_event'   => 'sometimes|required|date',
            'tanggal_booking' => 'sometimes|date',
            'jumlah_peserta'  => 'sometimes|required|integer|min:1',
            'harga_dasar'     => 'sometimes|required|numeric|min:0',
            'harga_disepakati'=> 'nullable|numeric|min:0',
            'total_revenue'   => 'nullable|numeric|min:0',
            'status'          => 'nullable|string',
            'catatan'         => 'nullable|string',
        ]);

        if (isset($data['tanggal_event'])) {
            $data['tanggal_event'] = Carbon::parse($data['tanggal_event']);
        }

        if (isset($data['tanggal_booking'])) {
            $data['tanggal_booking'] = Carbon::parse($data['tanggal_booking']);
        }

        $event->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil diperbarui.',
            'data'    => $event->fresh()->load(['season', 'client', 'booking']),
        ]);
    }
}
