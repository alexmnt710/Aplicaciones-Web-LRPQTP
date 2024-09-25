import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Clases from '../views/Clases.vue';
import Admin from '../views/Admin.vue';
import Register from '../views/Register.vue';
import AdminCursos from '../views/AdminCursos.vue';
import Usuarios from '../views/Usuarios.vue';
import Docentes from '../views/Docentes.vue';
import Perfil from '../views/Perfil.vue';
import Cursos from '../views/Cursos.vue';
   

const router = createRouter({
    history: createWebHistory('/Ulemi/'), 
    routes: [
        {
            path: '/',
            name: 'Home',
            component: Home,
            meta: { requiresAuth: false }, // Marca la ruta como no requiere autenticación
      
        },
        {
            path: '/login',
            name: 'Login',
            component: Login,
            meta: { requiresAuth: false } // Marca la ruta como no requiere autenticación
        },
        {
            path: '/clases/:claseId',
            name: 'Clases',
            component: Clases,
            meta: { requiresAuth: false }, // Marca la ruta como no requiere autenticación
            props: true
      
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
        {
            path: '/cursos',
            name: 'AdminCursos',
            component: AdminCursos,
            meta: { requiresAuth: true } // Cursos para el admin
        },
        {
            path: '/docentes',
            name: 'Docentes',
            component: Docentes,
            meta: { requiresAuth: true} // Listado de docentes, solo para admin
        },
        {
            path: '/usuarios',
            name: 'Usuarios',
            component: Usuarios,
            meta: { requiresAuth: true } // Gestión de usuarios, solo para admin
        },
        {
            path: '/perfil',
            name: 'Perfil',
            component: Perfil,
            meta: { requiresAuth: true } // Requiere autenticación para todos los usuarios
        },
        {
                path: '/cursos',
                name: 'Cursos',
                component: Cursos,
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