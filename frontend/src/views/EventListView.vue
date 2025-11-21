<script setup>
import { ref, onMounted } from "vue";
import api from "../services/api";

const events = ref([]);
const loading = ref(false);
const error = ref(null);

const fetchEvents = async () => {
  loading.value = true;
  error.value = null;

  try {
    const res = await api.get("/events");
    // kalau di Laravel kamu pakai resource: res.data.data
    events.value = res.data.data ?? res.data;
  } catch (err) {
    console.error(err);
    error.value = "Gagal mengambil data event";
  } finally {
    loading.value = false;
  }
};

onMounted(fetchEvents);
</script>

<template>
  <section>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-2xl font-semibold">Daftar Event</h2>

      <!-- Nanti kita arahkan ke form tambah event -->
      <!-- <RouterLink
        to="/events/new"
        class="bg-slate-800 text-white px-3 py-1.5 rounded text-sm"
      >
        + Tambah Event
      </RouterLink> -->
    </div>

    <div v-if="loading">Memuat data...</div>
    <div v-else-if="error" class="text-red-600">{{ error }}</div>

    <table
      v-else
      class="min-w-full bg-white border border-gray-200 text-sm"
    >
      <thead class="bg-gray-100">
        <tr>
          <th class="border px-3 py-2 text-left">#</th>
          <th class="border px-3 py-2 text-left">Nama Event</th>
          <th class="border px-3 py-2 text-left">Jenis</th>
          <th class="border px-3 py-2 text-left">Tanggal Event</th>
          <th class="border px-3 py-2 text-left">Peserta</th>
          <th class="border px-3 py-2 text-left">Aksi</th>
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
          <td class="border px-3 py-2">{{ event.jenis_event ?? "-" }}</td>
          <td class="border px-3 py-2">{{ event.tanggal_event ?? "-" }}</td>
          <td class="border px-3 py-2">
            {{ event.jumlah_peserta ?? "-" }}
          </td>
          <td class="border px-3 py-2">
            <!-- Nanti tombol pricing & edit -->
            <!-- <RouterLink
              :to="{ name: 'events.pricing', params: { id: event.id } }"
              class="text-blue-600 hover:underline text-xs"
            >
              Lihat Rekomendasi
            </RouterLink> -->
            <span class="text-gray-400 text-xs">Aksi nanti</span>
          </td>
        </tr>

        <tr v-if="events.length === 0">
          <td
            colspan="6"
            class="border px-3 py-4 text-center text-gray-500"
          >
            Belum ada data event
          </td>
        </tr>
      </tbody>
    </table>
  </section>
</template>
