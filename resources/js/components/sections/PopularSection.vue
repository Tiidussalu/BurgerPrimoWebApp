<script setup lang="ts">
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import EditableSection from '@/components/EditableSection.vue'

const content = ref({
  label: 'MEIE VALIK',
  labelColor: '#D2691E',
  title: 'Populaarsed',
  titleColor: '#F5DEB3',
  subtitle: 'Primos enim tuntud ja rohkelt tellitud toidud',
  buttonText: 'Avasta Kogu Menüüd',
  buttonLink: '/menu',
  buttonBgColor: '#D2691E',
})

const editContent = reactive(JSON.parse(JSON.stringify(content.value)))

const popularSlide = ref(0)
const popularCount = 3

function nextPopularSlide() {
  popularSlide.value = (popularSlide.value + 1) % popularCount
}
function prevPopularSlide() {
  popularSlide.value = (popularSlide.value - 1 + popularCount) % popularCount
}

const save = () => {
  content.value = { ...editContent }
  router.post('/admin/page-content', { page: 'welcome', section: 'popular', content: content.value })
}
const cancel = () => {
  Object.assign(editContent, content.value)
}
</script>

<template>
  <section class="max-w-7xl mx-auto px-6 py-20">
    <EditableSection section-id="popular" @save="save" @cancel="cancel">
      <template #default="{ isEditing }">
        <div v-if="isEditing" class="fixed top-4 left-4 z-[999] bg-blue-500 text-white px-4 py-2 rounded font-bold">
          EDIT MODE ACTIVE - POPULAR
        </div>

        <div class="text-center mb-16">
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
          <div v-else class="mb-4 space-y-2">
            <input v-model="editContent.title" type="text" placeholder="Title..." class="w-full max-w-md mx-auto p-3 bg-gray-800 text-white rounded border-2 border-white" />
            <div class="flex gap-2 justify-center items-center">
              <label class="text-sm">Title color:</label>
              <input v-model="editContent.titleColor" type="color" class="p-1 bg-gray-800 rounded border-2 border-white" />
            </div>
          </div>

          <p v-if="!isEditing" class="text-gray-300 text-lg">{{ content.subtitle }}</p>
          <textarea v-else v-model="editContent.subtitle" placeholder="Subtitle..." class="w-full max-w-md mx-auto p-3 bg-gray-800 text-white rounded border-2 border-white mb-4" rows="2"></textarea>
        </div>

        <!-- Desktop grid -->
        <div class="hidden md:grid md:grid-cols-3 gap-8 mb-12">
          <div v-for="i in 3" :key="i" class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition">
            <div class="aspect-square bg-gray-200 flex items-center justify-center">
              <span class="text-6xl font-bold text-gray-400">Pilt</span>
            </div>
          </div>
        </div>

        <!-- Mobile slider -->
        <div class="md:hidden relative mb-12">
          <div class="overflow-hidden rounded-lg">
            <div class="flex transition-transform duration-500 ease-in-out" :style="{ transform: `translateX(-${popularSlide * 100}%)` }">
              <div v-for="i in popularCount" :key="i" class="w-full flex-shrink-0">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg mx-1">
                  <div class="aspect-square bg-gray-200 flex items-center justify-center">
                    <span class="text-6xl font-bold text-gray-400">Pilt</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button v-if="popularCount > 1" @click="prevPopularSlide" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-2 bg-black/60 text-white w-9 h-9 rounded-full flex items-center justify-center text-2xl hover:bg-black/80 transition z-10">‹</button>
          <button v-if="popularCount > 1" @click="nextPopularSlide" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-2 bg-black/60 text-white w-9 h-9 rounded-full flex items-center justify-center text-2xl hover:bg-black/80 transition z-10">›</button>
          <div class="flex justify-center gap-2 mt-4">
            <button v-for="i in popularCount" :key="i" @click="popularSlide = i - 1" class="w-3 h-3 rounded-full transition-colors" :class="popularSlide === i - 1 ? 'bg-white' : 'bg-white/40'" />
          </div>
        </div>

        <!-- Button -->
        <div class="text-center">
          <div v-if="!isEditing">
            <Link :href="content.buttonLink" class="inline-block px-10 py-4 rounded-md font-semibold transition hover:opacity-90 text-white uppercase tracking-wide" :style="{ backgroundColor: content.buttonBgColor }">
              {{ content.buttonText }}
            </Link>
          </div>
          <div v-else class="space-y-2">
            <input v-model="editContent.buttonText" type="text" placeholder="Button text..." class="w-full max-w-md mx-auto p-2 bg-gray-800 text-white rounded border-2 border-white" />
            <input v-model="editContent.buttonLink" type="text" placeholder="Button link..." class="w-full max-w-md mx-auto p-2 bg-gray-800 text-white rounded border-2 border-white" />
            <div class="flex gap-2 justify-center">
              <label class="text-sm">Button color:</label>
              <input v-model="editContent.buttonBgColor" type="color" class="p-1 bg-gray-800 rounded border-2 border-white" />
            </div>
          </div>
        </div>
      </template>
    </EditableSection>
  </section>
</template>