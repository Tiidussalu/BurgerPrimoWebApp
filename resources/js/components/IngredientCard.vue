<template>
  <div class="bg-gray-900 rounded-lg p-4 border border-gray-800">
    <!-- Ingredient Name -->
    <div class="mb-3">
      <h4 class="font-semibold text-lg">{{ ingredient.name }}</h4>
      <p class="text-orange-500 font-bold">{{ Number(ingredient.price).toFixed(2) }}€</p>
    </div>

    <!-- Quantity Controls -->
    <div class="flex items-center justify-between">
      <!-- Remove Button -->
      <button
        @click="emit('remove')"
        :disabled="quantity === 0"
        class="w-10 h-10 rounded-full bg-orange-500 hover:bg-orange-600 disabled:bg-gray-700 disabled:cursor-not-allowed flex items-center justify-center transition"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M20 12H4"
          />
        </svg>
      </button>

      <!-- Quantity Display -->
      <span class="text-2xl font-bold w-12 text-center">{{ quantity }}</span>

      <!-- Add Button -->
      <button
        @click="emit('add')"
        :disabled="quantity >= 10"
        class="w-10 h-10 rounded-full bg-orange-500 hover:bg-orange-600 disabled:bg-gray-700 disabled:cursor-not-allowed flex items-center justify-center transition"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 4v16m8-8H4"
          />
        </svg>
      </button>
    </div>

    <!-- Selected Indicator -->
    <div v-if="quantity > 0" class="mt-3 text-center">
      <span class="text-green-500 text-sm font-semibold">
        ✓ Lisatud ({{ quantity }}x)
      </span>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Ingredient } from '@/types/burger-types';

interface Props {
  ingredient: Ingredient;
  quantity: number;
}

defineProps<Props>();

const emit = defineEmits<{
  add: [];
  remove: [];
}>();
</script>