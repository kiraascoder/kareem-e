import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import AboutView from "../views/AboutView.vue";
import ContactView from "../views/ContactView.vue";
import BookingView from "../views/BookingView.vue";
import DashboardView from "../views/DashboardView.vue";
import EventListView from "../views/EventListView.vue";
import LoginView from "@/views/LoginView.vue";
import RegisterView from "@/views/RegisterView.vue";

const routes = [
  { path: "/", name: "home", component: HomeView },
  { path: "/about", name: "about", component: AboutView },
  { path: "/contact", name: "contact", component: ContactView },
  { path: "/booking", name: "booking", component: BookingView },
  { path: "/login", name: "login", component: LoginView },
  { path: "/register", name: "register", component: RegisterView },

  // internal
  { path: "/dashboard", name: "dashboard", component: DashboardView },
  { path: "/events", name: "events.index", component: EventListView },

  // Auth



  // catch all
  { path: "/:pathMatch(.*)*", name: "not-found", component: HomeView },

  // Errors
  {}
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
