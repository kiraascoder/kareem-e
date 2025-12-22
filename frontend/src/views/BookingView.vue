<script setup>
import { computed, ref } from "vue";

const form = ref({
  client_name: "",
  email: "",
  phone: "",
  jenis_event: "corporate",
  tanggal_booking: new Date().toISOString().slice(0, 10),
  tanggal_event: "",
  jumlah_peserta: 100,
  season: "low",
  harga_dasar: 25000000,
  notes: "",
});

const sending = ref(false);
const sent = ref(false);

const leadTime = computed(() => {
  if (!form.value.tanggal_booking || !form.value.tanggal_event) return null;
  const a = new Date(form.value.tanggal_booking);
  const b = new Date(form.value.tanggal_event);
  const diff = Math.ceil((b - a) / (1000 * 60 * 60 * 24));
  if (Number.isNaN(diff)) return null;
  return diff;
});

const preview = computed(() => {
  const base = Number(form.value.harga_dasar || 0);
  // preview UI saja (bukan hasil ML):
  const factor = form.value.season === "high" ? 1.15 : 1.0;
  const pax = Number(form.value.jumlah_peserta || 0);
  const paxFactor = pax >= 300 ? 1.1 : pax >= 150 ? 1.05 : 1.0;
  const lt = leadTime.value ?? 0;
  const leadFactor = lt <= 7 && lt > 0 ? 1.1 : lt <= 14 && lt > 0 ? 1.05 : 1.0;

  const finalFactor = factor * paxFactor * leadFactor;
  return {
    base,
    factor: finalFactor,
    estimate: Math.round(base * finalFactor),
  };
});

function rupiah(n) {
  return Number(n || 0).toLocaleString("id-ID");
}

async function submit() {
  sending.value = true;
  sent.value = false;

  // TODO: sambungkan ke Laravel:
  // await api.post("/events", { ...form.value })
  await new Promise((r) => setTimeout(r, 900));

  sending.value = false;
  sent.value = true;

  setTimeout(() => (sent.value = false), 3500);
}
</script>

<template>
  <section class="space-y-10">
    <!-- Header -->
    <header class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-10">
      <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
        Booking
      </p>
      <h1 class="mt-2 font-serif text-4xl text-[#1B1A17]">Request a quote</h1>

      <div class="mt-4 h-px w-24 bg-black/10"></div>

      <p
        class="mt-5 max-w-3xl text-sm leading-relaxed text-[#1B1A17]/75 sm:text-base"
      >
        Isi data booking untuk kebutuhan event. Data ini dapat digunakan untuk
        proses rekomendasi harga melalui ML Service (Random Forest + Fuzzy
        Logic) di dashboard internal.
      </p>
    </header>

    <!-- Main -->
    <section class="grid gap-6 lg:grid-cols-2">
      <!-- FORM -->
      <form
        @submit.prevent="submit"
        class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-8 space-y-6"
      >
        <div class="flex items-center justify-between gap-4">
          <div>
            <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
              Booking form
            </p>
            <h2 class="mt-1 font-serif text-2xl text-[#1B1A17]">Details</h2>
          </div>

          <span
            class="hidden sm:inline rounded-full px-3 py-1 text-[11px] font-bold tracking-[0.16em] uppercase"
            style="background: #f0a500; color: #1b1a17"
          >
            Elegant • Simple
          </span>
        </div>

        <div class="h-px w-full bg-black/10"></div>

        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Client name</label
            >
            <input
              v-model="form.client_name"
              required
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              placeholder="Nama klien / perusahaan"
            />
          </div>

          <div>
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Email</label
            >
            <input
              v-model="form.email"
              type="email"
              required
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              placeholder="email@domain.com"
            />
          </div>

          <div>
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Phone</label
            >
            <input
              v-model="form.phone"
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              placeholder="08xx-xxxx-xxxx"
            />
          </div>

          <div>
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Event type</label
            >
            <select
              v-model="form.jenis_event"
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
            >
              <option value="corporate">Corporate</option>
              <option value="wedding">Wedding</option>
              <option value="music">Music</option>
              <option value="festival">Festival</option>
            </select>
          </div>

          <div>
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Booking date</label
            >
            <input
              v-model="form.tanggal_booking"
              type="date"
              required
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
            />
          </div>

          <div>
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Event date</label
            >
            <input
              v-model="form.tanggal_event"
              type="date"
              required
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
            />
          </div>

          <div>
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Participants</label
            >
            <input
              v-model.number="form.jumlah_peserta"
              type="number"
              min="1"
              required
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
            />
          </div>

          <div>
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Season</label
            >
            <select
              v-model="form.season"
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
            >
              <option value="low">Low</option>
              <option value="high">High</option>
            </select>
          </div>

          <div class="sm:col-span-2">
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Base price (Rp)</label
            >
            <input
              v-model.number="form.harga_dasar"
              type="number"
              min="0"
              required
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              placeholder="25000000"
            />
          </div>

          <div class="sm:col-span-2">
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Notes</label
            >
            <textarea
              v-model="form.notes"
              rows="4"
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              placeholder="Tambahkan kebutuhan, rundown singkat, lokasi, dll…"
            />
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-2">
          <button
            type="submit"
            :disabled="sending"
            class="rounded-full px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase transition disabled:opacity-60"
            style="background: #1b1a17; color: #e6d5b8"
          >
            {{ sending ? "Submitting…" : "Submit" }}
          </button>

          <span
            v-if="sent"
            class="text-sm font-semibold"
            style="color: #e45826"
          >
            Submitted ✓ (dummy)
          </span>

          <p class="text-xs text-[#1B1A17]/55">
            Nanti bisa diarahkan ke endpoint Laravel
            <span class="font-semibold">POST /api/events</span>.
          </p>
        </div>
      </form>

      <!-- RIGHT PANEL (Preview / Summary) -->
      <aside class="space-y-6">
        <div
          class="rounded-3xl border border-black/10 bg-[#1B1A17] p-7 sm:p-8 text-[#E6D5B8]"
        >
          <p class="text-xs tracking-[0.22em] uppercase text-[#E6D5B8]/70">
            Summary
          </p>
          <h2 class="mt-2 font-serif text-2xl">Booking overview</h2>

          <div class="mt-5 h-px w-24 bg-[#E6D5B8]/20"></div>

          <div class="mt-6 space-y-3 text-sm text-[#E6D5B8]/80">
            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Event</span>
              <span class="font-semibold text-[#E6D5B8]">{{
                form.jenis_event
              }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Participants</span>
              <span class="font-semibold text-[#E6D5B8]">{{
                form.jumlah_peserta
              }}</span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Season</span>
              <span
                class="font-semibold"
                :style="{
                  color: form.season === 'high' ? '#F0A500' : '#E6D5B8',
                }"
              >
                {{ form.season }}
              </span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Lead time</span>
              <span class="font-semibold text-[#E6D5B8]">
                {{ leadTime === null ? "-" : leadTime + " days" }}
              </span>
            </div>

            <div class="h-px bg-[#E6D5B8]/15"></div>

            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Base</span>
              <span class="font-semibold text-[#E6D5B8]"
                >Rp {{ rupiah(preview.base) }}</span
              >
            </div>
            <div class="flex justify-between gap-4">
              <span class="text-[#E6D5B8]/60">Preview factor</span>
              <span class="font-semibold" style="color: #f0a500">
                {{ preview.factor.toFixed(2) }}
              </span>
            </div>
            <div class="flex justify-between gap-4">
              <span class="font-semibold text-[#E6D5B8]">Est. price</span>
              <span class="font-bold" style="color: #e45826">
                Rp {{ rupiah(preview.estimate) }}
              </span>
            </div>

            <p class="mt-4 text-xs text-[#E6D5B8]/60 leading-relaxed">
              * Ini hanya preview UI (bukan output ML). Output asli akan muncul
              setelah proses rekomendasi di dashboard.
            </p>
          </div>

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
              API
            </span>
          </div>
        </div>

        <div class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-8">
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            Next
          </p>
          <h3 class="mt-2 font-serif text-2xl text-[#1B1A17]">After booking</h3>

          <div class="mt-5 h-px w-24 bg-black/10"></div>

          <ol class="mt-5 space-y-3 text-sm text-[#1B1A17]/75 leading-relaxed">
            <li class="flex gap-3">
              <span
                class="mt-1 h-2 w-2 rounded-full"
                style="background: #f0a500"
              ></span>
              <span>Data masuk ke database melalui Laravel.</span>
            </li>
            <li class="flex gap-3">
              <span
                class="mt-1 h-2 w-2 rounded-full"
                style="background: #e45826"
              ></span>
              <span
                >Buka Dashboard/Events untuk meminta rekomendasi harga.</span
              >
            </li>
            <li class="flex gap-3">
              <span
                class="mt-1 h-2 w-2 rounded-full"
                style="background: #f0a500"
              ></span>
              <span
                >Hasil: lead time, prediksi permintaan, faktor, harga
                rekomendasi (dan log).</span
              >
            </li>
          </ol>
        </div>
      </aside>
    </section>
  </section>
</template>
