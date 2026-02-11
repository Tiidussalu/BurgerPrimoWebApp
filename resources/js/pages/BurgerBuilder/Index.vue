<template>
  <MainLayout>
    <div class="min-h-screen bg-[#0B0B0B] text-white">
      <!-- Main Content -->
      <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <!-- Title Section -->
        <div class="mb-12">
          <h2 class="text-4xl font-bold mb-2">Ehita enda burger</h2>
          <p class="text-gray-400">Lisa enda unistuste burger</p>
        </div>

        <!-- Burger Name Input -->
        <div class="mb-8">
          <label class="block text-sm mb-2 font-semibold">Burgeri nimi</label>
          <input
            v-model="burgerName"
            type="text"
            placeholder="Sisesta burgeri nimi"
            class="w-full max-w-md bg-[#121212] border border-[#0B0B0B] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#D2691E]"
          />
        </div>

        <!-- Ingredients Grid -->
        <div class="space-y-12">
          <!-- Vöi Section -->
          <IngredientSection
            v-if="ingredients['vöi']"
            title="Vöi"
            subtitle="Vali endale sobiv kastee"
            :items="ingredients['vöi']"
            :selected="selectedIngredients['vöi'] || []"
            @update="(items) => updateIngredients('vöi', items)"
          />

          <!-- Pitav Section -->
          <IngredientSection
            v-if="ingredients.pitav"
            title="Pitav"
            subtitle="Vali endale sobiv pitav"
            :items="ingredients.pitav"
            :selected="selectedIngredients.pitav || []"
            @update="(items) => updateIngredients('pitav', items)"
          />

          <!-- Juust Section -->
          <IngredientSection
            v-if="ingredients.juust"
            title="Juust"
            subtitle="Vali endale sobiv juust"
            :items="ingredients.juust"
            :selected="selectedIngredients.juust || []"
            @update="(items) => updateIngredients('juust', items)"
          />

          <!-- Salat Section -->
          <IngredientSection
            v-if="ingredients.salat"
            title="Salat"
            subtitle="Vali endale sobiv salat"
            :items="ingredients.salat"
            :selected="selectedIngredients.salat || []"
            @update="(items) => updateIngredients('salat', items)"
          />

          <!-- Lisand Section -->
          <IngredientSection
            v-if="ingredients.lisand"
            title="Lisand"
            subtitle="Vali endale sobiv lisand"
            :items="ingredients.lisand"
            :selected="selectedIngredients.lisand || []"
            @update="(items) => updateIngredients('lisand', items)"
          />
        </div>

        <!-- Action Buttons -->
        <div class="mt-12 flex flex-wrap gap-4">
          <button
            @click="saveBurger(false)"
            :disabled="!canSave"
            class="bg-gradient-to-r from-[#D2691E] to-[#B8571A] hover:from-[#E07A2E] hover:to-[#D2691E] disabled:bg-gray-700 disabled:cursor-not-allowed text-white px-8 py-3 rounded-lg font-semibold transition shadow-lg hover:shadow-[#D2691E]/50"
          >
            Salvesta burger
          </button>
          <button
            @click="saveBurger(true)"
            :disabled="!canSave"
            class="bg-transparent border-2 border-[#D2691E] hover:bg-[#D2691E]/10 disabled:border-gray-700 disabled:cursor-not-allowed text-white px-8 py-3 rounded-lg font-semibold transition"
          >
            Lisa lemmikutesse
          </button>
          <button
            @click="orderBurger"
            :disabled="!canSave"
            class="bg-green-600 hover:bg-green-700 disabled:bg-gray-700 disabled:cursor-not-allowed text-white px-8 py-3 rounded-lg font-semibold transition"
          >
            Lisa ostukorvi ({{ totalPrice.toFixed(2) }}€)
          </button>
        </div>

        <!-- Total Price Display -->
        <div class="mt-8 text-2xl font-bold">
          Kogusumma: <span class="text-[#D2691E]">{{ totalPrice.toFixed(2) }}€</span>
        </div>

        <!-- Favorites Section -->
        <div v-if="favorites.length > 0" class="mt-16">
          <h3 class="text-2xl font-bold mb-6">Lemmik burgerid</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <FavoriteBurgerCard
              v-for="burger in favorites"
              :key="burger.id"
              :burger="burger"
              @load="loadBurger"
              @order="orderFavorite"
            />
          </div>
        </div>
      </main>
    </div>
  </MainLayout>
</template>

<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import IngredientSection from '@/components/IngredientSection.vue';
import FavoriteBurgerCard from '@/components/FavoriteBurgerCard.vue';
import type { Ingredient, SelectedIngredient, CustomBurger } from '@/types/burger-types';

interface Props {
  ingredients: Record<string, Ingredient[]>;
  favorites: CustomBurger[];
}

const props = defineProps<Props>();

const burgerName = ref('');
const selectedIngredients = ref<Record<string, SelectedIngredient[]>>({
  'vöi': [],
  pitav: [],
  juust: [],
  salat: [],
  lisand: [],
});

const updateIngredients = (category: string, items: SelectedIngredient[]) => {
  selectedIngredients.value[category] = items;
};

const totalPrice = computed(() => {
  let total = 0;
  Object.values(selectedIngredients.value).forEach((categoryItems) => {
    categoryItems.forEach((item) => {
      const ingredient = Object.values(props.ingredients)
        .flat()
        .find((ing) => ing.id === item.id);
      if (ingredient) {
        total += Number(ingredient.price) * item.quantity;
      }
    });
  });
  return total;
});

const canSave = computed(() => {
  return burgerName.value.trim() !== '' && 
         Object.values(selectedIngredients.value).some(items => items.length > 0);
});

const getAllSelectedIngredients = (): SelectedIngredient[] => {
  const allIngredients: SelectedIngredient[] = [];
  Object.values(selectedIngredients.value).forEach((categoryItems) => {
    allIngredients.push(...categoryItems);
  });
  return allIngredients;
};

const saveBurger = (isFavorite: boolean) => {
  router.post('/burger-builder', {
    name: burgerName.value,
    ingredients: getAllSelectedIngredients(),
    is_favorite: isFavorite,
  } as any);
};

const orderBurger = () => {
  router.post('/cart/add-new', {
    name: burgerName.value,
    ingredients: getAllSelectedIngredients(),
  } as any, {
    onSuccess: () => {
      router.visit('/cart');
    },
  });
};

const loadBurger = (burger: CustomBurger) => {
  burgerName.value = burger.name;
  selectedIngredients.value = {
    'vöi': [],
    pitav: [],
    juust: [],
    salat: [],
    lisand: [],
  };
  burger.ingredients.forEach((ingredient) => {
    const category = ingredient.category;
    if (selectedIngredients.value[category]) {
      selectedIngredients.value[category].push({
        id: ingredient.id,
        quantity: ingredient.pivot.quantity,
      });
    }
  });
};

const orderFavorite = (burgerId: number) => {
  router.post('/cart/add', {
    burger_id: burgerId,
    quantity: 1,
  } as any);
};
</script>