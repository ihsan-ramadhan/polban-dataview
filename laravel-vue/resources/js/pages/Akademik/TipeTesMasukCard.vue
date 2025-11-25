<template>
  <div class="chart-card h-full">
    
    <div class="card-header-section">
      <div class="header-text">
        <h2 class="chart-title">Rasio Tipe Tes Masuk</h2>
        <p class="chart-subtitle">
          Data: {{ selectedAngkatan === 'all' ? 'Semua Angkatan' : selectedAngkatan }} 
          <span v-if="selectedProdi !== 'all'">
             - Prodi {{ selectedProdi }}
             <span v-if="selectedKelas !== 'all'"> (Kelas {{ selectedKelas }})</span>
          </span>
          <span v-else> - Semua Prodi</span>
        </p>
      </div>

      <div class="filter-controls">
        <select v-model="selectedAngkatan" @change="fetchData" class="filter-select">
          <option value="all">Semua Angkatan</option>
          <option value="2024">2024</option>
          <option value="2023">2023</option>
        </select>

        <select v-model="selectedProdi" @change="onProdiChange" class="filter-select">
          <option value="all">Semua Prodi</option>
          <option value="D3-TI">D3-TI</option>
          <option value="D4-TI">D4-TI</option>
        </select>

        <select 
          v-if="selectedProdi !== 'all'" 
          v-model="selectedKelas" 
          @change="fetchData" 
          class="filter-select animate-fade-in"
        >
          <option value="all">Semua Kelas</option>
          <option value="2A">2A</option>
          <option value="2B">2B</option>
          <option value="2C">2C</option>
        </select>
      </div>
    </div>

    <div class="chart-container">
      <div v-if="isLoading" class="loading-placeholder">Memuat data...</div>
      <div v-else-if="errorMessage" class="error-placeholder">{{ errorMessage }}</div>
      
      <BasePieChart 
        v-else-if="chartData" 
        :chart-data="chartData" 
        ref="pieChart" 
      />
      <div v-else class="loading-placeholder">Data kosong.</div>

      <button @click="downloadChart" class="btn-download-floating" title="Download Chart">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
          <polyline points="7 10 12 15 17 10"></polyline>
          <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
      </button>
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
      
      // State Filter
      selectedAngkatan: 'all',
      selectedProdi: 'all',
      selectedKelas: 'all', // Default 'all' (Semua kelas di prodi tsb)
      
      chartColors: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    // Dipanggil saat Prodi berubah
    onProdiChange() {
      // Reset pilihan kelas ke 'all' setiap kali ganti prodi
      this.selectedKelas = 'all';
      // Langsung fetch data baru
      this.fetchData();
    },

    async fetchData() {
      this.isLoading = true;
      this.errorMessage = null;
      this.chartData = null;

      try {
        const baseUrl = 'http://localhost:6767/api/v1/akademik/tipe-tes-masuk';
        const params = new URLSearchParams();
        
        // Logic Pengiriman Parameter ke Backend
        if (this.selectedAngkatan !== 'all') params.append('angkatan', this.selectedAngkatan);
        if (this.selectedProdi !== 'all') params.append('prodi', this.selectedProdi);
        
        // Parameter Kelas HANYA dikirim kalau Prodi dipilih DAN Kelas bukan 'all'
        if (this.selectedProdi !== 'all' && this.selectedKelas !== 'all') {
            params.append('kelas', this.selectedKelas);
        }

        const response = await fetch(`${baseUrl}?${params}`);
        if (!response.ok) throw new Error('Gagal koneksi ke server');
        
        const json = await response.json();
        
        // Ambil array data (Logic Robust)
        let finalArray = [];
        if (Array.isArray(json.data)) finalArray = json.data;
        else if (json.data && Array.isArray(json.data.data)) finalArray = json.data.data;
        else if (Array.isArray(json)) finalArray = json;

        if (finalArray.length === 0) {
            this.chartData = null;
            return;
        }

        this.chartData = {
          labels: finalArray.map(item => item.tipe),
          datasets: [{
            backgroundColor: this.chartColors.slice(0, finalArray.length),
            data: finalArray.map(item => item.total)
          }]
        };

      } catch (e) {
        console.error(e);
        this.errorMessage = "Gagal memuat: " + e.message;
      } finally {
        this.isLoading = false;
      }
    },

    downloadChart() {
      if (!this.chartData) { alert("Tidak ada data chart!"); return; }
      const chartComponent = this.$refs.pieChart;
      if (!chartComponent) { alert("Chart belum siap."); return; }
      
      let canvas = chartComponent.$el ? chartComponent.$el.querySelector('canvas') : null;
      if (!canvas) {
         const container = this.$el.querySelector('.chart-container');
         if (container) canvas = container.querySelector('canvas');
      }

      if (!canvas) { alert("Canvas error."); return; }

      try {
        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');
        // Nama file dinamis tergantung filter aktif
        let filename = `chart-tipe-tes-${this.selectedAngkatan}`;
        if (this.selectedProdi !== 'all') filename += `-${this.selectedProdi}`;
        if (this.selectedProdi !== 'all' && this.selectedKelas !== 'all') filename += `-${this.selectedKelas}`;
        
        link.download = `${filename}.png`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      } catch (error) {
        console.error(error);
        alert("Gagal download.");
      }
    }
  }
};
</script>

<style scoped>
.chart-card {
  background: #fff;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
}
.card-header-section {
  margin-bottom: 1.5rem;
}
.header-text {
  margin-bottom: 1rem;
}
.chart-title { font-size: 1.25rem; font-weight: 600; margin: 0; color: #1f2937; }
.chart-subtitle { font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; }
.filter-controls { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
.filter-select {
  padding: 0.5rem 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  font-size: 0.875rem;
  cursor: pointer;
  background-color: white;
}

.chart-container { position: relative; height: 300px; width: 100%; }

.btn-download-floating {
  position: absolute;
  bottom: 16px;
  right: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem;
  background-color: white;
  border: 1px solid #e5e7eb;
  border-radius: 50%;
  cursor: pointer;
  color: #4b5563;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  transition: all 0.2s ease;
  z-index: 10;
}
.btn-download-floating:hover {
  background-color: #3b82f6;
  color: white;
  transform: scale(1.05);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.loading-placeholder, .error-placeholder { display: flex; align-items: center; justify-content: center; height: 100%; color: #9ca3af; }

/* Animasi kecil saat dropdown kelas muncul */
.animate-fade-in {
  animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateX(-10px); }
  to { opacity: 1; transform: translateX(0); }
}
</style>