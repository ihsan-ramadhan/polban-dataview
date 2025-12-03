<template>
  <div class="akademik-page" ref="pageContainer">
    
    <div class="page-header">
      <div class="header-content">
        <div>
          <h1 class="page-title">Statistik Akademik</h1>
          <p class="page-subtitle">
            Data dan visualisasi terkait aktivitas akademik Politeknik Negeri Bandung.
          </p>
        </div>
        
        <button 
          class="btn-export-all" 
          @click="downloadOnePageReport" 
          :disabled="isLoading || !chartDataHasData || isGeneratingPdf"
          data-html2canvas-ignore="true"
        >
          <span v-if="isGeneratingPdf">Memproses PDF...</span>
          <span v-else>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Download Laporan PDF(1 Halaman)
          </span>
        </button>
      </div>
    </div>

    <div class="dashboard-grid-row">
      
      <div class="chart-card" ref="chartCardPie">
        <div class="card-header-wrapper">
          <div class="chart-header">
            <h2 class="chart-title">Tipe Tes Masuk (Pie)</h2>
            <p class="chart-subtitle">Distribusi berdasarkan jalur masuk</p>
          </div>
          
          <div class="chart-filters" data-html2canvas-ignore="true">
             <div class="filter-dropdown" @mouseenter="showAngkatanDropdown = true" @mouseleave="showAngkatanDropdown = false">
              <div class="filter-select-styled">
                {{ getFilterLabel() }}
                <span class="dropdown-arrow">▼</span>
              </div>
              <div v-show="showAngkatanDropdown" class="dropdown-menu">
                <div class="dropdown-item" @click="selectAngkatan('')" :class="{ active: filters.angkatan === '' }">Semua Angkatan</div>
                <div v-for="year in angkatanList" :key="year" class="dropdown-item" @click="selectAngkatan(year)" :class="{ active: filters.angkatan === year }">{{ year }}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="chart-container">
          <div v-if="isLoading" class="state-container loading"><span class="loader-spinner"></span> Memuat...</div>
          <div v-else-if="error" class="state-container error"><p>{{ error }}</p><button @click="fetchData" class="retry-btn">Coba Lagi</button></div>
          <div v-else-if="!chartData || chartData.datasets[0].data.length === 0" class="state-container empty"><p>Data tidak ditemukan.</p></div>
          <BasePieChart v-else :chart-data="chartData" />
        </div>

        <div class="download-action-area" v-if="!isLoading && chartDataHasData">
           <ChartDownloadButton 
              :target-element="$refs.chartCardPie" 
              file-name="PieChart-TipeTes" 
           />
        </div>
      </div>

      <div class="chart-card" ref="chartCardBar">
        <div class="card-header-wrapper">
          <div class="chart-header">
            <h2 class="chart-title">Rata-Rata Nilai (Layout)</h2>
            <p class="chart-subtitle">Data sementara (Tipe Tes Masuk)</p>
          </div>
        </div>
        <div class="chart-container">
          <div v-if="isLoading" class="state-container loading"><span class="loader-spinner"></span> Memuat...</div>
          <div v-else-if="!chartData || chartData.datasets[0].data.length === 0" class="state-container empty"><p>Data tidak ditemukan.</p></div>
          <BaseBarChart v-else :chart-data="chartData" />
        </div>

        <div class="download-action-area" v-if="!isLoading && chartDataHasData">
           <ChartDownloadButton 
              :target-element="$refs.chartCardBar" 
              file-name="BarChart-RataNilai" 
           />
        </div>
      </div>
    </div> 

    <div class="dashboard-full-row">
        <div class="chart-card" ref="chartCardTren">
            <div class="card-header-wrapper">
                <div class="chart-header">
                    <h2 class="chart-title">Tren IP</h2>
                    <p class="chart-subtitle">Grafik ini memenuhi satu baris penuh</p>
                </div>
            </div>
            <div class="chart-container">
                <div v-if="isLoading" class="state-container loading"><span class="loader-spinner"></span> Memuat...</div>
                <div v-else-if="!chartData || chartData.datasets[0].data.length === 0" class="state-container empty"><p>Data tidak ditemukan.</p></div>
                <BaseBarChart v-else :chart-data="chartData" />
            </div>

            <div class="download-action-area" v-if="!isLoading && chartDataHasData">
               <ChartDownloadButton 
                  :target-element="$refs.chartCardTren" 
                  file-name="BarChart-TrenIP" 
               />
            </div>
        </div>
    </div>
  </div>
</template>

<script>
import BasePieChart from "../../components/Charts/BasePieChart.vue";
import BaseBarChart from "../../components/Charts/BaseBarChart.vue";
// Import Component Tombol Download
import ChartDownloadButton from "../../components/Shared/ChartDownloadButton.vue";

import html2canvas from "html2canvas";
import jsPDF from "jspdf";

const API_BASE_URL = "http://localhost:3000";

export default {
  name: "Akademik",
  components: {
    BasePieChart,
    BaseBarChart,
    ChartDownloadButton, // Jangan lupa didaftarkan
  },
  data() {
    return {
      chartData: null,
      isLoading: true,
      error: null,
      filters: { angkatan: "" },
      angkatanList: [2023, 2024, 2025],
      showAngkatanDropdown: false,
      isGeneratingPdf: false,
    };
  },
  computed: {
      chartDataHasData() {
          return this.chartData && this.chartData.datasets && this.chartData.datasets[0].data.length > 0;
      }
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    // --- FITUR DOWNLOAD PDF GLOBAL (1 Halaman) ---
    async downloadOnePageReport() {
      this.isGeneratingPdf = true;
      try {
        const elementToCapture = this.$refs.pageContainer;
        const canvas = await html2canvas(elementToCapture, {
          scale: 2, 
          useCORS: true,
          backgroundColor: '#ffffff' 
        });

        const doc = new jsPDF('l', 'mm', 'a4');
        const pdfWidth = doc.internal.pageSize.getWidth();
        const pdfHeight = doc.internal.pageSize.getHeight();
        
        const imgWidth = canvas.width;
        const imgHeight = canvas.height;
        const margin = 10;
        const usableWidth = pdfWidth - (margin * 2);
        const usableHeight = pdfHeight - (margin * 2);

        const widthRatio = usableWidth / imgWidth;
        const heightRatio = usableHeight / imgHeight;
        const scaleFactor = Math.min(widthRatio, heightRatio);

        const finalWidth = imgWidth * scaleFactor;
        const finalHeight = imgHeight * scaleFactor;
        const xPos = (pdfWidth - finalWidth) / 2;
        const yPos = (pdfHeight - finalHeight) / 2;

        const imgData = canvas.toDataURL('image/png');
        doc.addImage(imgData, 'PNG', xPos, yPos, finalWidth, finalHeight);
        doc.save(`Laporan-Akademik-OnePage-${Date.now()}.pdf`);
      } catch (err) {
        console.error("Gagal export PDF:", err);
        alert("Gagal membuat PDF.");
      } finally {
        this.isGeneratingPdf = false;
      }
    },

    // --- FETCH DATA ---
    async fetchData() {
      this.isLoading = true;
      this.error = null;
      try {
        const params = new URLSearchParams();
        if (this.filters.angkatan) params.append("angkatan", this.filters.angkatan);
        const finalUrl = `${API_BASE_URL}/api/v1/akademik/tipe-tes-masuk?${params.toString()}`;
        console.log("🚀 Requesting:", finalUrl);

        const response = await fetch(finalUrl, {
          headers: { Authorization: `Bearer ${localStorage.getItem("auth_token")}`, Accept: "application/json" },
        });

        if (response.status === 401 || response.status === 403) throw new Error("Sesi habis.");
        if (!response.ok) throw new Error(`Server Error: ${response.status}`);

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
          datasets: [{ label: "Jumlah Mahasiswa", backgroundColor: colors, data: processedData.values, borderWidth: 1 }],
        };
      } catch (error) {
        console.error("Akademik Error:", error);
        this.error = error.message;
        this.chartData = null;
      } finally {
        this.isLoading = false;
      }
    },
    normalizeData(responseData) {
      if (Array.isArray(responseData)) return responseData;
      if (responseData.data && Array.isArray(responseData.data)) return responseData.data;
      return [];
    },
    processChartData(data) {
       if (!data || data.length === 0) return { labels: [], values: [] };
      const firstItem = data[0];
      const keys = Object.keys(firstItem);
      let labelKey = keys.find(k => ['tipe', 'jenis', 'nama', 'jalur'].includes(k.toLowerCase())) || keys.find(k => typeof firstItem[k] === 'string');
      let valueKey = keys.find(k => ['total', 'jumlah', 'count'].includes(k.toLowerCase())) || keys.find(k => typeof firstItem[k] === 'number');
      if (!labelKey || !valueKey) return { labels: [], values: [] };
      const map = new Map();
      data.forEach(item => {
        const label = item[labelKey] || 'Lainnya';
        const val = Number(item[valueKey] || 0);
        map.set(label, (map.get(label) || 0) + val);
      });
      return { labels: Array.from(map.keys()), values: Array.from(map.values()) };
    },
    getFilterLabel() {
      if (this.filters.angkatan) return `${this.filters.angkatan}`;
      return 'Semua Angkatan';
    },
    selectAngkatan(year) {
      this.filters.angkatan = year; 
      this.showAngkatanDropdown = false; 
      this.fetchData();
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
  background-color: transparent; 
}

/* Header */
.header-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  flex-wrap: wrap;
  gap: 1rem;
}
.page-header { margin-bottom: 2rem; }
.page-title { font-size: 1.8rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem; }
.page-subtitle { font-size: 1rem; color: var(--text-secondary); }

/* Tombol Export Utama */
.btn-export-all {
  display: flex;
  align-items: center;
  background-color: var(--color-primary, #007bff);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
  font-size: 0.95rem;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.btn-export-all:hover { background-color: var(--color-primary-dark, #0056b3); transform: translateY(-1px); }
.btn-export-all:disabled { background-color: #ccc; cursor: not-allowed; transform: none; box-shadow: none; }

/* Grid Layout */
.dashboard-grid-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
.dashboard-full-row { display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }

/* Card Style */
.chart-card {
  background: var(--bg-secondary);
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  border: 1px solid var(--border-color);
  min-width: 0;
  position: relative;
  height: 100%; 
  display: flex;
  flex-direction: column;
}

.card-header-wrapper { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; gap: 1rem; }
.chart-header { flex: 1; }
.chart-title { font-size: 1.2rem; font-weight: 600; color: var(--text-primary); }
.chart-subtitle { font-size: 0.85rem; color: var(--text-tertiary); margin-top: 4px;}
.chart-filters { flex-shrink: 0; }
.chart-container { position: relative; width: 100%; height: 350px; flex-grow: 1; margin-bottom: auto; }
.download-action-area { display: flex; justify-content: flex-end; margin-top: 10px; }

/* Responsif */
@media (max-width: 992px) { .dashboard-grid-row { grid-template-columns: 1fr; } }
@media (max-width: 576px) { .chart-container { height: 300px; } .header-content { flex-direction: column; } }

/* Dropdown Style */
.filter-dropdown { position: relative; display: inline-block; z-index: 50; }
.filter-select-styled { padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.9rem; background: var(--bg-primary); color: var(--text-primary); cursor: pointer; min-width: 160px; display: flex; justify-content: space-between; align-items: center; user-select: none; transition: all 0.2s;}
.filter-select-styled:hover { border-color: var(--color-primary); background: var(--bg-hover);}
.dropdown-arrow { font-size: 0.7rem; margin-left: 8px; color: var(--text-tertiary); }

/* --- STYLE DROPDOWN (Fixed for Perfectionist) --- */

.dropdown-menu {
  position: absolute;
  
  /* 1. JARAK VISUAL YANG RAPI */
  /* Turunkan menu 8px ke bawah tombol. Memberi efek 'floating' yang elegan. */
  top: calc(100% + 8px); 
  left: 0;
  
  /* Style Kotak */
  background: var(--bg-secondary); 
  border: 1px solid var(--border-color);
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.12); /* Shadow halus */
  min-width: 200px;
  z-index: 999;
  padding: 8px 0;
  
  /* Animasi halus saat muncul (Opsional, biar makin smooth) */
  animation: fadeIn 0.2s ease-out;
}

/* 2. JEMBATAN GHAIB (INVISIBLE BRIDGE) */
/* Buat kotak transparan di celah antara tombol dan menu.
   Jadi saat mouse kamu menyebrang celah 8px itu, mouse dianggap masih nempel. */
.dropdown-menu::before {
  content: "";
  position: absolute;
  
  /* Mulai dari atas menu, tarik ke atas setinggi 20px untuk menutupi celah */
  top: -20px; 
  left: 0;
  width: 100%;
  height: 20px;
  
  background: transparent; /* Transparan, user gak liat */
  /* background: red; /* <-- Uncomment ini kalau mau liat jembatannya */
  z-index: -1;
}

/* Keyframes untuk animasi muncul */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}

.dropdown-item {
  padding: 10px 16px; cursor: pointer; font-size: 0.9rem;
  color: var(--text-primary); transition: background 0.2s;
  display: flex; justify-content: space-between; align-items: center; font-weight: 500;
}
.dropdown-item:hover { background-color: var(--bg-hover); }
.dropdown-item.active { background-color: var(--bg-hover); color: var(--color-primary); font-weight: 600; }

.state-container { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; width: 100%; color: var(--text-secondary); }
.state-container.error { color: #d32f2f; background-color: #ffebee; padding: 1rem; border-radius: 8px; border: 1px dashed #d32f2f; }
.retry-btn { margin-top: 8px; padding: 6px 16px; background: white; border: 1px solid #d32f2f; color: #d32f2f; border-radius: 4px; cursor: pointer; font-weight: 500; }
.loader-spinner { width: 24px; height: 24px; border: 3px solid #ccc; border-top-color: var(--color-primary); border-radius: 50%; animation: spin 0.8s linear infinite; margin-bottom: 12px; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>