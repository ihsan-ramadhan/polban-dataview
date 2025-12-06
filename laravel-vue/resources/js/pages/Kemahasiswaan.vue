<template>
    <div class="kemahasiswaan-page" ref="pageContainer">
        <div class="page-header">
            <div class="header-content">
                <h1>Statistik Kemahasiswaan Polban </h1>
                <button 
                    class="btn-export-all" 
                    @click="downloadOnePageReport" 
                    :disabled="isGeneratingPdf"
                    data-html2canvas-ignore="true"
                >
                    <span v-if="isGeneratingPdf">Memproses PDF...</span>
                    <span v-else style="display: flex; align-items: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                        Download Laporan (PDF)
                    </span>
                </button>
            </div>
            <p>Menampilkan visual data dan karakteristik mahasiswa Jurusan Teknik Komputer dan Informatika berdasarkan angkatan serta kelas.</p> 
        </div>

        <div class="charts-grid">
            <div class="chart-card jumlah-mahasiswa-card" ref="chartCardJumlah">
                <div class="card-header-wrapper">
                    <div class="card-header">
                        <h2 class="chart-title">Jumlah Mahasiswa</h2>
                        <p class="chart-subtitle">Total Mahasiswa Aktif per Angkatan</p>
                    </div>
                    <div class="chart-filters">
                        </div>
                </div>
                <div class="chart-container-large">
                    <div v-if="isLoadingJumlah" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <div v-else-if="errorJumlah" class="state-container error">
                        <p>{{ errorJumlah }}</p>
                        <button @click="fetchJumlahData" class="retry-btn">
                            Coba Lagi
                        </button>
                    </div>
                    <BaseBarChart
                        v-else-if="jumlahChartData"
                        :chart-data="jumlahChartData"
                        :key="filters.jumlah.angkatan" 
                    />
                </div>
                <div class="download-action-area" v-if="!isLoadingJumlah && jumlahChartData">
                    <ChartDownloadButton 
                        :target-element="$refs.chartCardJumlah" 
                        file-name="BarChart-JumlahMahasiswa" 
                    />
                </div>
            </div>

            <div class="chart-card" ref="chartCardGender">
                <div class="card-header-wrapper">
                    <div class="card-header">
                        <h2 class="chart-title">Rasio Gender</h2>
                        <p class="chart-subtitle">
                            Perbandingan Laki-laki dan Perempuan
                        </p>
                    </div>
                    <div class="chart-filters" data-html2canvas-ignore="true">
                        <AngkatanDropdown
                            v-model="filters.gender.angkatan"
                            :options="angkatanList"
                            @change="fetchGenderData"
                            placeholder="Semua Angkatan"
                        />
                    </div>
                </div>
                <div class="chart-container">
                    <div v-if="isLoadingGender" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <div v-else-if="errorGender" class="state-container error">
                        <p>{{ errorGender }}</p>
                        <button @click="fetchGenderData" class="retry-btn">
                            Coba Lagi
                        </button>
                    </div>
                    <BasePieChart
                        v-else-if="genderChartData"
                        :chart-data="genderChartData"
                        :key="filters.gender.angkatan" 
                    />
                </div>
                <div class="download-action-area" v-if="!isLoadingGender && genderChartData">
                    <ChartDownloadButton 
                        :target-element="$refs.chartCardGender" 
                        file-name="PieChart-RasioGender" 
                    />
                </div>
            </div>

            <div class="chart-card" ref="chartCardAgama">
                <div class="card-header-wrapper">
                    <div class="card-header">
                        <h2 class="chart-title">Persebaran Agama</h2>
                        <p class="chart-subtitle">Statistik Agama Mahasiswa</p>
                    </div>
                    <div class="chart-filters" data-html2canvas-ignore="true">
                        <AngkatanDropdown
                            v-model="filters.agama.angkatan"
                            :options="angkatanList"
                            @change="fetchAgamaData"
                            placeholder="Semua Angkatan"
                        />
                    </div>
                </div>
                <div class="chart-container">
                    <div v-if="isLoadingAgama" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <div v-else-if="errorAgama" class="state-container error">
                        <p>{{ errorAgama }}</p>
                        <button @click="fetchAgamaData" class="retry-btn">
                            Coba Lagi
                        </button>
                    </div>
                    <BasePieChart
                        v-else-if="agamaChartData"
                        :chart-data="agamaChartData"
                        :key="filters.agama.angkatan" 
                    />
                </div>
                <div class="download-action-area" v-if="!isLoadingAgama && agamaChartData">
                    <ChartDownloadButton 
                        :target-element="$refs.chartCardAgama" 
                        file-name="PieChart-PersebaranAgama" 
                    />
                </div>
            </div>

            <div class="chart-card slta-card" ref="chartCardSLTA">
                <div class="card-header-wrapper">
                    <div class="card-header">
                        <h2 class="chart-title">Asal Jenis Sekolah (SLTA)</h2>
                        <p class="chart-subtitle">
                            Statistik Berdasarkan Asal Jenis Sekolah
                        </p>
                    </div>
                    <div class="chart-filters" data-html2canvas-ignore="true">
                        <AngkatanDropdown
                            v-model="filters.slta.angkatan"
                            :options="angkatanList"
                            @change="fetchSLTAData"
                            placeholder="Semua Angkatan"
                        />
                    </div>
                </div>
                <div class="chart-container-wide">
                    <div v-if="isLoadingSLTA" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <div v-else-if="errorSLTA" class="state-container error">
                        <p>{{ errorSLTA }}</p>
                        <button @click="fetchSLTAData" class="retry-btn">
                            Coba Lagi
                        </button>
                    </div>
                    <BaseBarChart
                        v-else-if="sltaChartData"
                        :chart-data="sltaChartData"
                        :key="filters.slta.angkatan" 
                    />
                </div>
                <div class="download-action-area" v-if="!isLoadingSLTA && sltaChartData">
                    <ChartDownloadButton 
                        :target-element="$refs.chartCardSLTA" 
                        file-name="BarChart-JenisSLTA" 
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BaseBarChart from "../components/Charts/BaseBarChart.vue";
import BasePieChart from "../components/Charts/BasePieChart.vue";
// Import aset yang diperlukan dari folder Shared/
import ChartDownloadButton from "../components/Shared/ChartDownloadButton.vue";
import AngkatanDropdown from "../components/Shared/AngkatanDropdown.vue"; 
import html2canvas from 'html2canvas'; 
import { jsPDF } from 'jspdf'; 

const API_BASE_URL = "http://localhost:3000";

export default {
    name: "Kemahasiswaan",
    components: {
        BaseBarChart,
        BasePieChart,
        AngkatanDropdown,
        ChartDownloadButton,
    },
    data() {
        return {
            // Hardcoded Lists
            angkatanList: [2023, 2024, 2025],
            prodiList: ["D3 Teknik Informatika"],

            // Filters per Chart
            filters: {
                jumlah: { angkatan: "" },
                gender: { angkatan: "" },
                agama: { angkatan: "" },
                slta: { angkatan: "" },
            },

            // Data Charts & State
            jumlahChartData: null,
            isLoadingJumlah: true,
            errorJumlah: null,

            genderChartData: null,
            isLoadingGender: true,
            errorGender: null,

            agamaChartData: null,
            isLoadingAgama: true,
            errorAgama: null,

            sltaChartData: null,
            isLoadingSLTA: true,
            errorSLTA: null,
            
            isGeneratingPdf: false,
        };
    },
    mounted() {
        this.fetchAllData();
    },
    methods: {
        fetchAllData() {
            this.fetchJumlahData();
            this.fetchGenderData();
            this.fetchAgamaData();
            this.fetchSLTAData();
        },

        getQueryParams(filterObj) {
            const params = new URLSearchParams();
            if (filterObj.angkatan) params.append("angkatan", filterObj.angkatan);
            return params.toString();
        },

        // --- LOGIC DOWNLOAD PDF GLOBAL (DARI AKADEMIK.VUE) ---
        async downloadOnePageReport() {
            this.isGeneratingPdf = true;
            try {
                // Target div utama dengan ref="pageContainer"
                const elementToCapture = this.$refs.pageContainer;
                const canvas = await html2canvas(elementToCapture, {
                    scale: 2, 
                    useCORS: true,
                    backgroundColor: '#ffffff',
                    // Abaikan tombol download dan filter dropdown saat capture
                    ignoreElements: (element) => {
                        return element.hasAttribute('data-html2canvas-ignore');
                    }
                });

                // Logic resizing ke A4 portrait
                const doc = new jsPDF('p', 'mm', 'a4');
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
                doc.save(`Laporan-Kemahasiswaan-OnePage-${Date.now()}.pdf`);
            } catch (err) {
                console.error("Gagal export PDF:", err);
                alert("Gagal membuat PDF.");
            } finally {
                this.isGeneratingPdf = false;
            }
        },

        // Menambahkan method generatePDF yang memanggil downloadOnePageReport
        generatePDF() {
            this.downloadOnePageReport();
        },

        // 1. Jumlah Mahasiswa (Menambahkan Timeout)
        async fetchJumlahData() {
            this.isLoadingJumlah = true;
            this.errorJumlah = null;
            await new Promise(resolve => setTimeout(resolve, 300)); 
            try {
                const response = await fetch(
                    `${API_BASE_URL}/api/v1/mahasiswa/jumlah-mahasiswa?${this.getQueryParams(this.filters.jumlah)}`,
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
                            Accept: "application/json",
                        },
                    }
                );
                if (response.status === 401 || response.status === 403) {
                    throw new Error("Akses Ditolak: Anda perlu login untuk melihat data ini.");
                }
                if (!response.ok)
                    throw new Error(`Server Error: ${response.status}`);
                let rawData = this.normalizeData(await response.json());
                
                rawData = this.aggregateData(rawData, 'angkatan');
                const targetYears = [2023, 2024, 2025];
                rawData = rawData.filter(item => targetYears.includes(Number(item.angkatan)));
                rawData.sort((a, b) => a.angkatan - b.angkatan);

                this.jumlahChartData = {
                    labels: rawData.map((item) => item.angkatan),
                    datasets: [
                        {
                            label: "Total Mahasiswa",
                            backgroundColor: ["#08519c", "#42A5F5", "#6BAED6"],
                            data: rawData.map((item) => item.total),
                            borderRadius: 6,
                            barThickness: 50,
                        },
                    ],
                };
            } catch (error) {
                console.error("Jumlah Mhs Error:", error);
                this.errorJumlah = error.message;
            } finally {
                this.isLoadingJumlah = false;
            }
        },

        // 2. Gender (Menambahkan Timeout)
        async fetchGenderData() {
            this.isLoadingGender = true;
            this.errorGender = null;
            await new Promise(resolve => setTimeout(resolve, 300));
            try {
                const response = await fetch(
                    `${API_BASE_URL}/api/v1/mahasiswa/gender?${this.getQueryParams(this.filters.gender)}`,
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
                            Accept: "application/json",
                        },
                    }
                );
                if (!response.ok)
                    throw new Error(`Server Error: ${response.status}`);
                let rawData = this.normalizeData(await response.json());
                if (!this.filters.gender.angkatan) {
                    rawData = this.aggregateData(rawData, 'jenis');
                }

                this.genderChartData = {
                    labels: rawData.map((item) => item.jenis),
                    datasets: [
                        {
                            backgroundColor: ["#FF6384", "#42A5F5"],
                            data: rawData.map((item) => item.total),
                        },
                    ],
                };
            } catch (error) {
                console.error("Gender Error:", error);
                this.errorGender = "Gagal memuat data gender.";
            } finally {
                this.isLoadingGender = false;
            }
        },

        // 3. Agama (Menambahkan Timeout)
        async fetchAgamaData() {
            this.isLoadingAgama = true;
            this.errorAgama = null;
            await new Promise(resolve => setTimeout(resolve, 300)); 
            try {
                const response = await fetch(
                    `${API_BASE_URL}/api/v1/mahasiswa/agama?${this.getQueryParams(this.filters.agama)}`,
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
                            Accept: "application/json",
                        },
                    }
                );
                if (!response.ok)
                    throw new Error(`Server Error: ${response.status}`);
                let rawData = this.normalizeData(await response.json());
                if (!this.filters.agama.angkatan) {
                    rawData = this.aggregateData(rawData, 'agama');
                }

                const colors = [ "#3b82f6", "#FF6384", "#ef4444" ];
                this.agamaChartData = {
                    labels: rawData.map((item) => item.agama),
                    datasets: [
                        {
                            label: "Jumlah Mahasiswa",
                            backgroundColor: colors.slice(0, rawData.length),
                            data: rawData.map((item) => item.total),
                        },
                    ],
                };
            } catch (error) {
                console.error("Agama Error:", error);
                this.errorAgama = error.message;
            } finally {
                this.isLoadingAgama = false;
            }
        },

        // 4. SLTA (Menambahkan Timeout)
        async fetchSLTAData() {
            this.isLoadingSLTA = true;
            this.errorSLTA = null;
            await new Promise(resolve => setTimeout(resolve, 300)); 
            try {
                const response = await fetch(
                    `${API_BASE_URL}/api/v1/mahasiswa/jenis-slta?${this.getQueryParams(this.filters.slta)}`,
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
                            Accept: "application/json",
                        },
                    }
                );
                if (!response.ok)
                    throw new Error(`Server Error: ${response.status}`);
                let rawData = this.normalizeData(await response.json());
                if (!this.filters.slta.angkatan) {
                    rawData = this.aggregateData(rawData, 'jenis');
                }

                const colors = ["#5EB4FF", "#A1E1FD", "#FFFBD5"];
                this.sltaChartData = {
                    labels: rawData.map((item) => item.jenis),
                    datasets: [
                        {
                            label: "Jumlah Mahasiswa",
                            backgroundColor: colors.slice(0, rawData.length),
                            data: rawData.map((item) => item.total),
                            borderRadius: 6,
                            barThickness: 80,
                        },
                    ],
                };
            } catch (error) {
                console.error("SLTA Error:", error);
                this.errorSLTA = error.message;
            } finally {
                this.isLoadingSLTA = false;
            }
        },

        // Helper Methods
        normalizeData(responseData) {
            let rawData = responseData.data || responseData;
            if (responseData.payload) rawData = responseData.payload;
            if (!Array.isArray(rawData) && responseData.all?.data)
                rawData = responseData.all.data;
            if (!Array.isArray(rawData))
                throw new Error("Format data tidak valid");
            return rawData;
        },

        aggregateData(data, key) {
            const map = new Map();
            data.forEach((item) => {
                const label = item[key];
                if (!label) return; 
                
                if (!map.has(label)) {
                    map.set(label, { ...item, total: 0 });
                }
                
                const entry = map.get(label);
                entry.total += Number(item.total || 0);
            });
            return Array.from(map.values());
        },
    },
};
</script>

<style scoped>

.kemahasiswaan-page {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding-bottom: 2rem;
}

.page-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center; 
    gap: 1.5rem; 
    margin-bottom: 0.5rem;
}

.page-header h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0; 
}

/* --- GRID LAYOUT ASIMETRIS --- */
.charts-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-top: 1rem;
}

/* 1. Jumlah Mahasiswa (Card pertama) */
.charts-grid .chart-card:nth-child(1) {
    grid-row: 1 / span 2; 
    grid-column: 1 / span 1; 
}

/* 4. Jenis SLTA (Card keempat) */
.charts-grid .chart-card:nth-child(4) {
    grid-row: 3 / span 1;
    grid-column: 1 / span 2; 
}

.chart-card {
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-color);
    min-width: 0;
    
    display: flex;
    flex-direction: column;
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


/* Penyesuaian tinggi chart containers agar sesuai dengan layout asimetris */
.chart-container {
    position: relative;
    height: 250px; 
    width: 100%;
    flex-grow: 1; 
}

.chart-container-large {
    position: relative;
    height: 570px; 
    width: 100%;
    flex-grow: 1;
}

.chart-container-wide {
    position: relative;
    height: 350px; 
    width: 100%;
    flex-grow: 1;
}

/* Download Action Area */
.download-action-area {
    display: flex;
    justify-content: flex-end;
    margin-top: 1rem; 
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
}


/* State Containers (Loading, Error) */
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
    to {
        transform: rotate(360deg);
    }
}

/* --- STYLE TOMBOL DOWNLOAD GLOBAL --- */
.btn-export-all {
    display: flex;
    align-items: center;
    background-color: var(--color-banner, #007bff); 
    color: white;
    border: 1px solid var(--color-banner, #007bff);
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    font-size: 0.95rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    white-space: nowrap;
}
.btn-export-all:hover { background-color: var(--color-primary-hover, #0056b3); transform: translateY(-1px); }
.btn-export-all:disabled { background-color: #ccc; cursor: not-allowed; transform: none; box-shadow: none; }

/* Responsif */
@media (max-width: 992px) {
    .charts-grid {
        grid-template-columns: 1fr; 
    }
    /* Menghapus penempatan grid asimetris di mobile */
    .charts-grid .chart-card:nth-child(1),
    .charts-grid .chart-card:nth-child(2),
    .charts-grid .chart-card:nth-child(3),
    .charts-grid .chart-card:nth-child(4) {
        grid-row: auto;
        grid-column: auto;
    }
    
    /* Standarisasi tinggi chart di mobile */
    .chart-container-large,
    .chart-container-wide,
    .chart-container {
        height: 350px !important; 
    }
}
</style>