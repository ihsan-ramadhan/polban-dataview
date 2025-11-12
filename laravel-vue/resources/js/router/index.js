import { createRouter, createWebHistory } from 'vue-router'

const Home = () => import('../pages/Home.vue')
const Akademik = () => import('../pages/Akademik.vue')
const Kemahasiswaan = () => import('../pages/Kemahasiswaan.vue')

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/akademik',
    name: 'akademik', 
    component: Akademik
  },
  {
    path: '/kemahasiswaan',
    name: 'kemahasiswaan',
    component: Kemahasiswaan
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/'
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router