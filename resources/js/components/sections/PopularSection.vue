<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import EditableSection from '@/components/EditableSection.vue'

interface MenuItem {
  id: number
  name: string
  description: string
  price: number
  original_price: number | null
  image: string | null
  image_url: string | null
  is_featured: boolean
  category: {
    id: number
    name: string
  }
}

const props = defineProps<{
  featuredItems?: MenuItem[]
}>()

const content = ref({
  title: 'Populaarsed',
  titleColor: '#D2691E',
  subtitle: 'Primos enim tuntud ja rohkelt tellitud toidud',
  buttonText: 'Avasta Kogu Menüüd',
  buttonLink: '/menu',
  buttonBgColor: '#D2691E',
})

const editContent = reactive(JSON.parse(JSON.stringify(content.value)))

const displayedItems = ref<MenuItem[]>([])

const shuffleArray = <T,>(array: T[]): T[] => {
  const shuffled = [...array]
  for (let i = shuffled.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]]
  }
  return shuffled
}

onMounted(() => {
  if (props.featuredItems && props.featuredItems.length > 0) {
    const shuffled = shuffleArray(props.featuredItems)
    displayedItems.value = shuffled.slice(0, Math.min(3, shuffled.length))
  }
})

const popularSlide = ref(0)
const popularCount = computed(() => displayedItems.value.length || 3)

function nextPopularSlide() {
  popularSlide.value = (popularSlide.value + 1) % popularCount.value
}
function prevPopularSlide() {
  popularSlide.value = (popularSlide.value - 1 + popularCount.value) % popularCount.value
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

          <p v-if="!isEditing" class="text-white text-lg">{{ content.subtitle }}</p>
          <textarea v-else v-model="editContent.subtitle" placeholder="Subtitle..." class="w-full max-w-md mx-auto p-3 bg-gray-800 text-white rounded border-2 border-white mb-4" rows="2"></textarea>
        </div>

        <div class="hidden md:grid md:grid-cols-3 gap-8 mb-12">
          <Link
            v-for="item in displayedItems"
            :key="item.id"
            :href="`/menu`"
            class="bg-[#1a1a1a] rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition group"
          >
            <div class="aspect-square bg-gray-200 overflow-hidden relative">
              <img
                v-if="item.image_url"
                :src="item.image_url"
                :alt="item.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                <span class="text-6xl font-bold text-gray-400">{{ item.name.charAt(0) }}</span>
              </div>
            </div>
            <div class="p-4">
              <h4 class="text-xl font-bold text-[#F5DEB3] mb-2">{{ item.name }}</h4>
              <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ item.description }}</p>
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-2xl font-bold text-[#D2691E]">€{{ item.price }}</span>
                  <span v-if="item.original_price" class="ml-2 text-sm text-gray-400 line-through">€{{ item.original_price }}</span>
                </div>
              </div>
            </div>
          </Link>

          <div v-if="displayedItems.length === 0" v-for="i in 3" :key="`placeholder-${i}`" class="bg-white rounded-lg overflow-hidden shadow-lg">
            <div class="aspect-square bg-gray-200 flex items-center justify-center">
              <span class="text-6xl font-bold text-gray-400">Pilt</span>
            </div>
            <div class="p-4">
              <div class="h-6 bg-gray-200 rounded mb-2"></div>
              <div class="h-4 bg-gray-100 rounded mb-3"></div>
              <div class="h-8 bg-gray-200 rounded w-20"></div>
            </div>
          </div>
        </div>

        <div class="md:hidden relative mb-12">
          <div class="overflow-hidden rounded-lg">
            <div class="flex transition-transform duration-500 ease-in-out" :style="{ transform: `translateX(-${popularSlide * 100}%)` }">
              <Link
                v-for="item in displayedItems"
                :key="item.id"
                :href="`/menu`"
                class="w-full flex-shrink-0 px-1"
              >
                <div class="bg-[#1a1a1a] rounded-lg overflow-hidden shadow-lg">
                  <div class="aspect-square bg-gray-200 overflow-hidden relative">
                    <img
                      v-if="item.image_url"
                      :src="item.image_url"
                      :alt="item.name"
                      class="w-full h-full object-cover"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                      <span class="text-6xl font-bold text-gray-400">{{ item.name.charAt(0) }}</span>
                    </div>
                  </div>
                  <div class="p-4">
                    <h4 class="text-xl font-bold text-[#F5DEB3] mb-2">{{ item.name }}</h4>
                    <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ item.description }}</p>
                    <div class="flex items-center justify-between">
                      <div>
                        <span class="text-2xl font-bold text-[#D2691E]">€{{ item.price }}</span>
                        <span v-if="item.original_price" class="ml-2 text-sm text-gray-400 line-through">€{{ item.original_price }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </Link>

              <div v-if="displayedItems.length === 0" v-for="i in 3" :key="`placeholder-mobile-${i}`" class="w-full flex-shrink-0 px-1">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                  <div class="aspect-square bg-gray-200 flex items-center justify-center">
                    <span class="text-6xl font-bold text-gray-400">Pilt</span>
                  </div>
                  <div class="p-4">
                    <div class="h-6 bg-gray-200 rounded mb-2"></div>
                    <div class="h-4 bg-gray-100 rounded mb-3"></div>
                    <div class="h-8 bg-gray-200 rounded w-20"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button v-if="popularCount > 1" @click="prevPopularSlide" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-2 bg-black/60 text-white w-9 h-9 rounded-full flex items-center justify-center text-2xl hover:bg-black/80 transition z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <button v-if="popularCount > 1" @click="nextPopularSlide" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-2 bg-black/60 text-white w-9 h-9 rounded-full flex items-center justify-center text-2xl hover:bg-black/80 transition z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div class="flex justify-center gap-2 mt-4">
            <button v-for="i in popularCount" :key="i" @click="popularSlide = i - 1" class="w-3 h-3 rounded-full transition-colors" :class="popularSlide === i - 1 ? 'bg-white' : 'bg-white/40'" />
          </div>
        </div>

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