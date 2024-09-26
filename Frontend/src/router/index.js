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
import { Sesion } from '../store/sesion';

const router = createRouter({
    history: createWebHistory('/Ulemi/'), 
    routes: [
        { path: '/', name: 'Home', component: Home, meta: { requiresAuth: false } },
        { path: '/login', name: 'Login', component: Login, meta: { requiresAuth: false } },
        { path: '/register', name: 'Register', component: Register, meta: { requiresAuth: false } },
        { path: '/cursos', name: 'Cursos', component: Cursos, meta: { requiresAuth: false } },
        { path: '/clases/:claseId', name: 'Clases', component: Clases, meta: { requiresAuth: true }, props: true },
        { path: '/admin', name: 'Admin', component: Admin, meta: { requiresAuth: true, role: 'admin' } },
        { path: '/adcursos', name: 'AdminCursos', component: AdminCursos, meta: { requiresAuth: true, role: ['admin', 'teacher'] } },
        { path: '/docentes', name: 'Docentes', component: Docentes, meta: { requiresAuth: true, role: 'admin' } },
        { path: '/usuarios', name: 'Usuarios', component: Usuarios, meta: { requiresAuth: true, role: 'admin' } },
        { path: '/perfil', name: 'Perfil', component: Perfil, meta: { requiresAuth: true, role: ['admin', 'teacher', 'student'] } }
    ]
});

// Navigation guard
router.beforeEach((to, from, next) => {
    const sesionStore = Sesion(); // Obtén la sesión
    const userRole = sesionStore.rol; // Rol del usuario en sesión
    const isAuthenticated = !!userRole; // Verifica si hay un rol definido (usuario autenticado)

    // Restringir el acceso a login y registro si el usuario ya tiene sesión
    if (isAuthenticated && (to.name === 'Login' || to.name === 'Register')) {
        return next({ path: '/' }); // Redirige a Home si el usuario ya tiene sesión
    }

    // Verifica si la ruta requiere autenticación
    if (to.meta.requiresAuth) {
        if (!isAuthenticated) {
            // Si no está autenticado, redirige a login
            return next({ name: 'Login' });
        }

        // Verifica si la ruta tiene restricciones de rol
        if (to.meta.role) {
            const allowedRoles = Array.isArray(to.meta.role) ? to.meta.role : [to.meta.role];
            if (!allowedRoles.includes(userRole)) {
                // Si el rol no está permitido, redirige a Home
                return next({ path: '/' });
            }
        }
    }

    // Si todo está bien, permite la navegación
    next();
});

export default router;
