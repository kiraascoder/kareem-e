
<script setup>
import { ref } from "vue";
import api from "../services/api";

const form = ref({
  nama_event: "",
  jenis_event: "",
  tanggal_event: "",
  tanggal_booking: "",
  jumlah_peserta: 0,
  harga_dasar: 0,
});

const loadingPreview = ref(false);
const loadingBooking = ref(false);
const previewResult = ref(null);
const error = ref(null);
const successMessage = ref(null);

const previewPrice = async () => {
  loadingPreview.value = true;
  error.value = null;
  successMessage.value = null;
  previewResult.value = null;

  try {
    const payload = { ...form.value };
    if (!payload.tanggal_booking) {
      delete payload.tanggal_booking;
    }

    const res = await api.post("/pricing/preview", payload);
    previewResult.value = res.data;
  } catch (err) {
    console.error(err);
    error.value = "Gagal menghitung harga rekomendasi.";
  } finally {
    loadingPreview.value = false;
  }
};

const confirmBooking = async () => {
  loadingBooking.value = true;
  error.value = null;
  successMessage.value = null;

  try {
    const payload = { ...form.value };
    if (!payload.tanggal_booking) {
      delete payload.tanggal_booking;
    }

    const res = await api.post("/bookings", payload);
    successMessage.value = "Booking berhasil dikirim.";
    // reset form seperlunya
    form.value.nama_event = "";
    form.value.jenis_event = "";
    form.value.tanggal_event = "";
    form.value.tanggal_booking = "";
    form.value.jumlah_peserta = 0;
    form.value.harga_dasar = 0;
    previewResult.value = null;
  } catch (err) {
    console.error(err);
    error.value = "Gagal menyimpan booking.";
  } finally {
    loadingBooking.value = false;
  }
};
</script>

<template>
  <section class="max-w-xl">
    <h2 class="text-2xl font-semibold mb-3">Booking Event</h2>
    <p class="text-gray-700 mb-4">
      Isi formulir berikut untuk melihat rekomendasi harga dan mengajukan booking.
    </p>

    <form
      @submit.prevent="previewPrice"
      class="space-y-3 bg-white p-4 border rounded-lg shadow-sm"
    >
      <div>
        <label class="block text-sm font-medium mb-1">Nama Event</label>
        <input
          v-model="form.nama_event"
          type="text"
          class="w-full border rounded px-3 py-2 text-sm"
          required
        />
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Jenis Event</label>
        <select
          v-model="form.jenis_event"
          class="w-full border rounded px-3 py-2 text-sm"
          required
        >
          <option value="">Pilih jenis event</option>
          <option value="corporate">Corporate</option>
          <option value="social">Social</option>
          <option value="workshop">Workshop</option>
        </select>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium mb-1">Tanggal Event</label>
          <input
            v-model="form.tanggal_event"
            type="date"
            class="w-full border rounded px-3 py-2 text-sm"
            required
          />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Tanggal Booking</label>
          <input
            v-model="form.tanggal_booking"
            type="date"
            class="w-full border rounded px-3 py-2 text-sm"
            placeholder="kosongkan untuk hari ini"
          />
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium mb-1">Jumlah Peserta</label>
          <input
            v-model.number="form.jumlah_peserta"
            type="number"
            min="1"
            class="w-full border rounded px-3 py-2 text-sm"
            required
          />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Harga Dasar (Rp)</label>
          <input
            v-model.number="form.harga_dasar"
            type="number"
            min="0"
            class="w-full border rounded px-3 py-2 text-sm"
            required
          />
        </div>
      </div>

      <button
        type="submit"
        class="bg-slate-800 text-white text-sm px-4 py-2 rounded"
        :disabled="loadingPreview"
      >
        {{ loadingPreview ? "Menghitung..." : "Hitung Harga Rekomendasi" }}
      </button>

      <p v-if="error" class="text-red-600 text-sm mt-2">{{ error }}</p>
      <p v-if="successMessage" class="text-green-600 text-sm mt-2">
        {{ successMessage }}
      </p>
    </form>

    <!-- Hasil Preview -->
    <div v-if="previewResult?.success" class="mt-6 bg-white p-4 border rounded-lg shadow-sm">
      <h3 class="text-lg font-semibold mb-2">Hasil Rekomendasi Harga</h3>

      <div class="text-sm text-gray-700 space-y-1">
        <p>
          <span class="font-medium">Lead time:</span>
          {{ previewResult.input.lead_time }} hari
        </p>
        <p>
          <span class="font-medium">Season:</span>
          {{ previewResult.input.season || "-" }}
        </p>
        <p>
          <span class="font-medium">Harga dasar:</span>
          Rp {{ previewResult.input.harga_dasar.toLocaleString("id-ID") }}
        </p>
        <p v-if="previewResult.ml_result?.permintaan_prediksi !== undefined">
          <span class="font-medium">Prediksi permintaan:</span>
          {{ previewResult.ml_result.permintaan_prediksi }}
        </p>
        <p v-if="previewResult.ml_result?.faktor_harga !== undefined">
          <span class="font-medium">Faktor harga:</span>
          {{ previewResult.ml_result.faktor_harga }}
        </p>
        <p v-if="previewResult.ml_result?.harga_rekomendasi !== undefined">
          <span class="font-medium">Harga rekomendasi:</span>
          Rp {{ previewResult.ml_result.harga_rekomendasi.toLocaleString("id-ID") }}
        </p>
      </div>

      <button
        class="mt-4 bg-emerald-600 text-white text-sm px-4 py-2 rounded"
        @click="confirmBooking"
        :disabled="loadingBooking"
      >
        {{ loadingBooking ? "Menyimpan..." : "Konfirmasi Booking dengan Harga Ini" }}
      </button>
    </div>
  </section>
</template>
