<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
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
  return `€${amount.toFixed(2)}`;
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
          backgroundColor: '#ea580c',
          borderRadius: 6,
          borderSkipped: false,
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
            backgroundColor: '#1a1a1a',
            titleColor: '#fff',
            bodyColor: '#fff',
            borderColor: '#374151',
            borderWidth: 1,
            padding: 12,
            displayColors: false,
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
              callback: function(value) {
                return '€' + value;
              }
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
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          fill: true,
          tension: 0.4,
          pointBackgroundColor: '#10b981',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 7,
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
            backgroundColor: '#1a1a1a',
            titleColor: '#fff',
            bodyColor: '#fff',
            borderColor: '#374151',
            borderWidth: 1,
            padding: 12,
            displayColors: false,
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

<template>
  <AdminLayout>
    <template #header>
      <h2 class="text-xl lg:text-2xl font-bold">Dashboard</h2>
    </template>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
      <!-- Total Revenue -->
      <div class="bg-[#111111] rounded-xl p-6 border border-gray-800 hover:border-gray-700 transition-colors">
        <div class="flex items-start justify-between mb-4">
          <p class="text-sm text-gray-400 font-medium">Kogu tulu</p>
          <div class="w-10 h-10 rounded-lg bg-green-600/10 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
              <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-green-500 mb-1">{{ formatCurrency(stats.totalRevenue) }}</p>
        <p class="text-xs text-gray-500">Sellel kuul</p>
      </div>

      <!-- Total Orders -->
      <div class="bg-[#111111] rounded-xl p-6 border border-gray-800 hover:border-gray-700 transition-colors">
        <div class="flex items-start justify-between mb-4">
          <p class="text-sm text-gray-400 font-medium">Täidetud tellimused</p>
          <div class="w-10 h-10 rounded-lg bg-blue-600/10 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
              <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold mb-1">{{ stats.totalOrders }}</p>
        <p class="text-xs text-gray-500">Sellel kuul</p>
      </div>

      <!-- Growth -->
      <div class="bg-[#111111] rounded-xl p-6 border border-gray-800 hover:border-gray-700 transition-colors">
        <div class="flex items-start justify-between mb-4">
          <p class="text-sm text-gray-400 font-medium">Kasvumäär</p>
          <div :class="[
            'w-10 h-10 rounded-lg flex items-center justify-center',
            stats.growthPercentage >= 0 ? 'bg-green-600/10' : 'bg-red-600/10'
          ]">
            <svg v-if="stats.growthPercentage >= 0" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold mb-1" :class="stats.growthPercentage >= 0 ? 'text-green-500' : 'text-red-500'">
          {{ stats.growthPercentage >= 0 ? '+' : '' }}{{ stats.growthPercentage }}%
        </p>
        <p class="text-xs text-gray-500">Võrreldes eelmise kuuga</p>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
      <!-- Weekly Sales Bar Chart -->
      <div class="bg-[#111111] rounded-xl p-6 border border-gray-800">
        <div class="mb-6">
          <h3 class="text-lg font-bold mb-1">Nädala müük</h3>
          <p class="text-sm text-gray-400">Nädalate näitaja müügitulemused</p>
        </div>
        <div class="h-64">
          <canvas ref="weeklySalesChart"></canvas>
        </div>
      </div>

      <!-- Monthly Orders Line Chart -->
      <div class="bg-[#111111] rounded-xl p-6 border border-gray-800">
        <div class="mb-6">
          <h3 class="text-lg font-bold mb-1">Tellimused</h3>
          <p class="text-sm text-gray-400">Kuu tellimuste arv ja jooksul</p>
        </div>
        <div class="h-64">
          <canvas ref="monthlyOrdersChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Popular Products -->
    <div class="bg-[#111111] rounded-xl p-6 border border-gray-800">
      <div class="mb-6">
        <h3 class="text-lg font-bold mb-1">Populaarsed tooted</h3>
        <p class="text-sm text-gray-400">Täna enim müüdud tooted</p>
      </div>
      
      <div v-if="popularProducts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
          v-for="product in popularProducts"
          :key="product.name"
          class="bg-[#0a0a0a] rounded-lg p-4 flex items-center gap-4 border border-gray-800 hover:border-gray-700 transition-colors"
        >
          <!-- Product Icon -->
          <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
              <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
            </svg>
          </div>

          <!-- Product Info -->
          <div class="flex-1 min-w-0">
            <p class="font-semibold truncate text-white">{{ product.name }}</p>
            <p class="text-sm text-gray-400">{{ product.sold }} tükki müüdud</p>
          </div>

          <!-- Price -->
          <div class="text-right">
            <p class="font-bold text-orange-500">{{ formatCurrency(product.price) }}</p>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
          </svg>
        </div>
        <p class="text-gray-400 mb-1">Andmeid pole veel</p>
        <p class="text-sm text-gray-500">Populaarsed tooted kuvatakse pärast tellimusi</p>
      </div>
    </div>
  </AdminLayout>
</template>