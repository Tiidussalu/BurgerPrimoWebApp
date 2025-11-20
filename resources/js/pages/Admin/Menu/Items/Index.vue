<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Plus, Edit, Trash2, Star, Eye, EyeOff, Search, Filter } from 'lucide-vue-next';

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

<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between w-full">
        <div>
          <h2 class="text-xl lg:text-2xl font-bold">Menüü tooted</h2>
          <p class="text-sm text-gray-400 mt-1">Halda menüü tooteid ja hindu</p>
        </div>
        <Link
          href="/admin/menu/items/create"
          class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center gap-2"
        >
          <Plus :size="18" />
          <span class="hidden sm:inline">Lisa toode</span>
        </Link>
      </div>
    </template>

    <!-- Filters -->
    <div class="bg-[#111111] rounded-xl border border-gray-800 p-6 mb-6">
      <div class="flex items-center gap-2 mb-4">
        <Filter :size="18" class="text-gray-400" />
        <h3 class="font-semibold text-white">Filtrid</h3>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Category Filter -->
        <div>
          <label class="block text-sm font-medium text-gray-400 mb-2">Kategooria</label>
          <select
            v-model="filterForm.category_id"
            @change="applyFilters"
            class="w-full bg-[#0a0a0a] border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-orange-600 focus:ring-2 focus:ring-orange-600/20 transition"
          >
            <option value="">Kõik kategooriad</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>

        <!-- Search -->
        <div>
          <label class="block text-sm font-medium text-gray-400 mb-2">Otsi</label>
          <div class="relative">
            <Search :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" />
            <input
              v-model="filterForm.search"
              @input="applyFilters"
              type="text"
              class="w-full bg-[#0a0a0a] border border-gray-700 rounded-lg pl-10 pr-4 py-2.5 text-white focus:border-orange-600 focus:ring-2 focus:ring-orange-600/20 transition"
              placeholder="Otsi nime või kirjelduse järgi..."
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Items Grid -->
    <div v-if="items.data && items.data.length > 0" class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="item in items.data"
          :key="item.id"
          class="bg-[#111111] rounded-xl border border-gray-800 overflow-hidden hover:border-orange-600/50 transition group"
        >
          <!-- Image -->
          <div class="relative aspect-[4/3] bg-[#0a0a0a] overflow-hidden">
            <img
              v-if="item.image_url"
              :src="item.image_url"
              :alt="item.name"
              class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
            />
            <div v-else class="w-full h-full flex items-center justify-center">
              <span class="text-6xl">🍔</span>
            </div>

            <!-- Status Badges -->
            <div class="absolute top-3 left-3 flex flex-col gap-2">
              <span v-if="item.is_featured" class="inline-flex items-center gap-1.5 bg-yellow-500 text-black px-2.5 py-1 rounded-full text-xs font-bold shadow-lg">
                <Star :size="12" fill="currentColor" />
                Featured
              </span>
              <span v-if="!item.is_active" class="inline-flex items-center gap-1.5 bg-red-600 text-white px-2.5 py-1 rounded-full text-xs font-bold shadow-lg">
                <EyeOff :size="12" />
                Peidetud
              </span>
            </div>

            <!-- Discount Badge -->
            <div v-if="item.discount_percentage" class="absolute top-3 right-3 bg-blue-600 text-white px-2.5 py-1 rounded-full text-xs font-bold shadow-lg">
              -{{ item.discount_percentage }}%
            </div>
          </div>

          <!-- Content -->
          <div class="p-4">
            <!-- Category & Name -->
            <div class="mb-3">
              <span class="inline-block text-xs text-orange-400 font-semibold mb-1">
                {{ item.category.name }}
              </span>
              <h3 class="font-bold text-lg text-white line-clamp-1">{{ item.name }}</h3>
            </div>

            <!-- Description -->
            <p class="text-sm text-gray-400 mb-4 line-clamp-2 min-h-[40px]">
              {{ item.description }}
            </p>

            <!-- Price -->
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-800">
              <div>
                <span class="text-2xl font-bold text-orange-500">€{{ Number(item.price).toFixed(2) }}</span>
                <span v-if="item.original_price" class="text-sm text-gray-500 line-through ml-2">
                  €{{ Number(item.original_price).toFixed(2) }}
                </span>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-2">
              <Link
                :href="`/admin/menu/items/${item.id}/edit`"
                class="flex-1 bg-blue-600/10 hover:bg-blue-600/20 text-blue-400 px-4 py-2.5 rounded-lg font-semibold transition text-center text-sm flex items-center justify-center gap-2"
              >
                <Edit :size="16" />
                Muuda
              </Link>
              <button
                @click="deleteItem(item.id)"
                class="flex-1 bg-red-600/10 hover:bg-red-600/20 text-red-400 px-4 py-2.5 rounded-lg font-semibold transition text-sm flex items-center justify-center gap-2"
              >
                <Trash2 :size="16" />
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
            'px-4 py-2 rounded-lg font-semibold transition',
            link.active
              ? 'bg-orange-600 text-white'
              : link.url
                ? 'bg-[#111111] text-gray-400 hover:bg-[#1a1a1a] hover:text-white border border-gray-800'
                : 'bg-[#111111] text-gray-600 cursor-not-allowed border border-gray-800'
          ]"
          v-html="link.label"
        />
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-16 bg-[#111111] rounded-xl border border-gray-800">
      <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
        <span class="text-4xl">🍔</span>
      </div>
      <h3 class="text-xl font-bold text-white mb-2">Tooteid pole veel</h3>
      <p class="text-gray-400 mb-6">Lisa oma esimene menüü toode alustamiseks</p>
      <Link
        href="/admin/menu/items/create"
        class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-semibold transition"
      >
        <Plus :size="18" />
        Lisa toode
      </Link>
    </div>

    <!-- Quick Stats -->
    <div v-if="items.data && items.data.length > 0" class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
      <div class="bg-[#111111] rounded-lg border border-gray-800 p-4">
        <p class="text-sm text-gray-400 mb-1">Kokku tooteid</p>
        <p class="text-2xl font-bold text-white">{{ items.data.length }}</p>
      </div>
      <div class="bg-[#111111] rounded-lg border border-gray-800 p-4">
        <p class="text-sm text-gray-400 mb-1">Aktiivseid</p>
        <p class="text-2xl font-bold text-green-400">{{ items.data.filter(i => i.is_active).length }}</p>
      </div>
      <div class="bg-[#111111] rounded-lg border border-gray-800 p-4">
        <p class="text-sm text-gray-400 mb-1">Featured</p>
        <p class="text-2xl font-bold text-yellow-400">{{ items.data.filter(i => i.is_featured).length }}</p>
      </div>
      <div class="bg-[#111111] rounded-lg border border-gray-800 p-4">
        <p class="text-sm text-gray-400 mb-1">Allahindlusi</p>
        <p class="text-2xl font-bold text-blue-400">{{ items.data.filter(i => i.discount_percentage).length }}</p>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>