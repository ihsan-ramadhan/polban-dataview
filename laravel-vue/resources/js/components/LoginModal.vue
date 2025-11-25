<template>
  <transition name="modal-fade">
    <div v-if="isOpen" class="login-modal-overlay" @click.self="closeModal">
      <div class="login-modal-container">
        <!-- Logo Section -->
        <div class="modal-logo">
          <div class="logo-hexagon">
            <img src="/images/polban-logo.png" alt="Polban Logo" class="logo-img" />
          </div>
          <h2 class="logo-text">Polban DataVerse</h2>
        </div>

        <!-- Form Section -->
        <form class="login-form" @submit.prevent="handleLogin">
          <div class="form-group">
            <div class="input-wrapper">
              <span class="input-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
              </span>
              <input 
                type="text" 
                v-model="username" 
                placeholder="USERNAME" 
                class="form-input"
              />
            </div>
          </div>

          <div class="form-group">
            <div class="input-wrapper">
              <span class="input-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
              </span>
              <input 
                type="password" 
                v-model="password" 
                placeholder="PASSWORD" 
                class="form-input"
              />
            </div>
          </div>

          <button type="submit" class="login-btn">
            LOGIN
          </button>

          <a href="#" class="forgot-password">Forgot Password?</a>
          <a href="#" @click="closeModal" class="back-to-home">Back to Home</a>
        </form>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'LoginModal',
  props: {
    isOpen: {
      type: Boolean,
      default: false
    }
  },
  emits: ['close'],
  data() {
    return {
      username: '',
      password: ''
    }
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
    handleLogin() {
      // Implement login logic here
      console.log('Login attempt:', this.username);
    }
  }
}
</script>

<style scoped>
.login-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.4);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  z-index: 2000;
  display: flex;
  align-items: center;
  justify-content: center;
}

.login-modal-container {
  width: 100%;
  max-width: 400px;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  /* Ensure the modal content stands out */
  text-align: center;
}

.modal-logo {
  margin-bottom: 2.5rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.logo-hexagon {
  width: 80px;
  height: 80px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 0.5rem;
  /* Optional: Add a subtle glow or shadow if needed */
}

.logo-img {
  width: 60px;
  height: 60px;
  object-fit: contain;
}

.logo-text {
  color: white;
  font-size: 1.5rem;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.login-form {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  width: 100%;
}

.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  background: rgba(45, 55, 72, 0.8); /* Dark grey/blue background */
  border-radius: var(--radius-lg);
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: all 0.3s ease;
}

.input-wrapper:focus-within {
  border-color: var(--color-secondary);
  box-shadow: 0 0 0 2px rgba(246, 152, 62, 0.2);
}

.input-icon {
  padding: 0 1rem;
  color: #a0aec0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.form-input {
  flex: 1;
  background: transparent;
  border: none;
  color: white;
  padding: 1rem 1rem 1rem 0;
  font-size: 0.9rem;
  outline: none;
  width: 100%;
}

.form-input::placeholder {
  color: #718096;
  text-transform: uppercase;
  font-size: 0.8rem;
  letter-spacing: 1px;
}

.login-btn {
  width: 100%;
  padding: 1rem;
  background: white;
  color: var(--color-primary);
  border: none;
  border-radius: var(--radius-2xl); /* Rounded pill shape */
  font-weight: 700;
  font-size: 0.9rem;
  letter-spacing: 1px;
  text-transform: uppercase;
  cursor: pointer;
  margin-top: 1rem;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.login-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.forgot-password {
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  font-size: 0.85rem;
  margin-top: 0.5rem;
  transition: color 0.2s ease;
}

.forgot-password:hover {
  color: white;
}

.back-to-home {
  color: rgba(255, 255, 255, 0.8);
  font-size: 0.85rem;
  margin-top: 0.5rem;
  transition: color 0.2s ease;
}

.back-to-home:hover {
  color: white;
}

/* Transitions */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.3s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>
