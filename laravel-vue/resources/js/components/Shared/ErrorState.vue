<template>
    <div class="state-container error">
        <p>{{ message || 'Failed to fetch data' }}</p>
        <button v-if="showRetry" @click="$emit('retry')" class="retry-btn">
            {{ retryText || 'Coba Lagi' }}
        </button>
    </div>
</template>

<script>
export default {
    name: "ErrorState",
    props: {
        message: {
            type: String,
            default: "Gagal memuat data. Silakan coba lagi.",
        },
        showRetry: {
            type: Boolean,
            default: true,
        },
        retryText: {
            type: String,
            default: "Coba Lagi",
        },
    },
};
</script>

<style scoped>
.state-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
    color: var(--text-primary);
    font-size: 0.9rem;
    text-align: center;
}

.state-container.error {
    color: #d32f2f;
    background-color: #ffebee;
    padding: 1rem;
    border-radius: 8px;
    border: 1px dashed #d32f2f;
}

.retry-btn {
    margin-top: 8px;
    padding: 4px 12px;
    background: white;
    border: 1px solid #d32f2f;
    color: #d32f2f;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.2s;
}

.retry-btn:hover {
    background: #ffebee;
}
</style>
