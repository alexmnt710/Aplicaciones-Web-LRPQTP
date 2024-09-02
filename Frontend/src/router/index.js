import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
const router = createRouter({
    history: createWebHistory('/Ulemi/'), 
    routes: [
        {
            path: '/',
            name: 'Home',
            component: Home,
            meta: { requiresAuth: false } // Marca la ruta como no requiere autenticación
      
        },

    ]
  });
export default router;