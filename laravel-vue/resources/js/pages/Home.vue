<template>
    <div class="home-page" ref="pageContainer">
        <div class="welcome-banner">
            <div class="banner-content-wrapper">
                <div class="banner-text-group">
                    <h1 class="banner-title">Selamat Datang di Polban Dataverse</h1>
                    <p class="banner-text">
                        Polban Dataverse adalah portal visualisasi data terintegrasi
                        yang menyajikan cerita kampus dalam angka.
                        Jelajahi statistik umum, akademik dan kemahasiswaan Politeknik Negeri Bandung secara
                        interaktif, transparan, dan informatif untuk mendukung
                        pengambilan keputusan berbasis data.
                    </p>
                </div>
                
                <PdfReportButton 
                    :target-element="$refs.pageContainer"
                    file-name="Laporan-Ringkasan-Polban"
                    button-text="Download Laporan (PDF)"
                    :disabled="isLoadingAny"
                    class="custom-banner-btn"
                />
            </div>
        </div>

        <div class="stats-grid">
            <div
                v-for="(card, index) in statCards"
                :key="index"
                class="stat-card"
                @click="scrollToChart(card.chartId)"
            >
                <div class="icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" :viewBox="card.viewBox || '0 0 24 24'" fill="white" width="40" height="40">
                        <path :d="card.iconPath" />
                    </svg>
                </div>
                <div class="card-content">
                    <h3 class="card-title">{{ card.title }}</h3>
                    <p class="card-desc">{{ card.desc }}</p>
                </div>
            </div>
        </div>

        <div id="target-chart-section" class="charts-grid">
            
            <div id="chart-jenis-kelamin" class="chart-card" ref="chartCardGender">
                <div class="card-header">
                    <h2 class="chart-title">Jenis Kelamin</h2>
                    <p class="chart-subtitle">Perbandingan Laki-laki dan Perempuan</p>
                </div>
                <div class="chart-container">
                    <div v-if="isLoadingGender" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <ErrorState v-else-if="errorGender" @retry="fetchGenderData" />
                    <EmptyState v-else-if="!genderChartData || genderChartData.datasets[0].data.length === 0" />
                    <BasePieChart v-else-if="genderChartData" :chart-data="genderChartData" />
                </div>
                <div class="download-action-area" v-if="!isLoadingGender && genderChartData">
                    <ChartDownloadButton :target-element="$refs.chartCardGender" file-name="PieChart-Gender" />
                </div>
            </div>

            <div id="chart-rasio" class="chart-card" ref="chartCardRasio">
                <div class="card-header">
                    <h2 class="chart-title">Rasio</h2>
                    <p class="chart-subtitle">Perbandingan Dosen dan Mahasiswa</p>
                </div>
                <div class="chart-container">
                    <div v-if="isLoadingDosen" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <ErrorState v-else-if="errorDosen" @retry="fetchDosenData" />
                    <EmptyState v-else-if="!dosenChartData || dosenChartData.datasets[0].data.length === 0" />
                    <BasePieChart v-else-if="dosenChartData" :chart-data="dosenChartData" />
                </div>
                <div class="download-action-area" v-if="!isLoadingDosen && dosenChartData">
                    <ChartDownloadButton :target-element="$refs.chartCardRasio" file-name="PieChart-Rasio" />
                </div>
            </div>

            <div id="chart-agama" class="chart-card" ref="chartCardAgama">
                <div class="card-header">
                    <h2 class="chart-title">Agama</h2>
                    <p class="chart-subtitle">Statistik Agama Mahasiswa</p>
                </div>
                <div class="chart-container">
                    <div v-if="isLoadingAgama" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <ErrorState v-else-if="errorAgama" @retry="fetchAgamaData" />
                    <EmptyState v-else-if="!agamaChartData || agamaChartData.datasets[0].data.length === 0" />
                    <BasePieChart v-else-if="agamaChartData" :chart-data="agamaChartData" />
                </div>
                <div class="download-action-area" v-if="!isLoadingAgama && agamaChartData">
                    <ChartDownloadButton :target-element="$refs.chartCardAgama" file-name="PieChart-Agama" />
                </div>
            </div>

            <div id="chart-jalur-masuk" class="chart-card" ref="chartCardTipe">
                <div class="card-header">
                    <h2 class="chart-title">Jalur Masuk</h2>
                    <p class="chart-subtitle">Tipe Jalur Masuk Mahasiswa</p>
                </div>
                <div class="chart-container">
                    <div v-if="isLoadingTipe" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <ErrorState v-else-if="errorTipe" @retry="fetchTipeData" />
                    <EmptyState v-else-if="!tipeChartData || tipeChartData.datasets[0].data.length === 0" />
                    <BaseBarChart v-else-if="tipeChartData" :chart-data="tipeChartData" />
                </div>
                <div class="download-action-area" v-if="!isLoadingTipe && tipeChartData">
                    <ChartDownloadButton :target-element="$refs.chartCardTipe" file-name="BarChart-JalurMasuk" />
                </div>
            </div>

            <div id="chart-asal-slta" class="chart-card span-full" ref="chartCardSLTA">
                <div class="card-header">
                    <h2 class="chart-title">Asal SLTA</h2>
                    <p class="chart-subtitle">Asal Jenis Sekolah Pendaftar</p>
                </div>
                <div class="chart-container">
                    <div v-if="isLoadingSLTA" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat...
                    </div>
                    <ErrorState v-else-if="errorSLTA" @retry="fetchSLTAData" />
                    <EmptyState v-else-if="!sltaChartData || sltaChartData.datasets[0].data.length === 0" />
                    <BaseBarChart v-else-if="sltaChartData" :chart-data="sltaChartData" />
                </div>
                <div class="download-action-area" v-if="!isLoadingSLTA && sltaChartData">
                    <ChartDownloadButton :target-element="$refs.chartCardSLTA" file-name="BarChart-SLTA" />
                </div>
            </div>

            <div id="chart-domisili" class="chart-card span-full" ref="chartCardDomisili">
                <div class="card-header">
                    <h2 class="chart-title">Persebaran Mahasiswa</h2>
                    <p class="chart-subtitle">Lokasi Asal Mahasiswa per Provinsi</p>
                </div>
                <div class="chart-container chart-tall">
                    <div v-if="isLoadingDomisili" class="state-container loading">
                        <span class="loader-spinner"></span> Memuat Peta...
                    </div>
                    <ErrorState v-else-if="errorDomisili" @retry="fetchDomisiliData" />
                    <EmptyState v-else-if="!domisiliData || domisiliData.length === 0" />
                    <BaseMap v-else-if="domisiliData" :map-data="domisiliData" />
                </div>
                <div class="download-action-area" v-if="!isLoadingDomisili && domisiliData">
                    <ChartDownloadButton :target-element="$refs.chartCardDomisili" file-name="Map-Domisili" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// --- IMPORTS ---
import BasePieChart from "../components/Charts/BasePieChart.vue";
import BaseBarChart from "../components/Charts/BaseBarChart.vue";
import BaseMap from "../components/Charts/BaseMap.vue";
import ChartDownloadButton from "../components/Shared/ChartDownloadButton.vue";
import ErrorState from "../components/Shared/ErrorState.vue";
import EmptyState from "../components/Shared/EmptyState.vue";
import PdfReportButton from "../components/Shared/PdfReportButton.vue";

const API_BASE_URL = "http://localhost:3000";

export default {
    name: "Home",
    components: {
        BasePieChart,
        BaseBarChart,
        BaseMap,
        ChartDownloadButton,
        ErrorState,
        EmptyState,
        PdfReportButton,
    },
    data() {
        return {
            // Data Charts
            genderChartData: null,
            isLoadingGender: true,
            errorGender: null,

            sltaChartData: null,
            isLoadingSLTA: true,
            errorSLTA: null,

            agamaChartData: null,
            isLoadingAgama: true,
            errorAgama: null,

            dosenChartData: null,
            isLoadingDosen: true,
            errorDosen: null,

            tipeChartData: null,
            isLoadingTipe: true,
            errorTipe: null,

            domisiliData: null,
            isLoadingDomisili: true,
            errorDomisili: null,

            // Navigasi Cards (Statik)
            statCards: [
                {
                    title: "Jenis Kelamin",
                    desc: "Visualisasi perbandingan jumlah mahasiswa laki-laki dan perempuan di seluruh angkatan.",
                    chartId: "chart-jenis-kelamin",
                    viewBox: "0 0 80 85",
                    iconPath: "M77.3331 8.07798e-05H63.2859C62.9468 -0.00323341 62.6146 0.0955015 62.3323 0.28346C62.0501 0.471418 61.8308 0.739913 61.7032 1.05407C61.5667 1.36928 61.5273 1.71803 61.5901 2.05573C61.6529 2.39342 61.815 2.70472 62.0557 2.9498L65.322 6.21608C65.6817 6.56141 65.6817 7.13337 65.322 7.47871L58.052 14.889C57.8944 15.0061 57.7033 15.0693 57.507 15.0693C57.3107 15.0693 57.1196 15.0061 56.962 14.889C52.3987 11.9893 46.9737 10.7552 41.605 11.3956C36.2364 12.0361 31.2539 14.5117 27.5008 18.4035C13.1119 16.0725 0.0503611 27.1483 0 41.7207C0.00709728 46.7658 1.64081 51.6741 4.65863 55.7171C7.67645 59.7601 11.9175 62.7222 16.7523 64.1637C17.1264 64.2717 17.3818 64.617 17.3818 65.0055V67.7789C17.3818 68.0117 17.2893 68.235 17.1247 68.3996C16.9601 68.5642 16.7368 68.6567 16.5041 68.6567H14.9573C14.3371 68.5615 13.7037 68.6015 13.1005 68.774C12.4972 68.9465 11.9384 69.2474 11.4623 69.656C10.9862 70.0646 10.604 70.5713 10.342 71.1414C10.08 71.7115 9.94441 72.3315 9.94441 72.9589C9.94441 73.5864 10.08 74.2064 10.342 74.7765C10.604 75.3466 10.9862 75.8533 11.4623 76.2619C11.9384 76.6705 12.4972 76.9714 13.1005 77.1439C13.7037 77.3164 14.3371 77.3564 14.9573 77.2612H16.6839C17.1659 77.2612 17.558 77.6569 17.558 78.1389V79.862C17.5092 80.4614 17.5851 81.0645 17.7809 81.6331C17.9768 82.2017 18.2883 82.7236 18.6959 83.1657C19.1035 83.6079 19.5984 83.9609 20.1492 84.2023C20.7 84.4437 21.2949 84.5683 21.8963 84.5683C22.4977 84.5683 23.0926 84.4437 23.6434 84.2023C24.1942 83.9609 24.689 83.6079 25.0967 83.1657C25.5043 82.7236 25.8158 82.2017 26.0117 81.6331C26.2075 81.0645 26.2834 80.4614 26.2345 79.862V78.1389C26.2304 77.9337 26.2983 77.7335 26.4264 77.5731C26.5546 77.4128 26.7349 77.3024 26.936 77.2612H28.6914C29.7769 77.1674 30.7876 76.6698 31.524 75.867C32.2605 75.0641 32.669 74.0142 32.669 72.9248C32.669 71.8353 32.2605 70.7854 31.524 69.9826C30.7876 69.1797 29.7769 68.6822 28.6914 68.5883H26.936C26.7061 68.5794 26.4879 68.4841 26.3252 68.3214C26.1625 68.1587 26.0672 67.9405 26.0583 67.7106V65.7069C26.0654 65.4974 26.1473 65.2973 26.2892 65.1429C26.431 64.9885 26.6235 64.89 26.8317 64.8652C31.8606 64.1083 36.5136 61.7566 40.1054 58.1564C54.4727 60.3075 67.4371 49.2928 67.6386 34.7672C67.6925 30.3058 66.4747 25.9212 64.1277 22.1266C64.0077 21.9772 63.9423 21.7912 63.9423 21.5996C63.9423 21.408 64.0077 21.222 64.1277 21.0726L71.538 13.6623C71.6184 13.5744 71.7162 13.5041 71.8251 13.4558C71.9341 13.4075 72.0519 13.3823 72.1711 13.3817C72.4085 13.3925 72.6351 13.4897 72.8006 13.6623L76.0309 16.8926C76.2763 17.1338 76.5871 17.2975 76.9247 17.3634C77.2624 17.4294 77.612 17.3947 77.9301 17.2636C78.2481 17.1325 78.5207 16.9109 78.7139 16.6262C78.907 16.3415 79.0123 16.0064 79.0166 15.6624V1.61883C78.9888 1.18784 78.8005 0.782917 78.4889 0.483923C78.1772 0.184929 77.7648 0.0135807 77.3331 0.00367801M45.7243 49.4871C46.7915 46.4546 47.2268 43.2358 47.0034 40.0288C46.7801 36.8218 45.9029 33.6944 44.4257 30.8391C44.1643 30.3251 43.8035 29.868 43.3642 29.4944C42.9249 29.1208 42.4159 28.838 41.8666 28.6625C41.3173 28.487 40.7387 28.4222 40.1641 28.4718C39.5896 28.5215 39.0306 28.6846 38.5196 28.9518C38.0086 29.219 37.5556 29.5849 37.187 30.0283C36.8183 30.4718 36.5413 30.9839 36.3719 31.5352C36.2026 32.0864 36.1443 32.6658 36.2004 33.2397C36.2565 33.8136 36.426 34.3707 36.6989 34.8787C37.85 37.0371 38.4255 39.4544 38.386 41.9005C38.3716 53.2821 26.0439 60.3831 16.1911 54.6815C6.33831 48.9799 6.35629 34.7528 16.2199 29.0692C18.1039 27.9846 20.2045 27.3299 22.3711 27.1519C21.4396 29.5877 20.9288 32.164 20.8603 34.7708C20.8564 39.3966 22.2247 43.9196 24.7921 47.7676C25.46 48.6555 26.4422 49.2543 27.5374 49.4414C28.6326 49.6285 29.7579 49.3897 30.6827 48.774C31.6075 48.1582 32.2618 47.212 32.5116 46.1294C32.7614 45.0468 32.5877 43.9096 32.0261 42.9509C25.7165 33.4794 32.0261 20.7273 43.3825 19.997C54.7389 19.2668 62.6276 31.1053 57.5843 41.307C56.4565 43.5871 54.7594 45.5377 52.6572 46.97C50.5549 48.4024 48.1187 49.2681 45.584 49.4835L45.7243 49.4871Z",
                },
                {
                    title: "Asal SLTA",
                    desc: "Pemetaan latar belakang pendidikan pendaftar berdasarkan asal sekolah (SMA/SMK/MA).",
                    chartId: "chart-asal-slta",
                    viewBox: "0 0 80 85",
                    iconPath: "M0 30.2155V66.9058C0 68.0982 0.965817 69.064 2.15825 69.064H12.9495V25.899H4.3165C1.93298 25.899 0 27.832 0 30.2155ZM48.5606 23.7408H45.3233V18.3451C45.3233 17.7489 44.8403 17.266 44.2441 17.266H42.0859C41.4897 17.266 41.0068 17.7489 41.0068 18.3451V26.9781C41.0068 27.5744 41.4897 28.0573 42.0859 28.0573H48.5606C49.1568 28.0573 49.6398 27.5744 49.6398 26.9781V24.8199C49.6398 24.2237 49.1568 23.7408 48.5606 23.7408ZM67.1418 15.1132L45.5593 0.724374C44.8501 0.25203 44.0171 0 43.165 0C42.3129 0 41.4799 0.25203 40.7707 0.724374L19.1882 15.1132C18.597 15.5073 18.1123 16.0412 17.777 16.6676C17.4416 17.294 17.2661 17.9935 17.266 18.7039V69.064H34.532V49.6398C34.532 48.4473 35.4978 47.4815 36.6903 47.4815H49.6398C50.8322 47.4815 51.798 48.4473 51.798 49.6398V69.064H69.064V18.7053C69.064 17.262 68.3423 15.9131 67.1418 15.1132ZM43.165 34.532C37.2055 34.532 32.3738 29.7002 32.3738 23.7408C32.3738 17.7813 37.2055 12.9495 43.165 12.9495C49.1245 12.9495 53.9563 17.7813 53.9563 23.7408C53.9563 29.7002 49.1245 34.532 43.165 34.532ZM82.0135 25.899H73.3805V69.064H84.1718C85.3642 69.064 86.33 68.0982 86.33 66.9058V30.2155C86.33 27.832 84.397 25.899 82.0135 25.899Z",
                },
                {
                    title: "Jalur Masuk",
                    desc: "Statistik tren penerimaan mahasiswa baru melalui berbagai jalur seleksi masuk kampus.",
                    chartId: "chart-jalur-masuk",
                    viewBox: "0 0 80 85",
                    iconPath: "M63.8482 63.9579H56.9526V14.0844C56.9526 8.65277 52.4527 4.39023 47.0606 4.39023H42.8844C42.446 3.00918 41.5446 1.82166 40.3322 1.02808C39.1199 0.234494 37.6709 -0.116561 36.2298 0.0341569L35.5104 0.149264L11.7301 5.32906C11.1279 5.4597 10.5886 5.79253 10.2018 6.27222C9.81507 6.75191 9.60417 7.34954 9.60421 7.96573V13.8865L9.59342 14.0952V63.9579H2.69781C1.98231 63.9579 1.29611 64.2422 0.790171 64.7481C0.284233 65.254 0 65.9402 0 66.6557C0 67.3712 0.284233 68.0574 0.790171 68.5634C1.29611 69.0693 1.98231 69.3536 2.69781 69.3536H15.2876L15.5646 69.3392C15.6365 69.332 15.7109 69.32 15.7876 69.3032L35.5104 73.5945L36.2298 73.7096C37.6705 73.8605 39.1191 73.51 40.3314 72.7171C41.5436 71.9242 42.4453 70.7375 42.8844 69.3572H63.8482L64.1252 69.3392C64.789 69.2703 65.4037 68.9579 65.8506 68.4623C66.2974 67.9667 66.5448 67.3231 66.5448 66.6557C66.5448 65.9884 66.2974 65.3448 65.8506 64.8492C65.4037 64.3535 64.789 64.0411 64.1252 63.9723L63.8482 63.9579ZM51.557 14.0844V63.9579H43.165V9.78585H47.0606C49.6146 9.78585 51.557 11.775 51.557 14.0844ZM26.9781 33.3647C26.9781 32.6492 27.2624 31.963 27.7683 31.4571C28.2742 30.9512 28.9604 30.6669 29.6759 30.6669C30.3914 30.6669 31.0776 30.9512 31.5836 31.4571C32.0895 31.963 32.3738 32.6492 32.3738 33.3647V40.379C32.3738 41.0946 32.0895 41.7808 31.5836 42.2867C31.0776 42.7926 30.3914 43.0769 29.6759 43.0769C28.9604 43.0769 28.2742 42.7926 27.7683 42.2867C27.2624 41.7808 26.9781 41.0946 26.9781 40.379V33.3647Z",
                },
                {
                    title: "Agama",
                    desc: "Gambaran keberagaman mahasiswa berdasarkan keyakinan yang dianut.",
                    chartId: "chart-agama",
                    viewBox: "0 0 80 85",
                    iconPath:
                        "M47.3736 0.647728C47.8052 0.377946 48.2639 0.202588 48.7225 0.0946759C49.3565 -0.0402147 50.0174 -0.0267256 50.6245 0.108165C51.6631 0.350968 52.6208 0.984954 53.2413 1.94268L69.4147 26.196C70.6018 27.9631 71.2223 30.0539 71.2223 32.1852V42.0996C71.2223 43.0304 71.8158 43.8532 72.6926 44.15L83.3759 47.7111C85.143 48.2911 86.33 49.9368 86.33 51.7983V64.7478C86.33 66.0832 85.7095 67.3511 84.6439 68.174C83.5782 68.9968 82.2023 69.2666 80.9074 68.9159L58.1379 62.8458C50.584 60.8359 45.3233 53.9835 45.3233 46.1598V30.2158C45.3233 27.8282 47.2522 25.8993 49.6398 25.8993C52.0273 25.8993 53.9563 27.8282 53.9563 30.2158V41.007C53.9563 42.194 54.9275 43.1653 56.1145 43.1653C57.3015 43.1653 58.2728 42.194 58.2728 41.007V29.5548C58.2728 28.6106 58.0299 27.6933 57.5578 26.884L45.9033 6.48849C45.674 6.08382 45.5121 5.66566 45.4177 5.23401C45.2828 4.60002 45.2828 3.93906 45.4312 3.33205C45.6875 2.25293 46.3484 1.28171 47.3601 0.647728H47.3736ZM38.9564 0.647728C39.9681 1.26822 40.6291 2.25293 40.8853 3.33205C41.0337 3.95255 41.0472 4.60002 40.8988 5.23401C40.8044 5.66566 40.6425 6.08382 40.4132 6.48849L28.7722 26.884C28.3001 27.6933 28.0573 28.624 28.0573 29.5548V41.007C28.0573 42.194 29.0285 43.1653 30.2155 43.1653C31.4025 43.1653 32.3738 42.194 32.3738 41.007V30.2158C32.3738 27.8282 34.3027 25.8993 36.6903 25.8993C39.0778 25.8993 41.0068 27.8282 41.0068 30.2158V46.1598C41.0068 53.9835 35.746 60.8224 28.1921 62.8458L5.43609 68.9159C4.14114 69.2666 2.75177 68.9833 1.69962 68.174C0.647475 67.3646 0 64.7478 0 64.7478V51.7983C0 49.9368 1.18704 48.2911 2.9541 47.6976L13.6374 44.1365C14.5142 43.8397 15.1078 43.0169 15.1078 42.0861V32.1717C15.1078 30.0404 15.7417 27.9631 16.9153 26.1825L33.0752 1.95617C33.6957 0.998443 34.6534 0.364457 35.6921 0.121654C36.3126 -0.0267256 36.96 -0.0402147 37.594 0.108165C38.0661 0.216078 38.5248 0.391435 38.9429 0.661217L38.9564 0.647728Z",
                },
                {
                    title: "Rasio",
                    desc: "Analisis proporsi populasi civitas akademika dalam kampus.",
                    chartId: "chart-rasio",
                    viewBox: "0 0 24 24",
                    iconPath: "M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z",
                },
                {
                    title: "Domisili",
                    desc: "Peta sebaran asal daerah dan tempat tinggal mahasiswa Politeknik Negeri Bandung.",
                    chartId: "chart-domisili",
                    viewBox: "0 0 24 24",
                    iconPath: "M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z",
                },
            ],
        };
    },
    computed: {
        // Otomatis true jika salah satu chart sedang loading
        isLoadingAny() {
            return this.isLoadingGender || 
                   this.isLoadingSLTA || 
                   this.isLoadingAgama || 
                   this.isLoadingDosen || 
                   this.isLoadingTipe || 
                   this.isLoadingDomisili;
        }
    },
    mounted() {
        this.fetchAllData();
    },
    methods: {
        // --- SCROLL TO CHART ---
        scrollToChart(chartId) {
            const element = document.getElementById(chartId);
            if (element) {
                element.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        },

        // --- FETCH ALL ---
        fetchAllData() {
            this.fetchGenderData();
            this.fetchSLTAData();
            this.fetchAgamaData();
            this.fetchDosenData();
            this.fetchTipeData();
            this.fetchDomisiliData();
        },

        // --- 1. GENDER ---
        async fetchGenderData() {
            this.isLoadingGender = true;
            this.errorGender = null;
            try {
                const response = await fetch(`${API_BASE_URL}/api/v1/guest/mahasiswa/gender`);
                if (!response.ok) throw new Error(`Server Error: ${response.status}`);
                
                const rawData = this.normalizeData(await response.json());
                const processedData = this.processChartData(rawData);

                this.genderChartData = {
                    labels: processedData.labels,
                    datasets: [{
                        backgroundColor: ["#42A5F5", "#FF6384"],
                        data: processedData.values,
                        borderWidth: 1
                    }]
                };
            } catch (error) {
                console.error("Gender Error:", error);
                this.errorGender = "Gagal memuat data gender.";
            } finally {
                this.isLoadingGender = false;
            }
        },

        // --- 2. SLTA ---
        async fetchSLTAData() {
            this.isLoadingSLTA = true;
            this.errorSLTA = null;
            try {
                const response = await fetch(`${API_BASE_URL}/api/v1/guest/mahasiswa/jenis-slta`);
                if (!response.ok) throw new Error(`Server Error: ${response.status}`);
                
                const rawData = this.normalizeData(await response.json());
                const processedData = this.processChartData(rawData);

                const colors = ["#06b6d4", "#8b5cf6", "#f97316", "#10b981"];
                this.sltaChartData = {
                    labels: processedData.labels,
                    datasets: [{
                        label: "Jumlah Mahasiswa",
                        backgroundColor: colors,
                        data: processedData.values,
                        borderRadius: 6,
                        barThickness: 40,
                    }]
                };
            } catch (error) {
                console.error("SLTA Error:", error);
                this.errorSLTA = "Gagal memuat data SLTA.";
            } finally {
                this.isLoadingSLTA = false;
            }
        },

        // --- 3. AGAMA ---
        async fetchAgamaData() {
            this.isLoadingAgama = true;
            this.errorAgama = null;
            try {
                const response = await fetch(`${API_BASE_URL}/api/v1/guest/mahasiswa/agama`);
                if (!response.ok) throw new Error(`Server Error: ${response.status}`);
                
                const rawData = this.normalizeData(await response.json());
                const processedData = this.processChartData(rawData);
                
                const colors = ["#21308f", "#f6983e", "#34d399", "#ef4444", "#6366f1", "#64748b"];
                this.agamaChartData = {
                    labels: processedData.labels,
                    datasets: [{
                        backgroundColor: colors,
                        data: processedData.values,
                        borderWidth: 1
                    }]
                };
            } catch (error) {
                console.error("Agama Error:", error);
                this.errorAgama = "Gagal memuat data agama.";
            } finally {
                this.isLoadingAgama = false;
            }
        },

        // --- 4. RASIO DOSEN ---
        async fetchDosenData() {
            this.isLoadingDosen = true;
            this.errorDosen = null;
            try {
                const response = await fetch(`${API_BASE_URL}/api/v1/guest/mahasiswa/rasio-dosen-mhs`);
                if (!response.ok) throw new Error(`Server Error: ${response.status}`);
                
                const rawData = this.normalizeData(await response.json());
                const processedData = this.processChartData(rawData);

                this.dosenChartData = {
                    labels: processedData.labels,
                    datasets: [{
                        backgroundColor: ["#7E57C2", "#FFA726"],
                        data: processedData.values,
                        borderWidth: 1
                    }]
                };
            } catch (error) {
                console.error("Dosen Error:", error);
                this.errorDosen = "Gagal memuat data dosen.";
            } finally {
                this.isLoadingDosen = false;
            }
        },

        // --- 5. JALUR MASUK (TIPE) ---
        async fetchTipeData() {
            this.isLoadingTipe = true;
            this.errorTipe = null;
            try {
                const response = await fetch(`${API_BASE_URL}/api/v1/guest/akademik/tipe-tes-masuk`);
                if (!response.ok) throw new Error(`Server Error: ${response.status}`);
                
                const rawData = this.normalizeData(await response.json());
                const processedData = this.processChartData(rawData);
                
                if (processedData.labels.length === 0) {
                    this.tipeChartData = null;
                    return;
                }

                const colors = ["#21308f", "#f6983e", "#34d399", "#ef4444", "#6366f1", "#64748b"];
                this.tipeChartData = {
                    labels: processedData.labels,
                    datasets: [{
                        label: "Jumlah Mahasiswa",
                        backgroundColor: colors,
                        data: processedData.values,
                        borderRadius: 6,
                        barThickness: 30,
                    }]
                };
            } catch (error) {
                console.error("Tipe Error:", error);
                this.errorTipe = "Gagal memuat data jalur masuk.";
            } finally {
                this.isLoadingTipe = false;
            }
        },

        // --- 6. DOMISILI (MAP) ---
        async fetchDomisiliData() {
            this.isLoadingDomisili = true;
            this.errorDomisili = null;
            try {
                const response = await fetch(`${API_BASE_URL}/api/v1/guest/mahasiswa/domisili`);
                if (!response.ok) throw new Error(`Server Error: ${response.status}`);
                
                const rawData = this.normalizeData(await response.json());
                // Map data berbeda strukturnya, filter manual
                this.domisiliData = rawData.filter(item => item.geo && item.geo.lat && item.geo.lng);
            } catch (error) {
                console.error("Domisili Error:", error);
                this.errorDomisili = "Gagal memuat data peta.";
            } finally {
                this.isLoadingDomisili = false;
            }
        },

        // --- HELPERS ---
        normalizeData(responseData) {
            if (Array.isArray(responseData)) return responseData;
            if (responseData.data && Array.isArray(responseData.data)) return responseData.data;
            if (responseData.payload) return responseData.payload;
            return [];
        },

        processChartData(data) {
            if (!data || data.length === 0) return { labels: [], values: [] };
            const firstItem = data[0];
            const keys = Object.keys(firstItem);
            
            // Cari key label (jenis, tipe, nama, agama, dll)
            let labelKey = keys.find(k => ['tipe', 'jenis', 'nama', 'jalur', 'agama'].includes(k.toLowerCase())) 
                        || keys.find(k => typeof firstItem[k] === 'string');
            
            // Cari key value (total, jumlah, count)
            let valueKey = keys.find(k => ['total', 'jumlah', 'count'].includes(k.toLowerCase())) 
                        || keys.find(k => typeof firstItem[k] === 'number');
            
            if (!labelKey || !valueKey) return { labels: [], values: [] };
            
            const map = new Map();
            data.forEach(item => {
                const label = item[labelKey] || 'Lainnya';
                const val = Number(item[valueKey] || 0);
                map.set(label, (map.get(label) || 0) + val);
            });
            return { labels: Array.from(map.keys()), values: Array.from(map.values()) };
        }
    },
};
</script>

<style scoped>
/* --- STYLING --- */
.home-page {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding-bottom: 2rem;
}

/* --- WELCOME BANNER STYLE --- */
.welcome-banner {
    background-color: var(--color-banner);
    color: white;
    padding: 1.5rem 2rem 2rem 2.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.banner-content-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
}

.banner-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.banner-text {
    font-size: 0.95rem;
    line-height: 1.6;
    opacity: 0.9;
    max-width: 100%;
}

/* CUSTOM BUTTON STYLE untuk menimpa style default komponen PdfReportButton 
   agar sesuai dengan tema "Inverted" (Putih di atas background Banner)
*/
.custom-banner-btn {
    background-color: white !important;
    color: var(--color-banner) !important;
    border: 1px solid white !important;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    
    /* FIX LAYOUT */
    white-space: nowrap;  /* Prevents wrapping */
    flex-shrink: 0;       /* Prevents shrinking in flex container */
    min-width: fit-content; /* Ensures it takes required space */
}

.custom-banner-btn:hover {
    background-color: #f0f0f0 !important; /* Sedikit abu saat hover */
    transform: translateY(-1px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.custom-banner-btn:disabled {
    background-color: #ccc !important;
    border-color: #ccc !important;
    color: #666 !important;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Grid Layout */
.charts-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    width: 100%;
}

.chart-card {
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
    width: 100%;
    min-width: 0;
    border: 1px solid var(--border-color);
    position: relative;
}

.card-header {
    margin-bottom: 1rem;
}

.chart-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
}

.chart-subtitle {
    font-size: 0.8rem;
    color: var(--text-tertiary);
    margin-top: 4px;
}

.chart-container {
    position: relative;
    width: 100%;
    height: 300px;
    min-height: 300px;
    overflow: hidden;
}

.chart-tall {
    min-height: 400px;
    height: 100%;
}

.download-action-area {
    display: flex;
    justify-content: flex-end;
    margin-top: 1rem;
}

/* Loading State */
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

.loader-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid #ccc;
    border-top-color: var(--color-primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-bottom: 8px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Extra Styling for Home Nav Cards (Stat Cards) */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-color);
    transition: transform 0.2s;
    cursor: pointer;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
.icon-box {
    background-color: var(--color-box);
    min-width: 65px; height: 65px;
    border-radius: var(--radius-xl);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 5px;
}
.card-content { display: flex; flex-direction: column; }
.card-title { font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.1rem; }
.card-desc { font-size: 0.75rem; color: var(--text-tertiary); line-height: 1.3; }

/* Grid Spans */
.span-full { grid-column: 1 / -1; }
.span-col-2 { grid-column: span 1; }
.span-row-2 { grid-row: span 1; }



/* Responsive */
@media (min-width: 768px) {
    .charts-grid { grid-template-columns: repeat(2, 1fr); }
    .span-col-2 { grid-column: span 2; } /* Lebarkan jika layar besar */
    .span-full { grid-column: span 2; }
    .span-row-2 { grid-row: span 2; }
}

@media (max-width: 992px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 576px) {
    .banner-content-wrapper { flex-direction: column; gap: 1rem; }
    .welcome-banner { padding: 1.5rem; }
    .banner-title { font-size: 1.5rem; }
    
    .chart-container { height: 250px; min-height: 250px; }
    .stats-grid { grid-template-columns: 1fr; }
    .charts-grid { grid-template-columns: 1fr; }
    .span-col-2, .span-full, .span-row-2 { grid-column: span 1; grid-row: span 1; }
}
</style>