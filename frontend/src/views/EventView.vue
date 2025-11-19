<script setup>
import { ref, onMounted } from "vue";
import api from "../services/api";

// state
const events = ref([]);
const loading = ref(false);
const error = ref(null);

// fungsi ambil data
const fetchEvents = async () => {
  loading.value = true;
  error.value = null;

  try {
    const res = await api.get("/events");
    // sesuaikan dengan struktur response Laravel-mu
    // kalau pakai resource: res.data.data
    events.value = res.data.data ?? res.data;
  } catch (err) {
    console.error(err);
    error.value = "Gagal mengambil data event";
  } finally {
    loading.value = false;
  }
};

// jalankan saat component dimount
onMounted(fetchEvents);
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-semibold mb-4">Daftar Event</h1>

    <div v-if="loading">Memuat data...</div>
    <div v-else-if="error" class="text-red-500">{{ error }}</div>

    <table v-else class="min-w-full border border-gray-200 text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="border px-3 py-2 text-left">#</th>
          <th class="border px-3 py-2 text-left">Nama Event</th>
          <th class="border px-3 py-2 text-left">Jenis</th>
          <th class="border px-3 py-2 text-left">Tanggal</th>
          <th class="border px-3 py-2 text-left">Peserta</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(event, index) in events"
          :key="event.id ?? index"
          class="hover:bg-gray-50"
        >
          <td class="border px-3 py-2">{{ index + 1 }}</td>
          <td class="border px-3 py-2">
            {{ event.nama_event ?? event.name ?? "-" }}
          </td>
          <td class="border px-3 py-2">
            {{ event.jenis_event ?? "-" }}
          </td>
          <td class="border px-3 py-2">
            {{ event.tanggal_event ?? "-" }}
          </td>
          <td class="border px-3 py-2">
            {{ event.jumlah_peserta ?? "-" }}
          </td>
        </tr>

        <tr v-if="events.length === 0">
          <td colspan="5" class="border px-3 py-4 text-center text-gray-500">
            Belum ada data event
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
