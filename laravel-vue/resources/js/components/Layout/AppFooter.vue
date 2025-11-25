<template>
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Polban Dataverse</h4>
                <p>Portal visualisasi data terintegrasi untuk Politeknik Negeri Bandung. Menyajikan data umum, akademik, dan kemahasiswaan dalam bentuk yang informatif.</p>
            </div>

            <div class="footer-section">
                <h4>Menu Cepat</h4>
                <ul>
                    <li><router-link to="/">Home</router-link></li>
                    <li><router-link to="/akademik">Akademik</router-link></li>
                    <li>
                        <router-link to="/kemahasiswaan"
                            >Kemahasiswaan</router-link
                        >
                    </li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Hubungi Kami</h4>
                <p>Jl. Gegerkalong Hilir, Ciwaruga, Kec. Parongpong, Kabupaten Bandung Barat, Jawa Barat 
                    <br>Kode Pos 40559 | Kotak Pos Bandung 1234</p>
                <p>Telepon : 022 – 2013789 | 022 – 2015271 | 022 – 2013898 (Hunting)</p>
                <p>Email : <a href="mailto:polban@polban.ac.id">polban@polban.ac.id</a> | <a href="mailto:humas@polban.ac.id">humas@polban.ac.id</a></p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>
                &copy; {{ currentYear }} Polban Dataverse. All rights reserved.
            </p>
        </div>
    </footer>
</template>

<script>
export default {
    name: "AppFooter",
    computed: {
        currentYear() {
            return new Date().getFullYear();
        },
    },
};
</script>

<style scoped>
.footer {
    background: var(--color-primary);
    color: var(--color-white);
    padding: 3rem 2rem 1rem;
    margin-top: auto;
    width: 100%;
    transition: background-color 0.3s ease;
}

[data-theme="dark"] .footer {
    background: var(--bg-secondary);
    border-top: 1px solid var(--border-color);
}

.footer-content {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .footer-content {
    border-bottom: 1px solid var(--border-color);
}

.footer-section h4 {
    color: var(--color-white);
    font-size: 1.125rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

[data-theme="dark"] .footer-section h4 {
    color: var(--text-primary);
}

.footer-section p {
    color: var(--color-gray-300);
    font-size: 0.875rem;
    line-height: 1.6;
    margin: 0.5rem 0;
    transition: color 0.3s ease;
}

[data-theme="dark"] .footer-section p {
    color: var(--text-secondary);
}

.footer-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-section li {
    margin-bottom: 0.5rem;
}

.footer-section a {
    color: var(--color-gray-300);
    text-decoration: none;
    font-size: 0.875rem;
    transition: color var(--transition-normal);
}

[data-theme="dark"] .footer-section a {
    color: var(--text-secondary);
}

.footer-section a:hover {
    color: var(--color-secondary);
}

.footer-bottom {
    max-width: 1400px;
    margin: 0 auto;
    padding-top: 1.5rem;
    text-align: left;
}

.footer-bottom p {
    color: var(--color-gray-300);
    font-size: 0.875rem;
    margin: 0;
    transition: color 0.3s ease;
}

[data-theme="dark"] .footer-bottom p {
    color: var(--text-secondary);
}
</style>