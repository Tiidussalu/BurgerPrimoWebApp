<script setup lang="ts">
import { useToast } from '@/composables/useToast';

const { toasts, dismiss } = useToast();
</script>

<template>
  <Teleport to="body">
    <div class="fixed bottom-6 right-6 z-[9999] flex flex-col-reverse items-end gap-2.5 pointer-events-none">
      <TransitionGroup
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-x-4 scale-95"
        enter-to-class="opacity-100 translate-x-0 scale-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-x-0 scale-100"
        leave-to-class="opacity-0 translate-x-4 scale-95"
      >
        <div
          v-for="toast in toasts"
          :key="toast.id"
          @click="dismiss(toast.id)"
          class="pointer-events-auto flex items-center gap-3 min-w-[300px] max-w-xs px-4 py-3.5 rounded-xl shadow-2xl shadow-black/60 border cursor-pointer select-none backdrop-blur-sm"
          :class="{
            'bg-[#0a1a0a]/95 border-green-500/25 text-green-300': toast.type === 'success',
            'bg-[#1a0a0a]/95 border-red-500/25   text-red-300':   toast.type === 'error',
            'bg-[#1a1400]/95 border-yellow-500/25 text-yellow-300': toast.type === 'warning',
            'bg-[#0a1020]/95 border-blue-500/25   text-blue-300':  toast.type === 'info',
          }"
        >
          <!-- Icon -->
          <div
            class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center"
            :class="{
              'bg-green-500/15':  toast.type === 'success',
              'bg-red-500/15':    toast.type === 'error',
              'bg-yellow-500/15': toast.type === 'warning',
              'bg-blue-500/15':   toast.type === 'info',
            }"
          >
            <svg v-if="toast.type === 'success'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <svg v-else-if="toast.type === 'error'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <svg v-else-if="toast.type === 'warning'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>

          <span class="text-sm font-medium flex-1 leading-snug">{{ toast.message }}</span>

          <!-- Close hint -->
          <svg class="w-3.5 h-3.5 opacity-30 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>