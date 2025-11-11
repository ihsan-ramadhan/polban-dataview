<template>
  <div id="app">
    <AppHeader @toggle-sidebar="handleToggleSidebar" />
  </div>
</template>

<script>
import AppHeader from './components/Layout/AppHeader.vue'

export default {
  name: 'App',
  components: {
    AppHeader
  },
  methods: {
    handleToggleSidebar() {
      console.log('toggle sidebar di klik')
    }
  }
}
</script>

<style>
#app {
  min-height: 100vh;
  background: var(--color-gray-50);
}

.main-content {
  min-height: calc(100vh - 70px);
  margin-top: 70px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.content-area {
  text-align: center;
  padding: 2rem;
  max-width: 600px;
}

.content-area h1 {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--color-gray-900);
  margin-bottom: 1rem;
}

.content-area p {
  font-size: 1.125rem;
  color: var(--color-gray-600);
  margin-bottom: 1rem;
  line-height: 1.6;
}

.test-section {
  margin-top: 2rem;
  padding: 1.5rem;
  background: var(--color-white);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--color-gray-200);
}

.test-section p {
  margin-bottom: 0.5rem;
  font-size: 1rem;
}
</style>