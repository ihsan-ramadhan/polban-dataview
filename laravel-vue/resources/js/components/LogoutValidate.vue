<template>
    <transition name="fade">
        <div v-if="isOpen" class="modal-backdrop">
            <div class="modal-card">
                <h3>Konfirmasi Logout</h3>
                <p>Anda yakin ingin logout?</p>
                <div class="actions">
                    <button class="btn ghost" @click="$emit('close')">
                        Batal
                    </button>
                    <button class="btn danger" @click="$emit('confirm')">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: "LogoutConfirmModal",
    props: { isOpen: { type: Boolean, default: false } },
    emits: ["close", "confirm"],
};
</script>

<style scoped>
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.35);
    display: grid;
    place-items: center;
    z-index: 999;
}
.modal-card {
    background: var(--bg-primary);
    color: var(--text-primary);
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    width: min(360px, 90vw);
}
.actions {
    margin-top: 1.25rem;
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}
.btn {
    padding: 0.6rem 1rem;
    border-radius: 8px;
    border: 1px solid transparent;
    cursor: pointer;
}
.btn.ghost {
    background: transparent;
    color: var(--text-primary);
    border-color: var(--border-color, #d0d7de);
}
.btn.danger {
    background: #e74c3c;
    color: #fff;
}
</style>
