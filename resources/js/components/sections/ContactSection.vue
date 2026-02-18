<script setup lang="ts">
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import EditableSection from '@/components/EditableSection.vue'

const content = ref({
  label: 'Külasta Burger Primot Kuressaare südames',
  labelColor: '#ffffff',
  title: 'Külastage Meid',
  titleColor: '#D2691E',
  addressTitle: 'Meie Asukoht',
  address: ['Kauba tn 5/2', 'Kuressaare, Saaremaa 93819', 'Eesti', '', 'Telefon: +372 5743 8483', 'Email: info@burgerprimo.ee'],
  hoursTitle: 'Lahtiolekuajad',
  hours: ['Esmaspäev - Neljapäev: 11:00 - 22:00', 'Reede - Laupäev: 11:00 - 23:00', 'Pühapäev: 12:00 - 21:00'],
})

const editContent = reactive(JSON.parse(JSON.stringify(content.value)))

const save = () => {
  content.value = { ...editContent }
  router.post('/admin/page-content', { page: 'welcome', section: 'contact', content: content.value })
}
const cancel = () => {
  Object.assign(editContent, content.value)
}
</script>

<template>
  <section class="bg-[#121212] py-20">
    <div class="max-w-7xl mx-auto px-6">
      <EditableSection section-id="contact" @save="save" @cancel="cancel">
        <template #default="{ isEditing }">
          <div v-if="isEditing" class="fixed top-4 left-4 z-[999] bg-purple-500 text-white px-4 py-2 rounded font-bold">
            EDIT MODE ACTIVE - CONTACT
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

          <div class="grid md:grid-cols-2 gap-12 max-w-5xl mx-auto">
            <div>
              <h4 v-if="!isEditing" class="text-xl font-bold mb-6 text-[#F5DEB3]">{{ content.addressTitle }}</h4>
              <input v-else v-model="editContent.addressTitle" type="text" placeholder="Address title..." class="w-full p-2 bg-gray-800 text-white rounded border-2 border-white mb-6" />

              <div v-if="!isEditing" class="text-gray-300 text-base">
                <p v-for="(line, i) in content.address" :key="i">{{ line }}</p>
              </div>
              <div v-else class="space-y-2">
                <input
                  v-for="(line, i) in editContent.address"
                  :key="i"
                  v-model="editContent.address[i]"
                  type="text"
                  :placeholder="`Address line ${(i as number) + 1}`"
                  class="w-full p-2 bg-gray-800 text-white rounded border-2 border-white"
                />
              </div>

              <div class="rounded-lg overflow-hidden h-64 mt-6 shadow-lg">
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d8397.343520194667!2d22.483251!3d58.252736999999996!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46f26ce2774f2b63%3A0xea0f9eafb8fa644c!2sPrimo%20Burger!5e0!3m2!1sen!2see!4v1770927348729!5m2!1sen!2see"
                  width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
              </div>
            </div>

            <div>
              <h4 v-if="!isEditing" class="text-xl font-bold mb-6 text-[#F5DEB3]">{{ content.hoursTitle }}</h4>
              <input v-else v-model="editContent.hoursTitle" type="text" placeholder="Hours title..." class="w-full p-2 bg-gray-800 text-white rounded border-2 border-white mb-6" />

              <div v-if="!isEditing" class="space-y-4 text-base">
                <div v-for="(line, i) in content.hours" :key="i" class="flex justify-between items-center">
                  <span class="text-gray-300">{{ line.split(':')[0] }}</span>
                  <span class="text-white font-medium">{{ line.split(':').slice(1).join(':').trim() }}</span>
                </div>
              </div>
              <div v-else class="space-y-2">
                <input
                  v-for="(line, i) in editContent.hours"
                  :key="i"
                  v-model="editContent.hours[i]"
                  type="text"
                  :placeholder="`Hours line ${(i as number) + 1}`"
                  class="w-full p-2 bg-gray-800 text-white rounded border-2 border-white"
                />
              </div>

              <div v-if="!isEditing" class="mt-8 p-4 bg-[#1a1a1a] rounded-lg">
                <p class="text-sm text-gray-300 mb-2">Toidu kohaletoimetamine</p>
                <p class="text-sm text-gray-400">Toitu toimetab kohale Bolt Food ja Wolt, restorani lahtiolekuaegadel</p>
                <div class="mt-3 flex gap-3">
                  <a href="https://wolt.com/en/est/kuressaare/restaurant/primo-burger?srsltid=AfmBOoou9cug0gwJK-jJ-cEYiEtXBpvTEVDKnMaQkcQmfLMcCPv-CaLl" class="px-3 py-1 bg-[#12dcff] text-white rounded-full text-sm hover:scale-105 transition-transform">Wolt</a>
                  <a href="https://food.bolt.eu/en-US/164/p/90859-primo-burger" class="px-3 py-1 bg-[#21c93d] text-white rounded-full text-sm hover:scale-105 transition-transform">Bolt Food</a>
                </div>
              </div>
            </div>
          </div>
        </template>
      </EditableSection>
    </div>
  </section>
</template>