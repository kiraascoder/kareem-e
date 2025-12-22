<script setup>
import { ref } from "vue";

const form = ref({
  name: "",
  email: "",
  phone: "",
  topic: "general",
  message: "",
});

const loading = ref(false);
const sent = ref(false);

async function submit() {
  loading.value = true;
  sent.value = false;

  // TODO: ganti ke API kamu (Laravel) mis:
  // await api.post("/contact", form.value)
  await new Promise((r) => setTimeout(r, 900));

  loading.value = false;
  sent.value = true;

  // reset
  form.value = {
    name: "",
    email: "",
    phone: "",
    topic: "general",
    message: "",
  };
  setTimeout(() => (sent.value = false), 3500);
}
</script>

<template>
  <section class="space-y-10">
    <!-- Header -->
    <header class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-10">
      <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
        Contact
      </p>
      <h1 class="mt-2 font-serif text-4xl text-[#1B1A17]">Let’s talk.</h1>

      <div class="mt-4 h-px w-24 bg-black/10"></div>

      <p
        class="mt-5 max-w-3xl text-sm leading-relaxed text-[#1B1A17]/75 sm:text-base"
      >
        Kirim pesan untuk konsultasi event, pertanyaan layanan, atau kebutuhan
        demo sistem. Kami balas secepatnya melalui email/telepon yang kamu isi.
      </p>

      <div class="mt-7 flex flex-wrap gap-2">
        <span
          class="rounded-full px-4 py-2 text-[12px] font-bold tracking-[0.18em] uppercase"
          style="background: #1b1a17; color: #e6d5b8"
        >
          Kareem Entertainment
        </span>
        <span
          class="rounded-full border border-black/10 bg-[#E6D5B8]/60 px-4 py-2 text-[12px] font-bold tracking-[0.18em] uppercase text-[#1B1A17]"
        >
          Booking & Pricing
        </span>
        <span
          class="rounded-full border border-black/10 bg-white/60 px-4 py-2 text-[12px] font-bold tracking-[0.18em] uppercase text-[#1B1A17]"
        >
          Support
        </span>
      </div>
    </header>

    <!-- Content -->
    <section class="grid gap-6 lg:grid-cols-2">
      <!-- Form -->
      <form
        @submit.prevent="submit"
        class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-8 space-y-5"
      >
        <div class="flex items-center justify-between gap-4">
          <div>
            <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
              Message
            </p>
            <h2 class="mt-1 font-serif text-2xl text-[#1B1A17]">Send a note</h2>
          </div>
          <span
            class="hidden sm:inline rounded-full px-3 py-1 text-[11px] font-bold tracking-[0.16em] uppercase"
            style="background: #f0a500; color: #1b1a17"
          >
            Response within 24–48h
          </span>
        </div>

        <div class="h-px w-full bg-black/10"></div>

        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Name</label
            >
            <input
              v-model="form.name"
              required
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              placeholder="Nama kamu"
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
          </div>

          <div>
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Phone</label
            >
            <input
              v-model="form.phone"
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              placeholder="08xx-xxxx-xxxx"
            />
          </div>

          <div>
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Topic</label
            >
            <select
              v-model="form.topic"
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
            >
              <option value="general">General</option>
              <option value="booking">Booking</option>
              <option value="pricing">Pricing</option>
              <option value="partnership">Partnership</option>
              <option value="support">Support</option>
            </select>
          </div>

          <div class="sm:col-span-2">
            <label class="text-xs tracking-[0.18em] uppercase text-[#1B1A17]/60"
              >Message</label
            >
            <textarea
              v-model="form.message"
              required
              rows="5"
              class="mt-2 w-full rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#F0A500]/50"
              placeholder="Tulis pesan kamu…"
            />
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-2">
          <button
            type="submit"
            :disabled="loading"
            class="rounded-full px-6 py-3 text-[12px] font-bold tracking-[0.18em] uppercase transition disabled:opacity-60"
            style="background: #1b1a17; color: #e6d5b8"
          >
            {{ loading ? "Sending…" : "Send" }}
          </button>

          <span
            v-if="sent"
            class="text-sm font-semibold"
            style="color: #e45826"
          >
            Sent ✓ (dummy)
          </span>

          <p class="text-xs text-[#1B1A17]/55">
            Dengan mengirim, kamu setuju dihubungi untuk keperluan follow-up.
          </p>
        </div>
      </form>

      <!-- Info / Card -->
      <aside class="space-y-6">
        <div
          class="rounded-3xl border border-black/10 bg-[#1B1A17] p-7 sm:p-8 text-[#E6D5B8]"
        >
          <p class="text-xs tracking-[0.22em] uppercase text-[#E6D5B8]/70">
            Contact details
          </p>
          <h2 class="mt-2 font-serif text-2xl">Kareem Entertainment</h2>

          <div class="mt-5 h-px w-24 bg-[#E6D5B8]/20"></div>

          <div class="mt-6 space-y-4 text-sm text-[#E6D5B8]/80">
            <div class="flex items-start justify-between gap-4">
              <span
                class="text-xs tracking-[0.18em] uppercase text-[#E6D5B8]/60"
                >Email</span
              >
              <span class="text-right font-semibold text-[#E6D5B8]"
                >kareem@example.com</span
              >
            </div>
            <div class="flex items-start justify-between gap-4">
              <span
                class="text-xs tracking-[0.18em] uppercase text-[#E6D5B8]/60"
                >Phone</span
              >
              <span class="text-right font-semibold text-[#E6D5B8]"
                >+62 8xx xxxx xxxx</span
              >
            </div>
            <div class="flex items-start justify-between gap-4">
              <span
                class="text-xs tracking-[0.18em] uppercase text-[#E6D5B8]/60"
                >Hours</span
              >
              <span class="text-right font-semibold text-[#E6D5B8]"
                >09:00 – 17:00</span
              >
            </div>

            <div class="h-px w-full bg-[#E6D5B8]/15"></div>

            <p class="text-xs text-[#E6D5B8]/65 leading-relaxed">
              * Silakan ganti detail kontak di atas sesuai data asli. Bagian ini
              dibuat bergaya minimal agar tetap “European editorial”.
            </p>
          </div>

          <div class="mt-6 flex flex-wrap gap-2">
            <span
              class="rounded-full px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
              style="background: #f0a500; color: #1b1a17"
            >
              Booking
            </span>
            <span
              class="rounded-full px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
              style="background: #e45826; color: #fff"
            >
              Pricing
            </span>
            <span
              class="rounded-full border border-[#E6D5B8]/20 bg-white/5 px-3 py-1 text-xs font-bold tracking-[0.16em] uppercase"
            >
              Support
            </span>
          </div>
        </div>

        <!-- Map placeholder (editorial) -->
        <div class="rounded-3xl border border-black/10 bg-white/70 p-7 sm:p-8">
          <p class="text-xs tracking-[0.22em] uppercase text-[#1B1A17]/60">
            Location
          </p>
          <h3 class="mt-2 font-serif text-2xl text-[#1B1A17]">Our studio</h3>

          <div class="mt-5 h-px w-24 bg-black/10"></div>

          <div
            class="mt-6 rounded-3xl border border-black/10 bg-[#E6D5B8]/60 p-6"
          >
            <p class="text-sm text-[#1B1A17]/70 leading-relaxed">
              Tempatkan embed Google Maps di sini (opsional). Untuk tampilan
              elegan: gunakan frame tanpa border tebal, dan jaga spacing.
            </p>

            <!-- Dummy map block -->
            <div
              class="mt-5 aspect-[16/9] w-full rounded-2xl bg-black/10"
            ></div>
          </div>
        </div>
      </aside>
    </section>
  </section>
</template>
