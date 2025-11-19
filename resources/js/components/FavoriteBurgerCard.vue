<template>
  <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
    <div class="flex items-start justify-between mb-4">
      <div>
        <h4 class="font-bold text-xl mb-1">{{ burger.name }}</h4>
        <p class="text-orange-500 font-bold text-lg">{{ burger.total_price.toFixed(2) }}€</p>
      </div>
      <span class="text-yellow-500 text-2xl">⭐</span>
    </div>

    <!-- Ingredients List -->
    <div class="mb-4 space-y-1">
      <p class="text-sm text-gray-400 font-semibold mb-2">Koostisained:</p>
      <div v-for="ingredient in burger.ingredients" :key="ingredient.id" class="text-sm text-gray-300">
        {{ ingredient.pivot.quantity }}x {{ ingredient.name }}
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-2">
      <button
        @click="emit('load', burger)"
        class="flex-1 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded font-semibold transition"
      >
        Muuda
      </button>
      <button
        @click="emit('order', burger.id)"
        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold transition"
      >
        Telli
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { CustomBurger } from '@/types/burger-types';

interface Props {
  burger: CustomBurger;
}

defineProps<Props>();

const emit = defineEmits<{
  load: [burger: CustomBurger];
  order: [burgerId: number];
}>();
</script>