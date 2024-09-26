<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { Sesion } from '../../store/sesion';

const isMobileMenuOpen = ref(false);
const router = useRouter();
const sesionStore = Sesion();

onMounted(async () => {
  await sesionStore.getSesion();
});

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const handleLogout = async () => {
  await sesionStore.logout();  
  router.push({ name: 'Login' }); 
  window.location.reload(); // Recargar la página
};
</script>

<template>
  <header class="navbar">
    <div class="logo">
      <h1>Ulemi</h1>
    </div>
    <nav class="nav-links" :class="{ 'mobile-menu': isMobileMenuOpen }">
      <ul>
        <li>
          <router-link :to="{ name: 'Home' }"><i class="bi bi-house"></i> Home</router-link>
        </li>
        <li v-if="sesionStore.rol === 'student' || sesionStore.sesion == false">
          <router-link :to="{ name: 'Cursos' }"><i class="bi bi-book"></i> Cursos</router-link>
        </li>
        <li v-else-if="sesionStore.rol === 'admin' || sesionStore.rol === 'teacher'">
          <router-link :to="{ name: 'AdminCursos' }"><i class="bi bi-bookmarks"></i> Cursos</router-link>
        </li>
        <li v-if="sesionStore.rol === 'admin'">
          <router-link :to="{ name: 'Docentes' }"><i class="bi bi-person"></i> Docentes</router-link>
        </li>
        <li v-if="sesionStore.rol === 'admin'">
          <router-link :to="{ name: 'Usuarios' }"><i class="bi bi-person-lines-fill"></i> Usuarios</router-link>
        </li>
        <li>        
          <router-link :to="{ name: 'Perfil' }"><i class="bi bi-person-circle"></i> Perfil</router-link>
        </li>
      </ul>
    </nav>
    <div class="login-button">
      <button v-if="sesionStore.sesion == false">
        <router-link :to="{ name: 'Register' }"><i class="bi bi-person-plus"></i> Registrarse</router-link>
      </button>
      <button v-if="sesionStore.sesion == false">
        <router-link :to="{ name: 'Login' }"><i class="bi bi-box-arrow-in-right"></i> Acceder</router-link>
      </button>
      <button v-else @click="handleLogout">
        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
      </button>
    </div>
    <div class="mobile-menu-icon" @click="toggleMobileMenu">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </header>
</template>

<style scoped>
/* Estilos para el navbar */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background: linear-gradient(90deg, #0f3d28, #0d0f0c); 
  color: white;
  position: relative;
}

.logo h1 {
  color: #3ecf8e; 
  font-size: 1.8rem;
  margin: 0;
}

.nav-links ul {
  display: flex;
  gap: 2rem;
  list-style: none;
  margin: 0;
  padding: 0;
}

.nav-links ul li a {
  color: white;
  text-decoration: none;
  font-weight: 500;
  display: flex;
  align-items: center;
}

.nav-links ul li a i {
  margin-right: 0.5rem; /* Espacio entre el icono y el texto */
}

.nav-links ul li a:hover {
  text-decoration: underline;
}

.login-button {
  display: flex;
  gap: 1rem; /* Espacio entre los botones */
}

.login-button button {
  background-color: white;
  color: #0d0f0c;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 25px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s;
  display: flex;
  align-items: center;
}

.login-button button i {
  margin-right: 0.5rem; /* Espacio entre el icono y el texto en los botones */
}

.login-button button:hover {
  background-color: #f0f0f0;
}

/* Icono para móviles */
.mobile-menu-icon {
  display: none;
  flex-direction: column;
  justify-content: space-between;
  width: 24px;
  height: 24px;
  cursor: pointer;
}

.mobile-menu-icon span {
  display: block;
  height: 3px;
  background-color: white;
  border-radius: 2px;
  transition: all 0.3s;
}

/* Ocultar menú */
.nav-links.mobile-menu {
  display: none;
}

.nav-links.mobile-menu.open {
  display: block;
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  background-color: #0f3d28;
}

.nav-links.mobile-menu ul {
  flex-direction: column;
  gap: 1rem;
  padding: 1rem 0;
}

.nav-links.mobile-menu ul li {
  text-align: center;
}

/* Media Query para pantallas móviles */
@media (max-width: 768px) {
  .nav-links {
    display: none; /* Ocultar menú en móviles */
  }

  .mobile-menu-icon {
    display: flex; /* Mostrar icono de menú */
  }

  .nav-links.mobile-menu {
    display: block;
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background-color: #0f3d28;
    padding: 1rem 0;
  }

  .nav-links.mobile-menu ul {
    flex-direction: column;
    gap: 1rem;
    padding: 0;
  }

  .nav-links.mobile-menu ul li {
    text-align: center;
  }
}
</style>