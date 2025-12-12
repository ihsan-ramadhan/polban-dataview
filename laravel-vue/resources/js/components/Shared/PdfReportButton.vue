<template>
  <button 
    class="btn-export-pdf" 
    @click="generatePdf" 
    :disabled="disabled || isGenerating"
    data-html2canvas-ignore="true"
  >
    <span v-if="isGenerating">Memproses PDF...</span>
    <span v-else class="btn-content">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
        <polyline points="7 10 12 15 17 10"></polyline>
        <line x1="12" y1="15" x2="12" y2="3"></line>
      </svg>
      {{ buttonText }}
    </span>
  </button>
</template>

<script>
import html2canvas from "html2canvas";
import jsPDF from "jspdf";

export default {
  name: "PdfReportButton",
  props: {
    // Elemen halaman yang mau dijadiin PDF (WAJIB)
    targetElement: {
      type: [Object, HTMLElement],
      default: null
    },
    // Nama file output
    fileName: {
      type: String,
      default: "Laporan-Lengkap"
    },
    // Teks tombol
    buttonText: {
      type: String,
      default: "Download Laporan (1 Halaman)"
    },
    // Prop untuk disable tombol (misal saat data masih loading)
    disabled: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      isGenerating: false
    };
  },
  methods: {
    async generatePdf() {
      if (!this.targetElement) {
        alert("Elemen halaman tidak ditemukan!");
        return;
      }

      this.isGenerating = true;

      try {
        // 1. Tangkap seluruh elemen target
        const canvas = await html2canvas(this.targetElement, {
          scale: 2, // Resolusi tinggi
          useCORS: true,
          backgroundColor: '#ffffff' // Background putih bersih
        });

        // 2. Siapkan PDF Landscape A4
        const doc = new jsPDF('l', 'mm', 'a4');
        const pdfWidth = doc.internal.pageSize.getWidth();
        const pdfHeight = doc.internal.pageSize.getHeight();
        
        // 3. Hitung Rasio & Dimensi
        const imgWidth = canvas.width;
        const imgHeight = canvas.height;
        const margin = 10; // Margin 10mm
        const usableWidth = pdfWidth - (margin * 2);
        const usableHeight = pdfHeight - (margin * 2);

        const widthRatio = usableWidth / imgWidth;
        const heightRatio = usableHeight / imgHeight;
        // Pilih skala terkecil agar muat (contain)
        const scaleFactor = Math.min(widthRatio, heightRatio);

        const finalWidth = imgWidth * scaleFactor;
        const finalHeight = imgHeight * scaleFactor;

        // Posisi Tengah
        const xPos = (pdfWidth - finalWidth) / 2;
        const yPos = (pdfHeight - finalHeight) / 2;

        // 4. Tempel ke PDF & Download
        const imgData = canvas.toDataURL('image/png');
        doc.addImage(imgData, 'PNG', xPos, yPos, finalWidth, finalHeight);
        doc.save(`${this.fileName}-${Date.now()}.pdf`);

      } catch (err) {
        console.error("Gagal export PDF:", err);
        alert("Gagal membuat PDF.");
      } finally {
        this.isGenerating = false;
      }
    }
  }
};
</script>

<style scoped>
.btn-export-pdf {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--color-banner, #007bff);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s, transform 0.1s;
  font-size: 0.95rem;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  min-width: 200px; /* Biar ukurannya stabil pas loading */
}

.btn-content {
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-export-pdf:hover {
  background-color: var(--color-button-hover, #0056b3);
  transform: translateY(-1px);
}

.btn-export-pdf:disabled {
  background-color: #ccc;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}
</style>