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
          </div>
        </div>
      </div>

      <!-- kanan -->
      <div class="header-right">
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
  emits: ['toggle-sidebar']
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
  color: var(--color-white);
  box-shadow: var(--shadow-lg);
  z-index: var(--z-fixed);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
  border: 0px;
  color: var(--color-white);
  width: 40px;
  height: 40px;
  border-radius: var(--radius-lg);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all var(--transition-normal);
  box-shadow: var(--shadow-md);
}

.sidebar-toggle:hover {
  background: var(--color-primary-hover);
  transform: translateY(-1px);
}

.logo {
  display: flex;
  align-items: center;
  gap: var(--space-3);
}

.logo-icon {
  width: 40px;
  height: 40px;
  background: var(--color-primary-light);
  border-radius: var(--radius-lg);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: var(--shadow-md);
}

.logo-img {
  width: 30px;
  height: 30px;
  object-fit: contain;
}

.logo-text {
  display: flex;
  flex-direction: column;
  line-height: 1.2;
}

.logo-title {
  font-size: 1.15rem;
  font-weight: 600;
  color: var(--color-white);;
}

/* kanan */
.header-right {
  display: flex;
  align-items: center;
  gap: var(--space-6);
}

.user-button {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  height: 40px;
  padding: var(--space-2) var(--space-3);
  background: var(--color-primary-light);
  border: 0px;
  border-radius: var(--radius-lg);
  color: var(--color-white);
  cursor: pointer;
  transition: all var(--transition-normal);
  box-shadow: var(--shadow-md);
}

.user-button:hover {
  background: var(--color-primary-hover);
  transform: translateY(-1px);
}

.user-avatar {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: var(--color-gray-300);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.675rem;
  overflow: hidden;
  flex-shrink: 0;
}

.user-avatar-img {
  width: 60%;
  height: 80%;
  object-fit: cover;
  border-radius: 50%;
}

.user-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  line-height: 1.2;
}

.user-name {
  font-family: 'Poppins', sans-serif;
  font-weight: 550;
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
    gap: var(--space-4);
  }
}

@media (max-width: 480px) {
  .header-container {
    padding: 0 var(--space-3);
  }

  .logo-icon {
    display: none;
  }

  .user-info {
    display: none;
  }
}
</style>