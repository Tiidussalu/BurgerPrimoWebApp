<template>
  <aside
    class="fixed left-0 bottom-0 w-56 bg-[#0B0B0B] z-20 hidden lg:flex flex-col justify-center px-4 gap-1"
    :style="{ top: navbarHeight + 'px' }"
  >
    <!-- Search -->
    <div class="relative mb-2">
      <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
        </svg>
      </div>
      <input
        :value="modelValue"
        @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
        type="text"
        placeholder="Otsi menüüst..."
        class="w-full bg-[#121212] border border-[#1a1a1a] focus:border-[#D2691E]/50 rounded-xl pl-9 pr-8 py-2.5 text-sm text-white placeholder-gray-600 outline-none transition-all"
      />
      <button
        v-if="modelValue"
        @click="$emit('update:modelValue', '')"
        class="absolute inset-y-0 right-2.5 flex items-center text-gray-500 hover:text-white transition-colors"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Category nav -->
    <nav class="flex flex-col gap-1">
      <button
        v-if="hasFavorites"
        @click="$emit('scrollTo', 'favorites')"
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
        @click="$emit('scrollTo', `cat-${category.id}`)"
        :class="[
          'w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold transition-all uppercase tracking-wide',
          activeSection === `cat-${category.id}`
            ? 'bg-[#D2691E] text-white'
            : 'text-gray-400 hover:text-white hover:bg-[#1a1a1a]'
        ]"
      >{{ category.name }}</button>
    </nav>
  </aside>
</template>

<script setup lang="ts">
interface Category {
  id: number;
  name: string;
}

defineProps<{
  categories: Category[];
  hasFavorites: boolean;
  activeSection: string;
  modelValue: string;
  navbarHeight: number;
}>();

defineEmits<{
  'update:modelValue': [value: string];
  'scrollTo': [id: string];
}>();
</script>