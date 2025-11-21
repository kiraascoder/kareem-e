<script setup>
import { RouterLink, RouterView } from "vue-router";
</script>

<template>
  <div class="min-h-screen bg-slate-100">
    <!-- Navbar -->
    <header
      class="bg-slate-800 text-white px-6 py-3 flex items-center justify-between"
    >
      <h1 class="font-semibold text-lg">Kareem Pricing</h1>

      <nav class="flex gap-4 text-sm">
        <RouterLink
          to="/"
          class="hover:underline"
          active-class="font-bold underline"
        >
          Dashboard
        </RouterLink>
        <RouterLink
          to="/events"
          class="hover:underline"
          active-class="font-bold underline"
        >
          Events
        </RouterLink>
      </nav>
    </header>

    <!-- Konten halaman -->
    <main class="max-w-5xl mx-auto px-4 py-6">
      <RouterView />
    </main>
  </div>
</template>

<style>
/* Kalau kamu belum pakai Tailwind, ini agar kelas di atas tidak error.
   Kalau Vite default, hapus class Tailwind dan pakai CSS biasa di sini. */
</style>
