<template>
  <div id="app">
    <AppHeader @toggle-sidebar="handleToggleSidebar" />
    <AppSidebar :isOpen="isSidebarOpen" />
    <main class="main-content" :class="{ 'main-expanded': !isSidebarOpen }">
      <div class="content-area">
        <router-view />
      </div>
    </main>
  </div>
</template>

<script>
import AppHeader from './components/Layout/AppHeader.vue'
import AppSidebar from './components/Layout/AppSidebar.vue'

export default {
  name: 'App',
  components: {
    AppHeader,
    AppSidebar
  },
  data() {
    return {
      isSidebarOpen: true
    }
  },
  methods: {
    handleToggleSidebar() {
      this.isSidebarOpen = !this.isSidebarOpen
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
  margin-left: 280px;
  transition: margin-left var(--transition-normal);
}

.main-expanded {
  margin-left: 80px;
}

.content-area {
  padding: var(--space-6);
  min-height: 100%;
}

@media (max-width: 1024px) {
  .main-content {
    margin-left: 0;
  }
  
  .main-expanded {
    margin-left: 0;
  }
}

@media (max-width: 768px) {
  .content-area {
    padding: var(--space-4);
  }
}
</style>