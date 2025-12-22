<script setup>
import { RouterLink, useRouter } from "vue-router";

const router = useRouter();
</script>

<template>
  <section class="space-y-10">
    <!-- Header -->
    <header class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-10">
      <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
        Access
      </p>
      <h1 class="mt-2 font-serif text-4xl text-[#1B1A17]">Unauthorized</h1>

      <div class="mt-4 h-px w-24 bg-black/10"></div>

      <p
        class="mt-5 max-w-3xl text-sm leading-relaxed text-[#1B1A17]/75 sm:text-base"
      >
        Kamu tidak memiliki izin untuk membuka halaman ini. Silakan login
        menggunakan akun internal
        <span class="font-semibold">Kareem Pricing</span> milik
        <span class="font-semibold">Kareem Entertainment (Kareem E)</span>.
      </p>
    </header>

    <section class="grid gap-6 lg:grid-cols-2">
      <!-- Left panel (message card) -->
      <div
        class="rounded-3xl border border-black/10 bg-[#1B1A17] p-7 sm:p-8 text-[#E6D5B8]"
      >
        <p class="text-xs tracking-[0.22em] uppercase text-[#E6D5B8]/70">401</p>
        <h2 class="mt-2 font-serif text-2xl">Access denied</h2>

        <div class="mt-5 h-px w-24 bg-[#E6D5B8]/20"></div>

        <p class="mt-5 text-sm text-[#E6D5B8]/80 leading-relaxed">
          Kemungkinan penyebab:
        </p>

        <ul class="mt-4 space-y-2 text-sm text-[#E6D5B8]/80">
          <li class="flex gap-3">
            <span
              class="mt-2 h-2 w-2 rounded-full"
              style="background: #f0a500"
            ></span>
            <span>Belum login / token sesi tidak ada.</span>
          </li>
          <li class="flex gap-3">
            <span
              class="mt-2 h-2 w-2 rounded-full"
              style="background: #e45826"
            ></span>
            <span>Token kadaluarsa (session expired).</span>
          </li>
          <li class="flex gap-3">
            <span
              class="mt-2 h-2 w-2 rounded-full"
              style="background: #f0a500"
            ></span>
            <span>Akun tidak memiliki role/izin untuk halaman tersebut.</span>
          </li>
        </ul>

        <div class="mt-6 flex flex-wrap gap-2">
          <span
            class="rounded-full px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
            style="background: #f0a500; color: #1b1a17"
            >Auth</span
          >
          <span
            class="rounded-full px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
            style="background: #e45826; color: #fff"
            >Guard</span
          >
          <span
            class="rounded-full border border-[#E6D5B8]/20 bg-white/5 px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
          >
            Router
          </span>
        </div>
      </div>

      <!-- Right panel (CTA) -->
      <div class="space-y-6">
        <div class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-8">
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            What to do
          </p>
          <h3 class="mt-2 font-serif text-2xl text-[#1B1A17]">Continue</h3>

          <div class="mt-5 h-px w-24 bg-black/10"></div>

          <p class="mt-5 text-sm text-[#1B1A17]/75 leading-relaxed">
            Login untuk mengakses dashboard internal, atau kembali ke halaman
            publik.
          </p>

          <div class="mt-6 flex flex-wrap gap-3">
            <RouterLink
              to="/login"
              class="rounded-full px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase"
              style="background: #1b1a17; color: #e6d5b8"
            >
              Login
            </RouterLink>

            <RouterLink
              to="/"
              class="rounded-full px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase"
              style="background: #f0a500; color: #1b1a17"
            >
              Home
            </RouterLink>

            <button
              type="button"
              class="rounded-full border px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase hover:bg-white/60 transition"
              style="border-color: rgba(0, 0, 0, 0.12); color: #1b1a17"
              @click="router.back()"
            >
              Go back
            </button>
          </div>
        </div>

        <div
          class="rounded-3xl border border-black/10 bg-[#E6D5B8]/60 p-7 sm:p-8"
        >
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            Tip
          </p>
          <p class="mt-2 text-sm text-[#1B1A17]/75 leading-relaxed">
            Jika kamu memakai middleware router, arahkan user yang tidak login
            ke <span class="font-semibold">/login</span>, dan yang tidak punya
            role/izin ke <span class="font-semibold">/unauthorized</span>.
          </p>
        </div>
      </div>
    </section>
  </section>
</template>
