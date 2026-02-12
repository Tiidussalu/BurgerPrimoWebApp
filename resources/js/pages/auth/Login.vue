<template>
  <div class="min-h-screen bg-[#0B0B0B] flex items-center justify-center px-4 py-12">
    <Head title="Logi sisse" />

    <!-- Background Decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#D2691E]/5 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-[#D2691E]/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Login Card -->
    <div class="relative w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <Link href="/" class="inline-flex items-center gap-3 group">
          <div class="w-16 h-16 bg-gradient-to-br from-[#D2691E] to-[#B8571A] rounded-2xl flex items-center justify-center shadow-lg shadow-[#D2691E]/20 group-hover:shadow-[#D2691E]/40 transition-all duration-300 group-hover:scale-110">
            <span class="text-3xl font-bold text-white">BP</span>
          </div>
          <span class="text-3xl font-bold bg-gradient-to-r from-[#D2691E] to-[#E07A2E] bg-clip-text text-transparent">
            Burger Primo
          </span>
        </Link>
      </div>

      <!-- Card -->
      <div class="bg-[#121212] border border-[#0B0B0B] rounded-2xl shadow-2xl p-8">
        <!-- Header -->
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold text-white mb-2">Tere tulemast tagasi!</h1>
          <p class="text-gray-400">Sisesta oma andmed sisselogimiseks</p>
        </div>

        <!-- Status Message -->
        <div v-if="status" class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-lg text-green-400 text-sm text-center">
          {{ status }}
        </div>

        <!-- Login Form -->
        <form @submit.prevent="submit">
          <!-- Email -->
          <div class="mb-6">
            <label for="email" class="block text-sm font-semibold text-gray-300 mb-2">
              E-posti aadress
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              autofocus
              autocomplete="email"
              placeholder="sinu@email.ee"
              class="w-full bg-[#0B0B0B] border border-[#1a1a1a] rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-[#D2691E] focus:ring-2 focus:ring-[#D2691E]/20 transition-all"
              :class="{ 'border-red-500': form.errors.email }"
            />
            <p v-if="form.errors.email" class="mt-2 text-sm text-red-400">
              {{ form.errors.email }}
            </p>
          </div>

          <!-- Password -->
          <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
              <label for="password" class="block text-sm font-semibold text-gray-300">
                Parool
              </label>
              <Link
                v-if="canResetPassword"
                href="/forgot-password"
                class="text-sm text-[#D2691E] hover:text-[#E07A2E] transition-colors"
              >
                Unustasid parooli?
              </Link>
            </div>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              autocomplete="current-password"
              placeholder="••••••••"
              class="w-full bg-[#0B0B0B] border border-[#1a1a1a] rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-[#D2691E] focus:ring-2 focus:ring-[#D2691E]/20 transition-all"
              :class="{ 'border-red-500': form.errors.password }"
            />
            <p v-if="form.errors.password" class="mt-2 text-sm text-red-400">
              {{ form.errors.password }}
            </p>
          </div>

          <!-- Remember Me -->
          <div class="mb-6">
            <label class="flex items-center cursor-pointer group">
              <input
                v-model="form.remember"
                type="checkbox"
                class="w-5 h-5 rounded border-gray-600 bg-[#0B0B0B] text-[#D2691E] focus:ring-[#D2691E] focus:ring-offset-[#121212] cursor-pointer"
              />
              <span class="ml-3 text-sm text-gray-300 group-hover:text-white transition-colors">
                Jäta mind meelde
              </span>
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full bg-gradient-to-r from-[#D2691E] to-[#B8571A] hover:from-[#E07A2E] hover:to-[#D2691E] disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-[#D2691E]/50 flex items-center justify-center gap-2"
          >
            <svg
              v-if="form.processing"
              class="animate-spin h-5 w-5"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ form.processing ? 'Sisselogimine...' : 'Logi sisse' }}</span>
          </button>
        </form>

        <!-- Register Link -->
        <div v-if="canRegister" class="mt-6 text-center">
          <p class="text-sm text-gray-400">
            Pole veel kontot?
            <Link
              href="/register"
              class="text-[#D2691E] hover:text-[#E07A2E] font-semibold transition-colors"
            >
              Registreeru
            </Link>
          </p>
        </div>
      </div>

      <!-- Back to Home -->
      <div class="mt-6 text-center">
        <Link
          href="/"
          class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Tagasi avalehele
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

interface Props {
  status?: string;
  canResetPassword: boolean;
  canRegister: boolean;
}

defineProps<Props>();

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post('/login', {
    onFinish: () => {
      form.reset('password');
    },
  });
};
</script>