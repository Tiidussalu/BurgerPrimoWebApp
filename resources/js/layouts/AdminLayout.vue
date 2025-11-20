<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Home, ShoppingBag, Menu, X, ChevronDown, ChevronUp, LogOut, User as UserIcon } from 'lucide-vue-next';
import type { AppPageProps } from '@/types';

const page = usePage<AppPageProps>();
const user = computed(() => page.props.auth?.user);

const sidebarOpen = ref(true);
const menuExpanded = ref(false);
const userMenuOpen = ref(false);

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

const toggleMenu = () => {
  menuExpanded.value = !menuExpanded.value;
};

const toggleUserMenu = () => {
  userMenuOpen.value = !userMenuOpen.value;
};

const isActive = (routeName: string): boolean => {
  return page.url.startsWith(routeName);
};

const logout = () => {
  // Using Inertia's router to handle logout
  window.location.href = '/logout';
};
</script>

<template>
  <div class="flex min-h-screen bg-[#0a0a0a] text-white">
    <!-- Mobile Overlay -->
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 bg-black/50 z-20 lg:hidden"
      @click="toggleSidebar"
    />

    <!-- Sidebar -->
    <aside
      :class="[
        'fixed lg:static inset-y-0 left-0 z-30 w-[260px] bg-[#111111] border-r border-gray-800 flex flex-col transition-transform duration-300',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
      ]"
    >
      <!-- Logo/Brand -->
      <div class="p-6 border-b border-gray-800 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold bg-gradient-to-r from-orange-500 to-orange-600 bg-clip-text text-transparent">
            BurgerPrimo
          </h1>
          <p class="text-sm text-gray-400 mt-1">Admin Panel</p>
        </div>
        <button
          class="lg:hidden p-2 hover:bg-gray-800 rounded-lg transition"
          @click="toggleSidebar"
        >
          <X :size="20" />
        </button>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 p-4 overflow-y-auto">
        <p class="text-xs text-gray-500 uppercase font-semibold mb-4 px-4">Management</p>
        <div class="space-y-1">
          <!-- Dashboard -->
          <Link
            href="/admin/dashboard"
            :class="[
              'flex items-center gap-3 px-4 py-3 rounded-lg transition-colors',
              isActive('/admin/dashboard')
                ? 'bg-orange-600 text-white'
                : 'text-gray-400 hover:bg-gray-800 hover:text-white'
            ]"
          >
            <Home :size="20" />
            <span class="font-medium">Dashboard</span>
          </Link>

          <!-- Orders -->
          <Link
            href="/admin/orders"
            :class="[
              'flex items-center gap-3 px-4 py-3 rounded-lg transition-colors',
              isActive('/admin/orders')
                ? 'bg-orange-600 text-white'
                : 'text-gray-400 hover:bg-gray-800 hover:text-white'
            ]"
          >
            <ShoppingBag :size="20" />
            <span class="font-medium">Orders</span>
          </Link>

          <!-- Menu (with submenu) -->
          <div>
            <button
              @click="toggleMenu"
              :class="[
                'w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg transition-colors',
                isActive('/admin/menu')
                  ? 'bg-orange-600 text-white'
                  : 'text-gray-400 hover:bg-gray-800 hover:text-white'
              ]"
            >
              <div class="flex items-center gap-3">
                <Menu :size="20" />
                <span class="font-medium">Menu</span>
              </div>
              <ChevronDown v-if="!menuExpanded" :size="16" />
              <ChevronUp v-else :size="16" />
            </button>

            <!-- Submenu -->
            <div
              v-if="menuExpanded"
              class="ml-4 mt-1 space-y-1 border-l-2 border-gray-700 pl-4"
            >
              <Link
                href="/admin/menu/categories"
                :class="[
                  'block px-4 py-2 rounded-lg text-sm transition-colors',
                  isActive('/admin/menu/categories')
                    ? 'bg-gray-800 text-white'
                    : 'text-gray-400 hover:bg-gray-800 hover:text-white'
                ]"
              >
                Categories
              </Link>
              <Link
                href="/admin/menu/items"
                :class="[
                  'block px-4 py-2 rounded-lg text-sm transition-colors',
                  isActive('/admin/menu/items')
                    ? 'bg-gray-800 text-white'
                    : 'text-gray-400 hover:bg-gray-800 hover:text-white'
                ]"
              >
                Items
              </Link>
            </div>
          </div>
        </div>
      </nav>

      <!-- User Section (Bottom of Sidebar) -->
      <div class="p-4 border-t border-gray-800">
        <div class="relative">
          <button
            @click="toggleUserMenu"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors"
          >
            <div class="w-8 h-8 rounded-full bg-orange-600 flex items-center justify-center text-sm font-bold">
              {{ user?.name?.charAt(0).toUpperCase() || 'A' }}
            </div>
            <div class="flex-1 text-left">
              <p class="text-sm font-medium">{{ user?.name || 'Admin' }}</p>
              <p class="text-xs text-gray-400">Admin</p>
            </div>
            <ChevronUp v-if="userMenuOpen" :size="16" class="text-gray-400" />
            <ChevronDown v-else :size="16" class="text-gray-400" />
          </button>

          <!-- User Dropdown -->
          <div
            v-if="userMenuOpen"
            class="absolute bottom-full left-0 right-0 mb-2 bg-[#1a1a1a] border border-gray-800 rounded-lg shadow-xl overflow-hidden"
          >
            <Link
              href="/dashboard"
              class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 transition-colors text-gray-300"
            >
              <UserIcon :size="18" />
              <span class="text-sm">User Dashboard</span>
            </Link>
            <button
              @click="logout"
              class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-800 transition-colors text-red-400"
            >
              <LogOut :size="18" />
              <span class="text-sm">Logout</span>
            </button>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
      <!-- Top Header -->
      <header class="bg-[#0a0a0a] border-b border-gray-800 px-4 lg:px-8 py-4 lg:py-6 flex items-center justify-between sticky top-0 z-10">
        <div class="flex items-center gap-4">
          <!-- Mobile Menu Button -->
          <button
            class="lg:hidden p-2 hover:bg-gray-800 rounded-lg transition"
            @click="toggleSidebar"
          >
            <Menu :size="24" />
          </button>
          
          <!-- Page Title Slot -->
          <slot name="header">
            <h2 class="text-xl lg:text-2xl font-bold">Admin Dashboard</h2>
          </slot>
        </div>

        <!-- Header Actions Slot -->
        <div>
          <slot name="header-actions" />
        </div>
      </header>

      <!-- Main Content -->
      <main class="flex-1 overflow-auto p-4 lg:p-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Custom scrollbar for sidebar */
nav::-webkit-scrollbar {
  width: 6px;
}

nav::-webkit-scrollbar-track {
  background: transparent;
}

nav::-webkit-scrollbar-thumb {
  background: #374151;
  border-radius: 3px;
}

nav::-webkit-scrollbar-thumb:hover {
  background: #4b5563;
}
</style>