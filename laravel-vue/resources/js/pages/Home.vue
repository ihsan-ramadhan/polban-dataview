<template>
  <div class="home-page">
    <h1>Selamat Datang di Polban Dataverse</h1>
    <p>Ini adalah halaman utama dari aplikasi Polban Dataverse.</p>

    <div class="chart-card">
      <h2 class="chart-title">Rasio Gender Mahasiswa</h2>
      <p class="chart-subtitle">Data dummy rasio gender D3 Teknik Informatika (Seluruh Angkatan)</p>
      
      <div class="chart-container">
        <div v-if="isLoading" class="loading-placeholder">
          Memuat data chart...
        </div>

        <div v-else-if="errorMessage" class="error-placeholder">
          {{ errorMessage }}
        </div>
        
        <BasePieChart v-else-if="genderChartData" :chart-data="genderChartData" />
      </div>
    </div>

    </div>
</template>

<script>
import BasePieChart from '../components/Charts/BasePieChart.vue';

export default {
  name: 'Home',
  components: {
    BasePieChart
  },
  data() {
    return {
      genderChartData: null,
      // State disederhanakan karena hanya ada 1 chart
      isLoading: true, 
      errorMessage: null
    }
  },
  mounted() {
    // Hanya memanggil data gender
    this.fetchGenderData();
  },
  methods: {
    async fetchGenderData() {
      this.isLoading = true;
      this.errorMessage = null;

      try {
        const response = await fetch('http://localhost:6767/api/v1/guest/mahasiswa/gender');
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const responseData = await response.json();
        const rawData = responseData.data;

        if (!rawData || !Array.isArray(rawData)) {
          throw new Error('Format data tidak sesuai');
        }

        const labels = rawData.map(item => item.jenis);
        const data = rawData.map(item => item.total);

        this.genderChartData = {
          labels: labels,
          datasets: [
            {
              backgroundColor: ['#42A5F5', '#FF6384'],
              data: data
            }
          ]
        };

      } catch (error) {
        console.error('Error fetching gender data:', error);
        this.errorMessage = `Gagal memuat data: ${error.message}. Pastikan mock server di port 6767 berjalan.`;
      } finally {
        this.isLoading = false;
      }
    },

    // Fungsi fetchAgamaData() telah dihapus
  }
}
</script>

<style scoped>
/* Style untuk card chart */
.chart-card {
  background: var(--color-white);
  border-radius: var(--radius-lg);
  padding: var(--space-6);
  box-shadow: var(--shadow-md);
  margin-top: var(--space-6);
}

.chart-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--color-gray-800);
}

.chart-subtitle {
  font-size: 0.875rem;
  color: var(--color-gray-500);
  margin-bottom: var(--space-4);
}

/* Penting: Beri container chart sebuah tinggi (height) 
   agar 'maintainAspectRatio: false' di komponen chart berfungsi
*/
.chart-container {
  position: relative;
  height: 300px; /* Sesuaikan tingginya sesuai kebutuhan */
}

.loading-placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 300px;
  color: var(--color-gray-400);
  font-style: italic;
}

/* Style baru untuk error */
.error-placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 300px;
  color: #D32F2F; /* Warna merah untuk error */
  font-weight: 500;
  border: 1px dashed #D32F2F;
  border-radius: var(--radius-md);
  background-color: #FFEBEE;
  padding: var(--space-4);
  text-align: center;
}

.chart-legend {
  display: flex;
  justify-content: center;
  gap: var(--space-4);
  margin-top: var(--space-4);
  font-weight: 500;
}

.legend-item {
  font-size: 0.875rem;
}
</style>