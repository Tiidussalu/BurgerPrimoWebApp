<script setup lang="ts">
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import EditableSection from '@/components/EditableSection.vue'

const content = ref({
  label: 'Nautige sõpradega piljardimängu, nautides samal ajal meie maitsvaid toite ja jooke.',
  labelColor: '#ffffff',
  title: 'Mängi piljardit',
  sectionTitle: 'Piljardilaud',
  titleColor: '#D2691E',
  sectionTitleColor: '#F5DEB3',
  description: 'Nautige piljardilauda koos vastupidava villase segukanga ja pallikomplektiga. Ideaalne algajatele ja ka edasijõudnutele mänginiseks, nautides samal ajal oma lemmik Primo burgereid ja jooke.',
  pricingTitle: 'Hinnakiri',
  pricingTitleColor: '#F5DEB3',
  pricingItems: [
    { label: '8-palli mäng', price: '2€ mäng' },
  ],
  galleryImages: [
    '/img/pool25.jpg',
    '/img/pool15.jpg',
    '/img/pool35.jpg',
  ]
})

const editContent = reactive(JSON.parse(JSON.stringify(content.value)))
const currentSlide = ref(0)

function nextSlide() {
  const images = content.value.galleryImages
  if (!images?.length) return
  currentSlide.value = (currentSlide.value + 1) % images.length
}
function prevSlide() {
  const images = content.value.galleryImages
  if (!images?.length) return
  currentSlide.value = (currentSlide.value - 1 + images.length) % images.length
}

const save = () => {
  content.value = { ...editContent, pricingItems: [...editContent.pricingItems], galleryImages: [...editContent.galleryImages] }
  router.post('/admin/page-content', { page: 'welcome', section: 'entertainment', content: content.value })
}
const cancel = () => {
  Object.assign(editContent, JSON.parse(JSON.stringify(content.value)))
}
</script>

<template>
  <section class="bg-[#121212] py-20">
    <div class="max-w-7xl mx-auto px-6">
      <EditableSection section-id="entertainment" @save="save" @cancel="cancel">
        <template #default="{ isEditing }">
          <div v-if="isEditing" class="fixed top-4 left-4 z-[999] bg-purple-500 text-white px-4 py-2 rounded font-bold">
            EDIT MODE ACTIVE - ENTERTAINMENT
          </div>

          <div class="text-center mb-12">
            <h3 v-if="!isEditing" class="text-4xl md:text-5xl font-bold" :style="{ color: content.titleColor }">
              {{ content.title }}
            </h3>
            <div v-else class="space-y-2">
              <input v-model="editContent.title" type="text" placeholder="Title..." class="w-full max-w-md mx-auto p-3 bg-gray-800 text-white rounded border-2 border-white" />
              <div class="flex gap-2 justify-center items-center">
                <label class="text-sm">Title color:</label>
                <input v-model="editContent.titleColor" type="color" class="p-1 bg-gray-800 rounded border-2 border-white" />
              </div>
            </div>

            <h2 v-if="!isEditing" class="text-sm uppercase mt-4 tracking-widest font-semibold" :style="{ color: content.labelColor }">
              {{ content.label }}
            </h2>
            <div v-else class="mb-3 space-y-2">
              <input v-model="editContent.label" type="text" placeholder="Label..." class="w-full max-w-md mx-auto p-2 bg-gray-800 text-white rounded border-2 border-white" />
              <div class="flex gap-2 justify-center items-center">
                <label class="text-sm">Label color:</label>
                <input v-model="editContent.labelColor" type="color" class="p-1 bg-gray-800 rounded border-2 border-white" />
              </div>
            </div>
          </div>

          <div class="grid md:grid-cols-2 gap-8 items-start">
            <!-- Gallery slider -->
            <div class="relative rounded-lg overflow-hidden">
              <div class="w-full aspect-[4/3] overflow-hidden">
                <div
                  class="flex h-full transition-transform duration-500 ease-in-out"
                  :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
                >
                  <div
                    v-for="(image, i) in content.galleryImages"
                    :key="i"
                    class="w-full flex-shrink-0 h-full relative"
                  >
                    <img :src="image" class="absolute inset-0 w-full h-full object-cover" alt="Billiard image" />
                  </div>
                </div>
              </div>

              <button v-if="content.galleryImages.length > 1" @click="prevSlide" class="absolute left-3 top-1/2 -translate-y-1/2 bg-black/60 text-white w-9 h-9 rounded-full flex items-center justify-center text-2xl hover:bg-black/80 transition hover:cursor-pointer z-10">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                  </svg>
              </button>
              <button v-if="content.galleryImages.length > 1" @click="nextSlide" class="absolute right-3 top-1/2 -translate-y-1/2 bg-black/60 text-white w-9 h-9 rounded-full flex items-center justify-center text-2xl hover:bg-black/80 transition hover:cursor-pointer z-10">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                  </svg>
              </button>

              <div v-if="content.galleryImages.length > 1" class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
                <button
                  v-for="(img, i) in content.galleryImages"
                  :key="i"
                  @click="currentSlide = i"
                  class="w-3 h-3 rounded-full transition-colors hover:cursor-pointer"
                  :class="i === currentSlide ? 'bg-white' : 'bg-white/40'"
                />
              </div>
            </div>

            <!-- Info panel -->
            <div>
              <h2 v-if="!isEditing" class="text-3xl md:text-4xl font-bold mb-6" :style="{ color: content.sectionTitleColor }">
                {{ content.sectionTitle }}
              </h2>
              <div v-else class="space-y-2 mb-6">
                <input v-model="editContent.sectionTitle" type="text" placeholder="Section title..." class="w-full p-3 bg-gray-800 text-white rounded border-2 border-white" />
                <div class="flex gap-2 items-center">
                  <label class="text-sm">Title color:</label>
                  <input v-model="editContent.sectionTitleColor" type="color" class="p-1 bg-gray-800 rounded border-2 border-white" />
                </div>
              </div>

              <p v-if="!isEditing" class="text-gray-300 text-base leading-relaxed mb-8">{{ content.description }}</p>
              <textarea v-else v-model="editContent.description" rows="4" class="w-full p-3 bg-gray-800 text-white rounded border-2 border-white mb-8"></textarea>

              <div class="bg-[#1a1a1a] rounded-lg p-6 border border-gray-800">
                <h3 v-if="!isEditing" class="text-xl font-bold mb-4" :style="{ color: content.pricingTitleColor }">
                  {{ content.pricingTitle }}
                </h3>
                <div v-else class="space-y-2 mb-4">
                  <input v-model="editContent.pricingTitle" type="text" placeholder="Pricing title..." class="w-full p-2 bg-gray-800 text-white rounded border-2 border-white" />
                  <div class="flex gap-2 items-center">
                    <label class="text-sm">Pricing title color:</label>
                    <input v-model="editContent.pricingTitleColor" type="color" class="p-1 bg-gray-800 rounded border-2 border-white" />
                  </div>
                </div>

                <div v-if="!isEditing" class="space-y-3">
                  <div v-for="(item, i) in content.pricingItems" :key="i" class="flex justify-between items-center">
                    <span class="text-gray-300">{{ item.label }}</span>
                    <span class="text-white font-semibold text-lg">{{ item.price }}</span>
                  </div>
                </div>
                <div v-else class="space-y-2">
                  <div v-for="(item, i) in editContent.pricingItems" :key="i" class="flex gap-2">
                    <input v-model="editContent.pricingItems[i].label" type="text" class="flex-1 p-2 bg-gray-800 text-white rounded border-2 border-white" />
                    <input v-model="editContent.pricingItems[i].price" type="text" class="w-32 p-2 bg-gray-800 text-white rounded border-2 border-white" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </EditableSection>
    </div>
  </section>
</template>