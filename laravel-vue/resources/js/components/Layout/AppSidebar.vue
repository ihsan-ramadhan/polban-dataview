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
                <div class="nav-item-content">
                  <img 
                    :src="item.icon" 
                    :alt="item.name"
                    class="nav-icon"
                    :class="{ 'nav-icon--active': $route.path === item.path }"
                  />
                  <span class="nav-text">{{ item.name }}</span>
                </div>
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
        { name: 'Home', path: '/', icon: '/images/home.svg'},
        { name: 'Akademik', path: '/akademik', icon: '/images/akademik.svg'},
        { name: 'Kemahasiswaan', path: '/kemahasiswaan', icon: '/images/kemahasiswaan.svg'},
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
  background: var(--bg-secondary);
  border-right: 1px solid var(--border-color);
  box-shadow: var(--shadow-md);
  transition: width var(--transition-normal), background-color 0.3s ease, border-color 0.3s ease;
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
  overflow-y: auto;
  overflow-x: hidden;
}

.sidebar-nav {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: var(--space-6);
  padding: 0 var(--space-4);
}

/* navigasi */
.nav-section {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}

.nav-section-title {
  font-size: 0.7rem;
  font-weight: 600;
  color: var(--text-tertiary);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  padding: 0 var(--space-3);
  margin-bottom: var(--space-1);
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
  justify-content: space-between;
  gap: var(--space-3);
  padding: var(--space-3);
  text-decoration: none;
  color: var(--text-secondary);
  border-radius: var(--radius-xl);
  transition: background var(--transition-normal), transform var(--transition-normal), border-color var(--transition-normal), box-shadow var(--transition-normal);
  position: relative;
  border: 1px solid transparent;
}

[data-theme="dark"] .nav-item {
  color: var(--color-white);
}

.nav-item:hover {
  background: var(--hover-bg);
  color: var(--text-primary);
  transform: translateX(4px);
  border-color: var(--border-color);
}

[data-theme="dark"] .nav-item:hover {
  color: var(--color-white);
}

.nav-item--active {
  background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-secondary-dark) 100%);
  color: var(--color-white);
  box-shadow: 0 0 16px rgba(246, 152, 62, 0.4);
}

.nav-item--active:hover {
  background: linear-gradient(135deg, var(--color-secondary-dark) 0%, var(--color-secondary) 100%);
  color: var(--color-white);
  transform: translateX(4px);
}

.nav-item-content {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  flex: 1;
  min-width: 0;
}

.nav-icon {
  width: 22px;
  height: 22px;
  flex-shrink: 0;
  transition: transform var(--transition-normal), filter 0.3s ease;
  filter: saturate(100%) invert(33%) sepia(9%) hue-rotate(190deg) brightness(96%) contrast(89%);
}

[data-theme="dark"] .nav-icon {
  filter: invert(100%) brightness(100%);
}

.nav-icon--active {
  filter: invert(100%) brightness(100%);
  transform: scale(1.1);
}

.nav-text {
  font-size: 0.875rem;
  font-weight: 600;
}

.sidebar-collapsed .sidebar-profile,
.sidebar-collapsed .nav-section-title,
.sidebar-collapsed .nav-text,
.sidebar-collapsed .quick-actions,
.sidebar-collapsed .sidebar-footer {
  display: none;
}

.sidebar-collapsed .nav-item {
  justify-content: center;
  padding: var(--space-3);
}

.sidebar-collapsed .nav-item-content {
  justify-content: left;
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
  
  .sidebar-collapsed .sidebar-profile,
  .sidebar-collapsed .nav-section-title,
  .sidebar-collapsed .nav-text,
  .sidebar-collapsed .quick-actions,
  .sidebar-collapsed .sidebar-footer {
    display: flex;
  }
}
</style>