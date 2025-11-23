<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_event'      => 'required|string|max:255',
            'jenis_event'     => 'required|string|max:100',
            'tanggal_event'   => 'required|date',
            'tanggal_booking' => 'nullable|date',
            'jumlah_peserta'  => 'required|integer|min:1',
            'harga_dasar'     => 'required|numeric|min:0',            
            'season'          => 'nullable|string', 
        ];
    }

    public function messages(): array
    {
        return [
            'nama_event.required'     => 'Nama event wajib diisi.',
            'jenis_event.required'    => 'Jenis event wajib dipilih.',
            'tanggal_event.required'  => 'Tanggal event wajib diisi.',
            'jumlah_peserta.required' => 'Jumlah peserta wajib diisi.',
            'harga_dasar.required'    => 'Harga dasar wajib diisi.',
        ];
    }
}
