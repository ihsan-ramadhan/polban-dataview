<template>
  <aside class="app-sidebar" :class="{ 'sidebar-collapsed': !isOpen }">
    <div class="sidebar-content">
      <nav class="sidebar-nav">
        <div class="nav-section">
          <ul class="nav-list">
            <li v-for="item in mainMenu" :key="item.name">
              <router-link 
                :to="item.path" 
                class="nav-item"
                :class="{ 'nav-item--active': $route.path === item.path }"
                :title="item.name"
              >
                <img 
                  :src="item.icon" 
                  :alt="item.name"
                  class="nav-icon"
                  :class="{ 'nav-icon--active': $route.path === item.path }"
                />
                <span class="nav-text">{{ item.name }}</span>
                <span v-if="item.badge" class="nav-badge">{{ item.badge }}</span>
              </router-link>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </aside>
</template>

<script>
export default {
  name: 'AppSidebar',
  props: {
    isOpen: Boolean
  },
  data() {
    return {
      mainMenu: [
        { name: 'Home', path: '/', icon: '/images/home.svg', badge: null },
        { name: 'Akademik', path: '/akademik', icon: '/images/akademik.svg', badge: null },
        { name: 'Kemahasiswaan', path: '/kemahasiswaan', icon: '/images/kemahasiswaan.svg', badge: null },
      ]
    }
  }
}
</script>

<style scoped>
.app-sidebar {
  position: fixed;
  top: 70px;
  left: 0;
  bottom: 0;
  width: 280px;
  background: var(--color-white);
  border-right: 1px solid var(--color-gray-200);
  box-shadow: var(--shadow-md);
  transition: width var(--transition-normal);
  z-index: var(--z-fixed);
  overflow: hidden;
}

.app-sidebar.sidebar-collapsed {
  width: 80px;
}

.sidebar-content {
  height: 100%;
  display: flex;
  flex-direction: column;
  padding: var(--space-4) 0;
}

.sidebar-nav {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: var(--space-6);
  padding: 0 var(--space-4);
  overflow-y: auto;
  scrollbar-width: none;
}

/* navigasi */
.nav-section {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}

.nav-list {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: var(--space-1);
}

/* navigasi item */
.nav-item {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  padding: var(--space-2) var(--space-3);
  text-decoration: none;
  color: var(--color-gray-600);
  border-radius: var(--radius-2xl);
  transition: all var(--transition-normal);
  font-weight: 500;
  position: relative;
  border: 1px solid transparent;
}

.nav-item:hover {
  background: var(--color-gray-50);
  color: var(--color-gray-600);
  transform: translateX(2px);
}

.nav-item--active {
  background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-secondary-dark) 100%);
  color: var(--color-white);
  border-color: var(--color-secondary);
  box-shadow: var(--shadow-sm);
  padding: var(--space-2) var(--space-3);
}

.nav-item--active:hover {
  background: linear-gradient(135deg, var(--color-secondary-dark) 0%, var(--color-secondary) 100%);
  color: var(--color-white);
  transform: translateX(2px);
}

.nav-icon {
  width: 24px;
  height: 24px;
  flex-shrink: 0;
  transition: filter var(--transition-normal);
  filter: saturate(100%) invert(33%) sepia(9%) hue-rotate(190deg) brightness(96%) contrast(89%);
}

.nav-icon--active {
  filter: invert(100%);
}

.nav-text {
  font-size: 0.875rem;
  font-weight: 500;
  transition: opacity var(--transition-normal);
  white-space: nowrap;
  flex: 1;
}

.nav-badge {
  background: var(--color-secondary);
  color: var(--color-white);
  font-size: 0.625rem;
  font-weight: 600;
  padding: var(--space-1) var(--space-2);
  border-radius: var(--radius-xl);
  line-height: 1;
}

.sidebar-collapsed .nav-text,
.sidebar-collapsed .nav-badge {
  display: none;
}

.sidebar-collapsed .nav-item {
  justify-content: left;
  padding: var(--space-2) var(--space-3);
}

.sidebar-collapsed .nav-icon {
  width: 24px;
  height: 24px;
}

@media (max-width: 1024px) {
  
  .app-sidebar.sidebar-collapsed {
    transform: translateX(-100%);
  }
}

@media (max-width: 768px) {
  .app-sidebar {
    width: 100%;
    transform: translateX(-100%);
  }
  
  .app-sidebar.sidebar-collapsed {
    transform: translateX(0);
    width: 100%;
  }
  
  .sidebar-collapsed .nav-text {
    display: block;
  }
}
</style>