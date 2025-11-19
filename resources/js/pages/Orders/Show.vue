<template>
  <div class="min-h-screen bg-black text-white">
    <!-- Header -->
    <header class="border-b border-gray-800 px-6 py-4">
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <h1 class="text-2xl font-bold text-orange-500">Burger Primo</h1>
        <nav class="flex gap-6">
          <Link href="/" class="text-gray-400 hover:text-white">Eriüd</Link>
          <Link href="/menu" class="text-gray-400 hover:text-white">Menüükaart</Link>
          <Link href="/burger-builder" class="text-gray-400 hover:text-white">Ehita</Link>
          <Link href="/orders" class="text-white">Tellimus</Link>
          <Link href="/cart" class="text-gray-400 hover:text-white">Korv</Link>
        </nav>
        <Link href="/profile" class="text-gray-400 hover:text-white">Logi sisse</Link>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-12">
      <!-- Success Message -->
      <div class="mb-8 bg-green-900/30 border border-green-700 rounded-lg p-6 text-center">
        <div class="text-5xl mb-4">✅</div>
        <h2 class="text-3xl font-bold mb-2">Tellimus esitatud!</h2>
        <p class="text-gray-300">Tellimus number: <span class="font-mono text-orange-500">{{ order.order_number }}</span></p>
      </div>

      <!-- Order Details -->
      <div class="bg-gray-900 rounded-lg p-8 border border-gray-800">
        <h3 class="text-2xl font-bold mb-6">Tellimuse üksikasjad</h3>

        <!-- Status -->
        <div class="mb-6 pb-6 border-b border-gray-800">
          <div class="flex items-center justify-between">
            <span class="text-gray-400">Olek:</span>
            <span :class="getStatusClass(order.status)">
              {{ getStatusLabel(order.status) }}
            </span>
          </div>
          <div class="mt-2 flex items-center justify-between">
            <span class="text-gray-400">Esitatud:</span>
            <span>{{ formatDate(order.created_at) }}</span>
          </div>
        </div>

        <!-- Items -->
        <div class="mb-6">
          <h4 class="font-bold mb-4">Burgerid:</h4>
          <div class="space-y-4">
            <div
              v-for="item in order.items"
              :key="item.id"
              class="bg-gray-800 rounded-lg p-4"
            >
              <div class="flex justify-between items-start mb-2">
                <div>
                  <p class="font-bold">{{ item.burger_name }}</p>
                  <p class="text-sm text-gray-400">Kogus: {{ item.quantity }}x</p>
                </div>
                <p class="font-bold text-orange-500">{{ Number(item.price * item.quantity).toFixed(2) }}€</p>
              </div>
              <div class="text-sm text-gray-400 mt-2">
                <p v-for="(ingredient, index) in item.ingredients" :key="index">
                  {{ ingredient.quantity }}x {{ ingredient.name }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Customer Notes -->
        <div v-if="order.customer_notes" class="mb-6 pb-6 border-b border-gray-800">
          <h4 class="font-bold mb-2">Sinu märkused:</h4>
          <p class="text-gray-400">{{ order.customer_notes }}</p>
        </div>

        <!-- Total -->
        <div class="flex justify-between items-center text-2xl font-bold">
          <span>Kokku:</span>
          <span class="text-orange-500">{{ Number(order.total_amount).toFixed(2) }}€</span>
        </div>
      </div>

      <!-- Actions -->
      <div class="mt-8 flex gap-4">
        <Link
          href="/burger-builder"
          class="flex-1 bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded font-semibold transition text-center"
        >
          Telli veel burgereid
        </Link>
        <Link
          href="/orders"
          class="flex-1 bg-gray-800 hover:bg-gray-700 text-white px-6 py-3 rounded font-semibold transition text-center"
        >
          Vaata kõiki tellimusi
        </Link>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface Ingredient {
  name: string;
  quantity: number;
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
  total_amount: number;
  status: string;
  created_at: string;
  customer_notes: string | null;
  items: OrderItem[];
}

interface Props {
  order: Order;
}

defineProps<Props>();

const getStatusClass = (status: string): string => {
  const classes: Record<string, string> = {
    pending: 'px-3 py-1 rounded-full bg-yellow-900/30 text-yellow-400 text-sm font-semibold',
    confirmed: 'px-3 py-1 rounded-full bg-blue-900/30 text-blue-400 text-sm font-semibold',
    preparing: 'px-3 py-1 rounded-full bg-purple-900/30 text-purple-400 text-sm font-semibold',
    ready: 'px-3 py-1 rounded-full bg-orange-900/30 text-orange-400 text-sm font-semibold',
    completed: 'px-3 py-1 rounded-full bg-green-900/30 text-green-400 text-sm font-semibold',
    cancelled: 'px-3 py-1 rounded-full bg-red-900/30 text-red-400 text-sm font-semibold',
  };
  return classes[status] || classes.pending;
};

const getStatusLabel = (status: string): string => {
  const labels: Record<string, string> = {
    pending: 'Ootel',
    confirmed: 'Kinnitatud',
    preparing: 'Valmistamisel',
    ready: 'Valmis',
    completed: 'Täidetud',
    cancelled: 'Tühistatud',
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