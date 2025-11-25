<template>
  <header class="app-header">
    <div class="header-container">
      <!-- kiri -->
      <div class="header-left">
        <button 
          class="sidebar-toggle"
          @click="$emit('toggle-sidebar')"
          aria-label="Toggle sidebar"
        >
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
        
        <div class="logo">
          <div class="logo-icon">
            <img 
              src="/images/polban-logo.png" 
              alt="Polban Logo"
              class="logo-img"
            />
          </div>
          <div class="logo-text">
            <span class="logo-title">Polban Dataverse</span>
            <span class="logo-subtitle">Satu Portal, Ribuan Cerita dalam Angka</span>
          </div>
        </div>
      </div>

      <!-- kanan -->
      <div class="header-right">
        <button 
          class="theme-toggle"
          @click="toggleTheme"
          :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
        >
          <transition name="theme-icon" mode="out-in">
            <svg v-if="isDark" key="sun" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <circle cx="12" cy="12" r="5" stroke-width="2"/>
              <line x1="12" y1="1" x2="12" y2="3" stroke-width="2" stroke-linecap="round"/>
              <line x1="12" y1="21" x2="12" y2="23" stroke-width="2" stroke-linecap="round"/>
              <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" stroke-width="2" stroke-linecap="round"/>
              <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" stroke-width="2" stroke-linecap="round"/>
              <line x1="1" y1="12" x2="3" y2="12" stroke-width="2" stroke-linecap="round"/>
              <line x1="21" y1="12" x2="23" y2="12" stroke-width="2" stroke-linecap="round"/>
              <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" stroke-width="2" stroke-linecap="round"/>
              <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <svg v-else key="moon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </transition>
        </button>

        <div class="user-menu">
          <button class="user-button">
            <div class="user-avatar">
              <img 
                src="/images/profile/user-avatar.png" 
                alt="User Avatar"
                class="user-avatar-img"
              />
            </div>
            <div class="user-info">
              <span class="user-name">Guest</span>
            </div>
            <img 
              src="/images/arrow-v.png"   
              alt="Arrow Down"
              class="user-arrow"
            />
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'AppHeader',
  emits: ['toggle-sidebar'],
  data() {
    return {
      isDark: false
    }
  },
  mounted() {
    const savedTheme = localStorage.getItem('theme')
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
    
    this.isDark = savedTheme === 'dark' || (!savedTheme && prefersDark)
    this.applyTheme()
  },
  methods: {
    toggleTheme() {
      this.isDark = !this.isDark
      this.applyTheme()
      localStorage.setItem('theme', this.isDark ? 'dark' : 'light')
    },
    applyTheme() {
      if (this.isDark) {
        document.documentElement.setAttribute('data-theme', 'dark')
      } else {
        document.documentElement.removeAttribute('data-theme')
      }
    }
  }
}
</script>

<style scoped>
.app-header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 70px;
  background: var(--color-primary);
  border-bottom: 1px solid var(--border-color);
  color: var(--color-white);
  box-shadow: var(--shadow-lg);
  z-index: var(--z-fixed);
  backdrop-filter: blur(10px);
  transition: width var(--transition-normal), background-color 0.3s ease, border-color 0.3s ease;
}

[data-theme="dark"] .app-header {
  background: var(--bg-secondary);
}

.header-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 100%;
  padding: 0 var(--space-6);
  max-width: 100%;
}

/* kiri */
.header-left {
  display: flex;
  align-items: center;
  gap: var(--space-4);
}

.sidebar-toggle {
  background: var(--color-primary-light);
  color: var(--color-white);
  width: 45px;
  height: 45px;
  border-radius: var(--radius-xl);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all var(--transition-normal);
  box-shadow: var(--shadow-md);
}

[data-theme="dark"] .sidebar-toggle {
  background: var(--hover-bg);
}

.sidebar-toggle:hover {
  background: var(--color-primary-hover);
  transform: translateY(-1.5px);
}

[data-theme="dark"] .sidebar-toggle:hover {
  background: var(--color-gray-600);
}

.sidebar-toggle:active {
  transform: translateY(0);
}

.logo {
  display: flex;
  align-items: center;
  gap: var(--space-3);
}

.logo-icon {
  background: var(--color-primary-light);
  color: var(--color-white);
  width: 45px;
  height: 45px;
  border-radius: var(--radius-xl);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all var(--transition-normal);
  box-shadow: var(--shadow-md);
}

[data-theme="dark"] .logo-icon {
  background: var(--hover-bg);
}

.logo-icon:hover {
  transform: scale(1.05);
}

.logo-img {
  width: 32px;
  height: 32px;
}

.logo-text {
  display: flex;
  flex-direction: column;
  line-height: 1.2;
}

.logo-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: var(--color-white);
}

.logo-subtitle {
  font-size: 0.7rem;
  font-weight: 400;
  color: rgba(255, 255, 255, 0.8);
  letter-spacing: 0.02em;
}

/* kanan */
.header-right {
  display: flex;
  align-items: center;
  gap: var(--space-4);
}

.theme-toggle {
  background: var(--color-primary-light);
  color: var(--color-white);
  width: 45px;
  height: 45px;
  border-radius: var(--radius-xl);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
  box-shadow: var(--shadow-md);
}

[data-theme="dark"] .theme-toggle {
  background: var(--hover-bg);
}

.theme-toggle:hover {
  background: var(--color-primary-hover);
  transform: translateY(-1.5px);
}

[data-theme="dark"] .theme-toggle:hover {
  background: var(--color-gray-600);
}

.theme-toggle:active {
  transform: translateY(0);
}

.theme-toggle svg {
  transition: opacity 0.2s ease;
}

.theme-icon-enter-active,
.theme-icon-leave-active {
  transition: opacity 0.15s ease;
}

.theme-icon-enter-from,
.theme-icon-leave-to {
  opacity: 0;
}

.user-button {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  height: 45px;
  padding: var(--space-2) var(--space-3);
  background: var(--color-primary-light);
  border-radius: var(--radius-xl);
  color: var(--color-white);
  cursor: pointer;
  transition: all var(--transition-normal);
  box-shadow: var(--shadow-md);
}

[data-theme="dark"] .user-button {
  background: var(--hover-bg);
}

.user-button:hover {
  background: var(--color-primary-hover);
  transform: translateY(-1.5px);
}

[data-theme="dark"] .user-button:hover {
  background: var(--color-gray-600);
}

.user-button:active {
  transform: translateY(0);
}

.user-avatar {
  position: relative;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: var(--color-gray-300);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.675rem;
  overflow: hidden;
  flex-shrink: 0;
  box-shadow: var(--shadow-lg);
}

.user-avatar-img {
  width: 60%;
  height: 80%;
  object-fit: cover;
}

.user-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  line-height: 1.2;
}

.user-name {
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
}

.user-arrow {
  width: 14px;
  height: 14px;
  object-fit: contain;
  opacity: 0.7;
  transition: transform var(--transition-normal);
}

.user-button:hover .user-arrow {
  opacity: 1;
}

@media (max-width: 1024px) {
  .header-container {
    padding: 0 var(--space-4);
  }
}

@media (max-width: 768px) {
  .header-right {
    gap: var(--space-3);
  }
}

@media (max-width: 480px) {
  .header-container {
    padding: 0 var(--space-3);
  }
  
  .logo-title {
    font-size: 1rem;
  }

  .logo-subtitle {
    display: none;
  }

  .user-info {
    display: none;
  }
}
</style>