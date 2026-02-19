<template>
  <div class="bg-[#121212] hover:bg-[#161616] border border-[#1a1a1a] hover:border-[#D2691E]/30 rounded-xl overflow-hidden transition-all duration-200 flex items-stretch min-h-[110px]">
    <div class="w-1 flex-shrink-0 bg-gradient-to-b from-[#D2691E] to-[#B8571A]"></div>
    <div class="flex-1 p-4 flex flex-col justify-between min-w-0">
      <div>
        <span class="text-xs bg-[#D2691E]/15 text-[#D2691E] px-2 py-0.5 rounded-full font-semibold">🍔 Kohandatud</span>
        <h3 class="font-bold text-white text-sm mt-2 leading-snug">{{ burger.name }}</h3>
        <p class="text-xs text-gray-500 mt-1 line-clamp-1">
          {{ burger.ingredients?.slice(0, 4).map((i: any) => i.name).join(', ') }}<span v-if="burger.ingredients?.length > 4"> +{{ burger.ingredients.length - 4 }} veel</span>
        </p>
      </div>
      <div class="flex items-center justify-between mt-3">
        <span class="font-bold text-white text-sm">€{{ Number(burger.total_price).toFixed(2) }}</span>
        <button
          @click="emit('addToCart', burger.id)"
          class="flex items-center gap-1.5 px-3 py-1.5 bg-[#D2691E] hover:bg-[#E07A2E] text-white rounded-lg text-xs font-semibold transition-all"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          Lisa korvi
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{ burger: any }>();
const emit = defineEmits<{ addToCart: [id: number] }>();
</script>