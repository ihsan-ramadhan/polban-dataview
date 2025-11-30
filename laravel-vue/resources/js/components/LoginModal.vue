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
          <!-- Error Message -->
          <div v-if="error" class="error-message">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            {{ error }}
          </div>

          <div class="form-group">
            <div class="input-wrapper">
              <span class="input-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
              </span>
              <input 
                type="email" 
                v-model="email" 
                placeholder="Email" 
                class="form-input"
                required
                :disabled="isLoading"
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
                placeholder="Password" 
                class="form-input"
                required
                :disabled="isLoading"
              />
            </div>
          </div>

          <button type="submit" class="login-btn" :disabled="isLoading">
            <span v-if="isLoading" class="loader"></span>
            <span v-else>Login</span>
          </button>

          <a href="#" class="forgot-password">Forgot Password?</a>
          <a href="#" @click="closeModal" class="back-to-home">Back to Home</a>
        </form>
      </div>
    </div>
  </transition>
</template>

<script>
import axios from 'axios';

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
      email: '',
      password: '',
      isLoading: false,
      error: null
    }
  },
  methods: {
    closeModal() {
      this.$emit('close');
      // Reset form when closing
      this.email = '';
      this.password = '';
      this.error = null;
      this.isLoading = false;
    },
    async handleLogin() {
      // Reset error state
      this.error = null;
      this.isLoading = true;

      try {
        // Kirim request ke polban-datahub API
        const response = await axios.post('http://localhost:8000/api/login', {
          email: this.email,
          password: this.password
        });

        // Simpan token dan user data ke localStorage
        localStorage.setItem('auth_token', response.data.token);
        localStorage.setItem('user_data', JSON.stringify(response.data.user));

        console.log('✅ Login berhasil:', response.data);

        // Trigger custom event to notify other components
        window.dispatchEvent(new CustomEvent('auth-changed'));

        // Reset form
        this.email = '';
        this.password = '';
        
        // Close modal
        this.$emit('close');
        
        // Optional: Reload halaman atau redirect
        // window.location.reload();

      } catch (err) {
        // Handle error
        console.error('❌ Login gagal:', err);
        
        if (err.response && err.response.data) {
          // Error dari server
          this.error = err.response.data.message || 'Login gagal. Silakan coba lagi.';
        } else if (err.request) {
          // Network error
          this.error = 'Tidak dapat terhubung ke server. Pastikan backend berjalan di port 8000.';
        } else {
          // Error lainnya
          this.error = 'Terjadi kesalahan. Silakan coba lagi.';
        }
      } finally {
        this.isLoading = false;
      }
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
  border-radius: var(--radius-xl);
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
  font-family: poppins, sans-serif;
  font-size: 0.9rem;
  outline: none;
  width: 100%;
}

.form-input::placeholder {
  color: #718096;
  font-family: poppins, sans-serif;
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

/* Error Message */
.error-message {
  width: 100%;
  padding: 0.75rem 1rem;
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.3);
  border-radius: var(--radius-lg);
  color: #fca5a5;
  font-size: 0.85rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
}

.error-message svg {
  flex-shrink: 0;
}

/* Loading Spinner */
.loader {
  width: 16px;
  height: 16px;
  border: 2px solid var(--color-primary);
  border-top: 2px solid transparent;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
  display: inline-block;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.login-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.login-btn:disabled:hover {
  transform: none;
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
