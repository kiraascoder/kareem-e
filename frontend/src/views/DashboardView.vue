<script setup>
import { computed, onMounted, ref } from "vue";
import { RouterLink } from "vue-router";

const API_BASE =
  import.meta.env.VITE_API_BASE_URL?.replace(/\/$/, "") ||
  "http://localhost:8000/api";

const loading = ref(false);
const error = ref("");
const events = ref([]);

const backendStatus = ref("unknown"); // unknown | ok | down
const mlStatus = ref("unknown"); // optional: kalau kamu punya endpoint proxy status

function rupiah(n) {
  return Number(n || 0).toLocaleString("id-ID");
}

function parseDate(s) {
  const d = new Date(s);
  return Number.isNaN(d.getTime()) ? null : d;
}

function calcLeadTime(tanggal_booking, tanggal_event) {
  const a = parseDate(tanggal_booking);
  const b = parseDate(tanggal_event);
  if (!a || !b) return null;
  return Math.ceil((b - a) / (1000 * 60 * 60 * 24));
}

function normalizeEvent(e) {
  return {
    id: e.id,
    nama: e.nama ?? e.nama_event ?? e.client_name ?? "Untitled",
    jenis_event: e.jenis_event ?? "-",
    tanggal_booking: e.tanggal_booking ?? e.booking_date ?? null,
    tanggal_event: e.tanggal_event ?? e.event_date ?? null,
    jumlah_peserta: e.jumlah_peserta ?? 0,
    season: e.season ?? "low",
    harga_dasar: e.harga_dasar ?? e.base_price ?? 0,
    harga_rekomendasi: e.harga_rekomendasi ?? e.recommended_price ?? null,
    lead_time:
      e.lead_time ??
      calcLeadTime(
        e.tanggal_booking ?? e.booking_date,
        e.tanggal_event ?? e.event_date
      ),
  };
}

const now = computed(() => new Date());

const totalEvents = computed(() => events.value.length);

const highSeasonCount = computed(
  () =>
    events.value.filter((e) => String(e.season).toLowerCase() === "high").length
);

const upcomingCount = computed(() => {
  const today = now.value;
  return events.value.filter((e) => {
    const d = parseDate(e.tanggal_event);
    return (
      d && d >= new Date(today.getFullYear(), today.getMonth(), today.getDate())
    );
  }).length;
});

const avgBase = computed(() => {
  if (!events.value.length) return 0;
  const sum = events.value.reduce((a, b) => a + Number(b.harga_dasar || 0), 0);
  return Math.round(sum / events.value.length);
});

const recent = computed(() => {
  // urutkan berdasarkan tanggal_event desc (yang paling baru)
  return [...events.value]
    .sort((a, b) => {
      const da = parseDate(a.tanggal_event)?.getTime() ?? 0;
      const db = parseDate(b.tanggal_event)?.getTime() ?? 0;
      return db - da;
    })
    .slice(0, 6);
});

async function checkBackend() {
  try {
    const res = await fetch(`${API_BASE}/ping`);
    backendStatus.value = res.ok ? "ok" : "down";
  } catch {
    backendStatus.value = "down";
  }
}

async function fetchEvents() {
  loading.value = true;
  error.value = "";

  try {
    const res = await fetch(`${API_BASE}/events`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const data = await res.json();
    const list = Array.isArray(data) ? data : data.data ?? [];
    events.value = list.map(normalizeEvent);
  } catch (e) {
    // fallback dummy agar dashboard tetap hidup
    error.value = "Gagal mengambil data dari API. Menampilkan data dummy.";
    events.value = [
      normalizeEvent({
        id: 1,
        nama_event: "Corporate A",
        jenis_event: "corporate",
        tanggal_booking: "2025-11-20",
        tanggal_event: "2025-12-01",
        jumlah_peserta: 100,
        season: "high",
        harga_dasar: 25000000,
        harga_rekomendasi: null,
      }),
      normalizeEvent({
        id: 2,
        nama_event: "Wedding B",
        jenis_event: "wedding",
        tanggal_booking: "2025-11-10",
        tanggal_event: "2025-12-20",
        jumlah_peserta: 300,
        season: "low",
        harga_dasar: 40000000,
        harga_rekomendasi: 44800000,
      }),
      normalizeEvent({
        id: 3,
        nama_event: "Festival C",
        jenis_event: "festival",
        tanggal_booking: "2025-10-01",
        tanggal_event: "2025-11-15",
        jumlah_peserta: 500,
        season: "high",
        harga_dasar: 60000000,
        harga_rekomendasi: 72000000,
      }),
    ];
  } finally {
    loading.value = false;
  }
}

const lastTest = ref(null);
async function testEngine() {
  // dummy test (opsional kamu ubah jadi call endpoint Laravel/ML)
  lastTest.value = {
    lead_time: 10,
    permintaan_prediksi: 0.82,
    faktor_harga: 1.2,
    harga_rekomendasi: 30000000,
  };
}

onMounted(async () => {
  await checkBackend();
  await fetchEvents();
});
</script>

<template>
  <section class="space-y-10">
    <!-- Header -->
    <header class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-10">
      <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
        Dashboard
      </p>
      <h1 class="mt-2 font-serif text-4xl text-[#1B1A17]">Internal overview</h1>

      <div class="mt-4 h-px w-24 bg-black/10"></div>

      <p
        class="mt-5 max-w-3xl text-sm leading-relaxed text-[#1B1A17]/75 sm:text-base"
      >
        Ringkasan event & status layanan untuk
        <span class="font-semibold">Kareem Pricing</span> (internal
        <span class="font-semibold">Kareem Entertainment / Kareem E</span>).
      </p>

      <div class="mt-6 flex flex-wrap gap-2">
        <span
          class="rounded-full px-4 py-2 text-[12px] font-bold tracking-[0.18em] uppercase"
          style="background: #1b1a17; color: #e6d5b8"
        >
          Internal
        </span>

        <span
          class="rounded-full border px-4 py-2 text-[12px] font-bold tracking-[0.18em] uppercase"
          :style="{
            borderColor: 'rgba(0,0,0,.12)',
            background: '#ffffff66',
            color: '#1B1A17',
          }"
        >
          Backend:
          <span
            :style="{ color: backendStatus === 'ok' ? '#1B1A17' : '#E45826' }"
          >
            {{ backendStatus }}
          </span>
        </span>

        <span
          class="rounded-full border px-4 py-2 text-[12px] font-bold tracking-[0.18em] uppercase"
          style="
            border-color: rgba(0, 0, 0, 0.12);
            background: #E6D5B8/60;
            color: #1b1a17;
          "
        >
          ML: {{ mlStatus }}
        </span>
      </div>

      <p v-if="error" class="mt-4 text-sm font-semibold" style="color: #e45826">
        {{ error }}
      </p>
    </header>

    <!-- KPI -->
    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <div class="rounded-3xl border border-black/10 bg-white/70 p-6">
        <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
          Total events
        </p>
        <div class="mt-2 flex items-end justify-between gap-3">
          <p class="font-serif text-3xl text-[#1B1A17]">{{ totalEvents }}</p>
          <span class="h-3 w-3 rounded-full" style="background: #f0a500"></span>
        </div>
        <div class="mt-4 h-px w-full bg-black/10"></div>
        <p class="mt-3 text-sm text-[#1B1A17]/70">
          Jumlah event tersimpan di sistem.
        </p>
      </div>

      <div class="rounded-3xl border border-black/10 bg-white/70 p-6">
        <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
          High season
        </p>
        <div class="mt-2 flex items-end justify-between gap-3">
          <p class="font-serif text-3xl text-[#1B1A17]">
            {{ highSeasonCount }}
          </p>
          <span class="h-3 w-3 rounded-full" style="background: #e45826"></span>
        </div>
        <div class="mt-4 h-px w-full bg-black/10"></div>
        <p class="mt-3 text-sm text-[#1B1A17]/70">
          Event dengan season = high.
        </p>
      </div>

      <div class="rounded-3xl border border-black/10 bg-white/70 p-6">
        <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
          Upcoming
        </p>
        <div class="mt-2 flex items-end justify-between gap-3">
          <p class="font-serif text-3xl text-[#1B1A17]">{{ upcomingCount }}</p>
          <span class="h-3 w-3 rounded-full" style="background: #f0a500"></span>
        </div>
        <div class="mt-4 h-px w-full bg-black/10"></div>
        <p class="mt-3 text-sm text-[#1B1A17]/70">
          Event dengan tanggal ≥ hari ini.
        </p>
      </div>

      <div class="rounded-3xl border border-black/10 bg-white/70 p-6">
        <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
          Avg base price
        </p>
        <div class="mt-2 flex items-end justify-between gap-3">
          <p class="font-serif text-3xl text-[#1B1A17]">
            Rp {{ rupiah(avgBase) }}
          </p>
          <span class="h-3 w-3 rounded-full" style="background: #e45826"></span>
        </div>
        <div class="mt-4 h-px w-full bg-black/10"></div>
        <p class="mt-3 text-sm text-[#1B1A17]/70">
          Rata-rata harga dasar event.
        </p>
      </div>
    </section>

    <!-- Main panels -->
    <section class="grid gap-6 lg:grid-cols-2">
      <!-- Recent Events -->
      <div
        class="rounded-3xl border border-black/10 bg-white/70 overflow-hidden"
      >
        <div class="p-6">
          <div class="flex items-center justify-between gap-3">
            <div>
              <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
                Recent
              </p>
              <h2 class="mt-1 font-serif text-2xl text-[#1B1A17]">
                Recent events
              </h2>
            </div>

            <div class="flex items-center gap-2">
              <button
                class="rounded-full px-5 py-2.5 text-[12px] font-bold tracking-[0.18em] uppercase"
                style="background: #1b1a17; color: #e6d5b8"
                @click="fetchEvents"
                :disabled="loading"
              >
                {{ loading ? "Loading…" : "Refresh" }}
              </button>

              <RouterLink
                to="/events"
                class="rounded-full border px-5 py-2.5 text-[12px] font-bold tracking-[0.18em] uppercase hover:bg-white/60 transition"
                style="border-color: rgba(0, 0, 0, 0.12); color: #1b1a17"
              >
                Open events
              </RouterLink>
            </div>
          </div>

          <div class="mt-5 h-px w-full bg-black/10"></div>
        </div>

        <div class="overflow-auto">
          <table class="w-full text-sm">
            <thead class="bg-[#E6D5B8]/60">
              <tr class="text-left text-[#1B1A17]/75">
                <th class="px-6 py-4 font-semibold">Nama</th>
                <th class="px-6 py-4 font-semibold">Jenis</th>
                <th class="px-6 py-4 font-semibold">Pax</th>
                <th class="px-6 py-4 font-semibold">Lead</th>
                <th class="px-6 py-4 font-semibold">Rec</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td class="px-6 py-6 text-[#1B1A17]/70" colspan="5">
                  Mengambil data…
                </td>
              </tr>

              <tr v-else-if="recent.length === 0">
                <td class="px-6 py-6 text-[#1B1A17]/70" colspan="5">
                  Belum ada data.
                </td>
              </tr>

              <tr
                v-else
                v-for="e in recent"
                :key="e.id"
                class="border-t border-black/10 hover:bg-white/50"
              >
                <td class="px-6 py-4">
                  <div class="font-semibold text-[#1B1A17]">{{ e.nama }}</div>
                  <div
                    class="text-xs tracking-[0.16em] uppercase text-[#1B1A17]/55"
                  >
                    {{ e.season }}
                  </div>
                </td>
                <td class="px-6 py-4 text-[#1B1A17]/75">{{ e.jenis_event }}</td>
                <td class="px-6 py-4 text-[#1B1A17]/75">
                  {{ e.jumlah_peserta }}
                </td>
                <td class="px-6 py-4 text-[#1B1A17]/75">
                  {{ e.lead_time ?? "-" }}
                </td>
                <td class="px-6 py-4">
                  <span
                    v-if="e.harga_rekomendasi"
                    class="font-semibold"
                    style="color: #e45826"
                  >
                    Rp {{ rupiah(e.harga_rekomendasi) }}
                  </span>
                  <span v-else class="text-[#1B1A17]/55">-</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pricing Engine panel -->
      <div
        class="rounded-3xl border border-black/10 bg-[#1B1A17] p-7 sm:p-8 text-[#E6D5B8]"
      >
        <p class="text-xs tracking-[0.22em] uppercase text-[#E6D5B8]/70">
          Pricing engine
        </p>
        <h2 class="mt-2 font-serif text-2xl">Random Forest + Fuzzy Logic</h2>

        <div class="mt-5 h-px w-24 bg-[#E6D5B8]/20"></div>

        <p class="mt-5 text-sm text-[#E6D5B8]/80 leading-relaxed">
          Backend Laravel akan memanggil ML Service (FastAPI) untuk menghitung
          prediksi permintaan dan faktor koreksi, lalu menghasilkan harga
          rekomendasi. Panel ini bisa dipakai untuk demo (test).
        </p>

        <div class="mt-6 flex flex-wrap gap-2">
          <span
            class="rounded-full px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
            style="background: #f0a500; color: #1b1a17"
            >RF</span
          >
          <span
            class="rounded-full px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
            style="background: #e45826; color: #fff"
            >Fuzzy</span
          >
          <span
            class="rounded-full border border-[#E6D5B8]/20 bg-white/5 px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
          >
            Explainable
          </span>
        </div>

        <button
          class="mt-6 rounded-full px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase transition"
          style="background: #f0a500; color: #1b1a17"
          @click="testEngine"
        >
          Run test (dummy)
        </button>

        <div
          v-if="lastTest"
          class="mt-6 rounded-3xl border border-[#E6D5B8]/15 bg-white/5 p-5"
        >
          <p class="text-xs tracking-[0.18em] uppercase text-[#E6D5B8]/60">
            Result
          </p>

          <div class="mt-4 space-y-2 text-sm text-[#E6D5B8]/80">
            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Lead time</span>
              <span class="font-semibold text-[#E6D5B8]"
                >{{ lastTest.lead_time }} days</span
              >
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Demand (pred)</span>
              <span class="font-semibold text-[#E6D5B8]">{{
                lastTest.permintaan_prediksi
              }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Factor</span>
              <span class="font-semibold" style="color: #f0a500">{{
                lastTest.faktor_harga
              }}</span>
            </div>

            <div class="h-px bg-[#E6D5B8]/15"></div>

            <div class="flex justify-between gap-4">
              <span class="font-semibold text-[#E6D5B8]">Recommended</span>
              <span class="font-bold" style="color: #e45826">
                Rp {{ rupiah(lastTest.harga_rekomendasi) }}
              </span>
            </div>
          </div>

          <p class="mt-4 text-xs text-[#E6D5B8]/60">
            * Dummy output. Nanti ganti jadi call endpoint rekomendasi.
          </p>
        </div>
      </div>
    </section>

    <!-- Bottom CTA -->
    <section class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-10">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="max-w-2xl">
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            Next
          </p>
          <h2 class="mt-2 font-serif text-3xl text-[#1B1A17]">
            Manage events & request price
          </h2>
          <p class="mt-2 text-sm text-[#1B1A17]/75 leading-relaxed">
            Masuk ke halaman Events untuk melihat daftar event dan mengeksekusi
            rekomendasi harga.
          </p>
        </div>

        <div class="flex flex-wrap gap-3">
          <RouterLink
            to="/events"
            class="rounded-full px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase"
            style="background: #1b1a17; color: #e6d5b8"
          >
            Events
          </RouterLink>
          <RouterLink
            to="/booking"
            class="rounded-full px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase"
            style="background: #f0a500; color: #1b1a17"
          >
            Booking
          </RouterLink>
        </div>
      </div>
    </section>
  </section>
</template>
