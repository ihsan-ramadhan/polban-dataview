<template>
  <div class="kemahasiswaan-page">
    <div class="page-header">
      <h1>Statistik Kemahasiswaan Polban</h1>
      <p>Menampilkan visual data dan karakteristik mahasiswa Jurusan Teknik Komputer dan Informatika berdasarkan angkatan serta kelas.</p>
    </div>

    <div class="charts-grid">
            <div class="chart-card jumlah-mahasiswa-card">
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
                    />
                </div>
            </div>

            <div class="chart-card">
                <div class="card-header-wrapper">
                    <div class="card-header">
                        <h2 class="chart-title">Rasio Gender</h2>
                        <p class="chart-subtitle">
                            Perbandingan Laki-laki dan Perempuan
                        </p>
                    </div>
                    <div class="chart-filters">
                        <select v-model="filters.gender.angkatan" @change="fetchGenderData" class="filter-select">
                            <option value="">Semua Angkatan</option>
                            <option v-for="year in angkatanList" :key="year" :value="year">{{ year }}</option>
                        </select>
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
                    />
                </div>
            </div>

            <div class="chart-card">
                <div class="card-header-wrapper">
                    <div class="card-header">
                        <h2 class="chart-title">Persebaran Agama</h2>
                        <p class="chart-subtitle">Statistik Agama Mahasiswa</p>
                    </div>
                    <div class="chart-filters">
                        <select v-model="filters.agama.angkatan" @change="fetchAgamaData" class="filter-select">
                            <option value="">Semua Angkatan</option>
                            <option v-for="year in angkatanList" :key="year" :value="year">{{ year }}</option>
                        </select>
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
                    />
                </div>
            </div>

            <div class="chart-card slta-card">
                <div class="card-header-wrapper">
                    <div class="card-header">
                        <h2 class="chart-title">Asal Jenis Sekolah (SLTA)</h2>
                        <p class="chart-subtitle">
                            Statistik Berdasarkan Asal Jenis Sekolah
                        </p>
                    </div>
                    <div class="chart-filters">
                        <select v-model="filters.slta.angkatan" @change="fetchSLTAData" class="filter-select">
                            <option value="">Semua Angkatan</option>
                            <option v-for="year in angkatanList" :key="year" :value="year">{{ year }}</option>
                        </select>
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
                    />
                </div>                        
            </div>   
        </div>    
    </div>
    <button @click="generatePDF" class="pdf-button">
        <span class="pdf-icon">📄</span> Simpan sebagai PDF
    </button>
</template>

<script>
import BaseBarChart from "../components/Charts/BaseBarChart.vue";
import BasePieChart from "../components/Charts/BasePieChart.vue";
import html2canvas from 'html2canvas';
import { jsPDF } from 'jspdf';

const API_BASE_URL = "http://localhost:3000";

export default {
    name: "Kemahasiswaan",
    components: {
        BaseBarChart,
        BasePieChart,
    },
    data() {
        return {
            // Hardcoded Lists
            angkatanList: [2023, 2024, 2025],
            prodiList: [
                "D3 Teknik Informatika"
            ],

            // Filters per Chart
            filters: {
                jumlah: { angkatan: "" },
                gender: { angkatan: "" },
                agama: { angkatan: "" },
                slta: { angkatan: "" },
            },

            // Jumlah Mahasiswa
            jumlahChartData: null,
            isLoadingJumlah: true,
            errorJumlah: null,

            // Gender
            genderChartData: null,
            isLoadingGender: true,
            errorGender: null,

            // Agama
            agamaChartData: null,
            isLoadingAgama: true,
            errorAgama: null,

            // SLTA
            sltaChartData: null,
            isLoadingSLTA: true,
            errorSLTA: null,
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

        // 1. Jumlah Mahasiswa (Protected)
        async fetchJumlahData() {
            this.isLoadingJumlah = true;
            this.errorJumlah = null;
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
                    throw new Error(
                        "Akses Ditolak: Anda perlu login untuk melihat data ini."
                    );
                }
                if (!response.ok)
                    throw new Error(`Server Error: ${response.status}`);
                let rawData = this.normalizeData(await response.json());
                
                // Aggregate by 'angkatan'
                rawData = this.aggregateData(rawData, 'angkatan');

                // Filter for years 2023, 2024, 2025
                const targetYears = [2023, 2024, 2025];
                rawData = rawData.filter(item => targetYears.includes(Number(item.angkatan)));
                
                // Sort by year
                rawData.sort((a, b) => a.angkatan - b.angkatan);

                this.jumlahChartData = {
                    labels: rawData.map((item) => item.angkatan),
                    datasets: [
                        {
                            label: "Total Mahasiswa",
                            backgroundColor:  ["#08519c", "#42A5F5", "#6BAED6"],
                            data: rawData.map((item) => item.total),
                            borderRadius: 6,
                            barThickness: 30,
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

        // 2. Gender (Public -> Protected but bypassed)
        async fetchGenderData() {
            this.isLoadingGender = true;
            this.errorGender = null;
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

        // 3. Agama (Public -> Protected but bypassed)
        async fetchAgamaData() {
            this.isLoadingAgama = true;
            this.errorAgama = null;
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

                const colors = [
                    "#3b82f6",
                    "#FF6384",
                    "#ef4444",
                ];
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

        // 4. SLTA (Public -> Protected but bypassed)
        async fetchSLTAData() {
            this.isLoadingSLTA = true;
            this.errorSLTA = null;
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
                            barThickness: 40,
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
                if (!label) return; // Skip if key not found
                
                if (!map.has(label)) {
                    // Clone item to preserve other properties, reset total to 0 initially
                    map.set(label, { ...item, total: 0 });
                }
                
                const entry = map.get(label);
                // Ensure total is treated as a number
                entry.total += Number(item.total || 0);
            });
            return Array.from(map.values());
        },

        async generatePDF() {
            // 1. Dapatkan elemen yang ingin dijadikan PDF (container grid)
            const input = document.querySelector('.charts-grid');
            if (!input) {
                console.error("Elemen .charts-grid tidak ditemukan!");
                return;
            }

            try {
                // 2. Gunakan html2canvas untuk mengubah elemen HTML menjadi gambar (canvas)
                const canvas = await html2canvas(input, {
                    // Opsi untuk memastikan rendering yang baik
                    scale: 2, 
                    useCORS: true,
                    allowTaint: true 
                });

                // 3. Konfigurasi jsPDF
                const imgData = canvas.toDataURL('image/png');
                const imgWidth = 210; // Lebar A4 dalam mm
                const pageHeight = 297; // Tinggi A4 dalam mm
                const imgHeight = canvas.height * imgWidth / canvas.width;
                let heightLeft = imgHeight;
                
                // Buat instance PDF baru (A4, orientasi Portrait)
                const pdf = new jsPDF('p', 'mm', 'a4');
                let position = 0;

                // Tambahkan halaman jika gambar terlalu panjang (membutuhkan lebih dari 1 halaman)
                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                // 4. Simpan file PDF
                pdf.save('Statistik_Kemahasiswaan_Polban.pdf');
                
            } catch (error) {
                console.error("Gagal membuat PDF:", error);
                alert("Gagal menyimpan PDF. Periksa konsol untuk detailnya.");
            }
        },
    },
};
</script>

<style scoped>
/* Asumsikan variabel CSS seperti --text-primary, --bg-secondary, dll. sudah didefinisikan secara global */

.kemahasiswaan-page {
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

/* --- GRID LAYOUT ASIMETRIS --- */
.charts-grid {
    display: grid;
    /* Dua kolom, dengan lebar yang sama */
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
/* Card ke-2 (Gender) dan ke-3 (Agama) akan mengisi Baris 1 & 2 di Kolom 2 secara otomatis. */


.chart-card {
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-color);
    min-width: 0;
    
    /* Penting untuk Grid: Memastikan konten didalamnya terdorong ke bawah */
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

.filter-select {
    padding: 0.4rem 0.8rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 0.85rem;
    background: var(--bg-primary);
    color: var(--text-primary);
    cursor: pointer;
    outline: none;
    min-width: 150px;
}

.filter-select:focus {
    border-color: var(--color-primary);
}

/* Penyesuaian tinggi chart containers agar sesuai dengan layout asimetris */
.chart-container {
    position: relative;
    /* Tinggi standar untuk chart 1x1 */
    height: 250px; 
    width: 100%;
    flex-grow: 1; 
    margin-bottom: 1rem; /* Jarak sebelum detail link/footer */
}

/* Container untuk card yang membentang 2 baris (Jumlah Mahasiswa) */
.chart-container-large {
    position: relative;
    /* Tinggi yang diperkirakan untuk 2x tinggi chart biasa */
    height: 570px; 
    width: 100%;
    flex-grow: 1;
    margin-bottom: 1rem; 
}

/* Container untuk card yang membentang 2 kolom (SLTA) */
.chart-container-wide {
    position: relative;
    height: 350px; 
    width: 100%;
    flex-grow: 1;
    margin-bottom: 1rem; 
}


/* Footer/Detail Link Style */
.detail-link {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-primary); 
    text-decoration: none;
    margin-top: auto; 
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
    display: inline-block;
    align-self: flex-start;
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


/* Responsif */
@media (max-width: 992px) {
  .charts-grid {
    grid-template-columns: 1fr; 
  }
  .charts-grid .chart-card:nth-child(1),
  .charts-grid .chart-card:nth-child(2),
  .charts-grid .chart-card:nth-child(3),
  .charts-grid .chart-card:nth-child(4) {
    grid-row: auto;
    grid-column: auto;
  }
    
    /* Mengatur ulang tinggi container di mode responsif */
    .chart-container-large,
    .chart-container-wide,
    .chart-container {
        height: 350px !important; /* Standar yang sama untuk semua di mode 1 kolom */
    }
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.pdf-button {
    display: flex;
    align-items: center;
    padding: 0.6rem 1rem;
    background-color: #EF4444; 
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
}

.pdf-button:hover {
    background-color: #DC2626;
}

.pdf-icon {
    margin-right: 0.5rem;
    font-size: 1.1rem;
}
</style>