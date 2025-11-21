import { createRouter, createWebHistory } from "vue-router";
import DashboardView from "../views/DashboardView.vue";
import EventListView from "../views/EventListView.vue";
// PricingView, EventFormView nanti kita tambahkan

const routes = [
  {
    path: "/",
    name: "dashboard",
    component: DashboardView,
  },
  {
    path: "/events",
    name: "events.index",
    component: EventListView,
  },
  // nanti:
  // { path: "/events/new", name: "events.create", component: EventFormView },
  // { path: "/events/:id/pricing", name: "events.pricing", component: PricingView, props: true },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
