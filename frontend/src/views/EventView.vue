<script setup>
import { computed, onMounted, ref } from "vue";

const API_BASE =
  import.meta.env.VITE_API_BASE_URL?.replace(/\/$/, "") ||
  "http://localhost:8000/api";

const events = ref([]);
const loading = ref(false);
const error = ref("");
const q = ref("");
const season = ref("all");
const selected = ref(null);
const recommendingId = ref(null);

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
  const lead = e.lead_time ?? calcLeadTime(e.tanggal_booking, e.tanggal_event);

  return {
    id: e.id,
    nama: e.nama ?? e.nama_event ?? e.client_name ?? "Untitled",
    jenis_event: e.jenis_event ?? "-",
    tanggal_booking: e.tanggal_booking ?? e.booking_date ?? null,
    tanggal_event: e.tanggal_event ?? e.event_date ?? null,
    jumlah_peserta: e.jumlah_peserta ?? 0,
    season: e.season ?? "low",
    harga_dasar: e.harga_dasar ?? e.base_price ?? 0,

    // rekomendasi (optional)
    lead_time: lead,
    permintaan_prediksi: e.permintaan_prediksi ?? null,
    faktor_harga: e.faktor_harga ?? null,
    harga_rekomendasi: e.harga_rekomendasi ?? e.recommended_price ?? null,
  };
}

async function fetchEvents() {
  loading.value = true;
  error.value = "";
  try {
    const res = await fetch(`${API_BASE}/events`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const data = await res.json();

    // dukung response: {data:[...]} atau [...]
    const list = Array.isArray(data) ? data : data.data ?? [];
    events.value = list.map(normalizeEvent);

    if (events.value.length) selected.value = events.value[0];
  } catch (e) {
    // fallback dummy supaya UI tetap tampil
    error.value = "Gagal mengambil data dari API. Menampilkan data dummy.";
    events.value = [
      {
        id: 1,
        nama: "Corporate A",
        jenis_event: "corporate",
        tanggal_booking: "2025-11-20",
        tanggal_event: "2025-12-01",
        jumlah_peserta: 100,
        season: "high",
        harga_dasar: 25000000,
        lead_time: 11,
        permintaan_prediksi: null,
        faktor_harga: null,
        harga_rekomendasi: null,
      },
      {
        id: 2,
        nama: "Wedding B",
        jenis_event: "wedding",
        tanggal_booking: "2025-11-10",
        tanggal_event: "2025-12-20",
        jumlah_peserta: 300,
        season: "low",
        harga_dasar: 40000000,
        lead_time: 40,
        permintaan_prediksi: 0.76,
        faktor_harga: 1.12,
        harga_rekomendasi: 44800000,
      },
    ];
    selected.value = events.value[0];
  } finally {
    loading.value = false;
  }
}

async function recommendPrice(ev) {
  recommendingId.value = ev.id;
  error.value = "";
  try {
    const res = await fetch(`${API_BASE}/events/${ev.id}/recommend-price`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
    });

    if (!res.ok) {
      const txt = await res.text().catch(() => "");
      throw new Error(`HTTP ${res.status} ${txt}`);
    }

    const data = await res.json();

    // Sesuaikan dengan response FastAPI/Laravel kamu:
    // { lead_time, permintaan_prediksi, faktor_harga, harga_rekomendasi }
    ev.lead_time = data.lead_time ?? ev.lead_time;
    ev.permintaan_prediksi = data.permintaan_prediksi ?? ev.permintaan_prediksi;
    ev.faktor_harga = data.faktor_harga ?? ev.faktor_harga;
    ev.harga_rekomendasi = data.harga_rekomendasi ?? ev.harga_rekomendasi;

    selected.value = ev;
  } catch (e) {
    // fallback: biar tombol terasa bekerja saat belum ada backend
    ev.lead_time =
      ev.lead_time ?? calcLeadTime(ev.tanggal_booking, ev.tanggal_event);
    ev.permintaan_prediksi = ev.permintaan_prediksi ?? 0.82;
    ev.faktor_harga = ev.faktor_harga ?? (ev.season === "high" ? 1.2 : 1.08);
    ev.harga_rekomendasi =
      ev.harga_rekomendasi ??
      Math.round(Number(ev.harga_dasar || 0) * Number(ev.faktor_harga || 1));

    selected.value = ev;
    error.value =
      "Tidak bisa memanggil endpoint rekomendasi. Menggunakan preview (dummy).";
  } finally {
    recommendingId.value = null;
  }
}

const filtered = computed(() => {
  const s = q.value.trim().toLowerCase();
  return events.value.filter((e) => {
    const okSeason = season.value === "all" ? true : e.season === season.value;
    const okSearch =
      !s ||
      [e.nama, e.jenis_event, e.season]
        .map((x) => String(x || "").toLowerCase())
        .some((x) => x.includes(s));
    return okSeason && okSearch;
  });
});

onMounted(fetchEvents);
</script>

<template>
  <section class="space-y-8">
    <!-- Header -->
    <header class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-10">
      <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
        Events
      </p>
      <h1 class="mt-2 font-serif text-4xl text-[#1B1A17]">Manajemen event</h1>

      <div class="mt-4 h-px w-24 bg-black/10"></div>

      <p
        class="mt-5 max-w-3xl text-sm leading-relaxed text-[#1B1A17]/75 sm:text-base"
      >
        Kelola event dan minta rekomendasi harga. Tombol rekomendasi akan
        memanggil
        <span class="font-semibold">POST /api/events/{id}/recommend-price</span
        >.
      </p>

      <p v-if="error" class="mt-4 text-sm font-semibold" style="color: #e45826">
        {{ error }}
      </p>
    </header>

    <!-- Layout -->
    <div class="grid gap-6 lg:grid-cols-[1.35fr_.65fr]">
      <!-- Left: list -->
      <div
        class="rounded-3xl border border-black/10 bg-white/70 overflow-hidden"
      >
        <!-- toolbar -->
        <div class="p-5 sm:p-6">
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
              <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
                Directory
              </p>
              <h2 class="mt-1 font-serif text-2xl text-[#1B1A17]">
                Daftar event
              </h2>
            </div>

            <div class="flex flex-wrap items-center gap-2">
              <input
                v-model="q"
                placeholder="Search…"
                class="w-56 rounded-full border border-black/10 bg-white/70 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              />
              <select
                v-model="season"
                class="rounded-full border border-black/10 bg-white/70 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              >
                <option value="all">All season</option>
                <option value="low">Low</option>
                <option value="high">High</option>
              </select>

              <button
                @click="fetchEvents"
                class="rounded-full px-5 py-2.5 text-[12px] font-bold tracking-[0.18em] uppercase"
                style="background: #1b1a17; color: #e6d5b8"
              >
                {{ loading ? "Loading…" : "Refresh" }}
              </button>
            </div>
          </div>

          <div class="mt-5 h-px w-full bg-black/10"></div>
        </div>

        <!-- table -->
        <div class="overflow-auto">
          <table class="w-full text-sm">
            <thead class="bg-[#E6D5B8]/60">
              <tr class="text-left text-[#1B1A17]/75">
                <th class="px-6 py-4 font-semibold">Nama</th>
                <th class="px-6 py-4 font-semibold">Jenis</th>
                <th class="px-6 py-4 font-semibold">Tanggal</th>
                <th class="px-6 py-4 font-semibold">Season</th>
                <th class="px-6 py-4 font-semibold">Harga</th>
                <th class="px-6 py-4 font-semibold">Aksi</th>
              </tr>
            </thead>

            <tbody>
              <tr v-if="loading">
                <td class="px-6 py-6 text-[#1B1A17]/70" colspan="6">
                  Mengambil data…
                </td>
              </tr>

              <tr v-else-if="filtered.length === 0">
                <td class="px-6 py-6 text-[#1B1A17]/70" colspan="6">
                  Tidak ada data.
                </td>
              </tr>

              <tr
                v-else
                v-for="ev in filtered"
                :key="ev.id"
                class="border-t border-black/10 hover:bg-white/50 cursor-pointer"
                :class="selected?.id === ev.id ? 'bg-white/60' : ''"
                @click="selected = ev"
              >
                <td class="px-6 py-4">
                  <div class="font-semibold text-[#1B1A17]">{{ ev.nama }}</div>
                  <div
                    class="text-xs text-[#1B1A17]/55 tracking-[0.16em] uppercase"
                  >
                    Pax {{ ev.jumlah_peserta }}
                  </div>
                </td>

                <td class="px-6 py-4 text-[#1B1A17]/75">
                  {{ ev.jenis_event }}
                </td>

                <td class="px-6 py-4 text-[#1B1A17]/75">
                  <div
                    class="text-xs uppercase tracking-[0.16em] text-[#1B1A17]/55"
                  >
                    Event
                  </div>
                  <div>{{ ev.tanggal_event || "-" }}</div>
                </td>

                <td class="px-6 py-4">
                  <span
                    class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
                    :style="{
                      borderColor:
                        ev.season === 'high' ? '#F0A50055' : '#00000022',
                      color: ev.season === 'high' ? '#F0A500' : '#1B1A17',
                      backgroundColor:
                        ev.season === 'high' ? '#F0A50014' : '#ffffff66',
                    }"
                  >
                    <span
                      class="h-2 w-2 rounded-full"
                      :style="{
                        backgroundColor:
                          ev.season === 'high' ? '#F0A500' : '#E45826',
                      }"
                    ></span>
                    {{ ev.season }}
                  </span>
                </td>

                <td class="px-6 py-4">
                  <div class="text-[#1B1A17]/75">
                    Rp {{ rupiah(ev.harga_dasar) }}
                  </div>
                  <div
                    v-if="ev.harga_rekomendasi"
                    class="text-xs font-semibold"
                    style="color: #e45826"
                  >
                    Rec: Rp {{ rupiah(ev.harga_rekomendasi) }}
                  </div>
                  <div v-else class="text-xs text-[#1B1A17]/55">Rec: -</div>
                </td>

                <td class="px-6 py-4">
                  <button
                    class="rounded-full px-4 py-2 text-[11px] font-bold tracking-[0.16em] uppercase transition"
                    :disabled="recommendingId === ev.id"
                    :style="{
                      background: '#F0A500',
                      color: '#1B1A17',
                      opacity: recommendingId === ev.id ? 0.7 : 1,
                    }"
                    @click.stop="recommendPrice(ev)"
                  >
                    {{ recommendingId === ev.id ? "Working…" : "Recommend" }}
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Right: detail panel -->
      <aside class="space-y-6">
        <div
          class="rounded-3xl border border-black/10 bg-[#1B1A17] p-7 sm:p-8 text-[#E6D5B8]"
        >
          <p class="text-xs tracking-[0.22em] uppercase text-[#E6D5B8]/70">
            Details
          </p>
          <h2 class="mt-2 font-serif text-2xl">
            {{ selected ? selected.nama : "Select an event" }}
          </h2>

          <div class="mt-5 h-px w-24 bg-[#E6D5B8]/20"></div>

          <div v-if="selected" class="mt-6 space-y-3 text-sm text-[#E6D5B8]/80">
            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Type</span>
              <span class="font-semibold text-[#E6D5B8]">{{
                selected.jenis_event
              }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Participants</span>
              <span class="font-semibold text-[#E6D5B8]">{{
                selected.jumlah_peserta
              }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Lead time</span>
              <span class="font-semibold text-[#E6D5B8]">
                {{
                  selected.lead_time == null
                    ? "-"
                    : selected.lead_time + " days"
                }}
              </span>
            </div>

            <div class="h-px bg-[#E6D5B8]/15"></div>

            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Base price</span>
              <span class="font-semibold text-[#E6D5B8]"
                >Rp {{ rupiah(selected.harga_dasar) }}</span
              >
            </div>

            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Demand (pred)</span>
              <span class="font-semibold text-[#E6D5B8]">
                {{
                  selected.permintaan_prediksi == null
                    ? "-"
                    : selected.permintaan_prediksi
                }}
              </span>
            </div>

            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Factor</span>
              <span class="font-semibold" style="color: #f0a500">
                {{
                  selected.faktor_harga == null ? "-" : selected.faktor_harga
                }}
              </span>
            </div>

            <div class="flex justify-between gap-4">
              <span class="font-semibold text-[#E6D5B8]">Recommended</span>
              <span class="font-bold" style="color: #e45826">
                {{
                  selected.harga_rekomendasi == null
                    ? "-"
                    : "Rp " + rupiah(selected.harga_rekomendasi)
                }}
              </span>
            </div>

            <div
              class="mt-4 rounded-2xl border border-[#E6D5B8]/15 bg-white/5 p-4 text-xs text-[#E6D5B8]/65"
            >
              Output rekomendasi dibuat explainable: lead time, prediksi,
              faktor, dan hasil harga.
            </div>

            <button
              class="mt-4 w-full rounded-full px-5 py-3 text-[12px] font-bold tracking-[0.18em] uppercase transition"
              style="background: #e45826; color: #fff"
              :disabled="recommendingId === selected.id"
              @click="recommendPrice(selected)"
            >
              {{
                recommendingId === selected.id ? "Working…" : "Recommend again"
              }}
            </button>
          </div>

          <div v-else class="mt-6 text-sm text-[#E6D5B8]/70">
            Klik salah satu event untuk melihat detail.
          </div>
        </div>

        <div class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-8">
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            Notes
          </p>
          <h3 class="mt-2 font-serif text-2xl text-[#1B1A17]">API endpoints</h3>

          <div class="mt-5 h-px w-24 bg-black/10"></div>

          <ul class="mt-5 space-y-2 text-sm text-[#1B1A17]/75 leading-relaxed">
            <li class="flex gap-3">
              <span
                class="mt-2 h-2 w-2 rounded-full"
                style="background: #f0a500"
              ></span>
              <span><span class="font-semibold">GET</span> /api/events</span>
            </li>
            <li class="flex gap-3">
              <span
                class="mt-2 h-2 w-2 rounded-full"
                style="background: #e45826"
              ></span>
              <span
                ><span class="font-semibold">POST</span>
                /api/events/{id}/recommend-price</span
              >
            </li>
          </ul>

          <p class="mt-4 text-xs text-[#1B1A17]/55">
            Jika struktur field event kamu berbeda (nama kolom), tinggal
            sesuaikan fungsi
            <span class="font-semibold">normalizeEvent()</span>.
          </p>
        </div>
      </aside>
    </div>
  </section>
</template>
