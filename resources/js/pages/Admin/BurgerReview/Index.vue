<template>
  <AdminLayout>
    <template #header>
      <div>
        <h2 class="text-xl lg:text-2xl font-bold">Burger Ülevaatus</h2>
        <p class="text-sm text-gray-400 mt-1">Klientide saadetud burgerid</p>
      </div>
    </template>

    <div class="grid grid-cols-3 gap-4 mb-8">
      <div class="bg-[#111111] border border-gray-800 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-yellow-400">{{ pendingBurgers.length }}</p>
        <p class="text-xs text-gray-400 mt-1">Ootab kinnitust</p>
      </div>
      <div class="bg-[#111111] border border-gray-800 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-green-400">{{ approvedBurgers.length }}</p>
        <p class="text-xs text-gray-400 mt-1">Kinnitatud</p>
      </div>
      <div class="bg-[#111111] border border-gray-800 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-red-400">{{ rejectedBurgers.length }}</p>
        <p class="text-xs text-gray-400 mt-1">Tagasi lükatud</p>
      </div>
    </div>

    <div class="flex gap-1 mb-6 bg-[#111111] border border-gray-800 rounded-xl p-1 w-fit">
      <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
        :class="['px-4 py-2 rounded-lg text-sm font-semibold transition', activeTab === tab.key ? 'bg-[#D2691E] text-white' : 'text-gray-400 hover:text-white']">
        {{ tab.label }}
        <span v-if="tab.key === 'pending' && pendingBurgers.length > 0" class="ml-1.5 bg-white/20 text-white text-xs px-1.5 py-0.5 rounded-full">
          {{ pendingBurgers.length }}
        </span>
      </button>
    </div>

    <!-- Pending -->
    <div v-if="activeTab === 'pending'">
      <div v-if="pendingBurgers.length === 0" class="bg-[#111111] border border-gray-800 rounded-xl p-12 text-center">
        <p class="text-4xl mb-3">🍔</p>
        <p class="text-gray-400">Hetkel pole ühtegi burgeri ülevaatamiseks</p>
      </div>
      <div v-else class="space-y-4">
        <div v-for="burger in pendingBurgers" :key="burger.id"
          class="bg-[#111111] border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition">
          <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1 flex-wrap">
                <span class="text-white font-bold text-lg">{{ burger.name }}</span>
                <span class="bg-yellow-500/10 text-yellow-400 text-xs font-semibold px-2 py-0.5 rounded-full border border-yellow-500/20">Ootab kinnitust</span>
              </div>
              <p class="text-gray-400 text-sm">{{ burger.user.name }} &bull; {{ burger.user.email }}</p>
              <p class="text-gray-600 text-xs mb-3">{{ burger.created_at }}</p>
              <div class="flex flex-wrap gap-1.5">
                <span v-for="ing in burger.ingredients" :key="ing.name + ing.category"
                  class="bg-[#1a1a1a] border border-gray-700 text-gray-300 text-xs px-2 py-1 rounded-md">
                  {{ ing.name }}<span v-if="ing.quantity > 1" class="text-gray-500 ml-0.5">×{{ ing.quantity }}</span>
                </span>
              </div>
            </div>
            <div class="flex flex-col items-end gap-3 flex-shrink-0">
              <span class="text-[#D2691E] font-bold text-xl">{{ burger.total_price.toFixed(2) }}€</span>
              <div class="flex gap-2">
                <button @click="openReject(burger.id)"
                  class="px-4 py-2 bg-red-600/10 hover:bg-red-600/20 text-red-400 border border-red-600/20 rounded-lg text-sm font-semibold transition">
                  Lükka tagasi
                </button>
                <button @click="approve(burger.id)"
                  class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold transition">
                  ✓ Kinnita
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Approved -->
    <div v-if="activeTab === 'approved'">
      <div v-if="approvedBurgers.length === 0" class="bg-[#111111] border border-gray-800 rounded-xl p-12 text-center">
        <p class="text-gray-400">Ühtegi kinnitatud burgerit pole</p>
      </div>
      <div v-else class="space-y-3">
        <div v-for="burger in approvedBurgers" :key="burger.id"
          class="bg-[#111111] border border-gray-800 rounded-xl p-4 flex items-center justify-between gap-4 flex-wrap">
          <div>
            <div class="flex items-center gap-2 mb-0.5">
              <span class="text-white font-semibold">{{ burger.name }}</span>
              <span class="bg-green-500/10 text-green-400 text-xs px-2 py-0.5 rounded-full border border-green-500/20">Kinnitatud</span>
            </div>
            <p class="text-gray-500 text-xs">{{ burger.user.name }} &bull; {{ burger.created_at }}</p>
          </div>
          <span class="text-[#D2691E] font-bold">{{ burger.total_price.toFixed(2) }}€</span>
        </div>
      </div>
    </div>

    <!-- Rejected -->
    <div v-if="activeTab === 'rejected'">
      <div v-if="rejectedBurgers.length === 0" class="bg-[#111111] border border-gray-800 rounded-xl p-12 text-center">
        <p class="text-gray-400">Ühtegi tagasilükatud burgerit pole</p>
      </div>
      <div v-else class="space-y-3">
        <div v-for="burger in rejectedBurgers" :key="burger.id"
          class="bg-[#111111] border border-gray-800 rounded-xl p-4">
          <div class="flex items-center justify-between gap-4 flex-wrap mb-2">
            <div>
              <div class="flex items-center gap-2 mb-0.5">
                <span class="text-white font-semibold">{{ burger.name }}</span>
                <span class="bg-red-500/10 text-red-400 text-xs px-2 py-0.5 rounded-full border border-red-500/20">Tagasi lükatud</span>
              </div>
              <p class="text-gray-500 text-xs">{{ burger.user.name }} &bull; {{ burger.created_at }}</p>
            </div>
            <span class="text-[#D2691E] font-bold">{{ burger.total_price.toFixed(2) }}€</span>
          </div>
          <p v-if="burger.admin_note" class="text-sm text-gray-400 bg-[#1a1a1a] rounded-lg px-3 py-2 border border-gray-800">
            💬 {{ burger.admin_note }}
          </p>
        </div>
      </div>
    </div>

    <!-- Reject modal -->
    <div v-if="rejectModal.open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/75">
      <div class="bg-[#111111] border border-gray-700 rounded-xl p-6 w-full max-w-md mx-4 shadow-2xl">
        <h3 class="text-white font-bold text-lg mb-1">Tagasilükkamise põhjus</h3>
        <p class="text-gray-400 text-sm mb-4">Kasutaja näeb seda põhjust</p>
        <textarea v-model="rejectModal.note"
          placeholder="nt. Koostisosad ei sobi meie menüüga..."
          class="w-full bg-[#0a0a0a] border border-gray-700 rounded-lg px-4 py-3 text-white text-sm resize-none focus:outline-none focus:border-[#D2691E] transition"
          rows="3"></textarea>
        <div class="flex gap-3 mt-4">
          <button @click="rejectModal.open = false"
            class="flex-1 px-4 py-2.5 bg-gray-800 hover:bg-gray-700 text-white rounded-lg text-sm font-semibold transition">
            Tühista
          </button>
          <button @click="submitReject" :disabled="!rejectModal.note.trim()"
            class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 disabled:opacity-40 text-white rounded-lg text-sm font-semibold transition">
            Lükka tagasi
          </button>
        </div>
      </div>
    </div>

  </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface BurgerItem {
  id: number; name: string; total_price: number; created_at: string;
  status: string; admin_note?: string;
  user: { name: string; email: string };
  ingredients: { name: string; category: string; quantity: number; price: number }[];
}

interface Props {
  pendingBurgers: BurgerItem[];
  approvedBurgers: BurgerItem[];
  rejectedBurgers: BurgerItem[];
}

const props = defineProps<Props>();
const activeTab = ref<'pending' | 'approved' | 'rejected'>('pending');
const tabs = [
  { key: 'pending',  label: 'Ootavad' },
  { key: 'approved', label: 'Kinnitatud' },
  { key: 'rejected', label: 'Tagasi lükatud' },
];

const rejectModal = ref({ open: false, burgerId: null as number | null, note: '' });
const openReject = (id: number) => { rejectModal.value = { open: true, burgerId: id, note: '' }; };
const approve = (id: number) => { router.post(`/admin/burger-review/${id}/approve`, {}, { preserveScroll: true }); };
const submitReject = () => {
  if (!rejectModal.value.burgerId || !rejectModal.value.note.trim()) return;
  router.post(`/admin/burger-review/${rejectModal.value.burgerId}/reject`,
    { admin_note: rejectModal.value.note },
    { preserveScroll: true, onSuccess: () => { rejectModal.value = { open: false, burgerId: null, note: '' }; } }
  );
};
</script>