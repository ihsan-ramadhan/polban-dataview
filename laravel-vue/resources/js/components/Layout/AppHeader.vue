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
                    <img
                        src="/images/menu.svg"
                        alt="Menu"
                        width="20"
                        height="20"
                    />
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
                        <span class="logo-subtitle"
                            >Satu Portal, Ribuan Cerita dalam Angka</span
                        >
                    </div>
                </div>
            </div>

            <!-- kanan -->
            <div class="header-right">
                <button
                    class="theme-toggle"
                    @click="toggleTheme"
                    :aria-label="
                        isDark ? 'Switch to light mode' : 'Switch to dark mode'
                    "
                >
                    <transition name="theme-icon" mode="out-in">
                        <img
                            v-if="isDark"
                            key="sun"
                            src="/images/sun.svg"
                            alt="Light Mode"
                            width="20"
                            height="20"
                        />
                        <img
                            v-else
                            key="moon"
                            src="/images/moon.svg"
                            alt="Dark Mode"
                            width="20"
                            height="20"
                        />
                    </transition>
                </button>

                <div class="user-menu" v-click-outside="closeDropdown">
                    <button
                        class="user-button"
                        @click="toggleDropdown"
                        :class="{ active: isDropdownOpen }"
                    >
                        <div class="user-avatar">
                            <img
                                src="/images/profile/user-avatar.png"
                                alt="User Avatar"
                                class="user-avatar-img"
                            />
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ userName }}</span>
                        </div>
                        <img
                            src="/images/arrow-v.png"
                            alt="Arrow Down"
                            class="user-arrow"
                            :class="{ rotated: isDropdownOpen }"
                        />
                    </button>

                    <transition name="dropdown">
                        <div v-if="isDropdownOpen" class="user-dropdown">
                            <div class="dropdown-content">
                                <!-- Show LOGIN if not authenticated -->
                                <button
                                    v-if="!isAuthenticated"
                                    class="login-btn"
                                    @click="handleLoginClick"
                                >
                                    <span class="login-icon">
                                        <img
                                            src="/images/auth.svg"
                                            alt="Login"
                                            width="20"
                                            height="20"
                                        />
                                    </span>
                                    Login
                                </button>

                                <!-- Show LOGOUT if authenticated -->
                                <button
                                    v-else
                                    class="logout-btn"
                                    @click="$emit('request-logout')"
                                >
                                    <span class="logout-icon">
                                        <img
                                            src="/images/auth.svg"
                                            alt="Logout"
                                            width="20"
                                            height="20"
                                        />
                                    </span>
                                    Logout
                                </button>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
export default {
    name: "AppHeader",

    emits: ["toggle-sidebar", "show-login", "auth-changed", "show-logout"],
    directives: {
        "click-outside": {
            mounted(el, binding) {
                el.clickOutsideEvent = function (event) {
                    if (!(el === event.target || el.contains(event.target))) {
                        binding.value(event, el);
                    }
                };
                document.body.addEventListener("click", el.clickOutsideEvent);
            },
            unmounted(el) {
                document.body.removeEventListener(
                    "click",
                    el.clickOutsideEvent
                );
            },
        },
    },
    data() {
        return {
            isDark: false,
            isDropdownOpen: false,
            isAuthenticated: false,
            userName: "Guest",
            userData: null,
        };
    },
    mounted() {
        const savedTheme = localStorage.getItem("theme");
        const prefersDark = window.matchMedia(
            "(prefers-color-scheme: dark)"
        ).matches;

        this.isDark = savedTheme === "dark" || (!savedTheme && prefersDark);
        this.applyTheme();

        // Check authentication status
        this.checkAuth();

        // Listen for storage changes (when user logs in from LoginModal)
        window.addEventListener("storage", this.checkAuth);
        // Listen for custom event from LoginModal
        window.addEventListener("auth-changed", this.checkAuth);
    },
    beforeUnmount() {
        window.removeEventListener("storage", this.checkAuth);
        window.removeEventListener("auth-changed", this.checkAuth);
    },
    methods: {
        checkAuth() {
            const token = localStorage.getItem("auth_token");
            const userDataStr = localStorage.getItem("user_data");

            if (token && userDataStr) {
                try {
                    this.userData = JSON.parse(userDataStr);
                    this.userName = this.userData.name || "User";
                    this.isAuthenticated = true;
                } catch (e) {
                    console.error("Error parsing user data:", e);
                    this.resetAuth();
                }
            } else {
                this.resetAuth();
            }
        },
        resetAuth() {
            this.isAuthenticated = false;
            this.userName = "Guest";
            this.userData = null;
        },
        handleLogout() {
            this.$emit("show-logout");
        },
        toggleTheme() {
            this.isDark = !this.isDark;
            this.applyTheme();
            localStorage.setItem("theme", this.isDark ? "dark" : "light");
        },
        applyTheme() {
            if (this.isDark) {
                document.documentElement.setAttribute("data-theme", "dark");
            } else {
                document.documentElement.removeAttribute("data-theme");
            }
        },
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },
        closeDropdown() {
            this.isDropdownOpen = false;
        },
        handleLoginClick() {
            this.$emit("show-login");
            this.closeDropdown();
        },
    },
};
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
    transition: width var(--transition-normal), background-color 0.3s ease,
        border-color 0.3s ease;
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
    transition: background-color 0.3s ease, transform 0.2s ease,
        box-shadow 0.3s ease;
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
    font-family: "Poppins", sans-serif;
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

.user-arrow.rotated {
    transform: rotate(180deg);
}

.user-menu {
    position: relative;
}

.user-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    width: 180px;
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
    padding: var(--space-4);
    z-index: 100;
    transform-origin: top right;
}

[data-theme="dark"] .user-dropdown {
    background: var(--bg-secondary);
    border-color: var(--border-color);
}

.dropdown-content {
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
}

.login-btn,
.logout-btn {
    width: 100%;
    padding: var(--space-3);
    color: white;
    border: none;
    border-radius: var(--radius-lg);
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-3);
    transition: all 0.2s ease;
}

.login-btn {
    background: linear-gradient(
        135deg,
        var(--color-secondary) 0%,
        var(--color-secondary-dark) 100%
    );
    box-shadow: 0 4px 12px rgba(246, 152, 62, 0.3);
}

.login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(246, 152, 62, 0.4);
}

.logout-btn {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.logout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

.login-btn:active,
.logout-btn:active {
    transform: translateY(0);
}

.login-icon,
.logout-icon {
    display: flex;
    align-items: center;
}

.dropdown-enter-active,
.dropdown-leave-active {
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
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
