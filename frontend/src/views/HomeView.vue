<!-- frontend/src/views/EventListView.vue -->
<script setup>
import { ref, onMounted } from "vue";
import api from "../services/api";

const events = ref([]);
const loading = ref(false);
const error = ref(null);

// per-event loading & hasil rekomendasi
const pricingLoading = ref({});
const pricingResults = ref({});
const applyMessage = ref({});

const fetchEvents = async () => {
  loading.value = true;
  error.value = null;

  try {
    const res = await api.get("/events");
    events.value = res.data.data ?? res.data;
  } catch (err) {
    console.error(err);
    error.value = "Gagal mengambil data event.";
  } finally {
    loading.value = false;
  }
};

const generateRecommendation = async (eventId) => {
  pricingLoading.value[eventId] = true;
  applyMessage.value[eventId] = null;

  try {
    const res = await api.post(`/events/${eventId}/recommend-price`);
    pricingResults.value[eventId] = res.data;
  } catch (err) {
    console.error(err);
    error.value = "Gagal menghitung rekomendasi harga untuk event.";
  } finally {
    pricingLoading.value[eventId] = false;
  }
};

const applyRecommendation = async (eventId) => {
  const rec = pricingResults.value[eventId];
  const recId = rec?.saved?.id;

  if (!recId) {
    applyMessage.value[eventId] = "Tidak ada rekomendasi yang bisa diterapkan.";
    return;
  }

  try {
    const res = await api.post(`/price-recommendations/${recId}/apply`);
    applyMessage.value[eventId] = "Harga rekomendasi telah diterapkan.";
    // refresh event list supaya harga_disepakati ter-update
    await fetchEvents();
  } catch (err) {
    console.error(err);
    applyMessage.value[eventId] = "Gagal menerapkan rekomendasi.";
  }
};

onMounted(fetchEvents);
</script>

<template>
  <section>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-2xl font-semibold">Daftar Event</h2>
    </div>

    <div v-if="loading">Memuat data...</div>
    <div v-else-if="error" class="text-red-600 text-sm mb-3">{{ error }}</div>

    <table
      v-else
      class="min-w-full bg-white border border-gray-200 text-sm"
    >
      <thead class="bg-gray-100">
        <tr>
          <th class="border px-3 py-2 text-left">#</th>
          <th class="border px-3 py-2 text-left">Nama Event</th>
          <th class="border px-3 py-2 text-left">Jenis</th>
          <th class="border px-3 py-2 text-left">Tanggal</th>
          <th class="border px-3 py-2 text-left">Peserta</th>
          <th class="border px-3 py-2 text-left">Harga Dasar</th>
          <th class="border px-3 py-2 text-left">Harga Disepakati</th>
          <th class="border px-3 py-2 text-left">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(event, index) in events"
          :key="event.id ?? index"
          class="hover:bg-gray-50 align-top"
        >
          <td class="border px-3 py-2">{{ index + 1 }}</td>
          <td class="border px-3 py-2">
            <div class="font-medium">{{ event.nama_event ?? "-" }}</div>
            <div class="text-xs text-gray-500">
              Status: {{ event.status }}
            </div>
          </td>
          <td class="border px-3 py-2">{{ event.jenis_event ?? "-" }}</td>
          <td class="border px-3 py-2">{{ event.tanggal_event ?? "-" }}</td>
          <td class="border px-3 py-2">
            {{ event.jumlah_peserta ?? "-" }}
          </td>
          <td class="border px-3 py-2">
            <span v-if="event.harga_dasar != null">
              Rp {{ Number(event.harga_dasar).toLocaleString("id-ID") }}
            </span>
            <span v-else>-</span>
          </td>
          <td class="border px-3 py-2">
            <span v-if="event.harga_disepakati != null">
              Rp {{ Number(event.harga_disepakati).toLocaleString("id-ID") }}
            </span>
            <span v-else class="text-gray-400 text-xs">Belum ditetapkan</span>
          </td>
          <td class="border px-3 py-2">
            <button
              class="text-xs bg-slate-800 text-white px-2 py-1 rounded"
              @click="generateRecommendation(event.id)"
              :disabled="pricingLoading[event.id]"
            >
              {{
                pricingLoading[event.id]
                  ? "Menghitung..."
                  : "Generate Rekomendasi"
              }}
            </button>

            <div v-if="pricingResults[event.id]" class="mt-2 text-xs">
              <p>
                <span class="font-semibold">Lead time:</span>
                {{ pricingResults[event.id].input?.lead_time }} hari
              </p>
              <p>
                <span class="font-semibold">Season:</span>
                {{ pricingResults[event.id].input?.season || "-" }}
              </p>
              <p v-if="pricingResults[event.id].ml_result?.permintaan_prediksi">
                <span class="font-semibold">Prediksi permintaan:</span>
                {{ pricingResults[event.id].ml_result.permintaan_prediksi }}
              </p>
              <p v-if="pricingResults[event.id].ml_result?.faktor_harga">
                <span class="font-semibold">Faktor:</span>
                {{ pricingResults[event.id].ml_result.faktor_harga }}
              </p>
              <p v-if="pricingResults[event.id].ml_result?.harga_rekomendasi">
                <span class="font-semibold">Harga rekomendasi:</span>
                Rp
                {{
                  pricingResults[event.id].ml_result.harga_rekomendasi.toLocaleString(
                    "id-ID"
                  )
                }}
              </p>

              <button
                class="mt-1 bg-emerald-600 text-white px-2 py-1 rounded"
                @click="applyRecommendation(event.id)"
              >
                Terapkan ke Event
              </button>
              <p v-if="applyMessage[event.id]" class="mt-1 text-[11px] text-gray-700">
                {{ applyMessage[event.id] }}
              </p>
            </div>
          </td>
        </tr>

        <tr v-if="events.length === 0">
          <td colspan="8" class="border px-3 py-4 text-center text-gray-500">
            Belum ada event
          </td>
        </tr>
      </tbody>
    </table>
  </section>
</template>
