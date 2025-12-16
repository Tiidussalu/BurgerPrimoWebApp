<template>
  <div class="relative group" :class="containerClass">
    <!-- Edit Button (Only visible for admins) -->
    <button
      v-if="isAdmin && !isEditing"
      @click="startEditing"
      class="absolute top-2 right-2 z-10 opacity-0 group-hover:opacity-100 transition-opacity p-2 bg-[#D2691E] hover:bg-[#E07A2E] rounded-lg shadow-lg"
      title="Edit section"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
      </svg>
    </button>

    <!-- Content Slot -->
    <slot :is-editing="isEditing" :save-changes="saveChanges" :cancel-editing="cancelEditing" />

    <!-- Save/Cancel Buttons (When editing) -->
    <div v-if="isEditing" class="absolute bottom-2 right-2 z-10 flex gap-2">
      <button
        @click="saveChanges"
        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-lg transition"
      >
        Save
      </button>
      <button
        @click="cancelEditing"
        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg shadow-lg transition"
      >
        Cancel
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

interface Props {
  sectionId: string;
  containerClass?: string;
}

const props = defineProps<Props>();

const page = usePage();
const isAdmin = computed(() => (page.props.auth?.user as any)?.is_admin || false);

const isEditing = ref(false);

const startEditing = () => {
  isEditing.value = true;
};

const saveChanges = () => {
  isEditing.value = false;
  // Emit event for parent to handle saving
  emit('save');
};

const cancelEditing = () => {
  isEditing.value = false;
  // Emit event for parent to handle canceling
  emit('cancel');
};

const emit = defineEmits(['save', 'cancel']);
</script>