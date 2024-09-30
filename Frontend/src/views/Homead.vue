<script setup>
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { useRouter } from 'vue-router';
import { Sesion } from '../store/sesion';

const sesionStore = Sesion();  
const router = useRouter();

// Función para navegar a la ruta seleccionada
const navigateTo = (routeName) => {
    router.push({ name: routeName });
};
</script>

<template>
    <Header></Header>
    
    <div class="toolbox-container">
        <h2 class="admin-title">Home de Admin</h2>
        <div class="toolbox">
            <!-- Botón para la vista de Cursos -->
            <button @click="navigateTo('AdminCursos')" class="toolbox-item">
                <i class="bi bi-book"></i>
                <span>Cursos</span>
            </button>
            <!-- Botón para la vista de Docentes -->
            <button @click="navigateTo('Docentes')" class="toolbox-item">
                <i class="bi bi-person-badge"></i>
                <span>Docentes</span>
            </button>
            <!-- Botón para la vista de Usuarios -->
            <button @click="navigateTo('Usuarios')" class="toolbox-item">
                <i class="bi bi-people"></i>
                <span>Usuarios</span>
            </button>
            <!-- Botón para la vista de Categorías -->
            <button @click="navigateTo('Categorias')" class="toolbox-item">
                <i class="bi bi-card-list"></i>
                <span>Categorías</span>
            </button>
        </div>
    </div>

    <Footer></Footer>
</template>

<style scoped>
/* Quitar márgenes y rellenos del body */
body {
    margin: 0;
    padding: 0;
}

/* Contenedor principal */
.toolbox-container {
    text-align: center;
    background-color: #0f3d28; /* Fondo verde oscuro para coincidir con el header */
    padding: 3rem 0;
    min-height: 100vh; /* Ocupa toda la altura de la ventana */
    display: flex;
    flex-direction: column;
    justify-content: center; /* Centra verticalmente el contenido */
    align-items: center; /* Centra horizontalmente el contenido */
}

/* Título */
.admin-title {
    color: #3ecf8e; /* Color del texto para coincidir con el header */
    font-size: 2.5rem;
    margin-bottom: 2rem;
}

/* Toolbox */
.toolbox {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap; /* Permite que los botones se ajusten al tamaño de la pantalla */
}

/* Botones de la toolbox */
.toolbox-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    background-color: #3ecf8e;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 1.5rem;
    width: 200px;
    height: 200px;
}

.toolbox-item:hover {
    background-color: #35b67b;
}

/* Íconos */
.toolbox-item i {
    font-size: 4rem; /* Aumenta el tamaño del ícono */
    margin-bottom: 1rem;
}
</style>
