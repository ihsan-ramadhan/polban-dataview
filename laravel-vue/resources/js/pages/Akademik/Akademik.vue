<template>
  <div class="akademik-page">
    <h1>Selamat Datang di Polban Dataverse</h1>
    <p>Ini adalah halaman akademik dari aplikasi Polban Dataverse.</p>

    <div class="dashboard-grid">
      
      <div class="grid-item main-area">
        <TipeTesMasukCard />
      </div>

      <div class="grid-item side-area">
        <div class="chart-card placeholder">
          <h2>Chart Data Lain</h2>
          <p>Coming Soon</p>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
// PENTING: Kita import komponen yang sudah ada filternya
import TipeTesMasukCard from './TipeTesMasukCard.vue';

export default {
  name: 'Akademik',
  components: {
    TipeTesMasukCard
  }
}
</script>

<style scoped>
/* Layout Grid/Flexbox */
.dashboard-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  margin-top: 1.5rem;
}

.grid-item {
  min-width: 300px;
}

/* Mengatur Lebar: Kiri lebih besar (2/3), Kanan lebih kecil (1/3) */
.main-area { flex: 2; }
.side-area { flex: 1; }

/* Style Placeholder Simpel */
.placeholder {
  background: #fff;
  border-radius: 12px;
  padding: 24px;
  height: 100%;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  color: #9ca3af;
}
</style>