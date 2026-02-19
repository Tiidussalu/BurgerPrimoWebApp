<template>
  <Head title="Menüü" />
  <div class="min-h-screen bg-[#0B0B0B] text-white">
    <div ref="navbarRef"><Navbar /></div>

    <div class="max-w-[1400px] mx-auto flex">
      <!-- Sticky sidebar — stays in document flow, no fixed positioning -->
      <aside
        class="hidden lg:flex flex-col w-56 flex-shrink-0 px-4 gap-1 sticky self-start"
        :style="{ top: navbarHeight + 16 + 'px', maxHeight: `calc(100vh - ${navbarHeight + 32}px)` }"
      >
        <!-- Search -->
        <div class="relative mt-60 mb-2">
          <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
            </svg>
          </div>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Otsi menüüst..."
            class="w-full bg-[#121212] border border-[#1a1a1a] focus:border-[#D2691E]/50 rounded-xl pl-9 pr-8 py-2.5 text-sm text-white placeholder-gray-600 outline-none transition-all"
          />
          <button
            v-if="searchQuery"
            @click="searchQuery = ''"
            class="absolute inset-y-0 right-2.5 flex items-center text-gray-500 hover:text-white transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Category nav -->
        <nav class="flex flex-col gap-1 overflow-y-auto">
          <button
            v-if="favorites && favorites.length > 0"
            @click="scrollTo('favorites')"
            :class="[
              'w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold transition-all',
              activeSection === 'favorites'
                ? 'bg-[#D2691E] text-white'
                : 'text-gray-400 hover:text-white hover:bg-[#1a1a1a]'
            ]"
          >★ Lemmikud</button>
          <button
            v-for="category in categories"
            :key="category.id"
            @click="scrollTo(`cat-${category.id}`)"
            :class="[
              'w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold transition-all uppercase tracking-wide',
              activeSection === `cat-${category.id}`
                ? 'bg-[#D2691E] text-white'
                : 'text-gray-400 hover:text-white hover:bg-[#1a1a1a]'
            ]"
          >{{ category.name }}</button>
        </nav>
      </aside>

      <!-- Main content -->
      <main class="flex-1 min-w-0 px-6 py-8">
        <div class="max-w-5xl mx-auto">

          <!-- Search Results -->
          <section v-if="searchQuery.trim()">
            <div class="mb-6">
              <h2 class="text-xl font-bold text-white">Otsingutulemused</h2>
              <p class="text-sm text-gray-500 mt-0.5">
                <span class="text-white font-medium">{{ totalSearchResults }}</span>
                tulemust — "<span class="text-[#D2691E]">{{ searchQuery }}</span>"
              </p>
            </div>

            <div v-if="totalSearchResults > 0" class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <MenuItem :horizontal="true" v-for="item in searchResults.items" :key="`si-${item.id}`" :item="item" />
              <CustomBurgerCard
                v-for="burger in searchResults.customBurgers"
                :key="`sb-${burger.id}`"
                :burger="burger"
                @add-to-cart="addCustomBurgerToCart"
              />
            </div>

            <div v-else class="flex items-center justify-center py-16 bg-[#121212] rounded-2xl border border-[#1a1a1a]">
              <div class="text-center">
                <p class="text-4xl mb-3">🔍</p>
                <p class="text-gray-400 font-medium mb-1">Tulemusi ei leitud</p>
                <p class="text-gray-600 text-sm">Proovi teist otsingusõna</p>
              </div>
            </div>
          </section>

          <!-- Normal view -->
          <template v-else>

            <!-- Favorites -->
            <section v-if="favorites && favorites.length > 0" id="favorites" class="mb-10">
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                  <span class="text-[#D2691E]">★</span> Lemmikud
                  <span class="text-xs text-gray-600 font-normal ml-1">{{ favorites.length }} toodet</span>
                </h2>
                <button @click="favoritesCollapsed = !favoritesCollapsed" class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-white transition-colors">
                  {{ favoritesCollapsed ? 'Näita' : 'Peida' }}
                  <component :is="favoritesCollapsed ? ChevronDown : ChevronUp" :size="14" />
                </button>
              </div>
              <div v-show="!favoritesCollapsed" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <MenuItem :horizontal="true" v-for="item in favorites.filter((f: any) => f.type === 'menu_item')" :key="`fav-${item.id}`" :item="item" />
                <CustomBurgerCard
                  v-for="burger in favorites.filter((f: any) => f.type === 'custom_burger')"
                  :key="`favb-${burger.id}`"
                  :burger="burger"
                  @add-to-cart="addCustomBurgerToCart"
                />
              </div>
            </section>

            <!-- Categories -->
            <section
              v-for="category in categories"
              :key="category.id"
              :id="`cat-${category.id}`"
              class="mb-10"
            >
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-white uppercase tracking-wide flex items-center gap-3">
                  {{ category.name }}
                  <span class="text-xs text-gray-600 font-normal normal-case tracking-normal">{{ category.active_items?.length || 0 }} toodet</span>
                </h2>
                <button @click="toggleCategory(category.id)" class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-white transition-colors">
                  {{ collapsedCategories.includes(category.id) ? 'Näita' : 'Peida' }}
                  <component :is="collapsedCategories.includes(category.id) ? ChevronDown : ChevronUp" :size="14" />
                </button>
              </div>

              <div v-show="!collapsedCategories.includes(category.id)">
                <div v-if="category.active_items && category.active_items.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                  <MenuItem :horizontal="true" v-for="item in category.active_items" :key="item.id" :item="item" />
                </div>
                <div v-else class="flex items-center justify-center py-12 bg-[#121212] rounded-xl border border-[#1a1a1a]">
                  <p class="text-gray-600 text-sm">Selles kategoorias pole hetkel tooteid</p>
                </div>
              </div>
            </section>

            <div v-if="categories.length === 0" class="text-center py-32">
              <p class="text-6xl mb-6">🍔</p>
              <h3 class="text-2xl font-bold mb-3">Menüüsid pole veel lisatud</h3>
              <p class="text-gray-500">Tulge hiljem tagasi!</p>
            </div>

          </template>
        </div>
      </main>
    </div>
  </div>
  <Footer />
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import MenuItem from '@/components/Menu/MenuItem.vue';
import CustomBurgerCard from '@/components/Menu/CustomBurgerCard.vue';
import { ChevronDown, ChevronUp } from 'lucide-vue-next';

interface MenuItemData {
  id: number;
  name: string;
  description: string;
  price: number;
  original_price: number | null;
  image_url: string | null;
  is_featured: boolean;
  discount_percentage: number | null;
  size: string | null;
  has_discount: boolean;
  type?: string;
}

interface Category {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  active_items: MenuItemData[];
}

interface Props {
  categories: Category[];
  favorites?: any[];
}

const props = defineProps<Props>();

const navbarRef = ref<HTMLElement | null>(null);
const navbarHeight = ref(64);

let navRO: ResizeObserver | null = null;

const favoritesCollapsed = ref(false);
const collapsedCategories = ref<number[]>([]);
const searchQuery = ref('');
const activeSection = ref('');

const toggleCategory = (id: number) => {
  const index = collapsedCategories.value.indexOf(id);
  if (index > -1) collapsedCategories.value.splice(index, 1);
  else collapsedCategories.value.push(id);
};

const searchResults = computed(() => {
  const query = searchQuery.value.trim().toLowerCase();
  if (!query) return { items: [] as MenuItemData[], customBurgers: [] as any[] };

  const items: MenuItemData[] = [];
  const customBurgers: any[] = [];
  const seenItemIds = new Set<number>();

  for (const category of props.categories) {
    for (const item of category.active_items ?? []) {
      if (item.name.toLowerCase().includes(query) || item.description?.toLowerCase().includes(query)) {
        items.push(item);
        seenItemIds.add(item.id);
      }
    }
  }

  for (const fav of props.favorites ?? []) {
    if (fav.type === 'menu_item' && !seenItemIds.has(fav.id)) {
      if (fav.name?.toLowerCase().includes(query) || fav.description?.toLowerCase().includes(query)) {
        items.push(fav);
      }
    }
    if (fav.type === 'custom_burger') {
      const nameMatch = fav.name?.toLowerCase().includes(query);
      const ingredientMatch = fav.ingredients?.some((ing: any) => ing.name?.toLowerCase().includes(query));
      if (nameMatch || ingredientMatch) customBurgers.push(fav);
    }
  }

  return { items, customBurgers };
});

const totalSearchResults = computed(() =>
  searchResults.value.items.length + searchResults.value.customBurgers.length
);

const scrollTo = (id: string) => {
  const el = document.getElementById(id);
  if (el) {
    const top = el.getBoundingClientRect().top + window.scrollY - 80;
    window.scrollTo({ top, behavior: 'smooth' });
  }
};

let observer: IntersectionObserver | null = null;

onMounted(() => {
  if (navbarRef.value) {
    navbarHeight.value = navbarRef.value.offsetHeight;
    navRO = new ResizeObserver(() => {
      navbarHeight.value = navbarRef.value!.offsetHeight;
    });
    navRO.observe(navbarRef.value);
  }

  const sectionIds = [
    ...(props.favorites?.length ? ['favorites'] : []),
    ...props.categories.map(c => `cat-${c.id}`),
  ];

  observer = new IntersectionObserver(
  (entries) => {
    const visible = entries
      .filter(e => e.isIntersecting)
      .sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top);
    if (visible.length > 0) {
      activeSection.value = visible[0].target.id;
    }
  },
  { rootMargin: '-10% 0px -80% 0px' }
);

  sectionIds.forEach(id => {
    const el = document.getElementById(id);
    if (el) observer!.observe(el);
  });
});

onUnmounted(() => {
  observer?.disconnect();
  navRO?.disconnect();
});

const addCustomBurgerToCart = (burgerId: number) => {
  router.post('/cart/add', { burger_id: burgerId, quantity: 1 } as any, {
    onSuccess: () => router.visit('/cart'),
  });
};
</script>