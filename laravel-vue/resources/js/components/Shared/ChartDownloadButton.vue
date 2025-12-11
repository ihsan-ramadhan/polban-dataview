<template>
  <div class="download-wrapper" data-html2canvas-ignore="true">
    <button 
      class="download-btn" 
      @click.stop="downloadAsPng" 
      title="Download Grafik (PNG)"
      :disabled="isDownloading"
    >
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
        <polyline points="7 10 12 15 17 10"></polyline>
        <line x1="12" y1="15" x2="12" y2="3"></line>
      </svg>
    </button>
  </div>
</template>

<script>
import html2canvas from "html2canvas";

export default {
  name: "ChartDownloadButton",
  props: {
    // Parent WAJIB kirim elemen DOM chart (pakai $refs)
    targetElement: {
      type: [Object, HTMLElement], 
      default: null
    },
    // Nama file hasil download
    fileName: {
      type: String,
      default: "Chart"
    }
  },
  data() {
    return {
      isDownloading: false
    };
  },
  methods: {
    async downloadAsPng() {
      if (!this.targetElement) {
        console.warn("Target element not found!");
        return;
      }

      this.isDownloading = true;

      try {
        const canvas = await html2canvas(this.targetElement, { 
          scale: 2, // Resolusi tinggi (Retina)
          useCORS: true,
          backgroundColor: '#ffffff' // Pastikan background putih
        });

        const link = document.createElement('a');
        link.download = `${this.fileName}-${Date.now()}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
        
      } catch (err) {
        console.error("Gagal download PNG:", err);
        alert("Gagal mengunduh gambar.");
      } finally {
        this.isDownloading = false;
      }
    }
  }
};
</script>

<style scoped>
.download-wrapper {
  position: relative;
  display: inline-block;
}

.download-btn {
  background: transparent;
  border: 1px solid var(--border-color, #ddd);
  border-radius: 8px;
  padding: 8px;
  cursor: pointer;
  color: var(--text-secondary, #666);
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.download-btn:hover {
  background-color: var(--bg-primary, #f5f5f5);
  color: var(--color-primary, #007bff);
  border-color: var(--color-primary, #007bff);
}
</style>