<template>
  <div class="not-found-page">
    <div class="content">
      <div class="error-code">404</div>
      <h1 class="title">Halaman Tidak Ditemukan</h1>
      <p class="description">
        Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan.
      </p>
      <router-link to="/" class="home-button">
        Kembali ke Beranda
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound'
}
</script>

<style scoped>
.not-found-page {
  min-height: 60vh;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: var(--space-6);
}

.content {
  animation: fadeIn 0.5s ease-out;
}

.error-code {
  font-size: 8rem;
  font-weight: 800;
  line-height: 1;
  background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 1rem;
  opacity: 0.8;
}

.title {
  font-size: 2rem;
  color: var(--text-primary);
  margin-bottom: 1rem;
  font-weight: 700;
}

.description {
  color: var(--text-secondary);
  max-width: 500px;
  margin: 0 auto 2.5rem;
  line-height: 1.6;
}

.home-button {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  background: var(--color-banner);
  color: white;
  padding: 1rem 2rem;
  border-radius: var(--radius-xl);
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: var(--shadow-md);
}

.home-button:hover {
  background: var(--color-primary-hover);
  transform: translateY(-2px);
  box-shadow: var(--shadow-lg);
}

[data-theme="dark"] .home-button:hover {
  background: var(--color-gray-600);
}

.home-button:active {
  transform: translateY(0);
}

.icon {
  font-size: 1.2rem;
}

@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}

@media (max-width: 768px) {
  .error-code {
    font-size: 6rem;
  }
  
  .title {
    font-size: 1.5rem;
  }
}
</style>
