<template>
  <div class="min-h-screen bg-[#0B0B0B] text-white">
    <Navbar />

    <main class="max-w-7xl mx-auto px-6 py-12">
      <div class="mb-12 flex items-center justify-between">
        <div>
          <h1 class="text-4xl font-bold mb-2">Minu tellimused</h1>
          <p class="text-gray-400">Vaata kõiki oma tellimusi</p>
        </div>
        <button
          v-if="selectedOrders.length > 0"
          @click="deleteSelectedOrders"
          class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-semibold transition"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
          Kustuta valitud ({{ selectedOrders.length }})
        </button>
      </div>

      <!-- Empty State -->
      <div v-if="orders.length === 0" class="text-center py-24">
        <div class="text-7xl mb-6">📦</div>
        <h3 class="text-2xl font-bold mb-3">Tellimusi pole veel</h3>
        <p class="text-gray-400 mb-8">Telli oma esimene burger!</p>
        <Link
          href="/menu"
          class="inline-block px-8 py-4 rounded-xl font-semibold transition hover:opacity-90 text-white uppercase tracking-wide"
          style="background-color: #D2691E"
        >
          Avasta menüüd
        </Link>
      </div>

      <!-- Orders List -->
      <div v-else class="space-y-4">
        <div
          v-for="order in orders"
          :key="order.id"
          class="bg-[#121212] rounded-2xl overflow-hidden border border-[#1a1a1a] transition-all duration-200"
          :class="{ 'ring-2 ring-[#D2691E]': selectedOrders.includes(order.id) }"
        >
          <!-- Order Header -->
          <div class="bg-[#0d0d0d] px-6 py-4 flex items-center justify-between border-b border-[#1a1a1a]">
            <div class="flex items-center gap-4">
              <label
                v-if="order.status === 'completed'"
                class="flex items-center cursor-pointer"
              >
                <input
                  type="checkbox"
                  :checked="selectedOrders.includes(order.id)"
                  @change="toggleOrderSelection(order.id)"
                  class="w-5 h-5 rounded border-gray-600 bg-[#121212] text-[#D2691E] focus:ring-[#D2691E] cursor-pointer"
                />
              </label>
              <div>
                <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Tellimuse number</p>
                <p class="font-mono text-lg font-bold text-[#D2691E]">{{ order.order_number }}</p>
              </div>
            </div>
            <div class="flex items-center gap-6">
              <div class="text-right">
                <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Aeg</p>
                <p class="text-sm font-medium text-gray-300">{{ formatDate(order.created_at) }}</p>
              </div>
              <span :class="getStatusClass(order.status)">
                {{ getStatusLabel(order.status) }}
              </span>
            </div>
          </div>

          <!-- Order Body -->
          <div class="p-6 relative">
            <!-- Dismiss button for rejected orders -->
            <button
              v-if="order.status === 'rejected'"
              @click="dismissOrder(order.id)"
              class="absolute top-4 right-4 w-8 h-8 rounded-full bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white flex items-center justify-center transition"
              title="Sulge teade"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>

            <!-- Rejected notice -->
            <div v-if="order.status === 'rejected'" class="mb-4 bg-red-900/20 border border-red-800 rounded-xl px-4 py-3">
              <p class="text-sm text-red-400 font-medium">Tellimus lükati tagasi restorani poolt.</p>
            </div>

            <div class="flex items-start justify-between mb-5">
              <!-- Items Summary -->
              <div class="flex-1">
                <p class="text-xs text-gray-500 uppercase tracking-widest mb-3">Tooted</p>
                <div class="space-y-2">
                  <div
                    v-for="item in order.items"
                    :key="item.id"
                    class="flex items-center justify-between bg-[#0d0d0d] rounded-xl px-4 py-3"
                  >
                    <div>
                      <p class="font-semibold text-sm">{{ item.burger_name }}</p>
                      <p class="text-xs text-gray-500 mt-0.5">{{ item.quantity }}x kogus</p>
                    </div>
                    <p class="font-bold text-[#D2691E] text-sm">{{ Number(item.price * item.quantity).toFixed(2) }}€</p>
                  </div>
                </div>
              </div>

              <!-- Total -->
              <div class="ml-8 text-right">
                <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Kokku</p>
                <p class="text-3xl font-bold text-[#D2691E]">{{ Number(order.total_amount).toFixed(2) }}€</p>
              </div>
            </div>

            <!-- Customer Notes -->
            <div v-if="order.customer_notes" class="mb-4 bg-[#0d0d0d] rounded-xl px-4 py-3">
              <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Sinu märkused</p>
              <p class="text-sm text-gray-300">{{ order.customer_notes }}</p>
            </div>

            <!-- Admin Notes -->
            <div v-if="order.admin_notes" class="mb-4 bg-blue-900/10 border border-blue-800/50 rounded-xl px-4 py-3">
              <p class="text-xs text-blue-400 uppercase tracking-widest mb-1">Restorani märkused</p>
              <p class="text-sm text-gray-300">{{ order.admin_notes }}</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 mt-5">
              <Link
                :href="`/orders/${order.id}`"
                class="flex-1 bg-[#1a1a1a] hover:bg-[#222] text-white px-4 py-2.5 rounded-xl font-semibold transition text-center text-sm border border-[#2a2a2a] hover:border-[#D2691E]/30"
              >
                Vaata detaile
              </Link>
              <button
                v-if="order.status === 'pending' || order.status === 'confirmed'"
                @click="cancelOrder(order.id)"
                class="flex-1 bg-red-600/10 hover:bg-red-600 text-red-400 hover:text-white px-4 py-2.5 rounded-xl font-semibold transition text-sm border border-red-800/50 hover:border-red-600"
              >
                Tühista tellimus
              </button>
              <Link
                v-if="order.status === 'completed'"
                href="/menu"
                class="flex-1 text-white px-4 py-2.5 rounded-xl font-semibold transition text-center text-sm hover:opacity-90"
                style="background-color: #D2691E"
              >
                Telli uuesti
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- New Order Button -->
      <div v-if="orders.length > 0" class="mt-10 text-center">
        <Link
          href="/menu"
          class="inline-block px-10 py-4 rounded-xl font-semibold transition hover:opacity-90 text-white uppercase tracking-wide"
          style="background-color: #D2691E"
        >
          Telli veel burgereid
        </Link>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import Navbar from '@/components/Navbar.vue';

interface OrderItem {
  id: number;
  burger_name: string;
  price: number;
  quantity: number;
}

interface Order {
  id: number;
  order_number: string;
  total_amount: number;
  status: string;
  created_at: string;
  customer_notes: string | null;
  admin_notes: string | null;
  items: OrderItem[];
}

interface Props {
  orders: Order[];
}

const props = defineProps<Props>();
const selectedOrders = ref<number[]>([]);

// Auto-refresh every 15 seconds if any order is active
const hasActiveOrders = computed(() =>
  props.orders.some(o => ['pending', 'confirmed', 'preparing', 'ready'].includes(o.status))
);

let refreshInterval: ReturnType<typeof setInterval> | null = null;

onMounted(() => {
  if (hasActiveOrders.value) {
    refreshInterval = setInterval(() => {
      router.reload({ only: ['orders'] });
    }, 15000);
  }
});

onUnmounted(() => {
  if (refreshInterval) clearInterval(refreshInterval);
});

const toggleOrderSelection = (orderId: number) => {
  const index = selectedOrders.value.indexOf(orderId);
  if (index > -1) {
    selectedOrders.value.splice(index, 1);
  } else {
    selectedOrders.value.push(orderId);
  }
};

const deleteSelectedOrders = () => {
  if (selectedOrders.value.length === 0) return;
  const count = selectedOrders.value.length;
  if (confirm(`Kas oled kindel, et soovid kustutada ${count} tellimust?`)) {
    router.post('/orders/bulk-delete' as any, { order_ids: selectedOrders.value }, {
      preserveScroll: true,
      onSuccess: () => { selectedOrders.value = []; }
    });
  }
};

const cancelOrder = (orderId: number) => {
  if (confirm('Kas oled kindel, et soovid tellimuse tühistada?')) {
    router.post(`/orders/${orderId}/cancel` as any, {}, { preserveScroll: true });
  }
};

const dismissOrder = (orderId: number) => {
  if (confirm('Kas soovid selle tellimuse nimekirjast eemaldada?')) {
    router.delete(`/orders/${orderId}` as any, { preserveScroll: true });
  }
};

const getStatusClass = (status: string): string => {
  const classes: Record<string, string> = {
    pending:   'px-3 py-1 rounded-full bg-yellow-900/30 text-yellow-400 text-xs font-semibold',
    confirmed: 'px-3 py-1 rounded-full bg-blue-900/30 text-blue-400 text-xs font-semibold',
    preparing: 'px-3 py-1 rounded-full bg-purple-900/30 text-purple-400 text-xs font-semibold',
    ready:     'px-3 py-1 rounded-full bg-[#D2691E]/20 text-[#D2691E] text-xs font-semibold',
    completed: 'px-3 py-1 rounded-full bg-green-900/30 text-green-400 text-xs font-semibold',
    cancelled: 'px-3 py-1 rounded-full bg-gray-800 text-gray-400 text-xs font-semibold',
    rejected:  'px-3 py-1 rounded-full bg-red-900/30 text-red-400 text-xs font-semibold',
  };
  return classes[status] || classes.pending;
};

const getStatusLabel = (status: string): string => {
  const labels: Record<string, string> = {
    pending:   'Ootel',
    confirmed: 'Kinnitatud',
    preparing: 'Valmistamisel',
    ready:     'Valmis peale tulema',
    completed: 'Täidetud',
    cancelled: 'Tühistatud',
    rejected:  'Tagasi lükatud',
  };
  return labels[status] || status;
};

const formatDate = (date: string): string => {
  return new Date(date).toLocaleString('et-EE', {
    year: 'numeric', month: '2-digit', day: '2-digit',
    hour: '2-digit', minute: '2-digit',
  });
};
</script>