<template>
    <div class="state-container empty">
        <div class="empty-icon">
            <div class="empty-icon-svg"></div>
        </div>
        <p>{{ message || 'Data tidak tersedia' }}</p>
    </div>
</template>

<script>
export default {
    name: "EmptyState",
    props: {
        message: {
            type: String,
            default: "Data tidak tersedia",
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
    color: var(--text-tertiary); /* Use tertiary text color for neutral look */
    font-size: 0.95rem;
    text-align: center;
    padding: 1rem;
}

.state-container.empty {
    background-color: var(--bg-primary); /* Slightly different bg if needed, or keeping it clean */
    border-radius: 8px;
    border: 1px dashed var(--border-color);
}

.empty-icon {
    margin-bottom: 0.5rem;
    color: var(--text-tertiary);
    opacity: 0.7;
}

.empty-icon-svg {
    width: 48px;
    height: 48px;
    background-color: currentColor;
    -webkit-mask-image: url("/images/empty-state.svg");
    mask-image: url("/images/empty-state.svg");
    -webkit-mask-size: contain;
    mask-size: contain;
    -webkit-mask-repeat: no-repeat;
    mask-repeat: no-repeat;
    -webkit-mask-position: center;
    mask-position: center;
}
</style>
