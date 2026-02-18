<script setup lang="ts">
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import EditableSection from '@/components/EditableSection.vue'

const content = ref({
  title: 'Kirega valmistatud preemium burgerid, mida serveeritakse unkusega',
  titleColor: '#FFFFFF',
  subtitle: 'Kurssaare südames',
  subtitleColor: '#FFFFFF',
  buttonText: 'Avasta menüüd',
  buttonLink: '/menu',
  buttonBgColor: '#D2691E',
})

const editContent = reactive(JSON.parse(JSON.stringify(content.value)))

const save = () => {
  content.value = { ...editContent }
  router.post('/admin/page-content', { page: 'welcome', section: 'hero', content: content.value })
}

const cancel = () => {
  Object.assign(editContent, content.value)
}
</script>

<template>
  <section class="w-full h-screen overflow-hidden">
    <EditableSection section-id="hero" container-class="h-full" @save="save" @cancel="cancel">
      <template #default="{ isEditing }">
        <div class="w-full h-full flex items-center justify-center">
          <div v-if="isEditing" class="fixed top-4 left-4 z-[999] bg-red-500 text-white px-4 py-2 rounded font-bold">
            EDIT MODE ACTIVE - HERO
          </div>

          <div class="absolute inset-0 w-full h-full">
            <img src="/img/main25.jpg" alt="Burger Primo Interior" class="w-full h-full object-cover blur-md" />
            <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/50 to-black z-0 bg-black/60"></div>
          </div>

          <div class="relative max-w-4xl mx-auto px-6 text-center w-full z-10 flex flex-col justify-center min-h-[calc(100vh-8rem)]">
            <div class="mb-6 flex flex-col items-center">
              <img
                src="/img/Logo45.png"
                alt="Burger Primo Logo"
                class="w-64 h-64 md:w-96 md:h-96 object-contain"
                style="filter: drop-shadow(0 25px 25px rgba(0, 0, 0, 0.6));"
              />
            </div>

            <h1
              v-if="!isEditing"
              class="text-xl md:text-2xl font-light mb-8 leading-tight max-w-2xl mx-auto"
              :style="{ color: content.titleColor }"
            >
              {{ content.title }}
            </h1>
            <div v-else class="mb-8 space-y-2">
              <textarea
                v-model="editContent.title"
                placeholder="Enter title..."
                class="w-full max-w-2xl mx-auto p-3 bg-gray-800 text-white rounded border-2 border-white"
                rows="2"
              ></textarea>
              <div class="flex gap-2 justify-center items-center">
                <label class="text-sm">Title color:</label>
                <input v-model="editContent.titleColor" type="color" class="p-2 bg-gray-800 rounded border-2 border-white" />
              </div>
            </div>

            <div v-if="!isEditing">
              <Link
                :href="content.buttonLink"
                class="inline-block px-10 py-4 rounded-md font-semibold transition hover:opacity-90 text-white uppercase tracking-wide"
                :style="{ backgroundColor: content.buttonBgColor }"
              >
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

          <div class="absolute bottom-18 left-1/2 -translate-x-1/2 flex flex-col items-center text-white animate-bounce z-20">
            <span class="text-sm tracking-widest uppercase mb-2">Keri alla</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </div>
        </div>
      </template>
    </EditableSection>
  </section>
</template>