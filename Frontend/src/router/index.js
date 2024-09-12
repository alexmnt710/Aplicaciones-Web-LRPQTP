import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Clases from '../views/Clases.vue';
import Admin from '../views/Admin.vue';
import Register from '../views/Register.vue'
const router = createRouter({
    history: createWebHistory('/Ulemi/'), 
    routes: [
        {
            path: '/',
            name: 'Home',
            component: Home,
            meta: { requiresAuth: false } // Marca la ruta como no requiere autenticación
      
        },
        {
            path: '/login',
            name: 'Login',
            component: Login,
            meta: { requiresAuth: false } // Marca la ruta como no requiere autenticación
        },
        {
            path: '/clases',
            name: 'Clases',
            component: Clases,
            meta: { requiresAuth: false } // Marca la ruta como no requiere autenticación
      
        },
        {
            path: '/admin',
            name: 'Admin',
            component: Admin,
            meta: { requiresAuth: false } //  autenticación esto es solo para la prueba
      
        },
        {
            path: '/register',
            name: 'Register',
            component: Register,
            meta: { requiresAuth: false } // Marca la ruta como no requiere autenticación
      
        },
        
        // Ejemplo de como crear la ruta de una vista
            // {
            //     path: '/cursos',
            //     name: 'Cursos',
            //     component: Cursos,
            //     meta: { requiresAuth: false } // Marca la ruta como no requiere autenticación
        
            // },

    ]
  });
export default router;