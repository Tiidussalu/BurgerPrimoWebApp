<template>
  <div>
    <div class="mb-6">
      <h3 class="text-2xl font-bold mb-1">{{ title }}</h3>
      <p class="text-gray-400 text-sm">{{ subtitle }}</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <IngredientCard
        v-for="item in items"
        :key="item.id"
        :ingredient="item"
        :quantity="getQuantity(item.id)"
        @add="addIngredient(item.id)"
        @remove="removeIngredient(item.id)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import IngredientCard from '@/components/IngredientCard.vue';
import type { Ingredient, SelectedIngredient } from '@/types/burger-types';

interface Props {
  title: string;
  subtitle: string;
  items: Ingredient[];
  selected: SelectedIngredient[];
}

const props = defineProps<Props>();
const emit = defineEmits<{
  update: [items: SelectedIngredient[]];
}>();

const getQuantity = (ingredientId: number): number => {
  const item = props.selected.find((s) => s.id === ingredientId);
  return item ? item.quantity : 0;
};

const addIngredient = (ingredientId: number) => {
  const existingIndex = props.selected.findIndex((s) => s.id === ingredientId);
  const newSelected = [...props.selected];

  if (existingIndex >= 0) {
    // Increment quantity (max 10)
    if (newSelected[existingIndex].quantity < 10) {
      newSelected[existingIndex] = {
        ...newSelected[existingIndex],
        quantity: newSelected[existingIndex].quantity + 1,
      };
    }
  } else {
    // Add new ingredient
    newSelected.push({ id: ingredientId, quantity: 1 });
  }

  emit('update', newSelected);
};

const removeIngredient = (ingredientId: number) => {
  const existingIndex = props.selected.findIndex((s) => s.id === ingredientId);
  if (existingIndex === -1) return;

  const newSelected = [...props.selected];

  if (newSelected[existingIndex].quantity > 1) {
    // Decrement quantity
    newSelected[existingIndex] = {
      ...newSelected[existingIndex],
      quantity: newSelected[existingIndex].quantity - 1,
    };
  } else {
    // Remove ingredient
    newSelected.splice(existingIndex, 1);
  }

  emit('update', newSelected);
};
</script>