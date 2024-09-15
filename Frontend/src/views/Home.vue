<script setup>
import { ref, onMounted } from 'vue'
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { Cursos } from '../store/cursos';
import { sweetalert } from '../composables/sweetAlert';
import { useRouter } from 'vue-router';

const sweetAlert = sweetalert();
const cursoStore = Cursos();
const cursos = ref([]);
const router = useRouter(); // Para redirigir a otra página

onMounted(async () => {
   await cursoStore.getCursosHome();
   cursos.value = cursoStore.cursos;
   console.log(cursoStore.cursos);
});

// Función para mostrar el alert con los requisitos del curso
const mostrarRequisitos = (cursoRequisito) => {
  sweetAlert.showAlert("Requisitos", cursoRequisito || "No hay requisitos especificados");
}

// Función para navegar a la vista del curso
const irAVistaCurso = async (cursoId) => {
  cursoStore.getCurso(cursoId).then(()=>router.push(`/clases`)); // Obtener los detalles del curso y redirigir
}
</script>

<template>
  <Header />
  <div class="home-container">
    <!-- Sección de cursos destacados -->
    <section class="courses-section">
      <div class="course-header">
        <h2>Cursos Destacados</h2>
        <p>Explora nuestros cursos más populares</p>
      </div>
      
      <!-- Grid de cursos -->
      <div class="courses-grid">
        <div v-for="curso in cursos" :key="curso.cursoId" class="course-card">
          <img :src="curso.cursoImagen || 'ruta/imagen/por/defecto.jpg'" alt="Imagen del curso" class="course-image">
          <h3>{{ curso.cursoName }}</h3>
          <p>{{ curso.cursoDescripcion }}</p>
          <p><strong>Precio:</strong> ${{ curso.cursoValor }}</p>
          
          <!-- Mostrar la categoría -->
          <p><strong>Categoría:</strong> {{ curso.categoria.categoriaName }}</p>

          <!-- Botón para mostrar requisitos -->
          <button @click="mostrarRequisitos(curso.cursoRequisito)">
            Ver Requisitos
          </button>
          
          <!-- Botón para redirigir a la vista del curso -->
          <button @click="irAVistaCurso(curso.cursoId)">
            Ver Curso
          </button>
        </div>
      </div>
    </section>
  </div>
  <Footer />
</template>

<style scoped>
/* Estilos del contenedor principal */
.home-container {
  background-color: #f0f0f0;
  padding: 2rem;
  color: #0f3d28;
}

.courses-section {
  background-color: white;
  padding: 2rem;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.course-header {
  text-align: center;
  margin-bottom: 2rem;
}

.courses-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}

.course-card {
  background-color: #ffffff;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 450px; /* Ajustar la altura mínima para que todas las tarjetas tengan el mismo tamaño */
}

.course-image {
  width: 100%;
  border-radius: 10px;
  margin-bottom: 1rem;
  height: 200px;
  object-fit: cover; /* Mantener proporciones de la imagen */
}

/* Estilos para el título y descripción para que no excedan demasiado espacio */
.course-card h3 {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
}

.course-card p {
  flex-grow: 1;
  margin-bottom: 0.5rem;
}

button {
  background-color: #3ecf8e;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 1rem;
  width: 100%; /* Para hacer que los botones llenen el ancho de la tarjeta */
}

button:hover {
  background-color: #2fb477;
}

/* Asegura que los botones estén siempre en la parte inferior */
.course-card button {
  align-self: stretch;
}
</style>

