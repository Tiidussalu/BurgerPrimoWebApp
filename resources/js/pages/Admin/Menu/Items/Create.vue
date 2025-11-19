<template>
  <div class="min-h-screen bg-black text-white">
    <!-- Header -->
    <header class="border-b border-gray-800 px-6 py-4">
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <h1 class="text-2xl font-bold text-orange-500">
          {{ isEdit ? 'Muuda toodet' : 'Lisa toode' }}
        </h1>
        <Link href="/admin/menu/items" class="text-gray-400 hover:text-white transition">← Tagasi</Link>
      </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-12">
      <form @submit.prevent="submit" class="bg-gray-900 rounded-lg border border-gray-800 p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Left Column -->
          <div class="space-y-6">
            <!-- Category -->
            <div>
              <label class="block text-sm font-semibold mb-2">Kategooria *</label>
              <select
                v-model="form.category_id"
                required
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
              >
                <option value="">Vali kategooria</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
              <p v-if="errors.category_id" class="text-red-400 text-sm mt-1">{{ errors.category_id }}</p>
            </div>

            <!-- Name -->
            <div>
              <label class="block text-sm font-semibold mb-2">Toote nimi *</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
                placeholder="nt. Kebabipraad"
              />
              <p v-if="errors.name" class="text-red-400 text-sm mt-1">{{ errors.name }}</p>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-semibold mb-2">Kirjeldus *</label>
              <textarea
                v-model="form.description"
                required
                rows="4"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
                placeholder="Koostisosad ja kirjeldus..."
              ></textarea>
              <p v-if="errors.description" class="text-red-400 text-sm mt-1">{{ errors.description }}</p>
            </div>

            <!-- Size -->
            <div>
              <label class="block text-sm font-semibold mb-2">Suurus</label>
              <input
                v-model="form.size"
                type="text"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
                placeholder="nt. väike/suur"
              />
              <p v-if="errors.size" class="text-red-400 text-sm mt-1">{{ errors.size }}</p>
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-6">
            <!-- Image Upload -->
            <div>
              <label class="block text-sm font-semibold mb-2">Pilt</label>
              
              <!-- Current Image Preview -->
              <div v-if="item?.image_url && !imagePreview" class="mb-4">
                <img :src="item.image_url" alt="Current" class="w-full h-48 object-cover rounded-lg" />
                <p class="text-sm text-gray-400 mt-2">Praegune pilt</p>
              </div>

              <!-- New Image Preview -->
              <div v-if="imagePreview" class="mb-4">
                <img :src="imagePreview" alt="Preview" class="w-full h-48 object-cover rounded-lg" />
                <p class="text-sm text-gray-400 mt-2">Uus pilt (eelvaade)</p>
              </div>

              <input
                type="file"
                accept="image/*"
                @change="handleImageChange"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-orange-500 file:text-white file:cursor-pointer hover:file:bg-orange-600 transition"
              />
              <p class="text-gray-500 text-sm mt-1">JPG, PNG, GIF, WEBP (max 2MB)</p>
              <p v-if="errors.image" class="text-red-400 text-sm mt-1">{{ errors.image }}</p>
            </div>

            <!-- Price -->
            <div>
              <label class="block text-sm font-semibold mb-2">Hind *</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">€</span>
                <input
                  v-model.number="form.price"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="w-full bg-gray-800 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
                  placeholder="9.80"
                />
              </div>
              <p v-if="errors.price" class="text-red-400 text-sm mt-1">{{ errors.price }}</p>
            </div>

            <!-- Original Price -->
            <div>
              <label class="block text-sm font-semibold mb-2">Algne hind (kui on allahindlus)</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">€</span>
                <input
                  v-model.number="form.original_price"
                  type="number"
                  step="0.01"
                  min="0"
                  class="w-full bg-gray-800 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
                  placeholder="11.80"
                />
              </div>
              <p v-if="errors.original_price" class="text-red-400 text-sm mt-1">{{ errors.original_price }}</p>
            </div>

            <!-- Discount Percentage -->
            <div>
              <label class="block text-sm font-semibold mb-2">Allahindlus %</label>
              <div class="relative">
                <input
                  v-model.number="form.discount_percentage"
                  type="number"
                  min="0"
                  max="100"
                  class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
                  placeholder="20"
                />
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">%</span>
              </div>
              <p v-if="errors.discount_percentage" class="text-red-400 text-sm mt-1">{{ errors.discount_percentage }}</p>
            </div>

            <!-- Sort Order -->
            <div>
              <label class="block text-sm font-semibold mb-2">Järjekord</label>
              <input
                v-model.number="form.sort_order"
                type="number"
                min="0"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition"
                placeholder="0"
              />
              <p class="text-gray-500 text-sm mt-1">Väiksem number = kõrgem positsioon</p>
              <p v-if="errors.sort_order" class="text-red-400 text-sm mt-1">{{ errors.sort_order }}</p>
            </div>
          </div>
        </div>

        <!-- Checkboxes -->
        <div class="mt-6 space-y-4">
          <label class="flex items-center cursor-pointer">
            <input
              v-model="form.is_featured"
              type="checkbox"
              class="w-5 h-5 rounded border-gray-600 bg-gray-700 text-orange-500 focus:ring-orange-500 focus:ring-offset-gray-900 cursor-pointer"
            />
            <span class="ml-3 font-semibold">⭐ Populaarne toode</span>
          </label>

          <label class="flex items-center cursor-pointer">
            <input
              v-model="form.is_active"
              type="checkbox"
              class="w-5 h-5 rounded border-gray-600 bg-gray-700 text-orange-500 focus:ring-orange-500 focus:ring-offset-gray-900 cursor-pointer"
            />
            <span class="ml-3 font-semibold">Toode on aktiivne</span>
          </label>
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-4 mt-8">
          <button
            type="submit"
            :disabled="form.processing"
            class="flex-1 bg-orange-500 hover:bg-orange-600 disabled:bg-gray-700 disabled:cursor-not-allowed text-white px-6 py-3 rounded-lg font-semibold transition"
          >
            {{ form.processing ? 'Salvestamine...' : (isEdit ? 'Salvesta muudatused' : 'Lisa toode') }}
          </button>
          <Link
            href="/admin/menu/items"
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
import { computed, ref } from 'vue';

interface Category {
  id: number;
  name: string;
}

interface MenuItem {
  id: number;
  category_id: number;
  name: string;
  description: string;
  price: number;
  original_price: number | null;
  image: string | null;
  image_url: string | null;
  is_featured: boolean;
  is_active: boolean;
  sort_order: number;
  size: string | null;
  discount_percentage: number | null;
}

interface Props {
  item?: MenuItem;
  categories: Category[];
  errors?: Record<string, string>;
}

const props = defineProps<Props>();
const errors = props.errors || {};

const isEdit = computed(() => !!props.item);

const form = useForm({
  category_id: props.item?.category_id || '',
  name: props.item?.name || '',
  description: props.item?.description || '',
  price: props.item?.price || 0,
  original_price: props.item?.original_price || null,
  image: null as File | null,
  is_featured: props.item?.is_featured ?? false,
  is_active: props.item?.is_active ?? true,
  sort_order: props.item?.sort_order || 0,
  size: props.item?.size || '',
  discount_percentage: props.item?.discount_percentage || null,
});

const imagePreview = ref<string | null>(null);

const handleImageChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  
  if (file) {
    form.image = file;
    
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
};

const submit = () => {
  if (isEdit.value) {
    form.post(`/admin/menu/items/${props.item!.id}` as any, {
      preserveScroll: true,
      forceFormData: true,
      onSuccess: () => {
        imagePreview.value = null;
      },
    });
  } else {
    form.post('/admin/menu/items' as any, {
      preserveScroll: true,
      forceFormData: true,
      onSuccess: () => {
        imagePreview.value = null;
      },
    });
  }
};
</script>