<template>
    <div id="app">
        <AppHeader
            @toggle-sidebar="handleToggleSidebar"
            @show-login="isLoginModalOpen = true"
            @request-logout="isLogoutConfirmOpen = true"
        />
        <AppSidebar
            :isOpen="isSidebarOpen"
            @show-login="isLoginModalOpen = true"
        />
        <main class="main-content" :class="{ 'main-expanded': !isSidebarOpen }">
            <div class="content-area">
                <router-view v-slot="{ Component }">
                    <transition name="slide-fade" mode="out-in">
                        <component :is="Component" />
                    </transition>
                </router-view>
            </div>
            <AppFooter />
        </main>
        <LoginModal
            :isOpen="isLoginModalOpen"
            @close="isLoginModalOpen = false"
        />
        <LogoutConfirmModal
            :isOpen="isLogoutConfirmOpen"
            @close="isLogoutConfirmOpen = false"
            @confirm="handleLogout"
        />
        <ScrollToTop />
    </div>
</template>

<script>
import AppHeader from "./components/Layout/AppHeader.vue";
import AppSidebar from "./components/Layout/AppSidebar.vue";
import AppFooter from "./components/Layout/AppFooter.vue";
import LoginModal from "./components/LoginModal.vue";
import LogoutConfirmModal from "./components/LogoutValidate.vue";
import ScrollToTop from "./components/Button/BackToTop.vue";

export default {
    name: "App",
    components: {
        AppHeader,
        AppSidebar,
        AppFooter,
        LoginModal,
        LogoutConfirmModal,
        ScrollToTop,
    },
    data() {
        return {
            isSidebarOpen: true,
            isLoginModalOpen: false,
            isLogoutConfirmOpen: false,
        };
    },
    methods: {
        handleToggleSidebar() {
            this.isSidebarOpen = !this.isSidebarOpen;
        },
        async handleLogout() {
            try {
                await fetch("http://localhost:3000/api/auth/logout", {
                    method: "POST",
                    credentials: "include", // Sertakan cookie sesi
                });
            } catch (error) {
                console.error("Error during logout:", error);
            } finally {
                localStorage.removeItem("auth_token");
                localStorage.removeItem("user_data");
                window.dispatchEvent(new CustomEvent("auth-changed"));
                this.isLogoutConfirmOpen = false;
            }
        },
    },
};
</script>

<style>
#app {
    min-height: 100vh;
    background: var(--bg-primary);
    transition: background-color 0.3s ease;
}

.main-content {
    min-height: 100vh;
    padding-top: 70px;
    margin-left: 280px;
    transition: margin-left var(--transition-normal), background-color 0.3s ease;
    display: flex;
    flex-direction: column;
    background: var(--bg-primary);
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

.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>
