<template>
  <div class="min-h-screen bg-black text-white">
    <!-- Header -->
    <header class="border-b border-gray-800 px-6 py-4">
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <h1 class="text-2xl font-bold text-orange-500">Admin - Menüü kategooriad</h1>
        <Link href="/admin/dashboard" class="text-gray-400 hover:text-white transition">← Tagasi</Link>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-12">
      <!-- Header with Add Button -->
      <div class="flex items-center justify-between mb-8">
        <div>
          <h2 class="text-3xl font-bold mb-2">Menüü kategooriad</h2>
          <p class="text-gray-400">Halda menüü kategooriaid</p>
        </div>
        <Link
          href="/admin/menu/categories/create"
          class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold transition flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Lisa kategooria
        </Link>
      </div>

      <!-- Categories Table -->
      <div v-if="categories.length > 0" class="bg-gray-900 rounded-lg border border-gray-800 overflow-hidden">
        <table class="w-full">
          <thead class="bg-gray-800">
            <tr>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Järjekord</th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Nimi</th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Slug</th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Tooteid</th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Staatus</th>
              <th class="px-6 py-4 text-right text-sm font-semibold text-gray-400">Tegevused</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-800">
            <tr
              v-for="category in categories"
              :key="category.id"
              class="hover:bg-gray-800/50 transition"
            >
              <td class="px-6 py-4">
                <span class="text-gray-400 font-mono">{{ category.sort_order }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="font-semibold">{{ category.name }}</span>
              </td>
              <td class="px-6 py-4">
                <code class="text-sm text-orange-400 bg-gray-800 px-2 py-1 rounded">{{ category.slug }}</code>
              </td>
              <td class="px-6 py-4">
                <span class="text-gray-400">{{ category.items_count }} toode(t)</span>
              </td>
              <td class="px-6 py-4">
                <span
                  :class="[
                    'px-3 py-1 rounded-full text-xs font-semibold',
                    category.is_active
                      ? 'bg-green-900/30 text-green-400'
                      : 'bg-gray-700 text-gray-400'
                  ]"
                >
                  {{ category.is_active ? 'Aktiivne' : 'Mitteaktiivne' }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center justify-end gap-2">
                  <Link
                    :href="`/admin/menu/categories/${category.id}/edit`"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold transition text-sm"
                  >
                    Muuda
                  </Link>
                  <button
                    @click="deleteCategory(category.id)"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded font-semibold transition text-sm"
                  >
                    Kustuta
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16 bg-gray-900 rounded-lg border border-gray-800">
        <div class="text-6xl mb-4">📂</div>
        <h3 class="text-2xl font-bold mb-2">Kategooriaid pole veel</h3>
        <p class="text-gray-400 mb-6">Lisa oma esimene menüü kategooria</p>
        <Link
          href="/admin/menu/categories/create"
          class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded font-semibold transition"
        >
          Lisa kategooria
        </Link>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';

interface Category {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  sort_order: number;
  is_active: boolean;
  items_count: number;
}

interface Props {
  categories: Category[];
}

defineProps<Props>();

const deleteCategory = (categoryId: number) => {
  if (confirm('Kas oled kindel, et soovid selle kategooria kustutada?')) {
    router.delete(`/admin/menu/categories/${categoryId}` as any, {
      preserveScroll: true,
    });
  }
};
</script>