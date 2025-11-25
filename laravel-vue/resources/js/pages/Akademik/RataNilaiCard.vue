<template>
  <div class="chart-card placeholder">
    <h2>Chart Data Lain</h2>
    <p>Coming Soon</p>
  </div>
</template>

<script>
export default {
  name: 'RataNilaiCard'
}
</script>

<style scoped>
.chart-card {
  background: #fff;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.placeholder {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  color: #9ca3af;
  min-height: 300px;
}

.placeholder h2 {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0 0 0.5rem 0;
}

.placeholder p {
  font-size: 0.875rem;
  margin: 0;
}
</style>