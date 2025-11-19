<template>
  <div class="min-h-screen bg-black text-white">
    <!-- Header -->
    <header class="border-b border-gray-800 px-6 py-4">
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <h1 class="text-2xl font-bold text-orange-500">
          {{ isEdit ? 'Muuda kategooriat' : 'Lisa kategooria' }}
        </h1>
        <Link href="/admin/menu/categories" class="text-gray-400 hover:text-white transition">← Tagasi</Link>
      </div>
    </header>

    <main class="max-w-3xl mx-auto px-6 py-12">
      <form @submit.prevent="submit" class="bg-gray-900 rounded-lg border border-gray-800 p-8">
        <!-- Name -->
        <div class="mb-6">
          <label class="block text-sm font-semibold mb-2">Nimi *</label>
          <input
            v-model="form.name"
            type="text"
            required
            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
            placeholder="nt. BURGERID"
          />
          <p v-if="errors.name" class="text-red-400 text-sm mt-1">{{ errors.name }}</p>
        </div>

        <!-- Slug -->
        <div class="mb-6">
          <label class="block text-sm font-semibold mb-2">
            Slug <span class="text-gray-500">(jäta tühjaks automaatseks genereerimiseks)</span>
          </label>
          <input
            v-model="form.slug"
            type="text"
            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
            placeholder="nt. burgerid"
          />
          <p class="text-gray-500 text-sm mt-1">URL-i sõbralik nimi (kasutab automaatselt nime)</p>
          <p v-if="errors.slug" class="text-red-400 text-sm mt-1">{{ errors.slug }}</p>
        </div>

        <!-- Description -->
        <div class="mb-6">
          <label class="block text-sm font-semibold mb-2">Kirjeldus</label>
          <textarea
            v-model="form.description"
            rows="3"
            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
            placeholder="Kategooria lühikirjeldus..."
          ></textarea>
          <p v-if="errors.description" class="text-red-400 text-sm mt-1">{{ errors.description }}</p>
        </div>

        <!-- Sort Order -->
        <div class="mb-6">
          <label class="block text-sm font-semibold mb-2">Järjekord</label>
          <input
            v-model.number="form.sort_order"
            type="number"
            min="0"
            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
            placeholder="0"
          />
          <p class="text-gray-500 text-sm mt-1">Väiksem number = kõrgem positsioon menüüs</p>
          <p v-if="errors.sort_order" class="text-red-400 text-sm mt-1">{{ errors.sort_order }}</p>
        </div>

        <!-- Is Active -->
        <div class="mb-8">
          <label class="flex items-center cursor-pointer">
            <input
              v-model="form.is_active"
              type="checkbox"
              class="w-5 h-5 rounded border-gray-600 bg-gray-700 text-orange-500 focus:ring-orange-500 focus:ring-offset-gray-900 cursor-pointer"
            />
            <span class="ml-3 font-semibold">Kategooria on aktiivne</span>
          </label>
          <p class="text-gray-500 text-sm mt-1 ml-8">Mitteaktiivseid kategooriaid ei näidata klientidele</p>
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-4">
          <button
            type="submit"
            :disabled="processing"
            class="flex-1 bg-orange-500 hover:bg-orange-600 disabled:bg-gray-700 disabled:cursor-not-allowed text-white px-6 py-3 rounded-lg font-semibold transition"
          >
            {{ processing ? 'Salvestamine...' : (isEdit ? 'Salvesta muudatused' : 'Lisa kategooria') }}
          </button>
          <Link
            href="/admin/menu/categories"
            class="flex-1 bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition text-center"
          >
            Tühista
          </Link>
        </div>
      </form>
    </main>
  </div>
</template>

<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { computed, withDefaults } from 'vue';

interface Category {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  sort_order: number;
  is_active: boolean;
}
interface Props {
  category?: Category;
  errors?: Record<string, string>;
}

const props = withDefaults(defineProps<Props>(), {
  errors: () => ({}),
});

const isEdit = computed(() => !!props.category);

const form = useForm({
  name: props.category?.name || '',
  slug: props.category?.slug || '',
  description: props.category?.description || '',
  sort_order: props.category?.sort_order || 0,
  is_active: props.category?.is_active ?? true,
});

const processing = computed(() => form.processing);

const submit = () => {
  if (isEdit.value) {
    form.put(`/admin/menu/categories/${props.category!.id}` as any, {
      preserveScroll: true,
    });
  } else {
    form.post('/admin/menu/categories' as any, {
      preserveScroll: true,
    });
  }
};
</script>