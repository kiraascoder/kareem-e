import axios from "axios";
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || "http://localhost:8000/api",
});

const app = createApp(App);

app.use(router);
app.mount("#app");

export default api;

