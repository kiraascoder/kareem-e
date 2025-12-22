<script setup>
const pillars = [
  {
    no: "01",
    title: "Objective",
    desc: "Menetapkan harga penawaran event lebih objektif dan adaptif dengan pendekatan data-driven.",
    dot: "#F0A500",
  },
  {
    no: "02",
    title: "Predict",
    desc: "Random Forest digunakan untuk memprediksi permintaan berdasarkan data & karakteristik event.",
    dot: "#E45826",
  },
  {
    no: "03",
    title: "Adjust",
    desc: "Fuzzy Logic menentukan faktor penyesuaian harga (turun/naik) sesuai kondisi input.",
    dot: "#F0A500",
  },
  {
    no: "04",
    title: "Deliver",
    desc: "Frontend Vue menampilkan hasil, Laravel mengelola data, FastAPI menjalankan model & fuzzy engine.",
    dot: "#E45826",
  },
];

const stack = [
  { k: "Frontend", v: "Vue 3 (Vite) + Axios" },
  { k: "Backend", v: "Laravel (API) + MySQL/MariaDB" },
  { k: "ML Service", v: "FastAPI + scikit-learn + scikit-fuzzy" },
  {
    k: "Output",
    v: "Lead time, permintaan prediksi, faktor harga, harga rekomendasi",
  },
];

const timeline = [
  {
    year: "Step 01",
    title: "Input & booking data",
    desc: "Jenis event, jumlah peserta, tanggal booking & tanggal event, season, harga dasar.",
  },
  {
    year: "Step 02",
    title: "Predict demand (RF)",
    desc: "Model Random Forest memberikan skor prediksi permintaan (0–1).",
  },
  {
    year: "Step 03",
    title: "Compute factor (Fuzzy)",
    desc: "Fuzzy rules mengubah kondisi menjadi faktor koreksi (mis. 0.9–1.3).",
  },
  {
    year: "Step 04",
    title: "Recommend & log",
    desc: "Harga rekomendasi dihitung, bisa disimpan sebagai riwayat untuk audit.",
  },
];
</script>

<template>
  <section class="space-y-10">
    <!-- Header / Intro -->
    <header class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-10">
      <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">About</p>
      <h1 class="mt-2 font-serif text-4xl text-[#1B1A17]">Kareem Pricing</h1>

      <div class="mt-4 h-px w-24 bg-black/10"></div>

      <p
        class="mt-5 max-w-3xl text-sm leading-relaxed text-[#1B1A17]/75 sm:text-base"
      >
        Kareem Pricing adalah sistem web untuk membantu
        <span class="font-semibold">Kareem Entertainment</span> menetapkan harga
        penawaran event secara lebih
        <span class="font-semibold">objektif</span> dan
        <span class="font-semibold">adaptif</span>. Sistem menggabungkan:
        <span class="font-semibold">Machine Learning (Random Forest)</span>
        untuk memprediksi permintaan, serta
        <span class="font-semibold">Fuzzy Logic</span> untuk menentukan faktor
        penyesuaian harga.
      </p>

      <div class="mt-7 flex flex-wrap gap-3">
        <span
          class="rounded-full bg-[#1B1A17] px-4 py-2 text-[12px] font-bold tracking-[0.18em] uppercase text-[#E6D5B8]"
        >
          Research-ready
        </span>
        <span
          class="rounded-full border border-black/10 bg-[#E6D5B8]/60 px-4 py-2 text-[12px] font-bold tracking-[0.18em] uppercase text-[#1B1A17]"
        >
          Clean UI
        </span>
        <span
          class="rounded-full border border-black/10 bg-white/60 px-4 py-2 text-[12px] font-bold tracking-[0.18em] uppercase text-[#1B1A17]"
        >
          Explainable Output
        </span>
      </div>
    </header>

    <!-- Pillars -->
    <section class="space-y-5">
      <div class="flex items-end justify-between gap-3">
        <div>
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            Principles
          </p>
          <h2 class="mt-2 font-serif text-3xl text-[#1B1A17]">Pilar sistem</h2>
        </div>
        <div class="h-px w-28 bg-black/10"></div>
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <article
          v-for="(p, i) in pillars"
          :key="i"
          class="rounded-3xl border border-black/10 bg-white/70 p-6"
        >
          <div class="flex items-start justify-between gap-4">
            <div class="space-y-1">
              <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/55">
                Pillar {{ p.no }}
              </p>
              <h3 class="font-serif text-xl text-[#1B1A17]">{{ p.title }}</h3>
            </div>
            <span
              class="h-3 w-3 rounded-full"
              :style="{ backgroundColor: p.dot }"
            ></span>
          </div>

          <div class="mt-4 h-px w-full bg-black/10"></div>
          <p class="mt-4 text-sm leading-relaxed text-[#1B1A17]/75">
            {{ p.desc }}
          </p>
        </article>
      </div>
    </section>

    <!-- Architecture / Stack -->
    <section class="grid gap-6 lg:grid-cols-2">
      <div
        class="rounded-3xl border border-black/10 bg-[#1B1A17] p-7 sm:p-8 text-[#E6D5B8]"
      >
        <p class="text-xs tracking-[0.22em] uppercase text-[#E6D5B8]/70">
          Architecture
        </p>
        <h2 class="mt-2 font-serif text-3xl">Alur sistem</h2>

        <div class="mt-5 h-px w-24 bg-[#E6D5B8]/20"></div>

        <div class="mt-6 space-y-4 text-sm text-[#E6D5B8]/80 leading-relaxed">
          <p>
            Frontend (Vue) mengirim request ke Laravel API. Laravel akan
            mengambil data dari database, lalu memanggil ML Service (FastAPI)
            untuk menghasilkan prediksi permintaan dan faktor koreksi.
          </p>

          <div
            class="rounded-2xl border border-[#E6D5B8]/15 bg-white/5 p-5 font-mono text-xs leading-relaxed"
          >
            [Browser (Vue)] → [Laravel API] → [FastAPI ML Service]<br />
            &nbsp;&nbsp;&nbsp;&nbsp;↑&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↑<br />
            &nbsp;&nbsp;&nbsp;&nbsp;└───────────────[ MySQL ]──────────────┘
          </div>

          <p class="text-xs text-[#E6D5B8]/65">
            Catatan: tampilan & log rekomendasi bisa dipakai untuk screenshot
            BAB III–IV.
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
          <span
            class="rounded-full border border-[#E6D5B8]/20 bg-white/5 px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
          >
            Logging
          </span>
        </div>
      </div>

      <div class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-8">
        <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
          Tech stack
        </p>
        <h2 class="mt-2 font-serif text-3xl text-[#1B1A17]">Komponen utama</h2>

        <div class="mt-5 h-px w-24 bg-black/10"></div>

        <dl class="mt-6 space-y-4">
          <div v-for="(s, i) in stack" :key="i" class="grid grid-cols-3 gap-4">
            <dt class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60">
              {{ s.k }}
            </dt>
            <dd class="col-span-2 text-sm text-[#1B1A17]/75 leading-relaxed">
              {{ s.v }}
            </dd>
          </div>
        </dl>

        <div
          class="mt-8 rounded-2xl border border-black/10 bg-[#E6D5B8]/60 p-5"
        >
          <p class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60">
            Why this matters
          </p>
          <p class="mt-2 text-sm text-[#1B1A17]/75 leading-relaxed">
            Output model dibuat agar bisa dipahami: tidak hanya “angka harga”,
            tetapi juga faktor yang memengaruhi (lead time, prediksi permintaan,
            faktor koreksi).
          </p>
        </div>
      </div>
    </section>

    <!-- Timeline / How it works -->
    <section class="space-y-5">
      <div class="flex items-end justify-between gap-3">
        <div>
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            Flow
          </p>
          <h2 class="mt-2 font-serif text-3xl text-[#1B1A17]">
            Cara kerja singkat
          </h2>
        </div>
        <div class="h-px w-28 bg-black/10"></div>
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <div
          v-for="(t, i) in timeline"
          :key="i"
          class="rounded-3xl border border-black/10 bg-white/70 p-6"
        >
          <div class="flex items-start justify-between gap-4">
            <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/55">
              {{ t.year }}
            </p>
            <span
              class="h-3 w-3 rounded-full"
              :style="{ backgroundColor: i % 2 === 0 ? '#F0A500' : '#E45826' }"
            ></span>
          </div>
          <h3 class="mt-2 font-semibold text-[#1B1A17]">{{ t.title }}</h3>
          <p class="mt-2 text-sm text-[#1B1A17]/75 leading-relaxed">
            {{ t.desc }}
          </p>
        </div>
      </div>
    </section>

    <!-- Bottom CTA -->
    <section class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-10">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="max-w-2xl">
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            Next step
          </p>
          <h2 class="mt-2 font-serif text-3xl text-[#1B1A17]">
            Coba alurnya langsung
          </h2>
          <p class="mt-2 text-sm text-[#1B1A17]/75 leading-relaxed">
            Mulai dari Booking → simpan event → minta rekomendasi harga di
            Dashboard/Events.
          </p>
        </div>

        <div class="flex flex-wrap gap-3">
          <RouterLink
            to="/booking"
            class="rounded-full px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase"
            style="background: #1b1a17; color: #e6d5b8"
          >
            Booking
          </RouterLink>
          <RouterLink
            to="/dashboard"
            class="rounded-full px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase"
            style="background: #f0a500; color: #1b1a17"
          >
            Dashboard
          </RouterLink>
          <RouterLink
            to="/events"
            class="rounded-full border px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase hover:bg-white/60 transition"
            style="border-color: rgba(0, 0, 0, 0.12); color: #1b1a17"
          >
            Events
          </RouterLink>
        </div>
      </div>
    </section>
  </section>
</template>
