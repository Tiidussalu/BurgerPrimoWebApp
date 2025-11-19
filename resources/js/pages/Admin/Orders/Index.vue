<template>
  <div class="flex min-h-screen bg-[#0a0a0a] text-white">
    <!-- Sidebar -->
    <aside class="w-[200px] bg-[#111111] border-r border-gray-800 flex flex-col">
      <!-- Logo/Brand -->
      <div class="p-6 border-b border-gray-800">
        <h1 class="text-xl font-bold">Rena</h1>
        <p class="text-sm text-gray-400">Dashboard</p>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 p-4">
        <p class="text-xs text-gray-500 uppercase mb-4">Management</p>
        <div class="space-y-2">
          <Link
            href="/admin/dashboard"
            class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-800 transition text-gray-400 hover:text-white"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
          </Link>
          <Link
            href="/admin/orders"
            class="flex items-center gap-3 px-4 py-2 rounded bg-gray-800 text-white"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Orders
          </Link>
          <Link
            href="/admin/menu"
            class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-800 transition text-gray-400 hover:text-white"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            Menu
          </Link>
        </div>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1">
      <!-- Header -->
      <header class="border-b border-gray-800 px-8 py-6">
        <h2 class="text-2xl font-bold">Tellimused</h2>
      </header>

      <!-- Content -->
      <div class="p-8">
        <!-- Expandable Order Cards -->
        <div class="space-y-4">
          <div
            v-for="order in orders.data"
            :key="order.id"
            class="rounded-lg overflow-hidden transition-all"
            :class="[
              expandedOrders.includes(order.id) ? 'bg-[#1a1a1a]' : 'bg-[#111111]',
              order.status === 'pending' ? 'border-l-4 border-orange-500' : ''
            ]"
          >
            <!-- Collapsed View -->
            <div
              class="px-6 py-4 flex items-center justify-between cursor-pointer hover:bg-[#1a1a1a] transition"
            >
              <div class="flex items-center gap-6">
                <!-- Order Number -->
                <div>
                  <p class="text-lg font-bold">#{{ order.order_number }}</p>
                  <p class="text-sm text-gray-400">{{ order.user.name }}</p>
                </div>

                <!-- Status Badge -->
                <div>
                  <span :class="getStatusBadgeClass(order.status)">
                    {{ getStatusLabel(order.status) }}
                  </span>
                </div>
              </div>

              <div class="flex items-center gap-6">
                <!-- Date -->
                <div class="text-right">
                  <p class="text-sm text-gray-400">{{ formatDate(order.created_at) }}</p>
                </div>

                <!-- Total -->
                <div class="text-right min-w-[80px]">
                  <p class="text-sm text-gray-400">kokku</p>
                  <p class="font-bold">€{{ Number(order.total_amount).toFixed(2) }}</p>
                </div>

                <!-- Action Button -->
                <button
                  @click="toggleOrder(order.id)"
                  class="bg-[#D2691E] hover:bg-[#E07A2E] text-white px-6 py-2 rounded text-sm font-semibold transition"
                >
                  {{ expandedOrders.includes(order.id) ? 'Peida' : 'Vaata' }}
                </button>
              </div>
            </div>

            <!-- Expanded View -->
            <div
              v-if="expandedOrders.includes(order.id)"
              class="px-6 pb-6 border-t border-gray-800"
            >
              <!-- Order Details -->
              <div class="mt-4 space-y-4">
                <!-- Customer Info -->
                <div class="bg-[#0a0a0a] rounded-lg p-4">
                  <p class="text-xs text-gray-500 uppercase mb-3 font-semibold">Kliendi Info</p>
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <p class="text-sm text-gray-400 mb-1">Nimi</p>
                      <p class="font-semibold">{{ order.user.name }}</p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-400 mb-1">Email</p>
                      <p class="font-semibold">{{ order.user.email }}</p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-400 mb-1">Tellimuse number</p>
                      <p class="font-semibold text-[#D2691E]">#{{ order.order_number }}</p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-400 mb-1">Kuupäev</p>
                      <p class="font-semibold">{{ formatFullDate(order.created_at) }}</p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-400 mb-1">Tellimuse tüüp</p>
                      <p class="font-semibold flex items-center gap-2">
                        <span v-if="order.delivery_method === 'dine_in'" class="inline-flex items-center gap-1 bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-sm">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                          </svg>
                          Kohapeal
                        </span>
                        <span v-else-if="order.delivery_method === 'takeaway'" class="inline-flex items-center gap-1 bg-[#D2691E]/20 text-[#D2691E] px-3 py-1 rounded-full text-sm">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                          </svg>
                          Kaasa
                        </span>
                        <span v-else class="text-gray-500">-</span>
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Items -->
                <div class="bg-[#0a0a0a] rounded-lg p-4">
                  <p class="text-xs text-gray-500 uppercase mb-3 font-semibold">Tellitud Tooted</p>
                  <div class="space-y-4">
                    <div
                      v-for="item in order.items"
                      :key="item.id"
                      class="border-l-4 border-[#D2691E] pl-4 bg-[#111111] rounded-r-lg p-3"
                    >
                      <!-- Item Header -->
                      <div class="flex justify-between items-start mb-3">
                        <div>
                          <p class="font-bold text-lg">{{ item.burger_name }}</p>
                          <p class="text-sm text-gray-400">Kogus: <span class="font-semibold text-white">{{ item.quantity }}x</span></p>
                        </div>
                        <div class="text-right">
                          <p class="text-sm text-gray-400">Hind</p>
                          <p class="font-bold text-[#D2691E] text-xl">€{{ Number(item.price * item.quantity).toFixed(2) }}</p>
                        </div>
                      </div>

                      <!-- Ingredients List -->
                      <div class="mt-3 bg-[#0a0a0a] rounded p-3">
                        <p class="text-xs text-gray-500 uppercase mb-2 font-semibold">Koostis:</p>
                        <div
                          v-for="(ing, idx) in item.ingredients"
                          :key="idx"
                          class="flex items-center gap-2 text-sm py-1"
                        >
                          <span class="text-[#D2691E] text-lg">•</span>
                          <span class="text-gray-300 flex-1">
                            <span v-if="ing.quantity > 1" class="font-bold text-white">{{ ing.quantity }}x </span>
                            {{ ing.name }}
                          </span>
                          <span v-if="ing.price && ing.price > 0" class="text-gray-500 text-xs font-semibold">
                            +€{{ Number(ing.price).toFixed(2) }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Order Total -->
                  <div class="mt-4 pt-4 border-t border-gray-800 flex justify-between items-center">
                    <p class="text-lg font-bold uppercase text-gray-400">Tellimuse kokku</p>
                    <p class="text-3xl font-bold text-[#D2691E]">€{{ Number(order.total_amount).toFixed(2) }}</p>
                  </div>
                </div>

                <!-- Customer Notes -->
                <div v-if="order.customer_notes" class="bg-[#0a0a0a] rounded-lg p-4">
                  <p class="text-xs text-gray-500 uppercase mb-3 font-semibold">Kliendi Märkused</p>
                  <div class="bg-[#111111] rounded-lg p-4 border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-200 italic">"{{ order.customer_notes }}"</p>
                  </div>
                </div>

                <!-- Admin Actions -->
                <div class="bg-[#0a0a0a] rounded-lg p-4">
                  <p class="text-xs text-gray-500 uppercase mb-3 font-semibold">Admini Tegevused</p>
                  <div class="flex gap-3 items-end">
                    <div class="flex-1">
                      <label class="text-sm text-gray-400 mb-2 block">Muuda tellimuse olekut</label>
                      <select
                        v-model="orderStatuses[order.id]"
                        @change="updateOrderStatus(order.id)"
                        class="w-full bg-[#111111] border-2 border-gray-700 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#D2691E] transition"
                      >
                        <option value="pending">🟡 Ootel</option>
                        <option value="confirmed">🔵 Kinnitatud</option>
                        <option value="preparing">🟣 Valmistamisel</option>
                        <option value="ready">🟠 Valmis</option>
                        <option value="completed">🟢 Täidetud</option>
                      </select>
                    </div>

                    <div class="flex gap-2">
                      <button
                        v-if="order.status === 'pending'"
                        @click="confirmOrder(order.id)"
                        class="bg-[#D2691E] hover:bg-[#E07A2E] text-white px-6 py-3 rounded-lg text-sm font-semibold transition flex items-center gap-2"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Kinnita
                      </button>
                      <button
                        v-if="order.status === 'pending'"
                        @click="rejectOrder(order.id)"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg text-sm font-semibold transition flex items-center gap-2"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Lükka tagasi
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="orders.data.length === 0" class="text-center py-16">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="text-gray-400 text-lg">Tellimusi ei leitud</p>
        </div>

        <!-- Pagination -->
        <div v-if="orders.links.length > 3" class="mt-8 flex justify-center gap-2">
          <Link
            v-for="link in orders.links"
            :key="link.label"
            :href="link.url || '#'"
            :class="[
              'px-4 py-2 rounded text-sm font-semibold transition',
              link.active
                ? 'bg-[#D2691E] text-white'
                : link.url
                ? 'bg-[#111111] text-gray-300 hover:bg-[#1a1a1a]'
                : 'bg-[#111111] text-gray-600 cursor-not-allowed'
            ]"
            v-html="link.label"
          />
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';

interface User {
  id: number;
  name: string;
  email: string;
}

interface Ingredient {
  name: string;
  quantity: number;
  price?: number;
}

interface OrderItem {
  id: number;
  burger_name: string;
  price: number;
  quantity: number;
  ingredients: Ingredient[];
}

interface Order {
  id: number;
  order_number: string;
  user: User;
  total_amount: number;
  status: string;
  created_at: string;
  customer_notes?: string | null;
  delivery_method?: string;
  items: OrderItem[];
}

interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

interface Props {
  orders: {
    data: Order[];
    links: PaginationLink[];
  };
  stats?: any;
  filters?: any;
}

const props = defineProps<Props>();

const expandedOrders = ref<number[]>([]);
const orderStatuses = reactive<Record<number, string>>({});

// Initialize order statuses
props.orders.data.forEach(order => {
  orderStatuses[order.id] = order.status;
});

const toggleOrder = (orderId: number) => {
  const index = expandedOrders.value.indexOf(orderId);
  if (index > -1) {
    expandedOrders.value.splice(index, 1);
  } else {
    expandedOrders.value.push(orderId);
  }
};

const confirmOrder = (orderId: number) => {
  router.post(`/admin/orders/${orderId}/confirm`, {}, {
    preserveScroll: true,
  });
};

const updateOrderStatus = (orderId: number) => {
  router.post(`/admin/orders/${orderId}/status`, {
    status: orderStatuses[orderId],
  } as any, {
    preserveScroll: true,
  });
};

const rejectOrder = (orderId: number) => {
  const reason = prompt('Tagasilükkamise põhjus (valikuline):');
  if (reason !== null) {
    router.post(`/admin/orders/${orderId}/reject` as any, {
      admin_notes: reason || 'Tellimus lükati tagasi',
    }, {
      preserveScroll: true,
    });
  }
};

const getStatusBadgeClass = (status: string): string => {
  const classes: Record<string, string> = {
    pending: 'px-3 py-1 rounded bg-yellow-600 text-white text-xs font-semibold uppercase',
    confirmed: 'px-3 py-1 rounded bg-blue-600 text-white text-xs font-semibold uppercase',
    preparing: 'px-3 py-1 rounded bg-purple-600 text-white text-xs font-semibold uppercase',
    ready: 'px-3 py-1 rounded bg-orange-600 text-white text-xs font-semibold uppercase',
    completed: 'px-3 py-1 rounded bg-green-600 text-white text-xs font-semibold uppercase',
    cancelled: 'px-3 py-1 rounded bg-gray-600 text-white text-xs font-semibold uppercase',
    rejected: 'px-3 py-1 rounded bg-red-600 text-white text-xs font-semibold uppercase',
  };
  return classes[status] || classes.pending;
};

const getStatusLabel = (status: string): string => {
  const labels: Record<string, string> = {
    pending: 'uus',
    confirmed: 'kinnitatud',
    preparing: 'valmistamisel',
    ready: 'valmis',
    completed: 'täidetud',
    cancelled: 'tühistatud',
    rejected: 'tagasi lükatud',
  };
  return labels[status] || status;
};

const formatDate = (date: string): string => {
  return new Date(date).toLocaleString('et-EE', {
    day: '2-digit',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const formatFullDate = (date: string): string => {
  return new Date(date).toLocaleString('et-EE', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};
</script>