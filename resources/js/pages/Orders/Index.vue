<template>
  <div class="page-bg" aria-hidden="true">
    <div class="page-bg-img" />
    <div class="page-bg-overlay" />
  </div>

  <div class="page-root text-white">
    <Navbar />

    <!-- Confirm Modal -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="modal.show" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="modal.show = false">
          <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" />
          <div class="relative glass rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
            <div class="h-0.5 bg-gradient-to-r from-transparent via-[#D2691E] to-transparent" />
            <div class="p-6">
              <div class="flex items-center gap-4 mb-4">
                <div :class="modal.type === 'danger' ? 'bg-red-500/15 text-red-400' : 'bg-[#D2691E]/15 text-[#D2691E]'" class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </div>
                <div>
                  <h3 class="text-lg font-bold text-white">{{ modal.title }}</h3>
                  <p class="text-sm text-gray-400 mt-0.5">{{ modal.message }}</p>
                </div>
              </div>
              <div class="flex gap-3 mt-6">
                <button @click="modal.show = false" class="flex-1 py-3 rounded-xl border border-white/10 text-gray-300 hover:bg-white/5 transition font-semibold text-sm">Tühista</button>
                <button @click="modal.onConfirm(); modal.show = false" :class="modal.type === 'danger' ? 'bg-red-600 hover:bg-red-500' : 'bg-[#D2691E] hover:bg-[#B8571A]'" class="flex-1 py-3 rounded-xl font-bold text-sm text-white transition-all">{{ modal.confirmLabel }}</button>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-10 pb-24">
      <div class="mb-8 flex items-end justify-between gap-4">
        <div>
          <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-white/10 bg-white/5 text-gray-400 text-xs font-semibold uppercase tracking-widest mb-4">Tellimused</div>
          <h1 class="text-4xl font-bold mb-1">Minu tellimused</h1>
          <p class="text-gray-400 text-sm">Vaata kõiki oma tellimusi</p>
        </div>
        <button
          v-if="selectedOrders.length > 0"
          @click="confirmDeleteSelected"
          class="flex items-center gap-2 bg-red-600/10 hover:bg-red-600 border border-red-800/50 hover:border-red-600 text-red-400 hover:text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
          Kustuta ({{ selectedOrders.length }})
        </button>
      </div>

      <!-- Empty state -->
      <div v-if="orders.length === 0" class="glass p-16 text-center">
        <p class="text-6xl mb-5">📦</p>
        <h3 class="text-2xl font-bold mb-2">Tellimusi pole veel</h3>
        <p class="text-gray-400 mb-8 text-sm">Telli oma esimene burger!</p>
        <Link href="/menu" class="btn-magnetic inline-flex items-center gap-2 px-8 py-4 bg-[#D2691E] hover:bg-[#B8571A] text-white rounded-2xl font-bold text-sm transition-all">Avasta menüüd</Link>
      </div>

      <!-- Orders list -->
      <div v-else class="space-y-4">
        <div
          v-for="order in orders"
          :key="order.id"
          class="glass overflow-hidden transition-all duration-200"
          :class="selectedOrders.includes(order.id) ? 'border-[#D2691E]/40' : ''"
        >
          <!-- Header bar -->
          <div class="px-6 py-4 flex items-center justify-between border-b border-white/6 bg-white/2">
            <div class="flex items-center gap-4">
              <label v-if="order.status === 'completed'" class="flex items-center cursor-pointer">
                <input type="checkbox" :checked="selectedOrders.includes(order.id)" @change="toggleOrderSelection(order.id)" class="w-4 h-4 rounded border-white/20 bg-transparent text-[#D2691E] focus:ring-[#D2691E] cursor-pointer accent-[#D2691E]" />
              </label>
              <div>
                <p class="text-[10px] text-gray-600 uppercase tracking-widest mb-0.5">Tellimuse number</p>
                <p class="font-mono text-base font-bold text-[#D2691E]">{{ order.order_number }}</p>
              </div>
            </div>
            <div class="flex items-center gap-5">
              <div class="text-right hidden sm:block">
                <p class="text-[10px] text-gray-600 uppercase tracking-widest mb-0.5">Aeg</p>
                <p class="text-xs font-medium text-gray-400">{{ formatDate(order.created_at) }}</p>
              </div>
              <span :class="getStatusClass(order.status)">{{ getStatusLabel(order.status) }}</span>
            </div>
          </div>

          <!-- Body -->
          <div class="p-6 relative">
            <button v-if="order.status === 'rejected'" @click="confirmDismiss(order.id)" class="absolute top-4 right-4 w-7 h-7 rounded-full bg-red-600/15 hover:bg-red-600 text-red-400 hover:text-white flex items-center justify-center transition" title="Sulge">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div v-if="order.status === 'rejected'" class="mb-4 bg-red-900/15 border border-red-800/40 rounded-xl px-4 py-3">
              <p class="text-sm text-red-400 font-medium">Tellimus lükati tagasi restorani poolt.</p>
            </div>

            <div class="flex items-start justify-between gap-6 mb-4">
              <div class="flex-1 min-w-0">
                <p class="text-[10px] text-gray-600 uppercase tracking-widest mb-2">Tooted</p>
                <div class="space-y-2">
                  <div v-for="item in order.items" :key="item.id" class="glass-card flex items-center justify-between px-4 py-2.5">
                    <div>
                      <p class="font-semibold text-sm">{{ item.burger_name }}</p>
                      <p class="text-xs text-gray-600 mt-0.5">{{ item.quantity }}× kogus</p>
                    </div>
                    <p class="font-bold text-[#D2691E] text-sm shrink-0">{{ Number(item.price * item.quantity).toFixed(2) }}€</p>
                  </div>
                </div>
              </div>
              <div class="text-right shrink-0">
                <p class="text-[10px] text-gray-600 uppercase tracking-widest mb-1">Kokku</p>
                <p class="text-3xl font-bold text-[#D2691E]">{{ Number(order.total_amount).toFixed(2) }}€</p>
              </div>
            </div>

            <div v-if="order.customer_notes" class="mb-3 glass-card px-4 py-3">
              <p class="text-[10px] text-gray-600 uppercase tracking-widest mb-1">Sinu märkused</p>
              <p class="text-sm text-gray-300">{{ order.customer_notes }}</p>
            </div>
            <div v-if="order.admin_notes" class="mb-3 bg-blue-900/10 border border-blue-800/30 rounded-xl px-4 py-3">
              <p class="text-[10px] text-blue-400 uppercase tracking-widest mb-1">Restorani märkused</p>
              <p class="text-sm text-gray-300">{{ order.admin_notes }}</p>
            </div>

            <div class="flex gap-2 mt-4">
              <Link :href="`/orders/${order.id}`" class="flex-1 glass-card hover:border-[#D2691E]/30 text-gray-300 hover:text-white px-4 py-2.5 rounded-xl font-semibold transition-all text-center text-sm">Vaata detaile</Link>
              <button v-if="order.status === 'pending' || order.status === 'confirmed'" @click="confirmCancel(order.id)" class="flex-1 bg-red-600/8 hover:bg-red-600 text-red-400 hover:text-white px-4 py-2.5 rounded-xl font-semibold transition-all text-sm border border-red-800/40 hover:border-red-600">Tühista</button>
              <Link v-if="order.status === 'completed'" href="/menu" class="flex-1 btn-magnetic bg-[#D2691E] hover:bg-[#B8571A] text-white px-4 py-2.5 rounded-xl font-semibold transition-all text-center text-sm">Telli uuesti</Link>
            </div>
          </div>
        </div>
      </div>

      <div v-if="orders.length > 0" class="mt-10 text-center">
        <Link href="/menu" class="btn-magnetic inline-flex items-center gap-2 px-8 py-4 bg-[#D2691E] hover:bg-[#B8571A] text-white rounded-2xl font-bold text-sm transition-all">Telli veel burgereid</Link>
      </div>
    </main>

    <Footer />
  </div>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
import { useToast } from '@/composables/useToast'

const { success, error } = useToast()

interface OrderItem { id: number; burger_name: string; price: number; quantity: number }
interface Order { id: number; order_number: string; total_amount: number; status: string; created_at: string; customer_notes: string | null; admin_notes: string | null; items: OrderItem[] }

const props = defineProps<{ orders: Order[] }>()
const selectedOrders = ref<number[]>([])

const modal = reactive({ show: false, type: 'danger' as 'danger' | 'warning', title: '', message: '', confirmLabel: 'Kinnita', onConfirm: () => {} })
const openModal = (opts: Omit<typeof modal, 'show'>) => { Object.assign(modal, { show: true, ...opts }) }

const hasActiveOrders = computed(() => props.orders.some(o => ['pending', 'confirmed', 'preparing', 'ready'].includes(o.status)))
let refreshInterval: ReturnType<typeof setInterval> | null = null
onMounted(() => { if (hasActiveOrders.value) refreshInterval = setInterval(() => router.reload({ only: ['orders'] }), 15000) })
onUnmounted(() => { if (refreshInterval) clearInterval(refreshInterval) })

const toggleOrderSelection = (id: number) => {
  const i = selectedOrders.value.indexOf(id)
  if (i > -1) selectedOrders.value.splice(i, 1)
  else selectedOrders.value.push(id)
}

const confirmDeleteSelected = () => {
  const count = selectedOrders.value.length
  openModal({
    type: 'danger', title: 'Kustuta tellimused', message: `Kas oled kindel, et soovid kustutada ${count} tellimust?`, confirmLabel: 'Kustuta',
    onConfirm: () => {
      router.post('/orders/bulk-delete', { order_ids: selectedOrders.value }, {
        preserveScroll: true,
        onSuccess: () => { success(`${count} tellimust kustutatud`); selectedOrders.value = [] },
        onError: () => error('Kustutamine ebaõnnestus'),
      })
    },
  })
}

const confirmCancel = (orderId: number) => {
  openModal({
    type: 'danger', title: 'Tühista tellimus', message: 'Kas oled kindel, et soovid tellimuse tühistada?', confirmLabel: 'Tühista tellimus',
    onConfirm: () => {
      router.post(`/orders/${orderId}/cancel`, {}, {
        preserveScroll: true,
        onSuccess: () => success('Tellimus tühistatud'),
        onError: () => error('Tühistamine ebaõnnestus'),
      })
    },
  })
}

const confirmDismiss = (orderId: number) => {
  openModal({
    type: 'warning', title: 'Eemalda tellimus', message: 'Kas soovid selle tellimuse nimekirjast eemaldada?', confirmLabel: 'Eemalda',
    onConfirm: () => {
      router.delete(`/orders/${orderId}`, {
        preserveScroll: true,
        onSuccess: () => success('Tellimus eemaldatud'),
        onError: () => error('Eemaldamine ebaõnnestus'),
      })
    },
  })
}

const getStatusClass = (s: string) => ({
  pending:   'px-2.5 py-1 rounded-full bg-yellow-900/30 text-yellow-400 text-xs font-semibold',
  confirmed: 'px-2.5 py-1 rounded-full bg-blue-900/30 text-blue-400 text-xs font-semibold',
  preparing: 'px-2.5 py-1 rounded-full bg-purple-900/30 text-purple-400 text-xs font-semibold',
  ready:     'px-2.5 py-1 rounded-full bg-[#D2691E]/20 text-[#D2691E] text-xs font-semibold',
  completed: 'px-2.5 py-1 rounded-full bg-green-900/30 text-green-400 text-xs font-semibold',
  cancelled: 'px-2.5 py-1 rounded-full bg-white/6 text-gray-400 text-xs font-semibold',
  rejected:  'px-2.5 py-1 rounded-full bg-red-900/30 text-red-400 text-xs font-semibold',
}[s] ?? 'px-2.5 py-1 rounded-full bg-white/6 text-gray-400 text-xs font-semibold')

const getStatusLabel = (s: string) => ({
  pending: 'Ootel', confirmed: 'Kinnitatud', preparing: 'Valmistamisel',
  ready: 'Valmis', completed: 'Täidetud', cancelled: 'Tühistatud', rejected: 'Tagasi lükatud',
}[s] ?? s)

const formatDate = (d: string) => new Date(d).toLocaleString('et-EE', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' })
</script>

<style scoped>
.page-bg { position: fixed; inset: 0; z-index: 0; }
.page-bg-img { width: 100%; height: 100%; background-image: url('/img/main25.jpg'); background-size: cover; background-position: center; filter: brightness(0.28) saturate(0.65); }
.page-bg-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.35) 40%, rgba(0,0,0,0.50) 100%); }
.page-root { position: relative; z-index: 1; min-height: 100vh; }

.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
