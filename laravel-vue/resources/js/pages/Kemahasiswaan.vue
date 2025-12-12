<template>
    <div class="kemahasiswaan-page" ref="pageContainer">
        <div class="page-header">
            <div class="header-content">
                <div>
                    <h1>Statistik Kemahasiswaan</h1>
                    <p>
                        Visualisasi data karakteristik mahasiswa Jurusan Teknik Komputer dan Informatika.
                    </p>
                </div>
                
                <PdfReportButton 
                    :target-element="$refs.pageContainer"
                    file-name="Laporan-Kemahasiswaan"
                    button-text="Download Laporan (PDF)"
                    :disabled="isLoadingAny"
                />
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-card span-full" ref="chartCardJumlah">
                <div class="card-header">
                    <h2 class="chart-title">Jumlah Mahasiswa</h2>
                    <p class="chart-subtitle">Total Mahasiswa Aktif per Angkatan</p>
                </div>
                
                <div class="chart-container">
                    <div v-if="isLoadingJumlah" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <ErrorState 
                        v-else-if="errorJumlah" 
                        @retry="fetchJumlahData" 
                    />
                    <EmptyState 
                        v-else-if="!jumlahChartData || jumlahChartData.datasets[0].data.length === 0" 
                    />
                    <BaseBarChart
                        v-else-if="jumlahChartData"
                        :chart-data="jumlahChartData"
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
                <div class="card-header">
                    <h2 class="chart-title">Rasio Gender</h2>
                    <p class="chart-subtitle">Perbandingan Laki-laki dan Perempuan</p>
                </div>
                
                <div class="chart-filters" data-html2canvas-ignore="true">
                    <div class="filter-dropdown" @mousedown="showGenderDropdown = true">
                        <div class="filter-select-styled">
                            {{ filters.gender.angkatan || 'Semua Angkatan' }}
                            <span class="dropdown-arrow">▼</span>
                        </div>
                        <div v-show="showGenderDropdown" class="dropdown-menu">
                            <div class="dropdown-item" @click="selectFilter('gender', '')" :class="{ active: filters.gender.angkatan === '' }">Semua Angkatan</div>
                            <div v-for="year in angkatanList" :key="year" class="dropdown-item" @click="selectFilter('gender', year)" :class="{ active: filters.gender.angkatan === year }">{{ year }}</div>
                        </div>
                    </div>
                </div>

                <div class="chart-container">
                    <div v-if="isLoadingGender" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <ErrorState 
                        v-else-if="errorGender" 
                        @retry="fetchGenderData" 
                    />
                    <EmptyState 
                        v-else-if="!genderChartData || genderChartData.datasets[0].data.length === 0" 
                    />
                    <BasePieChart
                        v-else-if="genderChartData"
                        :chart-data="genderChartData"
                    />
                </div>

                <div class="download-action-area" v-if="!isLoadingGender && genderChartData">
                    <ChartDownloadButton 
                        :target-element="$refs.chartCardGender" 
                        file-name="PieChart-Gender" 
                    />
                </div>
            </div>

            <div class="chart-card" ref="chartCardAgama">
                <div class="card-header">
                    <h2 class="chart-title">Persebaran Agama</h2>
                    <p class="chart-subtitle">Statistik Agama Mahasiswa</p>
                </div>

                <div class="chart-filters" data-html2canvas-ignore="true">
                    <div class="filter-dropdown" @mousedown="showAgamaDropdown = true">
                        <div class="filter-select-styled">
                            {{ filters.agama.angkatan || 'Semua Angkatan' }}
                            <span class="dropdown-arrow">▼</span>
                        </div>
                        <div v-show="showAgamaDropdown" class="dropdown-menu">
                            <div class="dropdown-item" @click="selectFilter('agama', '')" :class="{ active: filters.agama.angkatan === '' }">Semua Angkatan</div>
                            <div v-for="year in angkatanList" :key="year" class="dropdown-item" @click="selectFilter('agama', year)" :class="{ active: filters.agama.angkatan === year }">{{ year }}</div>
                        </div>
                    </div>
                </div>

                <div class="chart-container">
                    <div v-if="isLoadingAgama" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <ErrorState 
                        v-else-if="errorAgama" 
                        @retry="fetchAgamaData" 
                    />
                    <EmptyState 
                        v-else-if="!agamaChartData || agamaChartData.datasets[0].data.length === 0" 
                    />
                    <BasePieChart
                        v-else-if="agamaChartData"
                        :chart-data="agamaChartData"
                    />
                </div>

                <div class="download-action-area" v-if="!isLoadingAgama && agamaChartData">
                    <ChartDownloadButton 
                        :target-element="$refs.chartCardAgama" 
                        file-name="PieChart-Agama" 
                    />
                </div>
            </div>

            <div class="chart-card span-full" ref="chartCardSLTA">
                <div class="card-header">
                    <h2 class="chart-title">Asal Jenis Sekolah (SLTA)</h2>
                    <p class="chart-subtitle">Statistik Berdasarkan Asal Sekolah</p>
                </div>

                <div class="chart-filters" data-html2canvas-ignore="true">
                    <div class="filter-dropdown" @mousedown="showSLTADropdown = true">
                        <div class="filter-select-styled">
                            {{ filters.slta.angkatan || 'Semua Angkatan' }}
                            <span class="dropdown-arrow">▼</span>
                        </div>
                        <div v-show="showSLTADropdown" class="dropdown-menu">
                            <div class="dropdown-item" @click="selectFilter('slta', '')" :class="{ active: filters.slta.angkatan === '' }">Semua Angkatan</div>
                            <div v-for="year in angkatanList" :key="year" class="dropdown-item" @click="selectFilter('slta', year)" :class="{ active: filters.slta.angkatan === year }">{{ year }}</div>
                        </div>
                    </div>
                </div>

                <div class="chart-container">
                    <div v-if="isLoadingSLTA" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <ErrorState 
                        v-else-if="errorSLTA" 
                        @retry="fetchSLTAData" 
                    />
                    <EmptyState 
                        v-else-if="!sltaChartData || sltaChartData.datasets[0].data.length === 0" 
                    />
                    <BaseBarChart
                        v-else-if="sltaChartData"
                        :chart-data="sltaChartData"
                    />
                </div>

                <div class="download-action-area" v-if="!isLoadingSLTA && sltaChartData">
                    <ChartDownloadButton 
                        :target-element="$refs.chartCardSLTA" 
                        file-name="BarChart-SLTA" 
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BaseBarChart from "../components/Charts/BaseBarChart.vue";
import BasePieChart from "../components/Charts/BasePieChart.vue";
import ChartDownloadButton from "../components/Shared/ChartDownloadButton.vue";
import ErrorState from "../components/Shared/ErrorState.vue";
import EmptyState from "../components/Shared/EmptyState.vue";
import PdfReportButton from "../components/Shared/PdfReportButton.vue";

const API_BASE_URL = "http://localhost:3000";

export default {
    name: "Kemahasiswaan",
    components: {
        BaseBarChart,
        BasePieChart,
        ChartDownloadButton,
        ErrorState,
        EmptyState,
        PdfReportButton,
    },
    data() {
        return {
            angkatanList: [2023, 2024, 2025],
            
            // Dropdown visibility states
            showGenderDropdown: false,
            showAgamaDropdown: false,
            showSLTADropdown: false,

            // Filter states
            filters: {
                jumlah: { angkatan: "" }, 
                gender: { angkatan: "" },
                agama: { angkatan: "" },
                slta: { angkatan: "" },
            },

            // Data & Loading States
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
        };
    },
    computed: {
        // Disable tombol jika ada salah satu chart yang masih loading
        isLoadingAny() {
            return this.isLoadingJumlah || 
                   this.isLoadingGender || 
                   this.isLoadingAgama || 
                   this.isLoadingSLTA;
        }
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

        // Helper untuk handle dropdown
        selectFilter(type, year) {
            this.filters[type].angkatan = year;
            if (type === 'gender') this.showGenderDropdown = false;
            if (type === 'agama') this.showAgamaDropdown = false;
            if (type === 'slta') this.showSLTADropdown = false;
            
            if (type === 'gender') this.fetchGenderData();
            if (type === 'agama') this.fetchAgamaData();
            if (type === 'slta') this.fetchSLTAData();
        },

        // --- FETCH METHODS ---
        async fetchJumlahData() {
            this.isLoadingJumlah = true;
            this.errorJumlah = null;
            try {
                const response = await fetch(
                    `${API_BASE_URL}/api/v1/mahasiswa/jumlah-mahasiswa`,
                    { headers: { Authorization: `Bearer ${localStorage.getItem("auth_token")}`, Accept: "application/json" } }
                );
                if (!response.ok) throw new Error("Gagal mengambil data");
                let rawData = this.normalizeData(await response.json());
                
                rawData = this.aggregateData(rawData, 'angkatan');
                const targetYears = [2023, 2024, 2025];
                rawData = rawData.filter(item => targetYears.includes(Number(item.angkatan)));
                rawData.sort((a, b) => a.angkatan - b.angkatan);

                this.jumlahChartData = {
                    labels: rawData.map(item => item.angkatan),
                    datasets: [{
                        label: "Total Mahasiswa",
                        backgroundColor: ["#1976D2", "#42A5F5", "#90CAF9"],
                        data: rawData.map(item => item.total),
                        borderRadius: 6,
                        barThickness: 50,
                    }],
                };
            } catch (error) {
                this.errorJumlah = error.message;
            } finally {
                this.isLoadingJumlah = false;
            }
        },

        async fetchGenderData() {
            this.isLoadingGender = true;
            this.errorGender = null;
            try {
                const response = await fetch(
                    `${API_BASE_URL}/api/v1/mahasiswa/gender?${this.getQueryParams(this.filters.gender)}`,
                    { headers: { Authorization: `Bearer ${localStorage.getItem("auth_token")}`, Accept: "application/json" } }
                );
                if (!response.ok) throw new Error("Gagal mengambil data");
                let rawData = this.normalizeData(await response.json());
                
                if (!this.filters.gender.angkatan) rawData = this.aggregateData(rawData, 'jenis');

                this.genderChartData = {
                    labels: rawData.map(item => item.jenis),
                    datasets: [{
                        backgroundColor: ["#42A5F5", "#EC407A"],
                        data: rawData.map(item => item.total),
                    }],
                };
            } catch (error) {
                this.errorGender = error.message;
            } finally {
                this.isLoadingGender = false;
            }
        },

        async fetchAgamaData() {
            this.isLoadingAgama = true;
            this.errorAgama = null;
            try {
                const response = await fetch(
                    `${API_BASE_URL}/api/v1/mahasiswa/agama?${this.getQueryParams(this.filters.agama)}`,
                    { headers: { Authorization: `Bearer ${localStorage.getItem("auth_token")}`, Accept: "application/json" } }
                );
                if (!response.ok) throw new Error("Gagal mengambil data");
                let rawData = this.normalizeData(await response.json());
                
                if (!this.filters.agama.angkatan) rawData = this.aggregateData(rawData, 'agama');

                const colors = ["#5C6BC0", "#26C6DA", "#66BB6A", "#FFA726", "#EF5350"];
                this.agamaChartData = {
                    labels: rawData.map(item => item.agama),
                    datasets: [{
                        label: "Mahasiswa",
                        backgroundColor: colors.slice(0, rawData.length),
                        data: rawData.map(item => item.total),
                    }],
                };
            } catch (error) {
                this.errorAgama = error.message;
            } finally {
                this.isLoadingAgama = false;
            }
        },

        async fetchSLTAData() {
            this.isLoadingSLTA = true;
            this.errorSLTA = null;
            try {
                const response = await fetch(
                    `${API_BASE_URL}/api/v1/mahasiswa/jenis-slta?${this.getQueryParams(this.filters.slta)}`,
                    { headers: { Authorization: `Bearer ${localStorage.getItem("auth_token")}`, Accept: "application/json" } }
                );
                if (!response.ok) throw new Error("Gagal mengambil data");
                let rawData = this.normalizeData(await response.json());
                
                if (!this.filters.slta.angkatan) rawData = this.aggregateData(rawData, 'jenis');

                const colors = ["#7E57C2", "#AB47BC", "#78909C", "#8D6E63"];
                this.sltaChartData = {
                    labels: rawData.map(item => item.jenis),
                    datasets: [{
                        label: "Total Mahasiswa",
                        backgroundColor: colors.slice(0, rawData.length),
                        data: rawData.map(item => item.total),
                        borderRadius: 6,
                        barThickness: 50,
                    }],
                };
            } catch (error) {
                this.errorSLTA = error.message;
            } finally {
                this.isLoadingSLTA = false;
            }
        },

        // --- UTILS ---
        normalizeData(responseData) {
            let rawData = responseData.data || responseData;
            if (responseData.payload) rawData = responseData.payload;
            if (!Array.isArray(rawData) && responseData.all?.data) rawData = responseData.all.data;
            return Array.isArray(rawData) ? rawData : [];
        },

        aggregateData(data, key) {
            const map = new Map();
            data.forEach((item) => {
                const label = item[key];
                if (!label) return;
                if (!map.has(label)) map.set(label, { ...item, total: 0 });
                map.get(label).total += Number(item.total || 0);
            });
            return Array.from(map.values());
        },
    },
};
</script>

<style scoped>
/* Base Layout */
.kemahasiswaan-page {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding-bottom: 2rem;
}

/* Header Styles */
.page-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
}

/* Grid Layout */
.charts-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    width: 100%;
}

/* Card Styles */
.chart-card {
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
    width: 100%;
    min-width: 0;
    border: 1px solid var(--border-color);
    position: relative;
}

.card-header {
    margin-bottom: 1rem;
}

.chart-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
}

.chart-subtitle {
    font-size: 0.8rem;
    color: var(--text-tertiary);
    margin-top: 4px;
}

.chart-container {
    position: relative;
    width: 100%;
    height: 300px;
    min-height: 300px;
    overflow: hidden;
}

/* Filters & Dropdown Styling */
.chart-filters {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    z-index: 10;
}

.filter-dropdown { position: relative; display: inline-block; }
.filter-select-styled { 
    padding: 0.4rem 0.8rem; 
    border: 1px solid var(--border-color); 
    border-radius: 8px; 
    font-size: 0.85rem; 
    background: var(--bg-primary); 
    color: var(--text-primary); 
    cursor: pointer; 
    min-width: 140px; 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    user-select: none; 
    transition: all 0.2s;
}
.filter-select-styled:hover { border-color: var(--color-primary); background: var(--bg-hover); }
.dropdown-arrow { font-size: 0.6rem; margin-left: 8px; color: var(--text-tertiary); }

.dropdown-menu {
    position: absolute; top: calc(100% + 4px); right: 0; 
    background: var(--bg-secondary); border: 1px solid var(--border-color);
    border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    min-width: 160px; z-index: 999; padding: 4px 0;
}
.dropdown-item {
    padding: 8px 12px; cursor: pointer; font-size: 0.85rem;
    color: var(--text-primary); transition: background 0.2s;
}
.dropdown-item:hover { background-color: var(--bg-hover); }
.dropdown-item.active { background-color: var(--bg-hover); color: var(--color-primary); font-weight: 600; }

/* Download Button Area per Chart */
.download-action-area {
    display: flex;
    justify-content: flex-end;
    margin-top: 1rem;
}

/* Loading & Error States */
.state-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
    color: var(--text-primary);
    font-size: 0.9rem;
    text-align: center;
}

.loader-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid #ccc;
    border-top-color: var(--color-primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-bottom: 8px;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* Responsive Grid */
@media (min-width: 768px) {
    .charts-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .span-full {
        grid-column: span 2;
    }
}

@media (max-width: 576px) {
    .header-content { flex-direction: column; }
    .chart-container { height: 250px; min-height: 250px; }
    .chart-filters { position: static; margin-bottom: 1rem; }
    .filter-select-styled { width: 100%; }
}
</style>