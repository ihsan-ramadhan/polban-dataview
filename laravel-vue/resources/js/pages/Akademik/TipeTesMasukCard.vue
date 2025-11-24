<template>
  <div class="chart-card h-full">
    <div class="card-header-row">
      <div>
        <h2 class="chart-title">Rasio Tipe Tes Masuk</h2>
        <p class="chart-subtitle">
          Distribusi per {{ selectedAngkatan === 'all' ? 'Semua Angkatan' : selectedAngkatan }} 
          - {{ selectedProdi === 'all' ? 'Semua Prodi' : selectedProdi }}
        </p>
      </div>
      <div class="filter-controls">
        <select v-model="selectedAngkatan" @change="fetchData" class="filter-select">
          <option value="all">Semua Angkatan</option>
          <option value="2024">2024</option>
          <option value="2023">2023</option>
        </select>
        <select v-model="selectedProdi" @change="fetchData" class="filter-select">
          <option value="all">Semua Prodi</option>
          <option value="D3-TI">D3-TI</option>
          <option value="D4-TI">D4-TI</option>
        </select>
      </div>
    </div>

    <div class="chart-container">
      <div v-if="isLoading" class="loading-placeholder">Memuat data...</div>
      <div v-else-if="errorMessage" class="error-placeholder">{{ errorMessage }}</div>
      <BasePieChart v-else-if="chartData" :chart-data="chartData" />
    </div>
  </div>
</template>

<script>
import BasePieChart from '../../components/Charts/BasePieChart.vue';

export default {
  name: 'TipeTesMasukCard',
  components: { BasePieChart },
  data() {
    return {
      chartData: null,
      isLoading: true,
      errorMessage: null,
      selectedAngkatan: 'all',
      selectedProdi: 'all',
      chartColors: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      this.isLoading = true;
      this.errorMessage = null;
      this.chartData = null;

      try {
        const baseUrl = 'http://localhost:6767/api/v1/akademik/tipe-tes-masuk';
        const params = new URLSearchParams({
          angkatan: this.selectedAngkatan,
          prodi: this.selectedProdi
        });

        const fullUrl = `${baseUrl}?${params}`;
        console.log('🔗 URL Request:', fullUrl);

        const response = await fetch(fullUrl);
        console.log('📡 Response Status:', response.status, response.statusText);
        
        if (!response.ok) throw new Error('Gagal mengambil data dari server');
        
        const json = await response.json();
        console.log('📦 Full JSON Response:', json);
        console.log('📊 json.data:', json.data);
        
        // Backend sudah handle filtering, tinggal ambil data aja
        const dataFinal = json.data;
        
        if (!dataFinal || dataFinal.length === 0) {
          throw new Error('Data tidak ditemukan');
        }

        console.log('✅ Data Final:', dataFinal);

        // Mapping ke Chart
        this.chartData = {
          labels: dataFinal.map(item => item.tipe),
          datasets: [{
            backgroundColor: this.chartColors.slice(0, dataFinal.length),
            data: dataFinal.map(item => item.total)
          }]
        };

      } catch (e) {
        console.error(e);
        this.errorMessage = e.message;
      } finally {
        this.isLoading = false;
      }
    }
  }
};
</script>

<style scoped>
.chart-card {
  background: var(--color-white, #fff);
  border-radius: var(--radius-lg, 12px);
  padding: var(--space-6, 24px);
  box-shadow: var(--shadow-md, 0 4px 6px -1px rgba(0, 0, 0, 0.1));
  display: flex;
  flex-direction: column;
}
.card-header-row {
  display: flex; 
  justify-content: space-between; 
  align-items: flex-start; 
  margin-bottom: 1rem; 
  flex-wrap: wrap; 
  gap: 1rem;
}
.chart-title { 
  font-size: 1.25rem; 
  font-weight: 600; 
  margin: 0; 
}
.chart-subtitle { 
  font-size: 0.875rem; 
  color: #6b7280; 
  margin-top: 0.25rem; 
}
.filter-controls { 
  display: flex; 
  gap: 0.5rem; 
}
.filter-select { 
  padding: 0.5rem 1rem; 
  border: 1px solid #d1d5db; 
  border-radius: 0.375rem;
  font-size: 0.875rem;
  cursor: pointer;
  transition: border-color 0.2s;
}
.filter-select:hover {
  border-color: #9ca3af;
}
.filter-select:focus {
  outline: none;
  border-color: #3b82f6;
}
.chart-container { 
  position: relative; 
  height: 300px; 
  width: 100%; 
  flex: 1;
}
.loading-placeholder, 
.error-placeholder { 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  height: 100%; 
  padding: 1rem;
}
.error-placeholder { 
  color: #dc2626; 
  background: #fee2e2; 
  border-radius: 8px; 
}
</style>