<script setup lang="ts">
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import EditableSection from '@/components/EditableSection.vue'

const reviews = [
  { text: 'Parim', initial: 'R', name: 'Robby' },
  { text: 'Mu lemmik burger ja pitsa ja asi ja...', initial: 'T', name: 'Taaniel' },
  { text: 'Robby on läks', initial: 'K', name: 'Keanu' }
]

const content = ref({
  label: 'ARVUSTUSED',
  labelColor: '#D2691E',
  title: 'Klientide Kogemus',
  titleColor: '#F5DEB3',
})

const editContent = reactive(JSON.parse(JSON.stringify(content.value)))
const reviewSlide = ref(0)

function nextReviewSlide() {
  reviewSlide.value = (reviewSlide.value + 1) % reviews.length
}
function prevReviewSlide() {
  reviewSlide.value = (reviewSlide.value - 1 + reviews.length) % reviews.length
}

const save = () => {
  content.value = { ...editContent }
  router.post('/admin/page-content', { page: 'welcome', section: 'reviews', content: content.value })
}
const cancel = () => {
  Object.assign(editContent, content.value)
}
</script>

<template>
  <section class="max-w-7xl mx-auto px-6 py-20">
    <EditableSection section-id="reviews" @save="save" @cancel="cancel">
      <template #default="{ isEditing }">
        <div v-if="isEditing" class="fixed top-4 left-4 z-[999] bg-yellow-500 text-black px-4 py-2 rounded font-bold">
          EDIT MODE ACTIVE - REVIEWS
        </div>

        <div class="text-center mb-12">
          <h2 v-if="!isEditing" class="text-sm uppercase mb-3 tracking-widest font-semibold" :style="{ color: content.labelColor }">
            {{ content.label }}
          </h2>
          <div v-else class="mb-3 space-y-2">
            <input v-model="editContent.label" type="text" placeholder="Label..." class="w-full max-w-md mx-auto p-2 bg-gray-800 text-white rounded border-2 border-white" />
            <div class="flex gap-2 justify-center items-center">
              <label class="text-sm">Label color:</label>
              <input v-model="editContent.labelColor" type="color" class="p-1 bg-gray-800 rounded border-2 border-white" />
            </div>
          </div>

          <h3 v-if="!isEditing" class="text-4xl md:text-5xl font-bold mb-4" :style="{ color: content.titleColor }">
            {{ content.title }}
          </h3>
          <div v-else class="space-y-2 mb-4">
            <input v-model="editContent.title" type="text" placeholder="Title..." class="w-full max-w-md mx-auto p-3 bg-gray-800 text-white rounded border-2 border-white" />
            <div class="flex gap-2 justify-center items-center">
              <label class="text-sm">Title color:</label>
              <input v-model="editContent.titleColor" type="color" class="p-1 bg-gray-800 rounded border-2 border-white" />
            </div>
          </div>

          <p v-if="!isEditing" class="text-gray-300 text-lg">
            Mida meie kliendid Burger Primo kogemuse kohta ütlevad
          </p>
        </div>

        <!-- Desktop -->
        <div class="hidden md:grid md:grid-cols-3 gap-6">
          <div v-for="(review, i) in reviews" :key="i" class="bg-[#1a1a1a] p-8 rounded-lg">
            <div class="flex gap-1 mb-4">
              <span v-for="j in 5" :key="j" class="text-yellow-500 text-xl">★</span>
            </div>
            <p class="text-gray-200 mb-6 text-base leading-relaxed">{{ review.text }}</p>
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center">
                <span class="text-white font-semibold">{{ review.initial }}</span>
              </div>
              <p class="text-sm text-gray-300 font-medium">{{ review.name }}</p>
            </div>
          </div>
        </div>

        <!-- Mobile slider -->
        <div class="md:hidden relative">
          <div class="overflow-hidden rounded-lg">
            <div class="flex transition-transform duration-500 ease-in-out" :style="{ transform: `translateX(-${reviewSlide * 100}%)` }">
              <div v-for="(review, i) in reviews" :key="i" class="w-full flex-shrink-0 px-1">
                <div class="bg-[#1a1a1a] p-8 rounded-lg">
                  <div class="flex gap-1 mb-4">
                    <span v-for="j in 5" :key="j" class="text-yellow-500 text-xl">★</span>
                  </div>
                  <p class="text-gray-200 mb-6 text-base leading-relaxed">{{ review.text }}</p>
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center">
                      <span class="text-white font-semibold">{{ review.initial }}</span>
                    </div>
                    <p class="text-sm text-gray-300 font-medium">{{ review.name }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button v-if="reviews.length > 1" @click="prevReviewSlide" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-2 bg-black/60 text-white w-9 h-9 rounded-full flex items-center justify-center text-2xl hover:bg-black/80 transition z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <button v-if="reviews.length > 1" @click="nextReviewSlide" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-2 bg-black/60 text-white w-9 h-9 rounded-full flex items-center justify-center text-2xl hover:bg-black/80 transition z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div class="flex justify-center gap-2 mt-4">
            <button v-for="(review, i) in reviews" :key="i" @click="reviewSlide = i" class="w-3 h-3 rounded-full transition-colors" :class="reviewSlide === i ? 'bg-white' : 'bg-white/40'" />
          </div>
        </div>
      </template>
    </EditableSection>
  </section>
</template>