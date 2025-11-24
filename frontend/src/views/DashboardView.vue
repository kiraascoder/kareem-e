<!-- frontend/src/views/DashboardView.vue -->
<script setup>
import { ref, onMounted } from "vue";
import api from "../services/api";

const loading = ref(false);
const error = ref(null);
const stats = ref({
  totalEvents: 0,
  scheduled: 0,
  completed: 0,
  cancelled: 0,
});

const fetchStats = async () => {
  loading.value = true;
  error.value = null;

  try {
    const res = await api.get("/events");
    const events = res.data.data ?? res.data;

    stats.value.totalEvents = events.length;
    stats.value.scheduled = events.filter((e) => e.status === "scheduled").length;
    stats.value.completed = events.filter((e) => e.status === "completed").length;
    stats.value.cancelled = events.filter((e) => e.status === "cancelled").length;
  } catch (err) {
    console.error(err);
    error.value = "Gagal mengambil data event.";
  } finally {
    loading.value = false;
  }
};

onMounted(fetchStats);
</script>

<template>
  <section>
    <h2 class="text-2xl font-semibold mb-3">Dashboard</h2>

    <div v-if="error" class="text-red-600 text-sm mb-3">{{ error }}</div>

    <div class="grid gap-4 md:grid-cols-4">
      <div class="bg-white border rounded-lg p-4 shadow-sm">
        <p class="text-sm text-gray-500">Total Event</p>
        <p class="mt-2 text-2xl font-semibold text-slate-800">
          {{ loading ? "..." : stats.totalEvents }}
        </p>
      </div>
      <div class="bg-white border rounded-lg p-4 shadow-sm">
        <p class="text-sm text-gray-500">Scheduled</p>
        <p class="mt-2 text-2xl font-semibold text-slate-800">
          {{ loading ? "..." : stats.scheduled }}
        </p>
      </div>
      <div class="bg-white border rounded-lg p-4 shadow-sm">
        <p class="text-sm text-gray-500">Completed</p>
        <p class="mt-2 text-2xl font-semibold text-slate-800">
          {{ loading ? "..." : stats.completed }}
        </p>
      </div>
      <div class="bg-white border rounded-lg p-4 shadow-sm">
        <p class="text-sm text-gray-500">Cancelled</p>
        <p class="mt-2 text-2xl font-semibold text-slate-800">
          {{ loading ? "..." : stats.cancelled }}
        </p>
      </div>
    </div>
  </section>
</template>
