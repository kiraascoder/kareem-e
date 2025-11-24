<script setup>
import { RouterLink, RouterView } from "vue-router";
</script>

<template>
  <div class="min-h-screen bg-slate-100">
    <header
      class="bg-slate-800 text-white px-6 py-3 flex flex-wrap items-center justify-between gap-3"
    >
      <h1 class="font-semibold text-lg">
        Kareem Pricing
      </h1>

      <nav class="flex flex-wrap gap-4 text-sm">
        <RouterLink to="/" active-class="font-bold underline" class="hover:underline">
          Home
        </RouterLink>
        <RouterLink to="/about" active-class="font-bold underline" class="hover:underline">
          About
        </RouterLink>
        <RouterLink to="/contact" active-class="font-bold underline" class="hover:underline">
          Contact
        </RouterLink>
        <RouterLink to="/booking" active-class="font-bold underline" class="hover:underline">
          Booking
        </RouterLink>

        <span class="opacity-50 hidden sm:inline">|</span>

        <RouterLink to="/dashboard" active-class="font-bold underline" class="hover:underline">
          Dashboard
        </RouterLink>
        <RouterLink to="/events" active-class="font-bold underline" class="hover:underline">
          Events
        </RouterLink>
      </nav>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-6">
      <RouterView />
    </main>
  </div>
</template>
