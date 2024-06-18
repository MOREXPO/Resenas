import { createRouter, createWebHistory } from 'vue-router'
import Peliculas from '../views/Peliculas.vue'
import Artistas from '../views/Artistas.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/peliculas'
    },
    {
      path: '/peliculas',
      name: 'peliculas',
      component: Peliculas
    },
    {
      path: '/artistas',
      name: 'artistas',
      component: Artistas
    }
  ]
})

export default router
