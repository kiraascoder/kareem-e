
<script setup>
import { ref } from "vue";

const form = ref({
  name: "",
  email: "",
  message: "",
});

const submitted = ref(false);

const submitForm = () => {
  submitted.value = true;
};
</script>

<template>
  <section class="max-w-xl">
    <h2 class="text-2xl font-semibold mb-3">Kontak</h2>
    <p class="text-gray-700 mb-4">
      Untuk informasi lebih lanjut terkait layanan dan penawaran,
      silakan kirim pesan melalui formulir berikut.
    </p>

    <form @submit.prevent="submitForm" class="space-y-3 bg-white p-4 border rounded-lg shadow-sm">
      <div>
        <label class="block text-sm font-medium mb-1">Nama</label>
        <input
          v-model="form.name"
          type="text"
          class="w-full border rounded px-3 py-2 text-sm"
          required
        />
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Email</label>
        <input
          v-model="form.email"
          type="email"
          class="w-full border rounded px-3 py-2 text-sm"
          required
        />
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Pesan</label>
        <textarea
          v-model="form.message"
          rows="4"
          class="w-full border rounded px-3 py-2 text-sm"
          required
        ></textarea>
      </div>

      <button
        type="submit"
        class="bg-slate-800 text-white text-sm px-4 py-2 rounded"
      >
        Kirim
      </button>

      <p v-if="submitted" class="text-green-600 text-sm mt-2">
        Terima kasih, pesan Anda sudah tercatat (dummy).
      </p>
    </form>
  </section>
</template>
