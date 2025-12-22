<script setup>
import { computed, ref } from "vue";
import { RouterLink, useRouter } from "vue-router";
import api from "../services/api";

const router = useRouter();

const form = ref({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
  agree: false,
});

const show = ref(false);
const show2 = ref(false);
const loading = ref(false);
const error = ref("");
const success = ref(false);

const validEmail = computed(() => /\S+@\S+\.\S+/.test(form.value.email));
const passwordsMatch = computed(
  () =>
    form.value.password &&
    form.value.password === form.value.password_confirmation
);

const canSubmit = computed(
  () =>
    form.value.name &&
    validEmail.value &&
    form.value.password &&
    form.value.password.length >= 6 &&
    passwordsMatch.value &&
    form.value.agree
);

async function submit() {
  error.value = "";
  success.value = false;

  if (!canSubmit.value) {
    error.value =
      "Pastikan nama, email valid, password minimal 6 karakter, konfirmasi sama, dan setuju syarat.";
    return;
  }

  loading.value = true;

  try {
    const res = await api.post("/auth/register", {
      name: form.value.name,
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation,
    });

    // kalau kamu mau langsung login setelah register:
    localStorage.setItem("token", res.data.token);

    success.value = true;

    // langsung ke dashboard (atau ke /login kalau kamu mau)
    router.push("/dashboard");
  } catch (e) {
    const msg =
      e?.response?.data?.message ||
      e?.response?.data?.errors?.email?.[0] ||
      e?.response?.data?.errors?.password?.[0] ||
      "Registrasi gagal. Coba lagi.";
    error.value = msg;
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <section class="space-y-10">
    <!-- Header -->
    <header class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-10">
      <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
        Account
      </p>
      <h1 class="mt-2 font-serif text-4xl text-[#1B1A17]">Register</h1>

      <div class="mt-4 h-px w-24 bg-black/10"></div>

      <p
        class="mt-5 max-w-3xl text-sm leading-relaxed text-[#1B1A17]/75 sm:text-base"
      >
        Buat akun untuk akses internal
        <span class="font-semibold">Kareem Pricing</span> milik
        <span class="font-semibold">Kareem Entertainment (Kareem E)</span>.
      </p>
    </header>

    <section class="grid gap-6 lg:grid-cols-2">
      <!-- Form -->
      <form
        @submit.prevent="submit"
        class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-8 space-y-6"
      >
        <div class="flex items-center justify-between gap-4">
          <div>
            <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
              Create account
            </p>
            <h2 class="mt-1 font-serif text-2xl text-[#1B1A17]">Sign up</h2>
          </div>

          <span
            class="hidden sm:inline rounded-full px-3 py-1 text-[11px] font-bold tracking-[0.16em] uppercase"
            style="background: #e45826; color: #fff"
          >
            Staff only
          </span>
        </div>

        <div class="h-px w-full bg-black/10"></div>

        <div class="space-y-4">
          <div>
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Full name</label
            >
            <input
              v-model="form.name"
              required
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              placeholder="Nama lengkap"
            />
          </div>

          <div>
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Email</label
            >
            <input
              v-model="form.email"
              type="email"
              required
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              placeholder="email@domain.com"
            />
            <p
              v-if="form.email && !validEmail"
              class="mt-2 text-xs font-semibold"
              style="color: #e45826"
            >
              Format email tidak valid.
            </p>
          </div>

          <div>
            <div class="flex items-center justify-between gap-3">
              <label
                class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
                >Password</label
              >
              <button
                type="button"
                class="text-xs tracking-[0.18em] uppercase font-bold hover:underline"
                style="color: #e45826"
                @click="show = !show"
              >
                {{ show ? "Hide" : "Show" }}
              </button>
            </div>
            <input
              v-model="form.password"
              :type="show ? 'text' : 'password'"
              required
              minlength="6"
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              placeholder="Minimal 6 karakter"
            />
          </div>

          <div>
            <div class="flex items-center justify-between gap-3">
              <label
                class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >
                Confirm password
              </label>
              <button
                type="button"
                class="text-xs tracking-[0.18em] uppercase font-bold hover:underline"
                style="color: #e45826"
                @click="show2 = !show2"
              >
                {{ show2 ? "Hide" : "Show" }}
              </button>
            </div>
            <input
              v-model="form.password_confirmation"
              :type="show2 ? 'text' : 'password'"
              required
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              placeholder="Ulangi password"
            />
            <p
              v-if="form.password_confirmation && !passwordsMatch"
              class="mt-2 text-xs font-semibold"
              style="color: #e45826"
            >
              Konfirmasi password tidak sama.
            </p>
          </div>

          <div class="flex items-start gap-3">
            <input
              v-model="form.agree"
              type="checkbox"
              class="mt-1 h-4 w-4 rounded border-black/20"
            />
            <p class="text-sm text-[#1B1A17]/75 leading-relaxed">
              Saya setuju akun ini digunakan untuk kebutuhan internal Kareem
              Entertainment dan data akan dipakai untuk pengelolaan event.
            </p>
          </div>

          <p v-if="error" class="text-sm font-semibold" style="color: #e45826">
            {{ error }}
          </p>
          <p
            v-if="success"
            class="text-sm font-semibold"
            style="color: #1b1a17"
          >
            Registrasi berhasil ✓ mengarahkan ke login…
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-2">
          <button
            type="submit"
            :disabled="loading || !canSubmit"
            class="rounded-full px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase transition disabled:opacity-60"
            style="background: #1b1a17; color: #e6d5b8"
          >
            {{ loading ? "Creating…" : "Create account" }}
          </button>

          <RouterLink
            to="/login"
            class="rounded-full border px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase hover:bg-white/60 transition"
            style="border-color: rgba(0, 0, 0, 0.12); color: #1b1a17"
          >
            Back to login
          </RouterLink>
        </div>

        <p class="text-xs text-[#1B1A17]/55 leading-relaxed">
          Catatan: ini tampilan frontend. Tinggal hubungkan ke endpoint Laravel
          untuk registrasi.
        </p>
      </form>

      <!-- Right panel -->
      <aside class="space-y-6">
        <div
          class="rounded-3xl border border-black/10 bg-[#1B1A17] p-7 sm:p-8 text-[#E6D5B8]"
        >
          <p class="text-xs tracking-[0.22em] uppercase text-[#E6D5B8]/70">
            Kareem Pricing
          </p>
          <h2 class="mt-2 font-serif text-2xl">Internal workspace</h2>

          <div class="mt-5 h-px w-24 bg-[#E6D5B8]/20"></div>

          <p class="mt-5 text-sm text-[#E6D5B8]/80 leading-relaxed">
            Setelah register, kamu bisa login untuk mengakses dashboard,
            manajemen event, dan fitur rekomendasi harga.
          </p>

          <div class="mt-6 flex flex-wrap gap-2">
            <span
              class="rounded-full px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
              style="background: #f0a500; color: #1b1a17"
              >Dashboard</span
            >
            <span
              class="rounded-full px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
              style="background: #e45826; color: #fff"
              >Events</span
            >
            <span
              class="rounded-full border border-[#E6D5B8]/20 bg-white/5 px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
            >
              ML
            </span>
          </div>
        </div>

        <div class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-8">
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            Already have an account?
          </p>
          <h3 class="mt-2 font-serif text-2xl text-[#1B1A17]">Sign in</h3>

          <div class="mt-5 h-px w-24 bg-black/10"></div>

          <p class="mt-5 text-sm text-[#1B1A17]/75 leading-relaxed">
            Jika akun sudah dibuat, silakan masuk melalui halaman login.
          </p>

          <RouterLink
            to="/login"
            class="mt-6 inline-flex items-center gap-2 rounded-full border border-[#1B1A17]/25 bg-transparent px-5 py-3 text-[12px] tracking-[0.16em] uppercase font-bold text-[#1B1A17] hover:bg-white/60 transition"
          >
            <span class="h-2 w-2 rounded-full bg-[#E45826]"></span>
            Login
          </RouterLink>
        </div>
      </aside>
    </section>
  </section>
</template>
