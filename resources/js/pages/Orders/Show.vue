<template>
  <div class="min-h-screen bg-[#0B0B0B] text-white">
    <Navbar />

    <main class="max-w-3xl mx-auto px-6 py-12">
      <!-- Success Banner -->
      <div class="mb-8 bg-green-900/20 border border-green-800/50 rounded-2xl p-8 text-center">
        <div class="text-6xl mb-4">✅</div>
        <h1 class="text-3xl font-bold mb-2">Tellimus esitatud!</h1>
        <p class="text-gray-400">Tellimuse number:
          <span class="font-mono font-bold text-[#D2691E]">{{ order.order_number }}</span>
        </p>
      </div>

      <!-- Order Card -->
      <div class="bg-[#121212] rounded-2xl overflow-hidden border border-[#1a1a1a]">

        <!-- Status Bar -->
        <div class="bg-[#0d0d0d] px-6 py-4 flex items-center justify-between border-b border-[#1a1a1a]">
          <div>
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Olek</p>
            <span :class="getStatusClass(order.status)">{{ getStatusLabel(order.status) }}</span>
          </div>
          <div class="text-right">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Esitatud</p>
            <p class="text-sm text-gray-300 font-medium">{{ formatDate(order.created_at) }}</p>
          </div>
        </div>

        <div class="p-6 space-y-6">
          <!-- Status progress indicator -->
          <div class="flex items-center gap-2">
            <div
              v-for="(step, i) in statusSteps"
              :key="step.key"
              class="flex items-center gap-2 flex-1"
            >
              <div class="flex flex-col items-center flex-1">
                <div
                  class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all"
                  :class="getStepClass(step.key)"
                >
                  <svg v-if="isStepDone(step.key)" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                  </svg>
                  <span v-else>{{ i + 1 }}</span>
                </div>
                <p class="text-xs text-gray-500 mt-1 text-center">{{ step.label }}</p>
              </div>
              <div
                v-if="i < statusSteps.length - 1"
                class="h-0.5 flex-1 mb-4 transition-all"
                :class="isStepDone(step.key) ? 'bg-[#D2691E]' : 'bg-[#1a1a1a]'"
              ></div>
            </div>
          </div>

          <!-- Items -->
          <div>
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-3">Tooted</p>
            <div class="space-y-3">
              <div
                v-for="item in order.items"
                :key="item.id"
                class="bg-[#0d0d0d] rounded-xl px-4 py-3"
              >
                <div class="flex justify-between items-start">
                  <div>
                    <p class="font-semibold">{{ item.burger_name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ item.quantity }}x kogus</p>
                    <div v-if="item.ingredients?.length" class="mt-2 space-y-0.5">
                      <p v-for="(ingredient, index) in item.ingredients" :key="index" class="text-xs text-gray-500">
                        {{ ingredient.quantity }}x {{ ingredient.name }}
                      </p>
                    </div>
                  </div>
                  <p class="font-bold text-[#D2691E]">{{ Number(item.price * item.quantity).toFixed(2) }}€</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Customer Notes -->
          <div v-if="order.customer_notes" class="bg-[#0d0d0d] rounded-xl px-4 py-3">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Sinu märkused</p>
            <p class="text-sm text-gray-300">{{ order.customer_notes }}</p>
          </div>

          <!-- Total -->
          <div class="flex items-center justify-between pt-4 border-t border-[#1a1a1a]">
            <span class="text-gray-400 font-medium">Kokku</span>
            <span class="text-3xl font-bold text-[#D2691E]">{{ Number(order.total_amount).toFixed(2) }}€</span>
          </div>
        </div>
      </div>

      <!-- Auto refresh notice -->
      <p class="text-center text-xs text-gray-600 mt-4">
        Staatus uuendatakse automaatselt iga 15 sekundi järel
      </p>

      <!-- Actions -->
      <div class="mt-6 flex gap-4">
        <Link
          href="/menu"
          class="flex-1 text-white px-6 py-3.5 rounded-xl font-semibold transition hover:opacity-90 text-center"
          style="background-color: #D2691E"
        >
          Telli veel
        </Link>
        <Link
          href="/orders"
          class="flex-1 bg-[#121212] hover:bg-[#1a1a1a] text-white px-6 py-3.5 rounded-xl font-semibold transition text-center border border-[#1a1a1a]"
        >
          Kõik tellimused
        </Link>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted } from 'vue';
import Navbar from '@/components/Navbar.vue';

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

const props = defineProps<Props>();

// Status progress steps
const statusSteps = [
  { key: 'pending',   label: 'Ootel' },
  { key: 'confirmed', label: 'Kinnitatud' },
  { key: 'preparing', label: 'Valmistamisel' },
  { key: 'ready',     label: 'Valmis' },
  { key: 'completed', label: 'Täidetud' },
];

const statusOrder = statusSteps.map(s => s.key);

const isStepDone = (stepKey: string): boolean => {
  const currentIndex = statusOrder.indexOf(props.order.status);
  const stepIndex = statusOrder.indexOf(stepKey);
  return stepIndex <= currentIndex;
};

const getStepClass = (stepKey: string): string => {
  if (props.order.status === stepKey) return 'bg-[#D2691E] text-white';
  if (isStepDone(stepKey)) return 'bg-[#D2691E]/30 text-[#D2691E]';
  return 'bg-[#1a1a1a] text-gray-600';
};

// Auto-refresh while order is active
const isActive = computed(() =>
  ['pending', 'confirmed', 'preparing', 'ready'].includes(props.order.status)
);

let refreshInterval: ReturnType<typeof setInterval> | null = null;

onMounted(() => {
  if (isActive.value) {
    refreshInterval = setInterval(() => {
      router.reload({ only: ['order'] });
    }, 15000);
  }
});

onUnmounted(() => {
  if (refreshInterval) clearInterval(refreshInterval);
});

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