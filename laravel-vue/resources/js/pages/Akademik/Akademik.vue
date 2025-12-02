<template>
  <div class="akademik-page">
    <div class="page-header">
      <h1 class="page-title">Statistik Akademik</h1>
      <p class="page-subtitle">
        Data dan visualisasi terkait aktivitas akademik Politeknik Negeri Bandung.
      </p>
    </div>

    <div class="charts-grid">
      <div class="chart-card" ref="chartCard">
        <div class="card-header-wrapper">
          <div class="chart-header">
            <h2 class="chart-title">Tipe Tes Masuk</h2>
            <p class="chart-subtitle">
              Jumlah Mahasiswa Berdasarkan Jalur Masuk
            </p>
          </div>
          <div class="chart-filters">
            <div class="filter-dropdown" @mouseenter="showAngkatanDropdown = true" @mouseleave="showAngkatanDropdown = false">
              <div class="filter-select-styled">
                {{ getFilterLabel() }}
                <span class="dropdown-arrow">▼</span>
              </div>
              
              <div v-show="showAngkatanDropdown" class="dropdown-menu">
                <div 
                  class="dropdown-item" 
                  @click="selectAngkatan('')"
                  :class="{ active: filters.angkatan === '' }"
                >
                  Semua Angkatan
                </div>
                
                <div 
                  v-for="year in angkatanList" 
                  :key="year" 
                  class="dropdown-item has-submenu"
                  @mouseenter="showKelasFor = year"
                  @mouseleave="showKelasFor = null"
                  @click="selectAngkatan(year)"
                  :class="{ active: filters.angkatan === year }"
                >
                  {{ year }}
                  <span class="submenu-arrow">›</span>
                  
                  <div v-show="showKelasFor === year" class="submenu" @click.stop>
                    <div class="submenu-content">
                      <div 
                        class="dropdown-item" 
                        @click.stop="selectKelas(year, '')"
                        :class="{ active: filters.angkatan === year && filters.kelas.length === 0 }"
                      >
                        Semua Kelas
                        <span v-if="filters.angkatan === year && filters.kelas.length === 0" class="check-icon">✓</span>
                      </div>
                      <div 
                        v-for="kelas in kelasList" 
                        :key="kelas" 
                        class="dropdown-item"
                        @click.stop="selectKelas(year, kelas)"
                        :class="{ active: filters.angkatan === year && filters.kelas.includes(kelas) }"
                      >
                        Kelas {{ kelas }}
                        <span v-if="filters.angkatan === year && filters.kelas.includes(kelas)" class="check-icon">✓</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="chart-container">
          <div v-if="isLoading" class="state-container loading">
            <span class="loader-spinner"></span> Memuat...
          </div>

          <div v-else-if="error" class="state-container error">
            <p>{{ error }}</p>
            <button @click="fetchData" class="retry-btn">Coba Lagi</button>
          </div>

          <div
            v-else-if="!chartData || chartData.datasets[0].data.length === 0"
            class="state-container empty"
          >
            <p>Data tidak ditemukan untuk filter ini.</p>
          </div>

          <BasePieChart v-else :chart-data="chartData" />
        </div>

        <div class="download-action-area" v-if="!isLoading && !error" data-html2canvas-ignore="true">
          <div class="download-wrapper">
            <button class="download-btn" @click="toggleDownloadMenu" title="Unduh Grafik">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
              </svg>
            </button>
            
            <div v-if="showDownloadMenu" class="download-menu">
              <button @click="downloadChart('png')">Save as PNG</button>
              <button @click="downloadChart('pdf')">Save as PDF</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import BasePieChart from "../../components/Charts/BasePieChart.vue";
import html2canvas from "html2canvas";
import jsPDF from "jspdf";

const API_BASE_URL = "http://localhost:3000";

export default {
  name: "Akademik",
  components: {
    BasePieChart,
  },
  data() {
    return {
      chartData: null,
      isLoading: true,
      error: null,
      filters: {
        angkatan: "",
        kelas: [], 
      },
      angkatanList: [2023, 2024, 2025],
      kelasList: ['A', 'B', 'C'],
      showDownloadMenu: false,
      showAngkatanDropdown: false,
      showKelasFor: null,
    };
  },
  mounted() {
    this.fetchData();
    document.addEventListener('click', this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
  },
  methods: {
    async fetchData() {
      this.isLoading = true;
      this.error = null;
      this.chartData = null;

      try {
        const params = new URLSearchParams();
        if (this.filters.angkatan) {
          params.append("angkatan", this.filters.angkatan);
        }

        // --- PERBAIKAN DI SINI ---
        // Hapus "[]" di nama parameter. 
        // URLSearchParams akan otomatis bikin format: kelas=A&kelas=B
        // Format ini lebih umum diterima API modern (Node/Express/Python) dibanding format PHP (kelas[])
        if (this.filters.kelas.length > 0) {
          this.filters.kelas.forEach(k => {
             params.append("kelas", k); 
          });
        }
        // -------------------------

        // Debugging: Cek Console Browser (F12) buat liat URL yang terbentuk
        const finalUrl = `${API_BASE_URL}/api/v1/akademik/tipe-tes-masuk?${params.toString()}`;
        console.log("🚀 Requesting:", finalUrl);

        const response = await fetch(
          finalUrl,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
              Accept: "application/json",
            },
          }
        );

        if (response.status === 401 || response.status === 403) {
          throw new Error("Sesi habis atau tidak ada akses. Silakan login ulang.");
        }
        
        if (!response.ok) {
          throw new Error(`Server Error: ${response.status} ${response.statusText}`);
        }

        const responseData = await response.json();
        let rawData = this.normalizeData(responseData);

        if (rawData.length === 0) {
          this.chartData = { labels: [], datasets: [{ data: [] }] };
          return;
        }

        const processedData = this.processChartData(rawData);
        const colors = ["#42A5F5", "#66BB6A", "#FFA726", "#AB47BC", "#EF5350", "#8D6E63", "#78909C"];

        this.chartData = {
          labels: processedData.labels,
          datasets: [
            {
              label: "Jumlah Mahasiswa",
              backgroundColor: colors,
              data: processedData.values,
              borderWidth: 1,
            },
          ],
        };
      } catch (error) {
        console.error("Akademik Error:", error);
        this.error = error.message;
      } finally {
        this.isLoading = false;
      }
    },

    // ... (Method sisanya normalizeData, processChartData, dll SAMA PERSIS kayak sebelumnya)
    normalizeData(responseData) {
      if (Array.isArray(responseData)) return responseData;
      if (responseData.data && Array.isArray(responseData.data)) return responseData.data;
      if (responseData.payload && Array.isArray(responseData.payload)) return responseData.payload;
      if (responseData.all?.data && Array.isArray(responseData.all.data)) return responseData.all.data;
      return [];
    },

    processChartData(data) {
      if (!data || data.length === 0) return { labels: [], values: [] };

      const firstItem = data[0];
      const keys = Object.keys(firstItem);
      const labelKeyCandidates = ['tipe', 'jenis', 'nama', 'jalur', 'label', 'kategori'];
      let labelKey = keys.find(k => labelKeyCandidates.includes(k.toLowerCase()));
      if (!labelKey) labelKey = keys.find(k => typeof firstItem[k] === 'string');

      const valueKeyCandidates = ['total', 'jumlah', 'count', 'nilai', 'value'];
      let valueKey = keys.find(k => valueKeyCandidates.includes(k.toLowerCase()));
      if (!valueKey) valueKey = keys.find(k => typeof firstItem[k] === 'number');

      if (!labelKey || !valueKey) return { labels: [], values: [] };

      const map = new Map();
      data.forEach(item => {
        const label = item[labelKey] || 'Lainnya';
        const val = Number(item[valueKey] || 0);
        if (map.has(label)) {
          map.set(label, map.get(label) + val);
        } else {
          map.set(label, val);
        }
      });

      return {
        labels: Array.from(map.keys()),
        values: Array.from(map.values())
      };
    },

    toggleDownloadMenu(event) {
      event.stopPropagation();
      this.showDownloadMenu = !this.showDownloadMenu;
    },
    
    handleClickOutside(event) {
      const wrapper = this.$el.querySelector('.download-wrapper');
      if (wrapper && !wrapper.contains(event.target)) {
        this.showDownloadMenu = false;
      }
    },

    getFilterLabel() {
      if (this.filters.kelas.length > 0) {
        return `${this.filters.angkatan} - Kelas ${this.filters.kelas.join(', ')}`;
      } else if (this.filters.angkatan) {
        return `${this.filters.angkatan}`;
      }
      return 'Semua Angkatan';
    },

    selectAngkatan(year) {
      this.filters.angkatan = year;
      this.filters.kelas = []; 
      this.showAngkatanDropdown = false;
      this.fetchData();
    },

    selectKelas(year, kelas) {
      this.filters.angkatan = year;

      if (kelas === '') {
        this.filters.kelas = [];
      } else {
        const index = this.filters.kelas.indexOf(kelas);
        if (index > -1) {
          this.filters.kelas.splice(index, 1);
        } else {
          this.filters.kelas.push(kelas);
        }
      }
      
      this.fetchData();
    },

    async downloadChart(format) {
      const element = this.$refs.chartCard;
      
      this.showDownloadMenu = false;
      await this.$nextTick();
      
      const btnArea = element.querySelector('.download-action-area');
      if(btnArea) btnArea.style.visibility = 'hidden';

      try {
        const canvas = await html2canvas(element, { scale: 2 });
        
        if (format === 'png') {
          const link = document.createElement('a');
          link.download = `Statistik-Akademik-${Date.now()}.png`;
          link.href = canvas.toDataURL();
          link.click();
        } else if (format === 'pdf') {
          const imgData = canvas.toDataURL('image/png');
          const pdf = new jsPDF('l', 'mm', 'a4');
          const pdfWidth = pdf.internal.pageSize.getWidth();
          const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
          
          pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
          pdf.save(`Statistik-Akademik-${Date.now()}.pdf`);
        }
      } catch (err) {
        console.error("Gagal download:", err);
        alert("Gagal mengunduh grafik.");
      } finally {
        if(btnArea) btnArea.style.visibility = 'visible';
      }
    }
  },
};
</script>

<style scoped>
.akademik-page {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
  padding-bottom: 2rem;
}

.page-header {
  margin-bottom: 2rem;
}

.page-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
}

.page-subtitle {
  font-size: 1rem;
  color: var(--text-secondary);
}

.charts-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
}

.chart-card {
  background: var(--bg-secondary);
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  border: 1px solid var(--border-color);
  min-width: 0;
  position: relative;
}

.card-header-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1.5rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.chart-header {
  flex: 1;
}

.chart-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--text-primary);
}

.chart-subtitle {
  font-size: 0.8rem;
  color: var(--text-tertiary);
}

.chart-filters {
  display: flex;
  gap: 0.5rem;
}

.filter-dropdown {
  position: relative;
  display: inline-block;
  z-index: 50; /* Pastiin ada z-index */
}

.filter-select-styled {
  padding: 0.4rem 0.8rem;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  font-size: 0.85rem;
  background: var(--bg-primary);
  color: var(--text-primary);
  cursor: pointer;
  min-width: 180px; /* Lebarin dikit buat label multi-select */
  display: flex;
  justify-content: space-between;
  align-items: center;
  user-select: none;
}

.filter-select-styled:hover {
  border-color: var(--color-primary);
}

.dropdown-arrow {
  font-size: 0.7rem;
  margin-left: 8px;
  color: var(--text-tertiary);
}

/* FIX UTAMA 1: Overflow Visible */
.dropdown-menu {
  position: absolute;
  top: calc(100% + 4px);
  left: 0;
  background: #ffffff;
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.15);
  min-width: 180px;
  z-index: 999;
  overflow: visible; /* WAJIB visible biar submenu nongol keluar */
}

.dropdown-item {
  padding: 10px 16px;
  cursor: pointer;
  font-size: 0.85rem;
  color: #000000;
  transition: background 0.2s;
  position: relative;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.dropdown-item:hover {
  background-color: #f5f5f5;
}

.dropdown-item.active {
  background-color: #e3f2fd;
  color: var(--color-primary);
  font-weight: 500;
}

.dropdown-item.has-submenu {
  padding-right: 30px;
}

.submenu-arrow {
  font-size: 1rem;
  color: var(--text-tertiary);
}

.check-icon {
  color: var(--color-primary);
  font-weight: bold;
  font-size: 0.9rem;
}

/* FIX UTAMA 2: Posisi Submenu ke Kiri & Jembatan Raksasa */
.submenu {
  position: absolute;
  
  /* Posisi di KIRI Parent (biar gak kepotong layar kanan) */
  left: auto; 
  right: 100%; 
  top: -10px; 
  
  /* Reset Margin */
  margin-right: 0; 
  margin-left: 0; 
  padding-right: 0;
  
  /* Wrapper transparan (opsional) */
  z-index: 1000;
}

/* Konten Submenu dibungkus biar rapi */
.submenu-content {
  background: #ffffff;
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.15);
  min-width: 140px;
  overflow: hidden;
}

/* FIX UTAMA 3: THE GIANT BRIDGE (ANTI EMOSI) */
.submenu::before {
  content: "";
  position: absolute;
  
  /* Area sensor digedein vertikal */
  top: -20%; 
  height: 140%; 
  
  /* Nempel di KANAN submenu (arah datang mouse) */
  right: -30px; 
  left: auto;
  
  /* Lebar jembatan aman */
  width: 40px; 
  
  background: transparent; /* Ubah 'red' kalau mau liat area-nya */
  z-index: 1001;
}

.chart-container {
  position: relative;
  height: 400px;
  width: 100%;
  margin-bottom: 1rem;
}

.state-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  width: 100%;
  color: var(--text-secondary);
}

.state-container.error {
  color: #d32f2f;
  background-color: #ffebee;
  padding: 1rem;
  border-radius: 8px;
  border: 1px dashed #d32f2f;
}

.retry-btn {
  margin-top: 8px;
  padding: 6px 16px;
  background: white;
  border: 1px solid #d32f2f;
  color: #d32f2f;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

.loader-spinner {
  width: 24px;
  height: 24px;
  border: 3px solid #ccc;
  border-top-color: var(--color-primary);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin-bottom: 12px;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.download-action-area {
  display: flex;
  justify-content: flex-end;
  margin-top: 10px;
  position: relative;
}

.download-wrapper {
  position: relative;
  display: inline-block;
}

.download-btn {
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 8px;
  cursor: pointer;
  color: var(--text-secondary);
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.download-btn:hover {
  background-color: var(--bg-primary);
  color: var(--color-primary);
  border-color: var(--color-primary);
}

.download-menu {
  position: absolute;
  bottom: 100%;
  right: 0;
  background: #ffffff;
  border: 1px solid #eee;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  border-radius: 8px;
  padding: 0.5rem 0;
  min-width: 140px;
  white-space: nowrap;
  z-index: 10;
  margin-bottom: 5px;
  display: flex;
  flex-direction: column;
}

.download-menu button {
  background: none;
  border: none;
  padding: 10px 16px;
  text-align: left;
  font-size: 0.9rem;
  font-weight: 500;
  color: #000000 !important;
  cursor: pointer;
  transition: all 0.2s;
}

.download-menu button:hover {
  background-color: #e0e0e0;
  color: #000000 !important;
}
</style>