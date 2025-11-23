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
  season: "",
});

const loading = ref(false);
const message = ref(null);
const error = ref(null);

const submitBooking = async () => {
  loading.value = true;
  message.value = null;
  error.value = null;

  try {
    // sesuaikan dengan struktur backend: /api/events atau /api/bookings
    const res = await api.post("/events", form.value);
    message.value = "Permintaan booking berhasil dikirim.";
    console.log(res.data);
  } catch (err) {
    console.error(err);
    error.value = "Terjadi kesalahan saat mengirim booking.";
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <section class="max-w-xl">
    <h2 class="text-2xl font-semibold mb-3">Booking Event</h2>
    <p class="text-gray-700 mb-4">
      Isi formulir berikut untuk mengajukan permintaan penyelenggaraan acara.
    </p>

    <form
      @submit.prevent="submitBooking"
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
            required
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

      <div>
        <label class="block text-sm font-medium mb-1">Season</label>
        <select
          v-model="form.season"
          class="w-full border rounded px-3 py-2 text-sm"
        >
          <option value="">Tidak ditentukan</option>
          <option value="high">High Season</option>
          <option value="low">Low Season</option>
        </select>
      </div>

      <button
        type="submit"
        class="bg-slate-800 text-white text-sm px-4 py-2 rounded"
        :disabled="loading"
      >
        {{ loading ? "Mengirim..." : "Kirim Booking" }}
      </button>

      <p v-if="message" class="text-green-600 text-sm mt-2">{{ message }}</p>
      <p v-if="error" class="text-red-600 text-sm mt-2">{{ error }}</p>
    </form>
  </section>
</template>
