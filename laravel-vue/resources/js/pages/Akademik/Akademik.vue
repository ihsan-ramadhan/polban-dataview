<template>
  <div class="akademik-page">
    <h1>Selamat Datang di Polban Dataverse</h1>
    <p>Ini adalah halaman akademik dari aplikasi Polban Dataverse.</p>

    <div class="dashboard-grid">
      
      <!-- Row 1: Dua card -->
      <div class="grid-item main-area">
        <TipeTesMasukCard />
      </div>

      <div class="grid-item side-area">
        <RataNilaiCard />
      </div>

      <!-- Row 2: Satu card full width -->
      <div class="grid-item full-width">
        <TrenIPCard />
      </div>

    </div>
  </div>
</template>

<script>
import TipeTesMasukCard from './TipeTesMasukCard.vue';
import RataNilaiCard from './RataNilaiCard.vue';
import TrenIPCard from './TrenIPCard.vue';

export default {
  name: 'Akademik',
  components: {
    TipeTesMasukCard,
    RataNilaiCard,
    TrenIPCard
  }
}
</script>

<style scoped>
.dashboard-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  margin-top: 1.5rem;
}

.grid-item {
  min-width: 300px;
}

.main-area { flex: 2; }
.side-area { flex: 1; }

/* Full width untuk card ketiga */
.full-width { 
  flex: 1 1 100%; 
  width: 100%;
}
</style>