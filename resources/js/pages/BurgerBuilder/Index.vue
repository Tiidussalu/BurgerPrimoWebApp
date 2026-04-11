<template>
  <MainLayout>
    <div class="bb-root">

      <!-- PAGE HEADER -->
      <div class="bb-page-header">
        <div class="bb-page-header-inner">
          <p class="bb-eyebrow">Burger Primo</p>
          <h1 class="bb-page-title">Ehita oma burger</h1>
          <p class="bb-page-sub">Vali koostisosad ja loo oma unistuste burger</p>
        </div>
      </div>

      <!-- ═══════════ BUILDER ═══════════ -->
      <section id="bb-builder" class="bb-builder">

        <!-- Vasakul: sticky burger preview -->
        <div class="bb-left">
          <div class="bb-burger-panel">

            <div class="bb-burger-title">
              <span class="bb-eyebrow">Sinu burger</span>
              <span class="bb-burger-name-display" v-if="burgerName">{{ burgerName }}</span>
            </div>

            <!-- Burger visuaal -->
            <div class="bb-burger-visual" ref="burgerVisualRef">
              <div class="bb-burger-glow"></div>
              <div class="bb-burger-stack" ref="burgerStackRef" :style="{ transform: burgerScale }">

                <!-- Shadow -->
                <div class="bb-burger-shadow" :style="{ opacity: totalLayers > 0 ? 0.7 : 0.2, transform: `translateX(-50%) scaleX(${0.4 + totalLayers * 0.08})` }"></div>

                <!-- Top bun — alati -->
                <div class="bb-layer bb-layer-topbun">
                  <canvas ref="cvTopBun" width="240" height="72"></canvas>
                </div>

                <!-- 2 PIHVI: ülemine pihv kohe saia all -->
                <div v-if="pitavCount > 1" class="bb-layer bb-layer-animate" :style="{ top: topPattyTop + 'px' }">
                  <canvas :ref="el => { if(el) pattyCanvases[0] = el as HTMLCanvasElement }" width="228" height="46"></canvas>
                </div>

                <!-- Köögiviljad -->
                <template v-for="(item, idx) in selectedIngredients['salat'] || []" :key="'salat-' + item.id">
                  <div class="bb-layer bb-layer-animate" :style="layerStyle('salat', idx)">
                    <canvas :ref="el => setVegCanvas(el, idx)" width="248" height="24"></canvas>
                  </div>
                </template>

                <!-- Juust -->
                <template v-for="(item, idx) in selectedIngredients['juust'] || []" :key="'juust-' + item.id">
                  <div class="bb-layer bb-layer-animate" :style="layerStyle('juust', idx)">
                    <canvas :ref="el => setCheeseCanvas(el, idx)" width="236" height="18"></canvas>
                  </div>
                </template>

                <!-- Kastmed -->
                <template v-for="(item, idx) in selectedIngredients['lisand'] || []" :key="'lisand-' + item.id">
                  <div class="bb-layer bb-layer-animate" :style="layerStyle('lisand', idx)">
                    <canvas :ref="el => setSauceCanvas(el, idx)" width="224" height="14"></canvas>
                  </div>
                </template>

                <!-- ALUMINE PIHV (1 pihvi puhul ainus, 2 puhul teine) -->
                <div v-if="pitavCount > 0" class="bb-layer bb-layer-animate" :style="{ top: bottomPattyTop + 'px' }">
                  <canvas :ref="el => { if(el) pattyCanvases[pitavCount > 1 ? 1 : 0] = el as HTMLCanvasElement }" width="228" height="46"></canvas>
                </div>

                <!-- Bottom bun — alati -->
                <div class="bb-layer bb-layer-botbun" :style="{ top: bottomBunTop + 'px' }">
                  <canvas ref="cvBotBun" width="240" height="38"></canvas>
                </div>

              </div>
            </div>

            <!-- Valitud koostisosad list -->
            <div class="bb-ingredient-list" v-if="totalLayers > 0">
              <div v-for="item in allSelectedFlat" :key="item.id" class="bb-ing-row">
                <div class="bb-ing-dot" :style="{ background: categoryColor(item.category) }"></div>
                <span class="bb-ing-name">{{ item.name }}</span>
                <span class="bb-ing-qty" v-if="item.quantity > 1">×{{ item.quantity }}</span>
                <span class="bb-ing-price">{{ (item.price * item.quantity).toFixed(2) }}€</span>
              </div>
            </div>
            <div class="bb-ing-empty" v-else>
              Vali paremal koostisosad →
            </div>

            <!-- Koguhind -->
            <div class="bb-price-row" v-if="totalLayers > 0">
              <span>Kokku</span>
              <span class="bb-price-total">{{ totalPrice.toFixed(2) }}€</span>
            </div>

          </div>
        </div>

        <!-- Paremal: valikud -->
        <div class="bb-right">

          <!-- Burgeri nimi -->
          <div class="bb-section">
            <div class="bb-section-header">
              <span class="bb-section-num">01</span>
              <h2 class="bb-section-title">Burgeri nimi</h2>
            </div>
            <input
              v-model="burgerName"
              type="text"
              placeholder="nt. Robby's Special..."
              class="bb-name-input"
            />
          </div>

          <!-- Kotlet -->
          <div class="bb-section" v-if="normalizedIngredients['pitav']?.length">
            <div class="bb-section-header">
              <span class="bb-section-num">02</span>
              <h2 class="bb-section-title">Pihvid</h2>
              <span class="bb-section-hint">vali kuni 2</span>
            </div>
            <div class="bb-grid">
              <div
                v-for="ing in normalizedIngredients['pitav']"
                :key="ing.id"
                class="bb-card"
                :class="{ 'bb-card--active': isSelected('pitav', ing.id) }"
                @click="togglePatty(ing.id)"
              >
                <div class="bb-card-icon">🥩</div>
                <div class="bb-card-name">{{ ing.name }}</div>
                <div class="bb-card-price">{{ Number(ing.price).toFixed(2) }}€</div>
                <div class="bb-card-check" v-if="isSelected('pitav', ing.id)">✓</div>
              </div>
            </div>
          </div>

          <!-- Köögiviljad -->
          <div class="bb-section" v-if="normalizedIngredients['salat']?.length">
            <div class="bb-section-header">
              <span class="bb-section-num">03</span>
              <h2 class="bb-section-title">Köögiviljad</h2>
              <span class="bb-section-hint">vali mitu</span>
            </div>
            <div class="bb-grid">
              <div
                v-for="ing in normalizedIngredients['salat']"
                :key="ing.id"
                class="bb-card"
                :class="{ 'bb-card--active': isSelected('salat', ing.id) }"
                @click="toggleMulti('salat', ing.id)"
              >
                <div class="bb-card-icon">🥬</div>
                <div class="bb-card-name">{{ ing.name }}</div>
                <div class="bb-card-price">{{ Number(ing.price).toFixed(2) }}€</div>
                <div class="bb-card-check" v-if="isSelected('salat', ing.id)">✓</div>
              </div>
            </div>
          </div>

          <!-- Kastmed -->
          <div class="bb-section" v-if="normalizedIngredients['lisand']?.length">
            <div class="bb-section-header">
              <span class="bb-section-num">04</span>
              <h2 class="bb-section-title">Kastmed & lisandid</h2>
              <span class="bb-section-hint">vali mitu</span>
            </div>
            <div class="bb-grid">
              <div
                v-for="ing in normalizedIngredients['lisand']"
                :key="ing.id"
                class="bb-card"
                :class="{ 'bb-card--active': isSelected('lisand', ing.id) }"
                @click="toggleMulti('lisand', ing.id)"
              >
                <div class="bb-card-icon">🫙</div>
                <div class="bb-card-name">{{ ing.name }}</div>
                <div class="bb-card-price">{{ Number(ing.price).toFixed(2) }}€</div>
                <div class="bb-card-check" v-if="isSelected('lisand', ing.id)">✓</div>
              </div>
            </div>
          </div>

          <!-- Juust -->
          <div class="bb-section" v-if="normalizedIngredients['juust']?.length">
            <div class="bb-section-header">
              <span class="bb-section-num">05</span>
              <h2 class="bb-section-title">Juust</h2>
              <span class="bb-section-hint">vali mitu</span>
            </div>
            <div class="bb-grid">
              <div
                v-for="ing in normalizedIngredients['juust']"
                :key="ing.id"
                class="bb-card"
                :class="{ 'bb-card--active': isSelected('juust', ing.id) }"
                @click="toggleMulti('juust', ing.id)"
              >
                <div class="bb-card-icon">🧀</div>
                <div class="bb-card-name">{{ ing.name }}</div>
                <div class="bb-card-price">{{ Number(ing.price).toFixed(2) }}€</div>
                <div class="bb-card-check" v-if="isSelected('juust', ing.id)">✓</div>
              </div>
            </div>
          </div>

          <!-- Tegevused -->
          <div class="bb-actions">
            <div class="bb-actions-price" v-if="totalLayers > 0">
              <span class="bb-actions-price-label">Kogusumma</span>
              <span class="bb-actions-price-val">{{ totalPrice.toFixed(2) }}€</span>
            </div>
            <div class="bb-actions-btns">
              <button @click="saveBurger(false)" :disabled="!canSave" class="bb-btn-secondary">Salvesta</button>
              <button @click="saveBurger(true)"  :disabled="!canSave" class="bb-btn-secondary">♥ Lemmik</button>
              <button @click="saveAndSubmit()" :disabled="!canSave" class="bb-btn-review">
                Saada adminile ↗
              </button>
            </div>
          </div>

        </div>
      </section>

      <!-- ═══════════ MINU BURGERID ═══════════ -->
      <section v-if="customBurgers?.length > 0" class="bb-saved">
        <div class="bb-saved-inner">
          <div class="bb-saved-grid">
            <div v-for="burger in customBurgers" :key="burger.id" class="bb-saved-card">
              <!-- Staatus badge -->
              <div class="bb-saved-card-header">
                <span class="bb-saved-card-name">{{ burger.name }}</span>
                <span class="bb-status-badge" :class="'bb-status-' + (burger.status || 'draft')">
                  {{ statusLabel(burger.status) }}
                </span>
              </div>

              <!-- Hind -->
              <p class="bb-saved-card-price">{{ Number(burger.total_price).toFixed(2) }}€</p>

              <!-- Admin note kui tagasi lükatud -->
              <div v-if="burger.status === 'rejected' && burger.admin_note" class="bb-rejected-note">
                💬 {{ burger.admin_note }}
              </div>

              <!-- Nupud -->
              <div class="bb-saved-card-btns">
                <button @click="loadBurger(burger)" class="bb-saved-btn">Muuda</button>
                <button v-if="burger.status === 'draft' || burger.status === 'rejected'"
                  @click="submitForReviewById(burger.id)" class="bb-saved-btn bb-saved-btn-orange">
                  Saada adminile ↗
                </button>
                <span v-if="burger.status === 'pending'" class="bb-saved-pending-hint">
                  Ootab admini kinnitust...
                </span>
                <button v-if="burger.status === 'approved'"
                  @click="orderSavedBurger(burger.id)" class="bb-saved-btn bb-saved-btn-green">
                  🛒 Lisa ostukorvi
                </button>
                <button @click="deleteBurger(burger.id)" class="bb-saved-btn bb-saved-btn-red">
                  Kustuta
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div>
  </MainLayout>
</template>

<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { useToast } from '@/composables/useToast';
import { ref, computed, onMounted, watch, nextTick } from 'vue';
import { router } from '@inertiajs/vue3';
import CustomBurgerCard from '@/components/CustomBurgerCard.vue';
import type { Ingredient, SelectedIngredient, CustomBurger } from '@/types/burger-types';

const { success, error, warning } = useToast();

interface Props {
  ingredients: Record<string, Ingredient[]> | any;
  customBurgers: CustomBurger[];
  canCreateMore: boolean;
  maxBurgers: number;
}
const props = defineProps<Props>();

// Normaliseeri ingredients
const normalizedIngredients = computed(() => {
  const raw = props.ingredients as any;
  if (!raw) return {};
  if (!Array.isArray(raw)) return raw;
  const result: Record<string, any[]> = {};
  raw.forEach((ing: any) => {
    if (!result[ing.category]) result[ing.category] = [];
    result[ing.category].push(ing);
  });
  return result;
});

const getAllIngredients = () => Object.values(normalizedIngredients.value).flat() as Ingredient[];

// State
const burgerName = ref('');
const selectedIngredients = ref<Record<string, SelectedIngredient[]>>({
  'vöi': [], 'pitav': [], 'salat': [], 'lisand': [], 'juust': [],
});

// Toggle helpers
const isSelected = (cat: string, id: number) =>
  selectedIngredients.value[cat]?.some(i => i.id === id) ?? false;

const togglePatty = (id: number) => {
  const arr = selectedIngredients.value['pitav'] ?? [];
  const idx = arr.findIndex(i => i.id === id);
  if (idx >= 0) {
    selectedIngredients.value['pitav'] = arr.filter(i => i.id !== id);
  } else if (arr.length < 2) {
    selectedIngredients.value['pitav'] = [...arr, { id, quantity: 1 }];
  }
};

const toggleMulti = (cat: string, id: number) => {
  const arr = selectedIngredients.value[cat] ?? [];
  const idx = arr.findIndex(i => i.id === id);
  if (idx >= 0) {
    selectedIngredients.value[cat] = arr.filter(i => i.id !== id);
  } else {
    selectedIngredients.value[cat] = [...arr, { id, quantity: 1 }];
  }
};

// Computed
const allSelectedFlat = computed(() => {
  const result: { id: number; name: string; price: number; quantity: number; category: string }[] = [];
  Object.entries(selectedIngredients.value).forEach(([cat, items]) => {
    items.forEach(item => {
      const ing = getAllIngredients().find(i => i.id === item.id);
      if (ing) result.push({ id: item.id, name: ing.name, price: Number(ing.price), quantity: item.quantity, category: cat });
    });
  });
  return result;
});

const totalLayers = computed(() => allSelectedFlat.value.length);
const totalPrice = computed(() => allSelectedFlat.value.reduce((t, i) => t + i.price * i.quantity, 0));
const canSave = computed(() => burgerName.value.trim() !== '' && totalLayers.value > 0);

const categoryColor = (cat: string) => {
  const map: Record<string, string> = {
    'vöi': '#D4822E', 'pitav': '#8B4513', 'salat': '#4CAF50',
    'lisand': '#FDD835', 'juust': '#F9A825',
  };
  return map[cat] ?? '#666';
};

// Layer positioning
const salatCount = computed(() => selectedIngredients.value['salat']?.length ?? 0);
const juustCount = computed(() => selectedIngredients.value['juust']?.length ?? 0);
const pitavCount = computed(() => selectedIngredients.value['pitav']?.length ?? 0);
const lisandCount = computed(() => selectedIngredients.value['lisand']?.length ?? 0);

const topPattyTop = computed(() => 46);

const fillingStart = computed(() => pitavCount.value > 1 ? 74 : 46);

const fillingHeight = computed(() =>
  salatCount.value * 11 + juustCount.value * 8 + lisandCount.value * 8
);

const layerStyle = (cat: string, idx: number) => {
  let top = fillingStart.value;
  if (cat === 'salat') {
    top = fillingStart.value + idx * 11;
  } else if (cat === 'juust') {
    top = fillingStart.value + salatCount.value * 11 + idx * 8;
  } else if (cat === 'lisand') {
    top = fillingStart.value + salatCount.value * 11 + juustCount.value * 8 + idx * 8;
  }
  return { top: top + 'px' };
};

const bottomPattyTop = computed(() => fillingStart.value + fillingHeight.value + 4);

const burgerScale = computed(() => {
  const totalH = bottomBunTop.value + 38;
  if (totalH > 220) {
    const scale = Math.max(0.5, 220 / totalH);
    return `scale(${scale.toFixed(2)})`;
  }
  return 'scale(1)';
});

const bottomBunTop = computed(() => {
  if (pitavCount.value === 0) return fillingStart.value + fillingHeight.value + 4;
  return bottomPattyTop.value + 28;
});

// Router helpers
const getAllSelected = (): SelectedIngredient[] => {
  const all: SelectedIngredient[] = [];
  Object.values(selectedIngredients.value).forEach(i => all.push(...i));
  return all;
};

const saveBurger = (isFavorite: boolean) => {
  if (!props.canCreateMore) {
    warning(`Oled jõudnud maksimaalse burgeri limiidini (${props.maxBurgers}). Kustuta mõni olemasolev burger.`);
    return;
  }
  router.post('/burger-builder', {
    name: burgerName.value,
    ingredients: getAllSelected(),
    is_favorite: isFavorite,
  } as any, {
    onSuccess: () => { success('Burger salvestatud! 🍔'); burgerName.value = ''; },
    onError: () => error('Salvestamine ebaõnnestus'),
  });
};

const orderBurger = () => {
  router.post('/cart/add-new', {
    name: burgerName.value,
    ingredients: getAllSelected(),
  } as any, {
    onSuccess: () => { success('Burger lisatud ostukorvi! 🛒'); router.visit('/cart'); },
    onError: () => error('Korvi lisamine ebaõnnestus'),
  });
};

const saveAndSubmit = () => {
  if (!canSave.value) return;
  if (!props.canCreateMore) {
    warning(`Oled jõudnud maksimaalse burgeri limiidini (${props.maxBurgers}). Kustuta mõni olemasolev burger.`);
    return;
  }
  router.post('/burger-builder', {
    name: burgerName.value,
    ingredients: getAllSelected(),
    is_favorite: false,
    submit_for_review: true,
  } as any, {
    preserveScroll: true,
    onSuccess: () => {
      success('Burger saadetud adminile! 🍔');
      burgerName.value = '';
      selectedIngredients.value = { 'vöi': [], 'pitav': [], 'salat': [], 'lisand': [], 'juust': [] };
    },
    onError: () => error('Saatmine ebaõnnestus'),
  });
};

const submitForReviewById = (id: number) => {
  router.post(`/burger-builder/${id}/submit`, {}, { preserveScroll: true });
};

const statusLabel = (status: string) => {
  const map: Record<string, string> = {
    draft: 'Mustand', pending: 'Ootab kinnitust',
    approved: 'Kinnitatud', rejected: 'Tagasi lükatud',
  };
  return map[status] ?? status;
};

const orderSavedBurger = (id: number) => router.post('/cart/add', { burger_id: id, quantity: 1 } as any, {
  onSuccess: () => router.visit('/cart'),
});

const deleteBurger = (id: number) => router.delete(`/burger-builder/${id}`, { preserveScroll: true });

const loadBurger = (burger: CustomBurger) => {
  burgerName.value = burger.name;
  selectedIngredients.value = { 'vöi': [], 'pitav': [], 'salat': [], 'lisand': [], 'juust': [] };
  burger.ingredients.forEach(ing => {
    const cat = ing.category;
    if (selectedIngredients.value[cat])
      selectedIngredients.value[cat].push({ id: ing.id, quantity: ing.pivot.quantity });
  });
};

// Canvas refs
const cvTopBun = ref<HTMLCanvasElement | null>(null);
const cvBotBun = ref<HTMLCanvasElement | null>(null);
const vegCanvases = ref<HTMLCanvasElement[]>([]);
const cheeseCanvases = ref<HTMLCanvasElement[]>([]);
const pattyCanvases = ref<HTMLCanvasElement[]>([]);
const sauceCanvases = ref<HTMLCanvasElement[]>([]);

const setVegCanvas    = (el: any, idx: number) => { if (el) vegCanvases.value[idx] = el; };
const setCheeseCanvas = (el: any, idx: number) => { if (el) cheeseCanvases.value[idx] = el; };
const setPattyCanvas  = (el: any, idx: number) => { if (el) pattyCanvases.value[idx] = el; };
const setSauceCanvas  = (el: any, idx: number) => { if (el) sauceCanvases.value[idx] = el; };

// ═══ CANVAS DRAWING ═══
function rnd(a: number, b: number) { return a + Math.random() * (b - a); }

function drawTopBun(cv: HTMLCanvasElement) {
  const c = cv.getContext('2d')!; const w = cv.width, h = cv.height, cx = w / 2;
  c.clearRect(0, 0, w, h);
  c.beginPath(); c.ellipse(cx, h - 5, 132, 10, 0, 0, Math.PI * 2);
  c.fillStyle = 'rgba(0,0,0,0.5)'; c.fill();
  c.beginPath(); c.ellipse(cx, h - 18, 130, 16, 0, 0, Math.PI * 2);
  c.fillStyle = '#7A3D0A'; c.fill();
  c.beginPath();
  c.moveTo(10, h - 18);
  c.bezierCurveTo(cx - 110, 6, cx + 110, 6, w - 10, h - 18);
  c.ellipse(cx, h - 18, 130, 16, 0, 0, Math.PI);
  c.closePath();
  const g = c.createRadialGradient(cx - 20, 20, 4, cx, 36, 92);
  g.addColorStop(0, '#F0A040'); g.addColorStop(0.4, '#D07020'); g.addColorStop(1, '#8A3E08');
  c.fillStyle = g; c.fill();
  const sg = c.createRadialGradient(cx - 18, 16, 2, cx - 8, 24, 46);
  sg.addColorStop(0, 'rgba(255,230,160,0.4)'); sg.addColorStop(1, 'rgba(255,180,80,0)');
  c.beginPath(); c.ellipse(cx - 8, 24, 48, 16, -.2, 0, Math.PI * 2); c.fillStyle = sg; c.fill();
  const seeds: [number, number, number][] = [
    [cx - 58, h - 38, -.32], [cx - 16, h - 50, .05], [cx + 26, h - 48, .2],
    [cx + 60, h - 36, .28], [cx - 34, h - 32, -.16], [cx + 40, h - 32, .14], [cx + 4, h - 44, -.06],
  ];
  seeds.forEach(([sx, sy, rot]) => {
    c.save(); c.translate(sx, sy); c.rotate(rot);
    const s = c.createRadialGradient(-1, -1.5, 1, 0, 0, 8);
    s.addColorStop(0, '#F8E8B0'); s.addColorStop(1, '#B08828');
    c.beginPath(); c.ellipse(0, 0, 8.5, 5, 0, 0, Math.PI * 2); c.fillStyle = s; c.fill();
    c.beginPath(); c.moveTo(-6, 0); c.lineTo(6, 0);
    c.strokeStyle = 'rgba(100,60,8,0.3)'; c.lineWidth = 0.7; c.stroke();
    c.restore();
  });
}

function drawBotBun(cv: HTMLCanvasElement) {
  const c = cv.getContext('2d')!; const w = cv.width, h = cv.height, cx = w / 2;
  c.clearRect(0, 0, w, h);
  c.beginPath(); c.ellipse(cx, h - 3, 135, 7, 0, 0, Math.PI * 2);
  c.fillStyle = 'rgba(0,0,0,0.45)'; c.fill();
  const g = c.createRadialGradient(cx - 16, 10, 2, cx, 18, 88);
  g.addColorStop(0, '#E89838'); g.addColorStop(0.5, '#C07020'); g.addColorStop(1, '#6E3008');
  c.beginPath(); c.ellipse(cx, 18, 133, 18, 0, 0, Math.PI * 2); c.fillStyle = g; c.fill();
  const dg = c.createLinearGradient(0, 26, 0, h - 4);
  dg.addColorStop(0, 'rgba(0,0,0,0)'); dg.addColorStop(1, 'rgba(0,0,0,0.35)');
  c.beginPath(); c.ellipse(cx, 18, 133, 18, 0, 0, Math.PI * 2); c.fillStyle = dg; c.fill();
  const sg = c.createRadialGradient(cx - 22, 10, 1, cx - 8, 14, 44);
  sg.addColorStop(0, 'rgba(255,210,120,0.28)'); sg.addColorStop(1, 'transparent');
  c.beginPath(); c.ellipse(cx - 8, 14, 48, 10, -.18, 0, Math.PI * 2); c.fillStyle = sg; c.fill();
  [[cx - 40, 12, -.18], [cx + 10, 9, .12], [cx + 50, 14, .26]].forEach(([sx, sy, rot]) => {
    c.save(); c.translate(sx, sy); c.rotate(rot);
    const s = c.createRadialGradient(-1, -1, 1, 0, 0, 7);
    s.addColorStop(0, '#F0E098'); s.addColorStop(1, '#A88828');
    c.beginPath(); c.ellipse(0, 0, 7, 4, 0, 0, Math.PI * 2); c.fillStyle = s; c.fill();
    c.restore();
  });
}

function drawVeg(cv: HTMLCanvasElement, id: number) {
  const c = cv.getContext('2d')!; const w = cv.width, h = cv.height;
  c.clearRect(0, 0, w, h);
  const ing = getAllIngredients().find(i => i.id === id);
  const name = ing?.name?.toLowerCase() ?? '';

  if (name.includes('tomat')) {
    [[68, 13, 56], [192, 13, 56]].forEach(([cx, cy, rx]) => {
      const g = c.createRadialGradient(cx - 10, cy - 4, 3, cx, cy, rx);
      g.addColorStop(0, '#FF7060'); g.addColorStop(0.5, '#E03030'); g.addColorStop(1, '#8C1010');
      c.beginPath(); c.ellipse(cx, cy, rx, 11, 0, 0, Math.PI * 2); c.fillStyle = g; c.fill();
      [-14, 0, 14].forEach(ox => { c.fillStyle = 'rgba(255,210,200,0.55)'; c.beginPath(); c.ellipse(cx + ox, cy + 1, 5, 3, .15, 0, Math.PI * 2); c.fill(); });
      c.beginPath(); c.ellipse(cx - 12, cy - 4, 16, 6, -.3, 0, Math.PI * 2); c.fillStyle = 'rgba(255,200,190,0.3)'; c.fill();
    });
  } else if (name.includes('kurk')) {
    [[68, 13, 54], [192, 13, 54]].forEach(([cx, cy, rx]) => {
      const g = c.createRadialGradient(cx - 8, cy - 3, 2, cx, cy, rx);
      g.addColorStop(0, '#B0DA70'); g.addColorStop(0.6, '#68A828'); g.addColorStop(1, '#386810');
      c.beginPath(); c.ellipse(cx, cy, rx, 10, 0, 0, Math.PI * 2); c.fillStyle = g; c.fill();
      c.strokeStyle = 'rgba(40,90,10,0.28)'; c.lineWidth = 1;
      for (let i = -3; i <= 3; i++) { c.beginPath(); c.moveTo(cx + i * 12, cy - 9); c.lineTo(cx + i * 10, cy + 9); c.stroke(); }
      c.beginPath(); c.ellipse(cx - 10, cy - 4, 16, 6, -.25, 0, Math.PI * 2); c.fillStyle = 'rgba(200,255,140,0.28)'; c.fill();
    });
  } else if (name.includes('sibul') || name.includes('kastrull')) {
    [[68, 13], [192, 13]].forEach(([cx, cy]) => {
      [50, 34, 20].forEach((rx, i) => {
        c.beginPath(); c.ellipse(cx, cy, rx, 11 - i * 2, 0, 0, Math.PI * 2);
        c.strokeStyle = ['rgba(205,115,215,0.9)', 'rgba(180,88,195,0.72)', 'rgba(158,68,178,0.52)'][i];
        c.lineWidth = i === 0 ? 5.5 : 3.5; c.stroke();
      });
    });
  } else if (name.includes('avocado')) {
    [[68, 13, 54], [192, 13, 54]].forEach(([cx, cy, rx]) => {
      const g = c.createRadialGradient(cx, cy, 4, cx, cy, rx);
      g.addColorStop(0, '#D0EC70'); g.addColorStop(0.5, '#78B830'); g.addColorStop(1, '#3A6810');
      c.beginPath(); c.ellipse(cx, cy, rx, 11, 0, 0, Math.PI * 2); c.fillStyle = g; c.fill();
      c.beginPath(); c.ellipse(cx, cy, rx * 0.36, 7, 0, 0, Math.PI * 2); c.fillStyle = '#8B5E3C'; c.fill();
      c.beginPath(); c.ellipse(cx - 8, cy - 3, 15, 6, -.2, 0, Math.PI * 2); c.fillStyle = 'rgba(230,255,170,0.28)'; c.fill();
    });
  } else {
    const pts: [number, number][] = [];
    for (let x = 0; x <= w; x += 10) pts.push([x, 8 + Math.sin(x * .09) * 7 + Math.sin(x * .17) * 4]);
    c.beginPath(); c.moveTo(-2, h + 2); c.lineTo(pts[0][0], pts[0][1]);
    for (let i = 1; i < pts.length; i++) c.quadraticCurveTo(pts[i-1][0], pts[i-1][1], (pts[i-1][0]+pts[i][0])/2, (pts[i-1][1]+pts[i][1])/2);
    c.lineTo(w + 2, h + 2); c.closePath();
    const g = c.createLinearGradient(0, 0, 0, h);
    g.addColorStop(0, '#70D858'); g.addColorStop(1, '#2E9018');
    c.fillStyle = g; c.fill();
  }
}

function drawCheese(cv: HTMLCanvasElement, id: number) {
  const c = cv.getContext('2d')!; const w = cv.width;
  c.clearRect(0, 0, w, cv.height);
  const ing = getAllIngredients().find(i => i.id === id);
  const name = ing?.name?.toLowerCase() ?? '';
  let col1 = '#FFD030', col2 = '#D09800';
  if (name.includes('mozzarella')) { col1 = '#FFF5E8'; col2 = '#E0D0B0'; }
  if (name.includes('blue'))       { col1 = '#C8D4E8'; col2 = '#7888A0'; }
  const g = c.createLinearGradient(0, 0, 0, cv.height);
  g.addColorStop(0, col1); g.addColorStop(1, col2);
  c.fillStyle = g; c.beginPath(); (c as any).roundRect(2, 2, w - 4, 13, 3); c.fill();
  [6, 40, 86, 140, 196, w - 9].forEach(dx => {
    const dh = 5 + Math.random() * 8;
    c.beginPath(); c.moveTo(dx - 3, 13); c.bezierCurveTo(dx - 4, 13 + dh*.4, dx + 6, 13 + dh*.6, dx + 3, 13 + dh);
    c.bezierCurveTo(dx + 5, 13 + dh*.6, dx - 1, 13 + dh*.3, dx - 3, 13); c.fillStyle = col2; c.fill();
  });
  if (name.includes('blue')) {
    c.fillStyle = 'rgba(70,90,150,0.38)';
    for (let i = 0; i < 9; i++) { c.beginPath(); c.ellipse(18 + i * 24 + rnd(-4,4), 7 + rnd(-2,2), rnd(3,6), rnd(2,4), rnd(-.3,.3), 0, Math.PI*2); c.fill(); }
  }
  c.beginPath(); (c as any).roundRect(8, 2, w - 16, 4, 2); c.fillStyle = 'rgba(255,255,255,0.3)'; c.fill();
}

function drawPatty(cv: HTMLCanvasElement, id: number) {
  const c = cv.getContext('2d')!; const w = cv.width, cx = w / 2;
  c.clearRect(0, 0, w, cv.height);
  const ing = getAllIngredients().find(i => i.id === id);
  const name = ing?.name?.toLowerCase() ?? '';

  let dark = '#1A0800', mid = '#3A1C08', lightC = '#9A5828', lightR = '#A06030';
  if (name.includes('kana'))  { dark='#1E1000'; mid='#4A2C10'; lightC='#C09050'; lightR='#B07830'; }
  if (name.includes('vegan')) { dark='#182008'; mid='#304018'; lightC='#809850'; lightR='#607030'; }
  if (name.includes('grill')) { dark='#0E0500'; mid='#280E04'; lightC='#7A3818'; lightR='#602010'; }

  c.beginPath(); c.ellipse(cx, 36, w/2-4, 13, 0, 0, Math.PI*2); c.fillStyle = dark; c.fill();
  c.beginPath(); c.ellipse(cx, 30, w/2-6, 13, 0, 0, Math.PI*2); c.fillStyle = mid; c.fill();
  const g = c.createRadialGradient(cx - 20, 14, 4, cx, 22, 72);
  g.addColorStop(0, lightR); g.addColorStop(0.4, lightC); g.addColorStop(1, dark);
  c.beginPath(); c.ellipse(cx, 22, w/2-8, 13, 0, 0, Math.PI*2); c.fillStyle = g; c.fill();
  c.fillStyle = 'rgba(180,100,30,0.08)';
  for (let i = 0; i < 14; i++) { c.beginPath(); c.ellipse(rnd(18,w-18), rnd(10,28), rnd(5,14), rnd(3,7), rnd(-.5,.5), 0, Math.PI*2); c.fill(); }
  const marks = name.includes('grill') ? 4 : 3;
  for (let i = 0; i < marks; i++) {
    const x1 = 32 + i * 46, y1 = 10, x2 = x1 + 28, y2 = 30;
    c.beginPath(); c.moveTo(x1, y1); c.lineTo(x2, y2);
    c.strokeStyle = 'rgba(6,1,0,0.88)'; c.lineWidth = name.includes('grill') ? 5 : 3.5; c.lineCap = 'round'; c.stroke();
    c.beginPath(); c.moveTo(x1, y1); c.lineTo(x2, y2);
    c.strokeStyle = 'rgba(180,80,0,0.22)'; c.lineWidth = name.includes('grill') ? 9 : 6; c.stroke();
  }
  const sg = c.createRadialGradient(cx - 22, 14, 2, cx - 6, 18, 52);
  sg.addColorStop(0, 'rgba(255,160,60,0.18)'); sg.addColorStop(1, 'transparent');
  c.beginPath(); c.ellipse(cx - 6, 18, 52, 11, -.2, 0, Math.PI*2); c.fillStyle = sg; c.fill();
}

function drawSauce(cv: HTMLCanvasElement, id: number) {
  const c = cv.getContext('2d')!; const w = cv.width, h = cv.height;
  c.clearRect(0, 0, w, h);
  const ing = getAllIngredients().find(i => i.id === id);
  const name = ing?.name?.toLowerCase() ?? '';
  let col1 = '#F8D840', col2 = '#D4A800';
  if (name.includes('ketšup'))   { col1 = '#FF5040'; col2 = '#BE1818'; }
  if (name.includes('majonees')) { col1 = '#FFFFF2'; col2 = '#E4E0C0'; }
  if (name.includes('bbq'))      { col1 = '#922010'; col2 = '#4C0A06'; }
  if (name.includes('chipotle')) { col1 = '#C84020'; col2 = '#841808'; }
  if (name.includes('peekon'))   { col1 = '#D04040'; col2 = '#8A1818'; }
  if (name.includes('muna'))     { col1 = '#FFE880'; col2 = '#E0C020'; }
  const pts: [number, number][] = [];
  for (let x = 0; x <= w; x += 10) pts.push([x, 5 + Math.sin(x * .1) * 3 + Math.sin(x * .06 + 1) * 2]);
  c.beginPath(); c.moveTo(-2, h + 2); c.lineTo(pts[0][0], pts[0][1]);
  for (let i = 1; i < pts.length; i++) c.quadraticCurveTo(pts[i-1][0], pts[i-1][1], (pts[i-1][0]+pts[i][0])/2, (pts[i-1][1]+pts[i][1])/2);
  c.lineTo(w + 2, h + 2); c.closePath();
  const g = c.createLinearGradient(0, 0, 0, h);
  g.addColorStop(0, col1); g.addColorStop(1, col2);
  c.fillStyle = g; c.fill();
  c.fillStyle = 'rgba(255,255,255,0.15)'; c.fillRect(0, 0, w, 3);
}

// Watch & redraw
watch(selectedIngredients, async () => {
  await nextTick(); await nextTick();
  if (cvTopBun.value) drawTopBun(cvTopBun.value);
  if (cvBotBun.value) drawBotBun(cvBotBun.value);
  const salat = selectedIngredients.value['salat'] ?? [];
  salat.forEach((item, idx) => { if (vegCanvases.value[idx]) drawVeg(vegCanvases.value[idx], item.id); });
  const juust = selectedIngredients.value['juust'] ?? [];
  juust.forEach((item, idx) => { if (cheeseCanvases.value[idx]) drawCheese(cheeseCanvases.value[idx], item.id); });
  await nextTick();
  const pitav = selectedIngredients.value['pitav'] ?? [];
  pitav.forEach((item, idx) => { if (pattyCanvases.value[idx]) drawPatty(pattyCanvases.value[idx], item.id); });
  const lisand = selectedIngredients.value['lisand'] ?? [];
  lisand.forEach((item, idx) => { if (sauceCanvases.value[idx]) drawSauce(sauceCanvases.value[idx], item.id); });
}, { deep: true });

onMounted(async () => {
  await nextTick();
  if (cvTopBun.value) drawTopBun(cvTopBun.value);
  if (cvBotBun.value) drawBotBun(cvBotBun.value);
});
</script>

<style scoped>
* { box-sizing: border-box; }
.bb-root { background: #0B0B0B; color: #fff; min-height: 100vh; }

/* ── PAGE HEADER ── */
.bb-page-header {
  background: #0B0B0B;
  border-bottom: 1px solid #141414;
  padding: 4rem 3rem 3rem;
}
.bb-page-header-inner {
  max-width: 1300px;
  margin: 0 auto;
}
.bb-eyebrow {
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .18em;
  text-transform: uppercase;
  color: #D2691E;
  margin-bottom: .75rem;
  display: block;
}
.bb-page-title {
  font-size: clamp(2rem, 5vw, 3.5rem);
  font-weight: 900;
  color: #fff;
  letter-spacing: -.02em;
  margin-bottom: .5rem;
}
.bb-page-sub {
  font-size: 1rem;
  color: #555;
}

/* ── BUILDER ── */
.bb-builder {
  display: grid;
  grid-template-columns: 420px 1fr;
  align-items: start;
  border-bottom: 1px solid #141414;
}
@media (max-width: 960px) { .bb-builder { grid-template-columns: 1fr; } }

/* LEFT */
.bb-left {
  border-right: 1px solid #141414;
  position: sticky;
  top: 64px;
  height: calc(100vh - 64px);
  overflow: hidden;
}
.bb-burger-panel {
  height: 100%;
  overflow-y: auto;
  padding: 2.5rem 2rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  scrollbar-width: none;
}
.bb-burger-panel::-webkit-scrollbar { display: none; }
.bb-burger-title {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.bb-burger-name-display {
  font-size: .8rem;
  color: #D2691E;
  font-weight: 600;
  max-width: 160px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

/* Burger visuaal */
.bb-burger-visual {
  position: relative;
  width: 100%;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  height: 240px;
  overflow: hidden;
  background: radial-gradient(ellipse at 50% 100%, rgba(200,100,20,0.08) 0%, transparent 70%);
  border-radius: 10px;
}
.bb-burger-glow { display: none; }
.bb-burger-stack {
  position: relative;
  width: 240px;
  margin: 8px auto 0;
  filter: drop-shadow(0 12px 24px rgba(180,90,10,0.25));
  transform-origin: top center;
}
.bb-burger-shadow {
  position: absolute;
  bottom: -8px; left: 50%;
  width: 260px; height: 16px;
  border-radius: 50%;
  background: radial-gradient(ellipse, rgba(150,70,8,0.55) 0%, transparent 70%);
  transition: opacity .3s, transform .3s;
}
.bb-layer {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
}
.bb-layer-topbun { top: 4px; width: 240px; }
.bb-layer-botbun { width: 240px; transition: top .3s ease; }
.bb-layer-animate {
  transition: top .25s ease;
  animation: layerIn .35s cubic-bezier(.22,1,.36,1);
}
@keyframes layerIn {
  from { opacity: 0; transform: translateX(-50%) translateY(-18px) scaleX(.88); }
  to   { opacity: 1; transform: translateX(-50%) translateY(0) scaleX(1); }
}

/* Ingredient list */
.bb-ingredient-list {
  display: flex;
  flex-direction: column;
  gap: 2px;
  border-top: 1px solid #161616;
  padding-top: 1rem;
}
.bb-ing-row {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 5px 0;
  border-bottom: 1px solid #0e0e0e;
}
.bb-ing-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  flex-shrink: 0;
}
.bb-ing-name { flex: 1; font-size: .85rem; color: #aaa; }
.bb-ing-qty  { font-size: .75rem; color: #444; }
.bb-ing-price { font-size: .85rem; color: #D2691E; font-weight: 600; min-width: 46px; text-align: right; }
.bb-ing-empty { font-size: .85rem; color: #333; padding: .5rem 0; }

.bb-price-row {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  padding: .75rem 0 0;
  border-top: 1px solid #1a1a1a;
  font-size: .75rem;
  color: #555;
  letter-spacing: .1em;
  text-transform: uppercase;
}
.bb-price-total { font-size: 1.8rem; font-weight: 900; color: #D2691E; }

/* RIGHT */
.bb-right {
  padding: 3rem 3rem 6rem;
  display: flex;
  flex-direction: column;
  gap: 3rem;
}
@media (max-width: 960px) { .bb-right { padding: 2rem 1.5rem 4rem; } }

.bb-section {}
.bb-section-header {
  display: flex;
  align-items: baseline;
  gap: 1rem;
  margin-bottom: 1.2rem;
  padding-bottom: .8rem;
  border-bottom: 1px solid #141414;
}
.bb-section-num {
  font-size: .72rem;
  font-weight: 700;
  color: #D2691E;
  letter-spacing: .1em;
}
.bb-section-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: #fff;
  flex: 1;
}
.bb-section-hint {
  font-size: .7rem;
  color: #333;
  letter-spacing: .08em;
  text-transform: uppercase;
}

.bb-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
  gap: 8px;
}

/* Cards */
.bb-card {
  background: #111;
  border: 1px solid #1c1c1c;
  border-radius: 8px;
  padding: 1rem;
  cursor: pointer;
  transition: border-color .18s, background .18s, transform .14s;
  position: relative;
  user-select: none;
}
.bb-card:hover {
  border-color: #2a2a2a;
  background: #161616;
  transform: translateY(-2px);
}
.bb-card--active {
  border-color: #D2691E !important;
  background: rgba(210,105,30,.07) !important;
}
.bb-card-icon { font-size: 1.1rem; margin-bottom: .4rem; }
.bb-card-name { font-size: .88rem; font-weight: 500; color: #ccc; margin-bottom: .3rem; line-height: 1.3; }
.bb-card-price { font-size: .8rem; color: #D2691E; font-weight: 600; }
.bb-card-check {
  position: absolute;
  top: 6px; right: 6px;
  width: 18px; height: 18px;
  border-radius: 50%;
  background: #D2691E;
  display: flex; align-items: center; justify-content: center;
  font-size: .62rem; color: #fff; font-weight: 800;
}

.bb-name-input {
  width: 100%;
  background: #111;
  border: 1px solid #1c1c1c;
  border-radius: 6px;
  padding: .85rem 1.1rem;
  color: #fff;
  font-size: .95rem;
  outline: none;
  transition: border-color .18s;
}
.bb-name-input:focus { border-color: #D2691E; }

/* Actions */
.bb-actions {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  padding-top: 1.5rem;
  border-top: 1px solid #141414;
}
.bb-actions-price {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
}
.bb-actions-price-label { font-size: .72rem; color: #444; text-transform: uppercase; letter-spacing: .1em; }
.bb-actions-price-val { font-size: 2rem; font-weight: 900; color: #D2691E; }
.bb-actions-btns { display: flex; gap: .6rem; flex-wrap: wrap; }

.bb-btn-primary {
  background: #D2691E;
  color: #fff;
  border: none;
  padding: .85rem 1.6rem;
  border-radius: 6px;
  font-size: .9rem;
  font-weight: 700;
  cursor: pointer;
  transition: background .18s, opacity .18s;
  flex: 1;
}
.bb-btn-primary:hover { background: #E07A2E; }
.bb-btn-primary:disabled { opacity: .28; cursor: not-allowed; }

.bb-btn-review {
  background: transparent;
  color: #D2691E;
  border: 1px solid #D2691E;
  padding: .85rem 1.6rem;
  border-radius: 6px;
  font-size: .9rem;
  font-weight: 700;
  cursor: pointer;
  transition: background .18s, opacity .18s;
  white-space: nowrap;
}
.bb-btn-review:hover { background: rgba(210,105,30,.1); }
.bb-btn-review:disabled { opacity: .28; cursor: not-allowed; }

.bb-btn-secondary {
  background: transparent;
  color: #888;
  border: 1px solid #222;
  padding: .85rem 1.2rem;
  border-radius: 6px;
  font-size: .85rem;
  font-weight: 600;
  cursor: pointer;
  transition: border-color .18s, color .18s;
}
.bb-btn-secondary:hover { border-color: #444; color: #ccc; }
.bb-btn-secondary:disabled { opacity: .28; cursor: not-allowed; }

/* SAVED */
.bb-saved { padding: 3rem 0 5rem; border-top: 1px solid #141414; }
.bb-saved-card { background:#111; border:1px solid #1c1c1c; border-radius:12px; padding:1.2rem; display:flex; flex-direction:column; gap:.75rem; }
.bb-saved-card-header { display:flex; align-items:center; justify-content:space-between; gap:.5rem; flex-wrap:wrap; }
.bb-saved-card-name { font-size:1rem; font-weight:700; color:#fff; }
.bb-saved-card-price { font-size:1.1rem; font-weight:700; color:#D2691E; }
.bb-status-badge { font-size:.68rem; font-weight:700; padding:3px 8px; border-radius:99px; text-transform:uppercase; letter-spacing:.06em; }
.bb-status-draft    { background:rgba(100,100,100,.15); color:#888; border:1px solid #333; }
.bb-status-pending  { background:rgba(250,200,0,.1); color:#F0B800; border:1px solid rgba(250,200,0,.25); }
.bb-status-approved { background:rgba(34,197,94,.1); color:#22c55e; border:1px solid rgba(34,197,94,.25); }
.bb-status-rejected { background:rgba(239,68,68,.1); color:#ef4444; border:1px solid rgba(239,68,68,.25); }
.bb-rejected-note { font-size:.8rem; color:#888; background:#0e0e0e; border:1px solid #1a1a1a; border-radius:6px; padding:.5rem .75rem; }
.bb-saved-card-btns { display:flex; gap:.5rem; flex-wrap:wrap; align-items:center; }
.bb-saved-pending-hint { font-size:.78rem; color:#F0B800; font-style:italic; }
.bb-saved-approved-hint { font-size:.78rem; color:#22c55e; font-weight:600; }
.bb-saved-btn { background:transparent; border:1px solid #222; color:#888; padding:.5rem .9rem; border-radius:6px; font-size:.8rem; font-weight:600; cursor:pointer; transition:all .15s; }
.bb-saved-btn:hover { border-color:#444; color:#ccc; }
.bb-saved-btn-orange { border-color:#D2691E !important; color:#D2691E !important; }
.bb-saved-btn-orange:hover { background:rgba(210,105,30,.1) !important; }
.bb-saved-btn-green { border-color:#22c55e !important; color:#22c55e !important; }
.bb-saved-btn-green:hover { background:rgba(34,197,94,.1) !important; }
.bb-saved-btn-red { border-color:#ef4444 !important; color:#ef4444 !important; }
.bb-saved-btn-red:hover { background:rgba(239,68,68,.1) !important; }
.bb-saved-inner { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
.bb-saved-title { font-size: clamp(2rem, 6vw, 4rem); font-weight: 900; color: #fff; margin: .4rem 0 .3rem; letter-spacing: -.02em; }
.bb-saved-count { font-size: .8rem; color: #333; margin-bottom: 2.5rem; }
.bb-saved-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.2rem; }
</style>