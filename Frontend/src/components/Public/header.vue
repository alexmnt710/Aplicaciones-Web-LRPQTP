<template>
  <!-- Como debe verse el header -->
  <header class="navbar">
    <div class="logo">
      <h1>Ulemi</h1>
    </div>
    <nav class="nav-links" :class="{ 'mobile-menu': isMobileMenuOpen }">
      <ul>
        <li>
          <!-- No usar el path -->
          <router-link :to="{ name: 'Home' }">Home</router-link>
        </li>
        <li>
          <router-link :to="{ name: 'Home' }">Cursos</router-link>
        </li>
        <li>
          <router-link :to="{ name: 'Home' }">Clases</router-link>
        </li>
        <li>
          <router-link :to="{ name: 'Home' }">Perfil</router-link>
        </li>
      </ul>
    </nav>
    <div class="login-button">
      <button><router-link :to="{ name: 'Login' }">Acceder</router-link></button>
          </div>
    <!-- Botón de menú para móviles -->
    <div class="mobile-menu-icon" @click="toggleMobileMenu">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue';

const isMobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};
</script>


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
}

.nav-links ul li a:hover {
  text-decoration: underline;
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
