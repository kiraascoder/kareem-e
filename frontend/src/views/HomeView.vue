<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from "vue";
import { RouterLink } from "vue-router";

const slides = [
  {
    // Ganti ke gambar kamu sendiri (mis: import dari /assets)
    img: "https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=2400&q=70",
    title: "Kareem Entertainment",
    subtitle: "Event Organizer • Dynamic Pricing System",
    cta: { label: "Booking", to: "/booking" },
  },
  {
    img: "https://images.unsplash.com/photo-1515165562835-c3b8a5d1f43a?auto=format&fit=crop&w=2400&q=70",
    title: "Gallery & Portfolio",
    subtitle: "Clean. Editorial. European feel.",
    cta: { label: "About Us", to: "/about" },
  },
  {
    img: "https://images.unsplash.com/photo-1505238680356-667803448bb6?auto=format&fit=crop&w=2400&q=70",
    title: "Smart Pricing Recommendation",
    subtitle: "Random Forest • Fuzzy Logic • Laravel • FastAPI",
    cta: { label: "Dashboard", to: "/dashboard" },
  },
];

const active = ref(0);
const hovering = ref(false);

const current = computed(() => slides[active.value]);

function next() {
  active.value = (active.value + 1) % slides.length;
}
function prev() {
  active.value = (active.value - 1 + slides.length) % slides.length;
}
function go(i) {
  active.value = i;
}

let timer;
onMounted(() => {
  timer = setInterval(() => {
    if (!hovering.value) next();
  }, 6000);
});
onBeforeUnmount(() => clearInterval(timer));

// FAQ accordion
const faqs = ref([
  {
    q: "Apakah harga rekomendasi otomatis menggantikan harga dasar?",
    a: "Tidak. Sistem memberi rekomendasi. Keputusan akhir tetap di internal Kareem Entertainment.",
    open: true,
  },
  {
    q: "Faktor apa saja yang memengaruhi rekomendasi harga?",
    a: "Jenis event, jumlah peserta, lead time, season (high/low), dan harga dasar.",
    open: false,
  },
  {
    q: "Apakah hasil rekomendasi bisa disimpan sebagai riwayat?",
    a: "Bisa. Umumnya disimpan ke tabel log agar mudah audit dan evaluasi.",
    open: false,
  },
]);

const categories = [
  {
    title: "Corporate",
    desc: "Meeting, gathering, brand activation",
    dot: "#F0A500",
  },
  {
    title: "Wedding",
    desc: "Decoration, venue, documentation",
    dot: "#E45826",
  },
  { title: "Music", desc: "Stage, talent, sound & lighting", dot: "#F0A500" },
  {
    title: "Festival",
    desc: "Booth, crowd management, rundown",
    dot: "#E45826",
  },
];
</script>

<template>
  <div class="space-y-14">
    <!-- =========================
      FULL WIDTH + FULL HEIGHT HERO
      Breakout dari max-w-5xl di App.vue
    ========================== -->
    <section
      class="relative w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] overflow-hidden"
      @mouseenter="hovering = true"
      @mouseleave="hovering = false"
    >
      <!-- tinggi full layar: sesuaikan angka header kalau perlu -->
      <div class="relative h-[calc(100vh-72px)] min-h-[520px] bg-[#1B1A17]">
        <!-- Slide image -->
        <transition name="fade" mode="out-in">
          <div :key="active" class="absolute inset-0">
            <img
              :src="current.img"
              class="h-full w-full object-cover"
              style="filter: grayscale(1) contrast(1.05) brightness(0.7)"
              alt="Hero slide"
            />
          </div>
        </transition>

        <!-- Overlay gradient -->
        <div
          class="absolute inset-0 bg-gradient-to-r from-[#1B1A17]/85 via-[#1B1A17]/45 to-transparent"
        ></div>
        <div class="absolute inset-0 bg-black/20"></div>

        <!-- Content -->
        <div class="relative h-full">
          <div class="h-full max-w-6xl mx-auto px-6 flex items-center">
            <div class="max-w-xl">
              <p class="text-xs tracking-[0.22em] uppercase text-[#E6D5B8]/70">
                {{ current.subtitle }}
              </p>

              <h1
                class="mt-3 font-serif text-4xl sm:text-5xl leading-tight text-[#E6D5B8]"
              >
                {{ current.title }}
              </h1>

              <div class="mt-5 h-px w-24 bg-[#E6D5B8]/25"></div>

              <p
                class="mt-5 text-sm sm:text-base leading-relaxed text-[#E6D5B8]/80"
              >
                Sistem web untuk membantu penawaran harga event lebih objektif
                dan adaptif (Random Forest + Fuzzy Logic) terintegrasi Laravel &
                FastAPI.
              </p>

              <div class="mt-7 flex flex-wrap gap-3">
                <RouterLink
                  :to="current.cta.to"
                  class="rounded-full px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase"
                  style="background: #f0a500; color: #1b1a17"
                >
                  {{ current.cta.label }}
                </RouterLink>

                <RouterLink
                  to="/contact"
                  class="rounded-full border px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase text-[#E6D5B8] hover:bg-white/10 transition"
                  style="border-color: rgba(230, 213, 184, 0.25)"
                >
                  Contact
                </RouterLink>
              </div>
            </div>
          </div>

          <!-- Arrows (kiri/kanan) -->
          <button
            @click="prev"
            class="absolute left-5 top-1/2 -translate-y-1/2 h-12 w-12 rounded-full bg-black/35 border border-white/15 text-white hover:bg-black/50 transition grid place-items-center"
            aria-label="Previous slide"
          >
            ‹
          </button>

          <button
            @click="next"
            class="absolute right-5 top-1/2 -translate-y-1/2 h-12 w-12 rounded-full bg-black/35 border border-white/15 text-white hover:bg-black/50 transition grid place-items-center"
            aria-label="Next slide"
          >
            ›
          </button>

          <!-- Dots indikator -->
          <div class="absolute bottom-6 left-0 right-0">
            <div class="max-w-6xl mx-auto px-6 flex items-center gap-2">
              <button
                v-for="(_, i) in slides"
                :key="i"
                @click="go(i)"
                class="h-2.5 w-10 rounded-full transition"
                :style="{
                  backgroundColor:
                    i === active ? '#F0A500' : 'rgba(230,213,184,.22)',
                }"
                aria-label="Go to slide"
              />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- =========================
      JENIS EVENT / CATEGORIES
    ========================== -->
    <section class="space-y-5">
      <div class="flex items-end justify-between gap-3">
        <div>
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            Categories
          </p>
          <h2 class="mt-2 font-serif text-3xl text-[#1B1A17]">Jenis Event</h2>
        </div>
        <div class="h-px w-28 bg-black/10"></div>
      </div>

      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div
          v-for="(c, i) in categories"
          :key="i"
          class="rounded-3xl border border-black/10 bg-white/70 p-5"
        >
          <div class="flex items-start justify-between gap-3">
            <h3 class="font-serif text-xl text-[#1B1A17]">{{ c.title }}</h3>
            <span
              class="h-3 w-3 rounded-full"
              :style="{ backgroundColor: c.dot }"
            ></span>
          </div>
          <p class="mt-3 text-sm text-[#1B1A17]/70 leading-relaxed">
            {{ c.desc }}
          </p>
          <div class="mt-4 h-px w-full bg-black/10"></div>
          <RouterLink
            to="/booking"
            class="mt-4 inline-flex text-xs tracking-[0.18em] uppercase font-bold"
            style="color: #e45826"
          >
            Request →
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- =========================
      FAQ
    ========================== -->
    <section class="space-y-5">
      <div class="flex items-end justify-between gap-3">
        <div>
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            FAQ
          </p>
          <h2 class="mt-2 font-serif text-3xl text-[#1B1A17]">
            Pertanyaan Umum
          </h2>
        </div>
        <div class="h-px w-28 bg-black/10"></div>
      </div>

      <div
        class="rounded-3xl border border-black/10 bg-white/70 overflow-hidden"
      >
        <button
          v-for="(f, i) in faqs"
          :key="i"
          class="w-full text-left"
          @click="faqs[i].open = !faqs[i].open"
        >
          <div class="flex items-center justify-between gap-4 px-6 py-5">
            <div class="space-y-1">
              <p class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/55">
                Question {{ String(i + 1).padStart(2, "0") }}
              </p>
              <p class="font-semibold text-[#1B1A17]">{{ f.q }}</p>
            </div>

            <span
              class="h-9 w-9 rounded-full border border-black/10 bg-white/60 grid place-items-center"
            >
              <span class="text-lg leading-none text-[#1B1A17]/70">{{
                f.open ? "–" : "+"
              }}</span>
            </span>
          </div>

          <div v-if="f.open" class="px-6 pb-6 -mt-2">
            <div class="h-px w-full bg-black/10 mb-4"></div>
            <p class="text-sm leading-relaxed text-[#1B1A17]/75">{{ f.a }}</p>
          </div>

          <div
            v-if="i !== faqs.length - 1"
            class="h-px w-full bg-black/10"
          ></div>
        </button>
      </div>
    </section>

    <!-- =========================
      BOOKING SECTION (CTA)
    ========================== -->
    <section class="space-y-5">
      <div class="rounded-3xl border border-black/10 bg-[#1B1A17] p-7 sm:p-10">
        <p class="text-xs tracking-[0.22em] uppercase text-[#E6D5B8]/70">
          Booking
        </p>
        <h2 class="mt-2 font-serif text-3xl text-[#E6D5B8]">
          Ready to request a quote?
        </h2>
        <p class="mt-3 text-sm text-[#E6D5B8]/80 max-w-2xl leading-relaxed">
          Isi data booking untuk mendapatkan penawaran. Kamu bisa lanjutkan
          proses rekomendasi harga lewat Dashboard setelah data tersimpan.
        </p>

        <div class="mt-6 flex flex-wrap gap-3">
          <RouterLink
            to="/booking"
            class="rounded-full px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase"
            style="background: #f0a500; color: #1b1a17"
          >
            Booking Now
          </RouterLink>

          <RouterLink
            to="/dashboard"
            class="rounded-full border px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase text-[#E6D5B8] hover:bg-white/10 transition"
            style="border-color: rgba(230, 213, 184, 0.25)"
          >
            Dashboard
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- =========================
      FOOTER (khusus Home)
    ========================== -->
    <footer class="rounded-3xl border border-black/10 bg-white/70 px-6 py-8">
      <div class="flex flex-wrap items-start justify-between gap-6">
        <div class="max-w-xl">
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            Kareem Pricing
          </p>
          <p class="mt-2 font-serif text-2xl text-[#1B1A17]">
            Elegant UI. Data-driven pricing.
          </p>
          <p class="mt-2 text-sm text-[#1B1A17]/70">
            Laravel API • FastAPI ML Service • Vue 3 • Random Forest • Fuzzy
            Logic
          </p>
        </div>

        <div class="text-sm text-[#1B1A17]/70 space-y-2">
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            Quick links
          </p>
          <div class="flex flex-col gap-1">
            <RouterLink class="hover:underline" to="/about">About</RouterLink>
            <RouterLink class="hover:underline" to="/contact"
              >Contact</RouterLink
            >
            <RouterLink class="hover:underline" to="/booking"
              >Booking</RouterLink
            >
            <RouterLink class="hover:underline" to="/dashboard"
              >Dashboard</RouterLink
            >
          </div>
        </div>
      </div>

      <div class="mt-7 h-px w-full bg-black/10"></div>
      <div class="mt-4 flex flex-wrap items-center justify-between gap-2">
        <p class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60">
          © {{ new Date().getFullYear() }} Kareem Pricing
        </p>      
      </div>
    </footer>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 280ms ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
