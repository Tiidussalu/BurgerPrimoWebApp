<template>
  <div class="min-h-screen bg-[#0B0B0B] text-white">
    <Navbar />

    <!-- Hero Section -->
    <section class="relative min-h-[600px] flex items-center justify-center overflow-hidden">
      <EditableSection section-id="hero" @save="saveHero" @cancel="cancelHero">
        <template #default="{ isEditing }">
          <!-- Debug indicator -->
          <div v-if="isEditing" class="fixed top-4 left-4 z-[999] bg-red-500 text-white px-4 py-2 rounded font-bold">
            EDIT MODE ACTIVE - HERO
          </div>
          
          <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black"></div>
          </div>
          
          <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 text-center">
            <h1 
              v-if="!isEditing"
              class="text-5xl font-bold mb-4"
              :style="{ color: content.hero.titleColor }"
            >
              {{ content.hero.title }}
            </h1>
            <div v-else class="mb-4 space-y-2">
              <input
                v-model="editContent.hero.title"
                type="text"
                placeholder="Enter title..."
                class="w-full max-w-2xl mx-auto p-3 bg-gray-800 text-white rounded border-2 border-white"
              />
              <div class="flex gap-2 justify-center items-center">
                <label class="text-sm">Title color:</label>
                <input
                  v-model="editContent.hero.titleColor"
                  type="color"
                  class="p-2 bg-gray-800 rounded border-2 border-white"
                />
              </div>
            </div>

            <p 
              v-if="!isEditing"
              class="text-lg mb-8"
              :style="{ color: content.hero.subtitleColor }"
            >
              {{ content.hero.subtitle }}
            </p>
            <div v-else class="mb-8 space-y-2">
              <textarea
                v-model="editContent.hero.subtitle"
                placeholder="Enter subtitle..."
                class="w-full max-w-2xl mx-auto p-3 bg-gray-800 text-white rounded border-2 border-white"
                rows="2"
              ></textarea>
              <div class="flex gap-2 justify-center items-center">
                <label class="text-sm">Subtitle color:</label>
                <input
                  v-model="editContent.hero.subtitleColor"
                  type="color"
                  class="p-2 bg-gray-800 rounded border-2 border-white"
                />
              </div>
            </div>

            <div v-if="!isEditing">
              <Link
                :href="content.hero.buttonLink"
                class="inline-block px-8 py-3 rounded-lg font-semibold transition"
                :style="{ backgroundColor: content.hero.buttonBgColor, color: '#FFFFFF' }"
              >
                {{ content.hero.buttonText }}
              </Link>
            </div>
            <div v-else class="space-y-2">
              <input
                v-model="editContent.hero.buttonText"
                type="text"
                placeholder="Button text..."
                class="w-full max-w-md mx-auto p-2 bg-gray-800 text-white rounded border-2 border-white"
              />
              <input
                v-model="editContent.hero.buttonLink"
                type="text"
                placeholder="Button link..."
                class="w-full max-w-md mx-auto p-2 bg-gray-800 text-white rounded border-2 border-white"
              />
              <div class="flex gap-2 justify-center">
                <label class="text-sm">Button color:</label>
                <input
                  v-model="editContent.hero.buttonBgColor"
                  type="color"
                  class="p-1 bg-gray-800 rounded border-2 border-white"
                />
              </div>
            </div>
          </div>
        </template>
      </EditableSection>
    </section>

    <!-- Popular Section -->
    <section class="max-w-7xl mx-auto px-6 py-20">
      <EditableSection section-id="popular" @save="savePopular" @cancel="cancelPopular">
        <template #default="{ isEditing }">
          <!-- Debug indicator -->
          <div v-if="isEditing" class="fixed top-4 left-4 z-[999] bg-blue-500 text-white px-4 py-2 rounded font-bold">
            EDIT MODE ACTIVE - POPULAR
          </div>
          
          <div class="text-center mb-12">
            <h2 
              v-if="!isEditing"
              class="text-sm uppercase mb-2"
              :style="{ color: content.popular.labelColor }"
            >
              {{ content.popular.label }}
            </h2>
            <div v-else class="mb-2 space-y-2">
              <input
                v-model="editContent.popular.label"
                type="text"
                placeholder="Label..."
                class="w-full max-w-md mx-auto p-2 bg-gray-800 text-white rounded border-2 border-white"
              />
              <div class="flex gap-2 justify-center items-center">
                <label class="text-sm">Label color:</label>
                <input
                  v-model="editContent.popular.labelColor"
                  type="color"
                  class="p-1 bg-gray-800 rounded border-2 border-white"
                />
              </div>
            </div>
            
            <h3 
              v-if="!isEditing"
              class="text-4xl font-bold mb-4"
              :style="{ color: content.popular.titleColor }"
            >
              {{ content.popular.title }}
            </h3>
            <div v-else class="mb-4 space-y-2">
              <input
                v-model="editContent.popular.title"
                type="text"
                placeholder="Title..."
                class="w-full max-w-md mx-auto p-3 bg-gray-800 text-white rounded border-2 border-white"
              />
              <div class="flex gap-2 justify-center items-center">
                <label class="text-sm">Title color:</label>
                <input
                  v-model="editContent.popular.titleColor"
                  type="color"
                  class="p-1 bg-gray-800 rounded border-2 border-white"
                />
              </div>
            </div>

            <p 
              v-if="!isEditing"
              class="text-gray-400"
            >
              {{ content.popular.subtitle }}
            </p>
            <textarea
              v-else
              v-model="editContent.popular.subtitle"
              placeholder="Subtitle..."
              class="w-full max-w-md mx-auto p-3 bg-gray-800 text-white rounded border-2 border-white mb-4"
              rows="2"
            ></textarea>

            <div v-if="!isEditing">
              <Link
                :href="content.popular.buttonLink"
                class="inline-block mt-8 px-8 py-3 rounded-lg font-semibold transition"
                :style="{ backgroundColor: content.popular.buttonBgColor, color: '#FFFFFF' }"
              >
                {{ content.popular.buttonText }}
              </Link>
            </div>
            <div v-else class="space-y-2 mt-4">
              <input
                v-model="editContent.popular.buttonText"
                type="text"
                placeholder="Button text..."
                class="w-full max-w-md mx-auto p-2 bg-gray-800 text-white rounded border-2 border-white"
              />
              <input
                v-model="editContent.popular.buttonLink"
                type="text"
                placeholder="Button link..."
                class="w-full max-w-md mx-auto p-2 bg-gray-800 text-white rounded border-2 border-white"
              />
              <div class="flex gap-2 justify-center">
                <label class="text-sm">Button color:</label>
                <input
                  v-model="editContent.popular.buttonBgColor"
                  type="color"
                  class="p-1 bg-gray-800 rounded border-2 border-white"
                />
              </div>
            </div>
          </div>
        </template>
      </EditableSection>
    </section>

    <!-- Contact Section -->
    <section class="bg-[#121212] py-20">
      <div class="max-w-7xl mx-auto px-6">
        <EditableSection section-id="contact" @save="saveContact" @cancel="cancelContact">
          <template #default="{ isEditing }">
            <!-- Debug indicator -->
            <div v-if="isEditing" class="fixed top-4 left-4 z-[999] bg-purple-500 text-white px-4 py-2 rounded font-bold">
              EDIT MODE ACTIVE - CONTACT
            </div>
            
            <div class="text-center mb-12">
              <h2 
                v-if="!isEditing"
                class="text-sm uppercase mb-2"
                :style="{ color: content.contact.labelColor }"
              >
                {{ content.contact.label }}
              </h2>
              <div v-else class="mb-2 space-y-2">
                <input
                  v-model="editContent.contact.label"
                  type="text"
                  placeholder="Label..."
                  class="w-full max-w-md mx-auto p-2 bg-gray-800 text-white rounded border-2 border-white"
                />
                <div class="flex gap-2 justify-center items-center">
                  <label class="text-sm">Label color:</label>
                  <input
                    v-model="editContent.contact.labelColor"
                    type="color"
                    class="p-1 bg-gray-800 rounded border-2 border-white"
                  />
                </div>
              </div>
              
              <h3 
                v-if="!isEditing"
                class="text-4xl font-bold"
                :style="{ color: content.contact.titleColor }"
              >
                {{ content.contact.title }}
              </h3>
              <div v-else class="space-y-2">
                <input
                  v-model="editContent.contact.title"
                  type="text"
                  placeholder="Title..."
                  class="w-full max-w-md mx-auto p-3 bg-gray-800 text-white rounded border-2 border-white"
                />
                <div class="flex gap-2 justify-center items-center">
                  <label class="text-sm">Title color:</label>
                  <input
                    v-model="editContent.contact.titleColor"
                    type="color"
                    class="p-1 bg-gray-800 rounded border-2 border-white"
                  />
                </div>
              </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
              <div>
                <h4 
                  v-if="!isEditing"
                  class="text-xl font-bold mb-4"
                >
                  {{ content.contact.addressTitle }}
                </h4>
                <input
                  v-else
                  v-model="editContent.contact.addressTitle"
                  type="text"
                  placeholder="Address title..."
                  class="w-full p-2 bg-gray-800 text-white rounded border-2 border-white mb-4"
                />
                
                <div v-if="!isEditing" class="text-gray-300 space-y-2">
                  <p v-for="(line, i) in content.contact.address" :key="i">{{ line }}</p>
                </div>
                <div v-else class="space-y-2">
                  <input
                    v-for="(line, i) in editContent.contact.address"
                    :key="i"
                    v-model="editContent.contact.address[i]"
                    type="text"
                    :placeholder="`Address line ${i + 1}`"
                    class="w-full p-2 bg-gray-800 text-white rounded border-2 border-white"
                  />
                </div>
              </div>
              <div>
                <h4 
                  v-if="!isEditing"
                  class="text-xl font-bold mb-4"
                >
                  {{ content.contact.hoursTitle }}
                </h4>
                <input
                  v-else
                  v-model="editContent.contact.hoursTitle"
                  type="text"
                  placeholder="Hours title..."
                  class="w-full p-2 bg-gray-800 text-white rounded border-2 border-white mb-4"
                />
                
                <div v-if="!isEditing" class="text-gray-300 space-y-2">
                  <p v-for="(line, i) in content.contact.hours" :key="i">{{ line }}</p>
                </div>
                <div v-else class="space-y-2">
                  <input
                    v-for="(line, i) in editContent.contact.hours"
                    :key="i"
                    v-model="editContent.contact.hours[i]"
                    type="text"
                    :placeholder="`Hours line ${i + 1}`"
                    class="w-full p-2 bg-gray-800 text-white rounded border-2 border-white"
                  />
                </div>
              </div>
            </div>
          </template>
        </EditableSection>
      </div>
    </section>

    <!-- Reviews Section -->
    <section class="max-w-7xl mx-auto px-6 py-20">
      <EditableSection section-id="reviews" @save="saveReviews" @cancel="cancelReviews">
        <template #default="{ isEditing }">
          <!-- Debug indicator -->
          <div v-if="isEditing" class="fixed top-4 left-4 z-[999] bg-yellow-500 text-black px-4 py-2 rounded font-bold">
            EDIT MODE ACTIVE - REVIEWS
          </div>
          
          <div class="text-center mb-12">
            <h2 
              v-if="!isEditing"
              class="text-sm uppercase mb-2"
              :style="{ color: content.reviews.labelColor }"
            >
              {{ content.reviews.label }}
            </h2>
            <div v-else class="mb-2 space-y-2">
              <input
                v-model="editContent.reviews.label"
                type="text"
                placeholder="Label..."
                class="w-full max-w-md mx-auto p-2 bg-gray-800 text-white rounded border-2 border-white"
              />
              <div class="flex gap-2 justify-center items-center">
                <label class="text-sm">Label color:</label>
                <input
                  v-model="editContent.reviews.labelColor"
                  type="color"
                  class="p-1 bg-gray-800 rounded border-2 border-white"
                />
              </div>
            </div>
            
            <h3 
              v-if="!isEditing"
              class="text-4xl font-bold"
              :style="{ color: content.reviews.titleColor }"
            >
              {{ content.reviews.title }}
            </h3>
            <div v-else class="space-y-2">
              <input
                v-model="editContent.reviews.title"
                type="text"
                placeholder="Title..."
                class="w-full max-w-md mx-auto p-3 bg-gray-800 text-white rounded border-2 border-white"
              />
              <div class="flex gap-2 justify-center items-center">
                <label class="text-sm">Title color:</label>
                <input
                  v-model="editContent.reviews.titleColor"
                  type="color"
                  class="p-1 bg-gray-800 rounded border-2 border-white"
                />
              </div>
            </div>
          </div>
          
          <div class="grid md:grid-cols-3 gap-6">
            <div v-for="i in 3" :key="i" class="bg-[#121212] p-6 rounded-lg">
              <div class="flex gap-1 mb-4">
                <span v-for="j in 5" :key="j" class="text-[#D2691E]">★</span>
              </div>
              <p class="text-gray-300 mb-4">Parim burger mida olen söönud!</p>
              <p class="text-sm text-gray-400">Klient</p>
            </div>
          </div>
        </template>
      </EditableSection>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';
import EditableSection from '@/components/EditableSection.vue';

const content = ref({
  hero: {
    title: 'Meile oluliselt premeeritud burgerid meie aastakümnete kogemuse tulemus',
    titleColor: '#FFFFFF',
    subtitle: 'Kõrgeim nivoo kvaliteet ja armastus',
    subtitleColor: '#D2691E',
    buttonText: 'Avasta menüüd',
    buttonLink: '/menu',
    buttonBgColor: '#D2691E',
  },
  popular: {
    label: 'MEIE VALIK',
    labelColor: '#D2691E',
    title: 'Populaarsed',
    titleColor: '#FFFFFF',
    subtitle: 'Proovi meie menüü ja valitud tellitud roogud',
    buttonText: 'Avasta kogu menüüd',
    buttonLink: '/menu',
    buttonBgColor: '#D2691E',
  },
  contact: {
    label: 'LOC MEID',
    labelColor: '#D2691E',
    title: 'Külasta Meid',
    titleColor: '#FFFFFF',
    addressTitle: 'Meie Asukoht',
    address: ['Burger Primo', 'Rannamäe tee 5', '+372 543 9483', 'info@burgerprimo.ee'],
    hoursTitle: 'Lahtiolekuajad',
    hours: ['Esmaspäev - Reede: 10:00 - 22:00', 'Laupäev - Pühapäev: 12:00 - 23:00'],
  },
  reviews: {
    label: 'ARVUSTUSED',
    labelColor: '#D2691E',
    title: 'Klientide Kogemus',
    titleColor: '#FFFFFF',
  },
});

const editContent = reactive(JSON.parse(JSON.stringify(content.value)));

const saveHero = () => {
  content.value.hero = { ...editContent.hero };
  router.post('/admin/page-content', { page: 'welcome', section: 'hero', content: content.value.hero });
};

const savePopular = () => {
  content.value.popular = { ...editContent.popular };
  router.post('/admin/page-content', { page: 'welcome', section: 'popular', content: content.value.popular });
};

const saveContact = () => {
  content.value.contact = { ...editContent.contact };
  router.post('/admin/page-content', { page: 'welcome', section: 'contact', content: content.value.contact });
};

const saveReviews = () => {
  content.value.reviews = { ...editContent.reviews };
  router.post('/admin/page-content', { page: 'welcome', section: 'reviews', content: content.value.reviews });
};

// Cancel handlers to reset editContent
const cancelHero = () => {
  Object.assign(editContent.hero, content.value.hero);
};

const cancelPopular = () => {
  Object.assign(editContent.popular, content.value.popular);
};

const cancelContact = () => {
  Object.assign(editContent.contact, content.value.contact);
};

const cancelReviews = () => {
  Object.assign(editContent.reviews, content.value.reviews);
};
</script>