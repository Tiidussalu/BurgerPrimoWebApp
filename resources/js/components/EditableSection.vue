<template>
  <div class="editable-section relative" :class="[containerClass, { 'editing-mode': isEditing }]">
    <!-- Edit Button (Only visible for admins on hover) -->
    <button
      v-if="isAdmin && !isEditing"
      @click="startEditing"
      class="edit-pencil absolute top-2 right-2 z-50 p-2 bg-[#D2691E] hover:bg-[#E07A2E] rounded-lg shadow-lg transition-all opacity-0 hover:opacity-100"
      title="Muuda sektsiooni"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
      </svg>
    </button>

    <!-- Content Slot -->
    <div class="editable-content" @mouseenter="handleHover" @mouseleave="handleLeave">
      <slot :is-editing="isEditing" :start-editing="startEditing" />
    </div>

    <!-- Save/Cancel Buttons (When editing) -->
    <div v-if="isEditing" class="fixed bottom-4 right-4 z-[100] flex gap-2 shadow-2xl">
      <button
        @click="saveChanges"
        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition shadow-lg"
      >
        Salvesta
      </button>
      <button
        @click="cancelEditing"
        class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-semibold transition shadow-lg"
      >
        Tühista
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

interface Props {
  sectionId: string;
  containerClass?: string;
}

const props = defineProps<Props>();
const emit = defineEmits(['save', 'cancel']);

const page = usePage();
const isAdmin = computed(() => (page.props.auth?.user as any)?.is_admin || false);

const isEditing = ref(false);
const isHovering = ref(false);

const startEditing = () => {
  console.log('Starting edit mode for section:', props.sectionId);
  isEditing.value = true;
};

const saveChanges = () => {
  console.log('Saving changes for section:', props.sectionId);
  isEditing.value = false;
  emit('save');
};

const cancelEditing = () => {
  console.log('Canceling edit for section:', props.sectionId);
  isEditing.value = false;
  emit('cancel');
};

const handleHover = () => {
  isHovering.value = true;
};

const handleLeave = () => {
  isHovering.value = false;
};
</script>

<style scoped>
.editable-section:hover .edit-pencil {
  opacity: 1;
}

.editing-mode {
  outline: 2px dashed #D2691E;
  outline-offset: 4px;
}
</style>