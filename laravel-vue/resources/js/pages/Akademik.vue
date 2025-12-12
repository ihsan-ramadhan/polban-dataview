<template>
    <div class="akademik-page" ref="pageContainer">
        <div class="page-header">
            <div class="header-content">
                <div>
                    <h1>Statistik Akademik</h1>
                    <p>
                        Data dan visualisasi terkait aktivitas akademik Politeknik Negeri Bandung.
                    </p>
                </div>
                
                <PdfReportButton 
                    :target-element="$refs.pageContainer"
                    file-name="Laporan-Akademik"
                    button-text="Download Laporan (PDF)"
                    :disabled="isLoadingTipeTes"
                />
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-card span-full" ref="chartCardTipeTes">
                <div class="card-header">
                    <h2 class="chart-title">Tipe Tes Masuk</h2>
                    <p class="chart-subtitle">Distribusi berdasarkan jalur masuk</p>
                </div>
                
                <div class="chart-filters" data-html2canvas-ignore="true">
                    <div class="filter-dropdown" @mousedown="showAngkatanDropdown = true">
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

                <div class="chart-container">
                    <div v-if="isLoadingTipeTes" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <ErrorState 
                        v-else-if="errorTipeTes" 
                        @retry="fetchTipeTesData" 
                    />
                    <EmptyState 
                        v-else-if="!tipeTesChartData || tipeTesChartData.datasets[0].data.length === 0" 
                    />
                    <BasePieChart
                        v-else-if="tipeTesChartData"
                        :chart-data="tipeTesChartData"
                    />
                </div>

                <div class="download-action-area" v-if="!isLoadingTipeTes && tipeTesChartData">
                    <ChartDownloadButton 
                        :target-element="$refs.chartCardTipeTes" 
                        file-name="PieChart-TipeTes" 
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BasePieChart from "../components/Charts/BasePieChart.vue";
import ChartDownloadButton from "../components/Shared/ChartDownloadButton.vue";
import ErrorState from "../components/Shared/ErrorState.vue";
import EmptyState from "../components/Shared/EmptyState.vue";
import PdfReportButton from "../components/Shared/PdfReportButton.vue";

const API_BASE_URL = "http://localhost:3000";

export default {
    name: "Akademik",
    components: {
        BasePieChart,
        ChartDownloadButton,
        ErrorState,
        EmptyState,
        PdfReportButton,
    },
    data() {
        return {
            // Data Tipe Tes
            tipeTesChartData: null,
            isLoadingTipeTes: true,
            errorTipeTes: null,

            // Filter
            filters: { angkatan: "" },
            angkatanList: [2023, 2024, 2025],
            showAngkatanDropdown: false,
        };
    },
    mounted() {
        this.fetchAllData();
    },
    methods: {
        fetchAllData() {
            this.fetchTipeTesData();
        },

        async fetchTipeTesData() {
            this.isLoadingTipeTes = true;
            this.errorTipeTes = null;
            try {
                const params = new URLSearchParams();
                if (this.filters.angkatan) params.append("angkatan", this.filters.angkatan);
                const finalUrl = `${API_BASE_URL}/api/v1/akademik/tipe-tes-masuk?${params.toString()}`;
                
                const response = await fetch(finalUrl, {
                    headers: { Authorization: `Bearer ${localStorage.getItem("auth_token")}`, Accept: "application/json" },
                });

                if (response.status === 401 || response.status === 403) throw new Error("Sesi habis.");
                if (!response.ok) throw new Error(`Server Error: ${response.status}`);
                
                const rawData = this.normalizeData(await response.json());
                const processedData = this.processChartData(rawData);
                
                const colors = ["#42A5F5", "#66BB6A", "#FFA726", "#AB47BC", "#EF5350", "#8D6E63", "#78909C"];

                this.tipeTesChartData = {
                    labels: processedData.labels,
                    datasets: [
                        {
                            label: "Jumlah Mahasiswa",
                            backgroundColor: colors,
                            data: processedData.values,
                            borderWidth: 1
                        },
                    ],
                };
            } catch (error) {
                console.error("Tipe Tes Error:", error);
                this.errorTipeTes = "Gagal memuat data tipe tes.";
            } finally {
                this.isLoadingTipeTes = false;
            }
        },

        normalizeData(responseData) {
            if (Array.isArray(responseData)) return responseData;
            if (responseData.data && Array.isArray(responseData.data)) return responseData.data;
            if (responseData.payload) return responseData.payload;
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
            this.fetchTipeTesData();
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

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
}

.charts-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    width: 100%;
}

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

.download-action-area {
    display: flex;
    justify-content: flex-end;
    margin-top: 1rem;
}

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

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

@media (min-width: 768px) {
    .charts-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .span-full {
        grid-column: span 2;
    }
}

@media (max-width: 576px) {
    .header-content {
        flex-direction: column;
    }
    .chart-container {
        height: 250px;
        min-height: 250px;
    }
}
</style>