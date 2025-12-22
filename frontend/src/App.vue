<script setup>
import { RouterLink, RouterView, useRoute } from "vue-router";

const route = useRoute();

const isActive = (path) => route.path === path;

const navLink = (path) =>
  [
    "relative px-2 py-1 text-[13px] tracking-[0.14em] uppercase transition",
    "text-[#1B1A17]/80 hover:text-[#1B1A17]",
    isActive(path)
      ? "text-[#1B1A17] after:absolute after:left-2 after:right-2 after:-bottom-1 after:h-[1px] after:bg-[#E45826]"
      : "",
  ].join(" ");
</script>

<template>
  <div class="min-h-screen bg-[#E6D5B8]">
    <header class="border-b border-black/10 bg-[#E6D5B8]">
      <div class="max-w-5xl mx-auto px-4 py-4">
        <!-- Top row -->
        <div class="flex flex-wrap items-center justify-between gap-3">
          <!-- Brand (European editorial) -->
          <RouterLink to="/" class="group inline-flex items-baseline gap-3">
            <span
              class="text-xl font-serif font-semibold text-[#1B1A17] tracking-tight"
            >
              Kareem Pricing
            </span>
            <span
              class="ml-1 h-[10px] w-[10px] rounded-full bg-[#F0A500] ring-2 ring-[#E6D5B8]"
              aria-hidden="true"
            ></span>
          </RouterLink>
                    
        </div>

        <!-- Nav row -->
        <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
          <nav class="flex flex-wrap items-center gap-4">
            <RouterLink to="/" :class="navLink('/')">Home</RouterLink>
            <RouterLink to="/about" :class="navLink('/about')"
              >About</RouterLink
            >
            <RouterLink to="/contact" :class="navLink('/contact')"
              >Contact</RouterLink
            >
            <RouterLink to="/booking" :class="navLink('/booking')"
              >Booking</RouterLink
            >

            <span
              class="mx-1 hidden sm:inline-block h-4 w-px bg-black/15"
            ></span>

            <RouterLink to="/events" :class="navLink('/events')"
              >Events</RouterLink
            >
          </nav>

          <!-- Subtle CTA (European style: not too loud) -->
          <div class="flex items-center gap-2">
            <RouterLink
              to="/login"
              class="inline-flex items-center gap-2 rounded-full border border-[#1B1A17]/25 bg-transparent px-4 py-2 text-[12px] tracking-[0.16em] uppercase text-[#1B1A17] hover:bg-white/25 transition"
            >
              <span class="h-2 w-2 rounded-full bg-[#E45826]"></span>
              Login
            </RouterLink>

            <RouterLink
              to="/register"
              class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-[12px] tracking-[0.16em] uppercase font-semibold transition"
              style="background: #1b1a17; color: #e6d5b8"
            >
              Register
            </RouterLink>
          </div>
        </div>

        <!-- Thin accent line (gold) -->
        <div
          class="mt-4 h-[2px] w-full bg-gradient-to-r from-transparent via-[#F0A500] to-transparent opacity-80"
        ></div>
      </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-6">
      <RouterView />
    </main>

    <footer class="border-t border-black/10">
      <div
        class="max-w-5xl mx-auto px-4 py-6 flex flex-wrap items-center justify-between gap-2"
      >
        <p class="text-xs tracking-[0.14em] uppercase text-[#1B1A17]/60">
          © {{ new Date().getFullYear() }} Kareem Pricing
        </p>
        <p class="text-xs text-[#1B1A17]/60">
          Built for research • Event Organizer Pricing
        </p>
      </div>
    </footer>
  </div>
</template>
