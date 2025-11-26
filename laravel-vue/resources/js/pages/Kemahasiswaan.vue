<template>
  <div class="kemahasiswaan-page">
    <div class="page-header">
      <h1>Statistik Kemahasiswaan Polban</h1>
      <p>Menampilkan visual data dan karakteristik mahasiswa Jurusan Teknik Komputer dan Informatika berdasarkan angkatan serta kelas.</p>
    </div>

    <div class="charts-grid">

      <div class="chart-card">
        <div class="chart-header">
          <h2 class="chart-title">Jumlah Mahasiswa</h2>
        </div>
        <div class="chart-container-placeholder">
          <p class="placeholder-text">Konten Grafik Belum Tersedia</p>
        </div>
      </div>

      <div class="chart-card">
        <div class="chart-header">
          <div>
            <h2 class="chart-title">Jenis Kelamin</h2>
          </div>  
        </div>
        <div class="chart-container-placeholder">
          <p class="placeholder-text">Konten Grafik Belum Tersedia</p>
        </div>
      </div>

      <div class="chart-card">
        <div class="chart-header">
          <div>
            <h2 class="chart-title">Agama</h2>
          </div>  
        </div>
        <div class="chart-container-placeholder">
          <p class="placeholder-text">Konten Grafik Belum Tersedia</p>
        </div>
      </div>

      <div class="chart-card">
        <div class="chart-header">
          <h2 class="chart-title">Asal Jenis Sekolah</h2>
        </div>
        <div class="chart-container-placeholder">
          <p class="placeholder-text">Konten Grafik Belum Tersedia</p>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'Kemahasiswaan'
}
</script>

<style scoped>

.charts-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr); 
  gap: var(--space-6);
  margin-top: var(--space-6);
}

.charts-grid .chart-card:nth-child(1) {
  grid-row: 1 / span 2; 
  grid-column: 1 / span 1; 
}

.charts-grid .chart-card:nth-child(4) {
  grid-row: 3 / span 1;
  grid-column: 1 / span 2; 
}

.chart-card {
  background: white;
  border-radius: var(--radius-lg);
  padding: var(--space-6);
  box-shadow: var(--shadow-md);
  display: flex;
  flex-direction: column;
}

.chart-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: var(--space-4);
  gap: var(--space-6);
}

.chart-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--color-gray-800);
  margin: 0;
}

.chart-container-placeholder {
  height: 250px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: var(--space-4);
  margin-bottom: var(--space-4);
  flex-grow: 1;
}

.placeholder-text {
  color: var(--color-gray-400);
  font-style: italic;
  font-size: 0.9rem;
}

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
}
</style>