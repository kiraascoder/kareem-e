import { createRouter, createWebHistory } from "vue-router";
import EventView from "../views/EventView.vue";
import DashboardView from "../views/DashboardView.vue";

const routes = [
  { path: "/", name: "dashboard", component: DashboardView },
  { path: "/events", name: "events", component: EventView },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
