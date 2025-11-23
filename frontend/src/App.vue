<script setup>
import { ref, onMounted } from "vue";
import api from "./services/api";

const backendMessage = ref("Loading...");
const mlMessage = ref("Loading...");

const fetchBackend = async () => {
  try {
    const res = await api.get("/ping");
    backendMessage.value = res.data.message ?? JSON.stringify(res.data);
  } catch (err) {
    console.error(err);
    backendMessage.value = "Gagal konek ke backend";
  }
};

const testMl = async () => {
  try {
    const res = await api.post("/test-ml");
    mlMessage.value = JSON.stringify(res.data.ml_result);
  } catch (err) {
    console.error(err);
    mlMessage.value = "Gagal konek ke ML service lewat backend";
  }
};

onMounted(() => {
  fetchBackend();
  testMl();
});
</script>

<template>
  <main style="padding: 2rem; font-family: system-ui">
    <h1 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem">
      Kareem Pricing – Test Koneksi
    </h1>

    <section style="margin-bottom: 1.5rem">
      <h2 style="font-weight: 600">Backend Laravel:</h2>
      <p>{{ backendMessage }}</p>
    </section>

    <section>
      <h2 style="font-weight: 600">ML Service (via Laravel /api/test-ml):</h2>
      <pre
        style="
          background: #f5f5f5;
          padding: 1rem;
          border-radius: 8px;
          max-width: 600px;
        "
        >{{ mlMessage }}
      </pre>
    </section>
  </main>
</template>
