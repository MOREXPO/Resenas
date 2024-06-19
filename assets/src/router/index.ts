import { createRouter, createWebHistory } from 'vue-router'
import Peliculas from '../views/Peliculas.vue'
import Pelicula from '../views/Pelicula.vue'
import Artistas from '../views/Artistas.vue'
import Reparto from '../views/Reparto.vue'

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
    },
    {
      path: '/peliculas/:id',  // Añade la ruta dinámica
      name: 'pelicula',
      component: Pelicula,
      props: true  // Permite pasar el parámetro id como prop al componente
    },
    {
      path: '/peliculas/:id/reparto',  // Añade la ruta dinámica
      name: 'reparto',
      component: Reparto,
      props: true  // Permite pasar el parámetro id como prop al componente
    }
  ]
})

export default router
