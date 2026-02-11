<template>
  <div class="min-h-screen bg-[#0B0B0B] text-white">
    <Navbar />

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-12">
      <!-- Title -->
      <div class="mb-12">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-4xl font-bold mb-2">Minu tellimused</h2>
            <p class="text-gray-400">Vaata kõiki oma tellimusi</p>
          </div>
          
          <!-- Delete Button (shown when orders are selected) -->
          <button
            v-if="selectedOrders.length > 0"
            @click="deleteSelectedOrders"
            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Kustuta valitud ({{ selectedOrders.length }})
          </button>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="orders.length === 0" class="text-center py-16">
        <div class="text-6xl mb-4">📦</div>
        <h3 class="text-2xl font-bold mb-2">Tellimusi pole veel</h3>
        <p class="text-gray-400 mb-6">Telli oma esimene burger!</p>
        <Link
          href="/burger-builder"
          class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded font-semibold transition"
        >
          Ehita burger
        </Link>
      </div>

      <!-- Orders List -->
      <div v-else class="space-y-6">
        <div
          v-for="order in orders"
          :key="order.id"
          class="bg-gray-900 rounded-lg border border-gray-800 overflow-hidden"
          :class="{ 'ring-2 ring-orange-500': selectedOrders.includes(order.id) }"
        >
          <!-- Order Header -->
          <div class="bg-gray-800 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
              <!-- Checkbox for completed orders -->
              <label
                v-if="order.status === 'completed'"
                class="flex items-center cursor-pointer"
              >
                <input
                  type="checkbox"
                  :checked="selectedOrders.includes(order.id)"
                  @change="toggleOrderSelection(order.id)"
                  class="w-5 h-5 rounded border-gray-600 bg-gray-700 text-orange-500 focus:ring-orange-500 focus:ring-offset-gray-900 cursor-pointer"
                />
              </label>
              
              <div>
                <p class="text-sm text-gray-400">Tellimuse number</p>
                <p class="font-mono text-lg font-bold text-orange-500">{{ order.order_number }}</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-sm text-gray-400">Aeg</p>
              <p class="font-semibold">{{ formatDate(order.created_at) }}</p>
            </div>
          </div>

          <!-- Order Body -->
          <div class="p-6 relative">
            <!-- Close button for rejected orders -->
            <button
              v-if="order.status === 'rejected'"
              @click="dismissOrder(order.id)"
              class="absolute top-4 right-4 w-8 h-8 rounded-full bg-red-600 hover:bg-red-700 flex items-center justify-center transition"
              title="Sulge teade"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>

            <!-- Status -->
            <div class="mb-4 flex items-center justify-between">
              <span :class="getStatusClass(order.status)">
                {{ getStatusLabel(order.status) }}
              </span>
              <span class="text-2xl font-bold text-orange-500">
                {{ Number(order.total_amount).toFixed(2) }}€
              </span>
            </div>

            <!-- Items Summary -->
            <div class="mb-4">
              <p class="text-sm text-gray-400 mb-2">Burgerid:</p>
              <div class="space-y-2">
                <div
                  v-for="item in order.items"
                  :key="item.id"
                  class="flex items-center justify-between bg-gray-800 rounded px-4 py-2"
                >
                  <div>
                    <p class="font-semibold">{{ item.burger_name }}</p>
                    <p class="text-sm text-gray-400">Kogus: {{ item.quantity }}x</p>
                  </div>
                  <p class="font-bold text-orange-500">{{ Number(item.price * item.quantity).toFixed(2) }}€</p>
                </div>
              </div>
            </div>

            <!-- Customer Notes -->
            <div v-if="order.customer_notes" class="mb-4 bg-gray-800 rounded px-4 py-3">
              <p class="text-sm text-gray-400 mb-1">Sinu märkused:</p>
              <p class="text-sm">{{ order.customer_notes }}</p>
            </div>

            <!-- Admin Notes -->
            <div v-if="order.admin_notes" class="mb-4 bg-blue-900/20 border border-blue-700 rounded px-4 py-3">
              <p class="text-sm text-blue-400 mb-1">Restorani märkused:</p>
              <p class="text-sm">{{ order.admin_notes }}</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
              <Link
                :href="`/orders/${order.id}`"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold transition text-center"
              >
                Vaata detaile
              </Link>
              <button
                v-if="order.status === 'pending' || order.status === 'confirmed'"
                @click="cancelOrder(order.id)"
                class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded font-semibold transition"
              >
                Tühista tellimus
              </button>
              <Link
                v-if="order.status === 'completed'"
                href="/burger-builder"
                class="flex-1 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded font-semibold transition text-center"
              >
                Telli uuesti
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- New Order Button -->
      <div v-if="orders.length > 0" class="mt-8 text-center">
        <Link
          href="/burger-builder"
          class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded font-semibold transition"
        >
          Telli veel burgereid
        </Link>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
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

const hasCompletedOrders = computed(() => {
  return props.orders.some(order => order.status === 'completed');
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
    router.post('/orders/bulk-delete' as any, {
      order_ids: selectedOrders.value
    }, {
      preserveScroll: true,
      onSuccess: () => {
        selectedOrders.value = [];
      }
    });
  }
};

const cancelOrder = (orderId: number) => {
  if (confirm('Kas oled kindel, et soovid tellimuse tühistada?')) {
    router.post(`/orders/${orderId}/cancel` as any, {}, {
      preserveScroll: true,
    });
  }
};

const dismissOrder = (orderId: number) => {
  if (confirm('Kas soovid selle tellimuse nimekirjast eemaldada?')) {
    router.delete(`/orders/${orderId}` as any, {
      preserveScroll: true,
    });
  }
};

const getStatusClass = (status: string): string => {
  const classes: Record<string, string> = {
    pending: 'px-4 py-2 rounded-full bg-yellow-900/30 text-yellow-400 text-sm font-semibold inline-block',
    confirmed: 'px-4 py-2 rounded-full bg-blue-900/30 text-blue-400 text-sm font-semibold inline-block',
    preparing: 'px-4 py-2 rounded-full bg-purple-900/30 text-purple-400 text-sm font-semibold inline-block',
    ready: 'px-4 py-2 rounded-full bg-orange-900/30 text-orange-400 text-sm font-semibold inline-block',
    completed: 'px-4 py-2 rounded-full bg-green-900/30 text-green-400 text-sm font-semibold inline-block',
    cancelled: 'px-4 py-2 rounded-full bg-gray-900/30 text-gray-400 text-sm font-semibold inline-block',
    rejected: 'px-4 py-2 rounded-full bg-red-900/30 text-red-400 text-sm font-semibold inline-block',
  };
  return classes[status] || classes.pending;
};

const getStatusLabel = (status: string): string => {
  const labels: Record<string, string> = {
    pending: 'Ootel',
    confirmed: 'Kinnitatud',
    preparing: 'Valmistamisel',
    ready: 'Valmis peale tulema',
    completed: 'Täidetud',
    cancelled: 'Tühistatud',
    rejected: 'Tagasi lükatud',
  };
  return labels[status] || status;
};

const formatDate = (date: string): string => {
  return new Date(date).toLocaleString('et-EE', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  });
};
</script>