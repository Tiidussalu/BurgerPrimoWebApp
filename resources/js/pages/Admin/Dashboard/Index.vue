<template>
  <div class="flex min-h-screen bg-[#0a0a0a] text-white">
    <!-- Sidebar -->
    <aside class="w-[200px] bg-[#111111] border-r border-gray-800 flex flex-col">
      <!-- Logo/Brand -->
      <div class="p-6 border-b border-gray-800">
        <h1 class="text-xl font-bold">Rena</h1>
        <p class="text-sm text-gray-400">Dashboard</p>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 p-4">
        <p class="text-xs text-gray-500 uppercase mb-4">Management</p>
        <div class="space-y-2">
          <Link
            href="/admin/dashboard"
            class="flex items-center gap-3 px-4 py-2 rounded bg-gray-800 text-white"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
          </Link>
          <Link
            href="/admin/orders"
            class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-800 transition text-gray-400 hover:text-white"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Orders
          </Link>
          <Link
            href="/admin/menu"
            class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-800 transition text-gray-400 hover:text-white"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            Menu
          </Link>
        </div>
      </nav>
    </aside>

    

    <!-- Main Content -->
    <main class="flex-1 overflow-auto">
      <!-- Header -->
      <header class="border-b border-gray-800 px-8 py-6">
        <h2 class="text-2xl font-bold">Dashboard</h2>
      </header>

      <!-- Content -->
      <div class="p-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <!-- Total Revenue -->
          <div class="bg-[#111111] rounded-lg p-6">
            <p class="text-sm text-gray-400 mb-2">Kogu tulu</p>
            <p class="text-3xl font-bold text-green-500 mb-1">{{ formatCurrency(stats.totalRevenue) }}</p>
            <p class="text-xs text-gray-500">Sellel kuul</p>
          </div>

          <!-- Total Orders -->
          <div class="bg-[#111111] rounded-lg p-6">
            <p class="text-sm text-gray-400 mb-2">Täidetud tellimused</p>
            <p class="text-3xl font-bold mb-1">{{ stats.totalOrders }}</p>
          </div>

          <!-- Growth -->
          <div class="bg-[#111111] rounded-lg p-6">
            <p class="text-sm text-gray-400 mb-2">Kasvumäär</p>
            <p class="text-3xl font-bold mb-1" :class="stats.growthPercentage >= 0 ? 'text-green-500' : 'text-red-500'">
              {{ stats.growthPercentage >= 0 ? '+' : '' }}{{ stats.growthPercentage }}%
            </p>
            <p class="text-xs text-gray-500">Võrreldes eelmise kuuga</p>
          </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Weekly Sales Bar Chart -->
          <div class="bg-[#111111] rounded-lg p-6">
            <h3 class="text-lg font-bold mb-2">Nädala müük</h3>
            <p class="text-sm text-gray-400 mb-6">Nädalate näitaja müügitulemused</p>
            <div class="h-64">
              <canvas ref="weeklySalesChart"></canvas>
            </div>
          </div>

          <!-- Monthly Orders Line Chart -->
          <div class="bg-[#111111] rounded-lg p-6">
            <h3 class="text-lg font-bold mb-2">Tellimused</h3>
            <p class="text-sm text-gray-400 mb-6">Kuu tellimuste arv ja jooksul</p>
            <div class="h-64">
              <canvas ref="monthlyOrdersChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Popular Products -->
        <div class="bg-[#111111] rounded-lg p-6">
          <h3 class="text-lg font-bold mb-2">Populaarsed tooted</h3>
          <p class="text-sm text-gray-400 mb-6">Täna enim müüdud tooted</p>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
              v-for="product in popularProducts"
              :key="product.name"
              class="bg-[#0a0a0a] rounded-lg p-4 flex items-center gap-4"
            >
              <!-- Product Icon -->
              <div class="w-12 h-12 bg-orange-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
              </div>

              <!-- Product Info -->
              <div class="flex-1 min-w-0">
                <p class="font-semibold truncate">{{ product.name }}</p>
                <p class="text-sm text-gray-400">{{ product.sold }} tükki müüdud</p>
              </div>

              <!-- Price -->
              <div class="text-right">
                <p class="font-bold">{{ formatCurrency(product.price) }}</p>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="popularProducts.length === 0" class="text-center py-8 text-gray-400">
            Andmeid pole veel
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

interface DailySale {
  day: number;
  date: string;
  total: number;
}

interface MonthlyOrder {
  month: number;
  year: number;
  count: number;
}

interface PopularProduct {
  name: string;
  orders: number;
  sold: number;
  price: number;
}

interface Props {
  stats: {
    totalRevenue: number;
    totalOrders: number;
    growthPercentage: number;
  };
  dailySales: DailySale[];
  monthlyOrders: MonthlyOrder[];
  popularProducts: PopularProduct[];
}

const props = defineProps<Props>();

const weeklySalesChart = ref<HTMLCanvasElement | null>(null);
const monthlyOrdersChart = ref<HTMLCanvasElement | null>(null);

const formatCurrency = (amount: number): string => {
  return `${amount.toFixed(2)} €`;
};

const getMonthName = (month: number): string => {
  const months = ['Jan', 'Veb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dets'];
  return months[month - 1] || '';
};

const getMonthShort = (month: number): string => {
  const months = ['Jan', 'Veb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dets'];
  return months[month - 1] || '';
};

const getDayName = (day: number): string => {
  const days = ['Esmaspäev', 'Teisipäev', 'Kolmapäev', 'Neljapäev', 'Reede', 'Laupäev', 'Pühapäev'];
  return days[day - 1] || '';
};

const getDayShort = (day: number): string => {
  const days = ['E', 'T', 'K', 'N', 'R', 'L', 'P'];
  return days[day - 1] || '';
};

onMounted(() => {
  // Weekly Sales Bar Chart (now Daily Sales)
  if (weeklySalesChart.value) {
    new Chart(weeklySalesChart.value, {
      type: 'bar',
      data: {
        labels: props.dailySales.map(item => getDayShort(item.day)),
        datasets: [{
          data: props.dailySales.map(item => item.total),
          backgroundColor: '#d97706',
          borderRadius: 4,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            callbacks: {
              title: (context) => {
                const index = context[0].dataIndex;
                return getDayName(props.dailySales[index].day);
              },
              label: (context) => {
                return `Müük: €${(context.parsed.y ?? 0).toFixed(2)}`;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: '#1f1f1f',
            },
            ticks: {
              color: '#9ca3af',
            },
          },
          x: {
            grid: {
              display: false,
            },
            ticks: {
              color: '#9ca3af',
            },
          },
        },
      },
    });
  }

  // Monthly Orders Line Chart
  if (monthlyOrdersChart.value) {
    new Chart(monthlyOrdersChart.value, {
      type: 'line',
      data: {
        labels: props.monthlyOrders.map(item => getMonthShort(item.month)),
        datasets: [{
          data: props.monthlyOrders.map(item => item.count),
          borderColor: '#10b981',
          backgroundColor: 'transparent',
          tension: 0.4,
          pointBackgroundColor: '#10b981',
          pointBorderColor: '#10b981',
          pointRadius: 4,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            callbacks: {
              title: (context) => {
                const index = context[0].dataIndex;
                const item = props.monthlyOrders[index];
                return `${getMonthName(item.month)} ${item.year}`;
              },
              label: (context) => {
                return `Tellimused: ${context.parsed.y}`;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: '#1f1f1f',
            },
            ticks: {
              color: '#9ca3af',
              stepSize: 1,
            },
          },
          x: {
            grid: {
              display: false,
            },
            ticks: {
              color: '#9ca3af',
            },
          },
        },
      },
    });
  }
});
</script>