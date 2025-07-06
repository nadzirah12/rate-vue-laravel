<template>
  <div class="p-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-2xl font-bold text-gray-800">
        Currency rate as of {{ formatDate(selectedDate) }}
      </h2>
      <input type="date" v-model="selectedDate" class="border p-2 rounded" @change="onDateChange"/>
    </div>


    <div ref="container" class="overflow-auto" style="height: 30vh;">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
        <div
          v-for="rate in rates"
          :key="rate.id"
          class="p-4 rounded-xl shadow-sm border border-gray-200 bg-white flex justify-between items-center"
        >
          {{ rate.id }}
          <span class="text-sm text-gray-700 font-medium">
            {{ rate.base_currency.code }} → {{ rate.target_currency.code }}
          </span>
          <span class="text-sm text-gray-900 font-semibold">
            {{ parseFloat(rate.rate) }}
          </span>
        </div>
      </div>
    </div>

    <div ref="loadMoreTrigger" class="h-1"></div>

    <!-- Spinner -->
    <div v-if="loading" class="flex justify-center mt-4">
      <svg class="animate-spin h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
      </svg>
    </div>

    <div v-else-if="!hasMore" class="text-center mt-4 text-gray-400">No more data.</div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const rates = ref([...usePage().props.rates.data]);
const page = ref(usePage().props.rates.current_page);
const lastPage = usePage().props.rates.last_page;
const loading = ref(false);
const hasMore = ref(page.value < lastPage);
const loadMoreTrigger = ref(null);
const container = ref(null);

const selectedDate = ref()

const onScroll = () => {
  if (!hasMore.value || loading.value) return;

  const scrollBottom = container.value.scrollTop + container.value.clientHeight;
  const scrollHeight = container.value.scrollHeight;

  if (scrollBottom >= scrollHeight - 100) {
    loadMore();
  }
};

const loadMore = () => {
  if (loading.value || !hasMore.value) return;

  loading.value = true;
  
  // Use next page number for query
  const nextPage = page.value + 1;
  
  router.get('/', { page: nextPage }, {
    preserveScroll: true,
    preserveState: true,
    only: ['rates'],
    onSuccess: () => {
      const newRates = usePage().props.rates.data;

      // Append new rates to existing list
      rates.value.push(...newRates);

      page.value = usePage().props.rates.current_page;
      hasMore.value = page.value < usePage().props.rates.last_page;

      loading.value = false;
    },
    onError: () => {
      loading.value = false;
    },
  });
};

onMounted(() => {
  if (container.value) {
    container.value.addEventListener('scroll', onScroll);
  }
});

onUnmounted(() => {
  if (container.value) {
    container.value.removeEventListener('scroll', onScroll);
  }
});

function formatDate(dateStr) {
  if (!dateStr) return '';
  const [year, month, day] = dateStr.split('-');
  return `${day}-${month}-${year}`;
}

const onDateChange = () => {
  // Reset pagination and rates on date change for display
  page.value = 1;
  hasMore.value = true;
  rates.value = [];
  loading.value = true;

  router.get(
    '/', 
    { page: 1, date: selectedDate.value },
    {
      preserveScroll: true,
      preserveState: true,
      only: ['rates'],
      onSuccess: () => {
        const newRates = usePage().props.rates.data;
        rates.value = newRates;
        page.value = usePage().props.rates.current_page;
        hasMore.value = page.value < usePage().props.rates.last_page;
        loading.value = false;
      },
      onError: () => {
        loading.value = false;
      },
    }
  );
};

</script>
