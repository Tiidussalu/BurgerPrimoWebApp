<template>
  <div class="min-h-screen bg-black text-white">
    <!-- Header -->
    <header class="border-b border-gray-800 px-6 py-4">
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <h1 class="text-2xl font-bold text-orange-500">Admin - Menüü tooted</h1>
        <Link href="/admin/dashboard" class="text-gray-400 hover:text-white transition">← Tagasi</Link>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-12">
      <!-- Header with Add Button -->
      <div class="flex items-center justify-between mb-8">
        <div>
          <h2 class="text-3xl font-bold mb-2">Menüü tooted</h2>
          <p class="text-gray-400">Halda menüü tooteid</p>
        </div>
        <Link
          href="/admin/menu/items/create"
          class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold transition flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Lisa toode
        </Link>
      </div>

      <!-- Filters -->
      <div class="bg-gray-900 rounded-lg border border-gray-800 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Category Filter -->
          <div>
            <label class="block text-sm font-semibold mb-2">Kategooria</label>
            <select
              v-model="filterForm.category_id"
              @change="applyFilters"
              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
            >
              <option value="">Kõik kategooriad</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>

          <!-- Search -->
          <div>
            <label class="block text-sm font-semibold mb-2">Otsi</label>
            <input
              v-model="filterForm.search"
              @input="applyFilters"
              type="text"
              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
              placeholder="Otsi nime või kirjelduse järgi..."
            />
          </div>
        </div>
      </div>

      <!-- Items Grid -->
      <div v-if="items.data && items.data.length > 0" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="item in items.data"
            :key="item.id"
            class="bg-gray-900 rounded-lg border border-gray-800 overflow-hidden hover:border-orange-500 transition"
          >
            <!-- Image -->
            <div class="relative aspect-[4/3] bg-gray-800">
              <img
                v-if="item.image_url"
                :src="item.image_url"
                :alt="item.name"
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full flex items-center justify-center text-6xl">
                🍔
              </div>

              <!-- Badges -->
              <div class="absolute top-3 left-3 flex gap-2">
                <span v-if="item.is_featured" class="bg-yellow-500 text-black px-2 py-1 rounded-full text-xs font-bold">
                  ⭐ Featured
                </span>
                <span v-if="!item.is_active" class="bg-red-600 text-white px-2 py-1 rounded-full text-xs font-bold">
                  Mitteaktiivne
                </span>
              </div>

              <div v-if="item.discount_percentage" class="absolute top-3 right-3 bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                -{{ item.discount_percentage }}%
              </div>
            </div>

            <!-- Content -->
            <div class="p-4">
              <div class="mb-2">
                <h3 class="font-bold text-lg">{{ item.name }}</h3>
                <p class="text-sm text-gray-400">{{ item.category.name }}</p>
              </div>

              <p class="text-sm text-gray-400 mb-4 line-clamp-2">
                {{ item.description }}
              </p>

              <div class="flex items-center justify-between mb-4">
                <div>
                  <span class="text-xl font-bold text-orange-500">€{{ Number(item.price).toFixed(2) }}</span>
                  <span v-if="item.original_price" class="text-sm text-gray-500 line-through ml-2">
                    €{{ Number(item.original_price).toFixed(2) }}
                  </span>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex gap-2">
                <Link
                  :href="`/admin/menu/items/${item.id}/edit`"
                  class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold transition text-center text-sm"
                >
                  Muuda
                </Link>
                <button
                  @click="deleteItem(item.id)"
                  class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded font-semibold transition text-sm"
                >
                  Kustuta
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="items.links && items.links.length > 3" class="flex justify-center gap-2 mt-8">
          <Link
            v-for="(link, index) in items.links"
            :key="index"
            :href="link.url || '#'"
            :class="[
              'px-4 py-2 rounded font-semibold transition',
              link.active
                ? 'bg-orange-500 text-white'
                : link.url
                  ? 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white'
                  : 'bg-gray-900 text-gray-600 cursor-not-allowed'
            ]"
            v-html="link.label"
          />
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16 bg-gray-900 rounded-lg border border-gray-800">
        <div class="text-6xl mb-4">🍔</div>
        <h3 class="text-2xl font-bold mb-2">Tooteid pole veel</h3>
        <p class="text-gray-400 mb-6">Lisa oma esimene menüü toode</p>
        <Link
          href="/admin/menu/items/create"
          class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded font-semibold transition"
        >
          Lisa toode
        </Link>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface Category {
  id: number;
  name: string;
}

interface MenuItem {
  id: number;
  name: string;
  description: string;
  price: number;
  original_price: number | null;
  image_url: string | null;
  is_featured: boolean;
  is_active: boolean;
  discount_percentage: number | null;
  category: Category;
}

interface PaginatedItems {
  data: MenuItem[];
  links: Array<{
    url: string | null;
    label: string;
    active: boolean;
  }>;
}

interface Props {
  items: PaginatedItems;
  categories: Category[];
  filters: {
    category_id?: string;
    search?: string;
  };
}

const props = defineProps<Props>();

const filterForm = ref({
  category_id: props.filters.category_id || '',
  search: props.filters.search || '',
});

let timeout: ReturnType<typeof setTimeout>;

const applyFilters = () => {
  clearTimeout(timeout);
  timeout = setTimeout(() => {
    router.get('/admin/menu/items', filterForm.value, {
      preserveState: true,
      preserveScroll: true,
    });
  }, 300);
};

const deleteItem = (itemId: number) => {
  if (confirm('Kas oled kindel, et soovid selle toote kustutada?')) {
    router.delete(`/admin/menu/items/${itemId}` as any, {
      preserveScroll: true,
    });
  }
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>