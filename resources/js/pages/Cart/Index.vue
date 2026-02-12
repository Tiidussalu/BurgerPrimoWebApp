<template>
  <div class="min-h-screen bg-[#0B0B0B] text-white">
    <!-- Navbar -->
    <Navbar :cartCount="cartItems.length" />

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold mb-2">Ostukorv</h1>
        <p class="text-gray-400">Vaadake üle oma tellimus</p>
      </div>

      <!-- Cart Content -->
      <div v-if="cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4">
          <div
            v-for="item in cartItems"
            :key="item.type === 'custom_burger' ? `burger-${item.id}` : `menu-${item.id}`"
            class="bg-[#121212] rounded-2xl p-6 hover:ring-2 hover:ring-[#D2691E] transition-all"
          >
            <!-- Custom Burger -->
            <template v-if="item.type === 'custom_burger'">
              <div class="flex gap-4">
                <div class="flex-1">
                  <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs bg-yellow-500/20 text-yellow-400 px-2 py-1 rounded-full font-bold">
                      🍔 Kohandatud
                    </span>
                  </div>
                  <h3 class="text-xl font-bold mb-2">{{ item.burger.name }}</h3>
                  
                  <!-- Ingredients -->
                  <div class="text-sm text-gray-400 mb-2">
                    <span class="font-semibold text-[#D2691E]">Koostisosad:</span>
                    <div class="ml-4 mt-1">
                      <div v-for="ingredient in item.burger.ingredients" :key="ingredient.id">
                        • {{ ingredient.name }} ({{ ingredient.pivot.quantity }}x)
                      </div>
                    </div>
                  </div>

                  <!-- Quantity Controls -->
                  <div class="flex items-center gap-3 mt-4">
                    <button
                      @click="updateQuantity(item.id, item.quantity - 1, 'burger')"
                      :disabled="item.quantity <= 1"
                      class="w-8 h-8 rounded-lg bg-[#0B0B0B] hover:bg-[#D2691E] disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
                    >
                      <span class="text-xl">−</span>
                    </button>
                    <span class="text-lg font-bold w-8 text-center">{{ item.quantity }}</span>
                    <button
                      @click="updateQuantity(item.id, item.quantity + 1, 'burger')"
                      class="w-8 h-8 rounded-lg bg-[#0B0B0B] hover:bg-[#D2691E] transition-colors"
                    >
                      <span class="text-xl">+</span>
                    </button>
                  </div>
                </div>

                <!-- Price & Remove -->
                <div class="flex flex-col items-end justify-between">
                  <button
                    @click="removeItem(item.id, 'burger')"
                    class="text-red-400 hover:text-red-300 p-2 hover:bg-red-500/10 rounded-lg transition-colors"
                    title="Eemalda"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                  <div class="text-right">
                    <div class="text-2xl font-bold text-[#D2691E]">
                      €{{ Number(item.subtotal).toFixed(2) }}
                    </div>
                    <div class="text-sm text-gray-500">
                      €{{ Number(item.burger.total_price).toFixed(2) }} × {{ item.quantity }}
                    </div>
                  </div>
                </div>
              </div>
            </template>

            <!-- Menu Item -->
            <template v-else-if="item.type === 'menu_item'">
              <div class="flex gap-4">
                <div class="flex-1">
                  <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs bg-[#D2691E]/20 text-[#D2691E] px-2 py-1 rounded-full font-bold">
                      📋 Menüü
                    </span>
                  </div>
                  <h3 class="text-xl font-bold mb-2">{{ item.name }}</h3>
                  
                  <!-- Size -->
                  <div class="text-sm text-gray-400 mb-2">
                    <span class="font-semibold text-[#D2691E]">Suurus:</span> 
                    {{ getSizeName(item.size) }}
                  </div>

                  <!-- Drinks -->
                  <div v-if="item.drinks && item.drinks.length > 0" class="text-sm text-gray-400 mb-2">
                    <span class="font-semibold text-[#D2691E]">Joogid:</span>
                    <div class="ml-4">
                      <div v-for="(drink, idx) in item.drinks" :key="`drink-${idx}`">
                        • {{ drink.name }}
                      </div>
                    </div>
                  </div>

                  <!-- Sauces -->
                  <div v-if="item.sauces && item.sauces.length > 0" class="text-sm text-gray-400 mb-2">
                    <span class="font-semibold text-[#D2691E]">Kastmed:</span>
                    <div class="ml-4">
                      <div v-for="(sauce, idx) in item.sauces" :key="`sauce-${idx}`">
                        • {{ sauce.name }}
                      </div>
                    </div>
                  </div>

                  <!-- Fries -->
                  <div v-if="item.fries" class="text-sm text-gray-400 mb-2">
                    <span class="font-semibold text-[#D2691E]">Friikartul:</span> {{ item.fries.name }}
                  </div>

                  <!-- Utensils -->
                  <div v-if="item.needs_utensils" class="text-sm text-gray-400 mb-2">
                    <span class="font-semibold text-[#D2691E]">🍴</span> Söögiriistad kaasas
                  </div>

                  <!-- Special Instructions -->
                  <div v-if="item.special_instructions" class="text-sm text-gray-400 mb-2">
                    <span class="font-semibold text-[#D2691E]">Märkused:</span> {{ item.special_instructions }}
                  </div>

                  <!-- Quantity Controls -->
                  <div class="flex items-center gap-3 mt-4">
                    <button
                      @click="updateQuantity(item.id, item.quantity - 1, 'menu')"
                      :disabled="item.quantity <= 1"
                      class="w-8 h-8 rounded-lg bg-[#0B0B0B] hover:bg-[#D2691E] disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
                    >
                      <span class="text-xl">−</span>
                    </button>
                    <span class="text-lg font-bold w-8 text-center">{{ item.quantity }}</span>
                    <button
                      @click="updateQuantity(item.id, item.quantity + 1, 'menu')"
                      class="w-8 h-8 rounded-lg bg-[#0B0B0B] hover:bg-[#D2691E] transition-colors"
                    >
                      <span class="text-xl">+</span>
                    </button>
                  </div>
                </div>

                <!-- Price & Remove -->
                <div class="flex flex-col items-end justify-between">
                  <button
                    @click="removeItem(item.id, 'menu')"
                    class="text-red-400 hover:text-red-300 p-2 hover:bg-red-500/10 rounded-lg transition-colors"
                    title="Eemalda"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                  <div class="text-right">
                    <div class="text-2xl font-bold text-[#D2691E]">
                      €{{ Number(item.subtotal).toFixed(2) }}
                    </div>
                    <div class="text-sm text-gray-500">
                      €{{ Number(item.total_price).toFixed(2) }} × {{ item.quantity }}
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-[#121212] rounded-2xl p-6 sticky top-24">
            <h2 class="text-2xl font-bold mb-6">Kokkuvõte</h2>

            <!-- Items Count -->
            <div class="flex justify-between mb-4 text-gray-400">
              <span>Tooteid:</span>
              <span>{{ cartItems.length }}</span>
            </div>

            <!-- Subtotal -->
            <div class="flex justify-between mb-4 text-gray-400">
              <span>Vahesumma:</span>
              <span>€{{ Number(total).toFixed(2) }}</span>
            </div>

            <!-- Delivery Method Selection -->
            <div class="mb-4">
              <label class="text-sm text-gray-400 mb-3 block font-semibold">Tellimuse tüüp *</label>
              <div class="grid grid-cols-2 gap-3">
                <button
                  type="button"
                  @click="deliveryMethod = 'dine_in'"
                  :class="deliveryMethod === 'dine_in' ? 'border-[#D2691E] bg-[#D2691E]/10 text-white' : 'border-[#0B0B0B] text-gray-400'"
                  class="py-4 rounded-xl border-2 flex flex-col items-center gap-2 transition-all"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                  </svg>
                  <span class="text-sm font-semibold">Kohapeal</span>
                </button>
                <button
                  type="button"
                  @click="deliveryMethod = 'takeaway'"
                  :class="deliveryMethod === 'takeaway' ? 'border-[#D2691E] bg-[#D2691E]/10 text-white' : 'border-[#0B0B0B] text-gray-400'"
                  class="py-4 rounded-xl border-2 flex flex-col items-center gap-2 transition-all"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                  </svg>
                  <span class="text-sm font-semibold">Kaasa</span>
                </button>
              </div>
            </div>

            <!-- Packaging Fee (only for takeaway) -->
            <div v-if="deliveryMethod === 'takeaway'" class="flex justify-between mb-4 text-[#D2691E]">
              <span>Pakendamise tasu:</span>
              <span>€{{ packagingFee.toFixed(2) }}</span>
            </div>

            <hr class="border-[#0B0B0B] my-6" />

            <!-- Total -->
            <div class="flex justify-between mb-6">
              <span class="text-xl font-bold">Kokku:</span>
              <span class="text-3xl font-bold text-[#D2691E]">€{{ finalTotal.toFixed(2) }}</span>
            </div>
            
            <!-- Checkout Button -->
            <form @submit.prevent="checkout">
              <button
                type="submit"
                class="block w-full bg-gradient-to-r from-[#D2691E] to-[#B8571A] hover:from-[#E07A2E] hover:to-[#D2691E] text-white py-4 rounded-xl font-bold text-lg text-center transition-all shadow-lg hover:shadow-[#D2691E]/50"
              >
                Vormista tellimus
              </button>
            </form>

            <!-- Clear Cart -->
            <button
              @click="clearCart"
              class="w-full mt-3 text-red-400 hover:text-red-300 py-3 rounded-xl hover:bg-red-500/10 transition-colors text-sm"
            >
              Tühjenda korv
            </button>
          </div>
        </div>
      </div>

      <!-- Empty Cart -->
      <div v-else class="text-center py-16">
        <div class="text-6xl mb-4">🛒</div>
        <h2 class="text-3xl font-bold mb-4">Teie ostukorv on tühi</h2>
        <p class="text-gray-400 mb-8">Lisa tooteid menüüst või ehita oma burger</p>
        <div class="flex gap-4 justify-center">
          <Link
            href="/menu"
            class="inline-block px-8 py-4 bg-gradient-to-r from-[#D2691E] to-[#B8571A] hover:from-[#E07A2E] hover:to-[#D2691E] text-white rounded-xl font-bold transition-all shadow-lg hover:shadow-[#D2691E]/50"
          >
            Vaata menüüd
          </Link>
          <Link
            href="/burger-builder"
            class="inline-block px-8 py-4 bg-[#121212] hover:bg-[#D2691E]/10 text-white rounded-xl font-bold transition-all border-2 border-[#D2691E]"
          >
            Ehita burger
          </Link>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';

interface CartItem {
  type: 'custom_burger' | 'menu_item';
  id: string | number;
  quantity: number;
  subtotal: number;
  burger?: any;
  menu_item_id?: number;
  name?: string;
  base_price?: number;
  size?: string;
  drinks?: Array<{ id: number; name: string; price: number }>;
  sauces?: Array<{ id: number; name: string; price: number }>;
  fries?: { id: string; name: string; price: number } | null;
  needs_utensils?: boolean;
  special_instructions?: string;
  total_price?: number;
}

interface Props {
  cartItems: CartItem[];
  total: number;
}

const props = defineProps<Props>();

const deliveryMethod = ref<'dine_in' | 'takeaway' | null>(null);

// Packaging fee: €0.20 per item for takeaway
const packagingFee = computed(() => {
  if (deliveryMethod.value === 'takeaway') {
    return props.cartItems.length * 0.20;
  }
  return 0;
});

// Final total including packaging fee
const finalTotal = computed(() => {
  return props.total + packagingFee.value;
});

const getSizeName = (size?: string) => {
  const sizes: Record<string, string> = {
    small: 'Väike',
    medium: 'Keskmine',
    large: 'Suur',
  };
  return sizes[size ?? ''] || (size ?? '');
};

const updateQuantity = (itemId: string | number, newQuantity: number, type: string) => {
  if (newQuantity < 1) return;
  
  router.post(`/cart/${itemId}/update`, {
    quantity: newQuantity,
  }, {
    preserveScroll: true,
  });
};

const removeItem = (itemId: string | number, type: string) => {
  if (confirm('Kas olete kindel, et soovite selle toote eemaldada?')) {
    router.delete(`/cart/${itemId}`, {
      preserveScroll: true,
    });
  }
};

const clearCart = () => {
  if (confirm('Kas olete kindel, et soovite kogu korvi tühjendada?')) {
    router.post('/cart/clear', {}, {
      preserveScroll: true,
    });
  }
};

const checkout = () => {
  if (!deliveryMethod.value) {
    alert('Palun valige tellimuse tüüp!');
    return;
  }

  // Store delivery method and navigate to payment
  router.visit('/payment/checkout', {
    method: 'get',
    data: {
      delivery_method: deliveryMethod.value,
    },
  });
};
</script>
EOF

cat /home/claude/Cart_Index.vue