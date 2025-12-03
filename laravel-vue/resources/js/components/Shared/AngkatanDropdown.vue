<template>
  <div class="filter-dropdown" 
       @mouseenter="showDropdown = true" 
       @mouseleave="showDropdown = false">
    
    <div class="filter-select-styled">
      {{ getLabel() }}
      <span class="dropdown-arrow">▼</span>
    </div>
    
    <div v-show="showDropdown" class="dropdown-menu">
      <div 
        class="dropdown-item" 
        @click="selectItem('')" 
        :class="{ active: modelValue === '' }">
        Semua Angkatan
      </div>
      <div 
        v-for="item in options" 
        :key="item" 
        class="dropdown-item" 
        @click="selectItem(item)" 
        :class="{ active: modelValue === item }">
        {{ item }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AngkatanDropdown',
  props: {
    // Digunakan sebagai v-model: nilai yang dipilih saat ini
    modelValue: {
      type: [String, Number],
      default: ''
    },
    // Daftar tahun angkatan atau opsi yang tersedia
    options: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      showDropdown: false,
    };
  },
  methods: {
    getLabel() {
      if (this.modelValue) {
        return `${this.modelValue}`;
      }
      return 'Semua Angkatan';
    },
    selectItem(value) {
      // Mengeluarkan event 'update:modelValue' untuk v-model
      this.$emit('update:modelValue', value);
      this.showDropdown = false;
      
      // Tambahkan event 'change' kustom jika ada logika lain yang perlu dijalankan di parent
      this.$emit('change', value);
    }
  }
};
</script>

<style scoped>
/* Anda bisa memindahkan semua style dropdown dari Akademik.vue ke sini */
/* --- STYLE DROPDOWN (Reusable) --- */

.filter-dropdown { position: relative; display: inline-block; z-index: 50; }
.filter-select-styled { 
    padding: 0.5rem 1rem; 
    border: 1px solid var(--border-color); 
    border-radius: 8px; 
    font-size: 0.9rem; 
    background: var(--bg-primary); 
    color: var(--text-primary); 
    cursor: pointer; 
    min-width: 160px; 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    user-select: none; 
    transition: all 0.2s;
}
.filter-select-styled:hover { border-color: var(--color-primary); background: var(--bg-hover);}
.dropdown-arrow { font-size: 0.7rem; margin-left: 8px; color: var(--text-tertiary); }

.dropdown-menu {
  position: absolute;
  top: calc(100% + 8px); 
  left: 0;
  background: var(--bg-secondary); 
  border: 1px solid var(--border-color);
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.12);
  min-width: 200px;
  z-index: 999;
  padding: 8px 0;
  animation: fadeIn 0.2s ease-out;
}

/* JEMBATAN GHAIB */
.dropdown-menu::before {
  content: "";
  position: absolute;
  top: -20px; 
  left: 0;
  width: 100%;
  height: 20px;
  background: transparent;
  z-index: -1;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}

.dropdown-item {
  padding: 10px 16px; cursor: pointer; font-size: 0.9rem;
  color: var(--text-primary); transition: background 0.2s;
  display: flex; justify-content: space-between; align-items: center; font-weight: 500;
}
.dropdown-item:hover { background-color: var(--bg-hover); }
.dropdown-item.active { background-color: var(--bg-hover); color: var(--color-primary); font-weight: 600; }
</style>