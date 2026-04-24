<template>
  <AdminLayout>
    <div class="max-w-3xl mx-auto px-4 py-8">
      <h1 class="text-2xl font-black text-white mb-1">Kasutajahaldus</h1>
      <p class="text-gray-500 text-sm mb-6">Kulleri õiguste haldamine</p>

      <div class="space-y-2">
        <div
          v-for="user in props.users"
          :key="user.id"
          class="flex items-center justify-between bg-[#111] border border-white/8 rounded-2xl px-5 py-4"
        >
          <div>
            <p class="font-semibold text-white">{{ user.name }}</p>
            <p class="text-xs text-gray-500">{{ user.email }}</p>
            <div class="flex gap-2 mt-1">
              <span v-if="user.is_admin" class="text-[10px] px-2 py-0.5 rounded-full bg-orange-900/40 text-orange-400 font-bold">ADMIN</span>
              <span v-if="user.is_courier" class="text-[10px] px-2 py-0.5 rounded-full bg-cyan-900/40 text-cyan-400 font-bold">KULLER</span>
            </div>
          </div>

          <button
            v-if="!user.is_admin"
            @click="toggleCourier(user.id)"
            :class="user.is_courier
              ? 'bg-red-900/30 border-red-800/50 text-red-400 hover:bg-red-900/60'
              : 'bg-cyan-900/20 border-cyan-800/40 text-cyan-400 hover:bg-cyan-900/40'"
            class="text-xs px-4 py-2 rounded-xl border font-semibold transition"
          >
            {{ user.is_courier ? 'Eemalda kuller' : 'Tee kulleriks' }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';

interface User {
  id: number;
  name: string;
  email: string;
  is_admin: boolean;
  is_courier: boolean;
}

const props = defineProps<{ users: User[] }>();

const toggleCourier = (userId: number) => {
  router.post(`/admin/users/${userId}/toggle-courier`, {}, { preserveScroll: true });
};
</script>
