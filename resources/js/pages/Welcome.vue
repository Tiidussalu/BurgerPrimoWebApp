<template>
  <MainLayout>
    <div class="bg-[#0B0B0B] text-white">
      <!-- Hero Section -->
      <section class="relative min-h-[600px] flex items-center justify-center overflow-hidden">
        <EditableSection
          section-id="hero"
          container-class="absolute inset-0"
          @save="saveSection('hero')"
        >
          <template #default="{ isEditing }">
            <div class="absolute inset-0">
              <img
                v-if="!isEditing"
                :src="content.hero.bgImage"
                alt="Restaurant"
                class="w-full h-full object-cover opacity-60"
              />
              <input
                v-else
                v-model="editContent.hero.bgImage"
                type="text"
                class="w-full p-2 bg-gray-800 text-white"
                placeholder="Background image URL"
              />
            </div>
            
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black"></div>
            
            <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 text-center">
              <h1 
                v-if="!isEditing"
                class="text-5xl font-bold mb-4"
                :style="{ color: content.hero.titleColor }"
              >
                {{ content.hero.title }}
              </h1>
              <input
                v-else
                v-model="editContent.hero.title"
                type="text"
                class="w-full max-w-2xl mx-auto p-3 bg-gray-800 text-white mb-2"
              />
              <input
                v-if="isEditing"
                v-model="editContent.hero.titleColor"
                type="color"
                class="p-2 bg-gray-800"
              />

              <p 
                v-if="!isEditing"
                class="text-lg mb-8"
                :style="{ color: content.hero.subtitleColor }"
              >
                {{ content.hero.subtitle }}
              </p>
              <input
                v-else
                v-model="editContent.hero.subtitle"
                class="w-full max-w-2xl mx-auto p-3 bg-gray-800 text-white"
              />

              <Link
                :href="content.hero.buttonLink"
                class="inline-block px-8 py-3 rounded-lg font-semibold"
                :style="{ 
                  backgroundColor: content.hero.buttonBgColor,
                  color: content.hero.buttonTextColor
                }"
              >
                {{ content.hero.buttonText }}
              </Link>
              <input
                v-if="isEditing"
                v-model="editContent.hero.buttonText"
                class="mt-2 p-2 bg-gray-800 text-white"
              />
            </div>
          </template>
        </EditableSection>
      </section>

      <!-- Popular Section -->
      <section class="max-w-7xl mx-auto px-6 py-20">
        <EditableSection section-id="popular" @save="saveSection('popular')">
          <template #default="{ isEditing }">
            <div class="text-center mb-12">
              <h2 class="text-sm uppercase mb-2" :style="{ color: content.popular.labelColor }">
                {{ content.popular.label }}
              </h2>
              <h3 class="text-4xl font-bold" :style="{ color: content.popular.titleColor }">
                {{ content.popular.title }}
              </h3>
              <p class="text-gray-400 mt-4">{{ content.popular.subtitle }}</p>
              
              <Link
                :href="content.popular.buttonLink"
                class="inline-block mt-8 px-8 py-3 rounded-lg font-semibold"
                :style="{ backgroundColor: content.popular.buttonBgColor, color: content.popular.buttonTextColor }"
              >
                {{ content.popular.buttonText }}
              </Link>
            </div>
          </template>
        </EditableSection>
      </section>

      <!-- Contact Section -->
      <section class="bg-[#121212] py-20">
        <div class="max-w-7xl mx-auto px-6">
          <EditableSection section-id="contact" @save="saveSection('contact')">
            <template #default="{ isEditing }">
              <div class="text-center mb-12">
                <h2 class="text-sm uppercase mb-2" :style="{ color: content.contact.labelColor }">
                  {{ content.contact.label }}
                </h2>
                <h3 class="text-4xl font-bold" :style="{ color: content.contact.titleColor }">
                  {{ content.contact.title }}
                </h3>
              </div>

              <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div>
                  <h4 class="text-xl font-bold mb-4">{{ content.contact.addressTitle }}</h4>
                  <div class="text-gray-300 space-y-2">
                    <p v-for="(line, i) in content.contact.address" :key="i">{{ line }}</p>
                  </div>
                </div>
                <div>
                  <h4 class="text-xl font-bold mb-4">{{ content.contact.hoursTitle }}</h4>
                  <div class="text-gray-300 space-y-2">
                    <p v-for="(line, i) in content.contact.hours" :key="i">{{ line }}</p>
                  </div>
                </div>
              </div>
            </template>
          </EditableSection>
        </div>
      </section>

      <!-- Reviews Section -->
      <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-12">
          <h2 class="text-sm uppercase mb-2 text-[#D2691E]">ARVUSTUSED</h2>
          <h3 class="text-4xl font-bold">Klientide Kogemus</h3>
        </div>
        
        <div class="grid md:grid-cols-3 gap-6">
          <div v-for="i in 3" :key="i" class="bg-[#121212] p-6 rounded-lg">
            <div class="flex gap-1 mb-4">
              <span v-for="j in 5" :key="j" class="text-[#D2691E]">★</span>
            </div>
            <p class="text-gray-300 mb-4">{{ content.reviews.items[i-1]?.text }}</p>
            <p class="text-sm text-gray-400">{{ content.reviews.items[i-1]?.author }}</p>
          </div>
        </div>
      </section>
    </div>
  </MainLayout>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import MainLayout from '@/layouts/MainLayout.vue';
import EditableSection from '@/components/EditableSection.vue';

const content = ref({
  hero: {
    bgImage: '/images/restaurant.jpg',
    title: 'Meile oluliselt premeeritud burgerid meie aastakümnete kogemuse tulemus',
    titleColor: '#FFFFFF',
    subtitle: 'Kõrgeim nivoo kvaliteet ja armastus',
    subtitleColor: '#D2691E',
    buttonText: 'Avasta menüüd',
    buttonLink: '/menu',
    buttonBgColor: '#D2691E',
    buttonTextColor: '#FFFFFF',
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
    buttonTextColor: '#FFFFFF',
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
    items: [
      { text: 'Parim burger!', author: 'Klient' },
      { text: 'Suurepärane teenindus.', author: 'Klient' },
      { text: 'Alati värske.', author: 'Klient' },
    ],
  },
});

const editContent = reactive(JSON.parse(JSON.stringify(content.value)));

const saveSection = (section: string) => {
  content.value[section as keyof typeof content.value] = { ...editContent[section as keyof typeof editContent] };
  router.post('/admin/page-content', { section, content: content.value[section as keyof typeof content.value] });
};
</script>