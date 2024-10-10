<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { Cursos } from '../store/cursos';
import { Categoria } from '../store/categoria';
import { sweetalert } from '../composables/sweetAlert';

const matriculado = true;
const categoriaStore = Categoria();
const sweetAlert = sweetalert();
const cursoStore = Cursos();
const route = useRoute(); // Obtener la ruta actual para obtener el parámetro 'id' del curso

const curso = ref(null); // Referencia para almacenar los datos del curso
const claseSeleccionada = ref(null); // Variable para almacenar la clase seleccionada

// Método para seleccionar una clase del contenido
const seleccionarClase = (index) => {
  claseSeleccionada.value = index;
};

// Método para obtener la imagen de la categoría asociada al curso
const getCategoriaImagen = (categoriaId) => {
  const categoria = categoriaStore.categorianormal.find(cat => cat.categoriaId === categoriaId);
  return categoria ? categoria.categoriaImagen : 'default-image.jpg';
};

const mostrarRequisitos = (cursoRequisito) => {
  sweetAlert.showAlert("Requisitos", cursoRequisito || "No hay requisitos especificados");
};

// Poner algo hasta que cargue
onMounted(async () => {
  const closeLoading = sweetAlert.ShowLoading();
  const cursoId = route.params.claseId; // Obtener 'claseId' de los parámetros de la ruta
  try {
    await categoriaStore.getCategoria();
    await cursoStore.getCurso(cursoId); // Llamar a la función del store para obtener el curso
    curso.value = cursoStore.cursoIndividual; // Asignar el curso obtenido a la referencia 'curso'

    // Verificar si el contenido está en formato JSON string y convertirlo en un array de objetos
    if (curso.value && typeof curso.value.cursoContenido === 'string') {
      curso.value.cursoContenido = JSON.parse(curso.value.cursoContenido);
    }

    // Seleccionar la primera clase por defecto si hay contenido
    if (curso.value.cursoContenido.length > 0) {
      claseSeleccionada.value = 0;
    }

    console.log('Curso individual:', curso.value);
  } catch (error) {
    console.error('Error al cargar el curso:', error);
  }
  closeLoading();
});
</script>

<template>
  <Header />

  <div v-if="matriculado === false" class="curso-no-matriculado">
    <div v-if="curso" class="curso-detalle">
      <div class="curso-info">
        <h2>{{ curso.cursoName }}</h2>
        <p class="curso-subtitulo">Para ver el contenido de este curso debes matricularte</p>
        <strong>¿Qué Aprenderé?</strong>
        <p>{{ curso.cursoDescripcion }}</p>
        <p><strong>Creado por:</strong> {{ curso.createdBy }}</p>
        <p><strong>Nivel:</strong> {{ curso.nivel.nivelName }}</p>
        <p><strong>Valor:</strong> ${{ curso.cursoValor }}</p>

        <button class="btn-add-cart" @click="mostrarRequisitos(curso.cursoRequisito)">
          Ver Requisitos
        </button>
        <button class="btn-add-cart">Matricularse</button>
      </div>

      <div class="curso-acciones">
        <img :src="getCategoriaImagen(curso.cursoCategoriaId)" class="curso-imagen" alt="Imagen del curso" />
      </div>
    </div>

    <div v-else>
      <p>Cargando detalles del curso...</p>
    </div>
  </div>

  <!-- Columna lateral con los títulos de las clases -->
  <div class="curso-contenido-layout" v-if="curso && Array.isArray(curso.cursoContenido)">
    <div class="clases-lista">
      <ul>
        <li 
          v-for="(contenido, index) in curso.cursoContenido" 
          :key="index"
          @click="seleccionarClase(index)"
          :class="{ activo: claseSeleccionada === index }"
        >
          {{ contenido.titulo }}
        </li>
      </ul>
    </div>

    <!-- Sección principal para mostrar el contenido de la clase seleccionada -->
    <div class="contenido-detalle" v-if="claseSeleccionada !== null">
      <h3>{{ curso.cursoContenido[claseSeleccionada].titulo }}</h3>
      <div class="media-section">
        <img :src="curso.cursoContenido[claseSeleccionada].media" alt="Imagen del contenido" class="contenido-imagen" />
      </div>
      <div class="concepto-section">
        <p>{{ curso.cursoContenido[claseSeleccionada].concepto }}</p>
      </div>
    </div>
  </div>

  <Footer />
</template>

<style scoped>
.curso-no-matriculado {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin: 20px auto;
  max-width: 1200px;
  padding: 20px;
  border: 1px solid #ddd;
  background-color: #f9f9f9;
}

.curso-detalle {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  width: 100%;
}

.curso-info {
  flex: 1;
  padding-right: 40px;
}

.curso-info h2 {
  font-size: 2rem;
  font-weight: bold;
}

.curso-subtitulo {
  font-size: 1.25rem;
  color: #555;
}

.curso-rating {
  font-size: 1rem;
  margin-bottom: 10px;
}

.curso-imagen {
  width: 300px;
  height: auto;
  border-radius: 10px;
}

.curso-acciones {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.curso-precio {
  font-size: 1.5rem;
  font-weight: bold;
  margin-top: 10px;
  margin-bottom: 20px;
}

.btn-add-cart {
  padding: 10px 20px;
  background-color: #6a1b9a;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn-add-cart:hover {
  background-color: #7e22ce;
}

.curso-detalle p {
  margin: 5px 0;
}

/* Estilos para el contenido del curso */
.curso-contenido-layout {
  display: flex;
  max-width: 1200px;
  margin: 20px auto;
  gap: 20px;
}

.clases-lista {
  width: 20%;
  border: 1px solid #ddd;
  padding: 20px;
  border-radius: 8px;
  background-color: #f9f9f9;
}

.clases-lista ul {
  list-style-type: none;
  padding: 0;
}

.clases-lista li {
  margin-bottom: 10px;
  padding: 10px;
  border-bottom: 1px solid #ddd;
  cursor: pointer;
}

.clases-lista li:hover {
  background-color: #eee;
}

.clases-lista li.activo {
  font-weight: bold;
  background-color: #e0e0e0;
  border-left: 4px solid #007bff;
}

.contenido-detalle {
  width: 75%;
}

.contenido-detalle h3 {
  font-size: 1.8rem;
  margin-bottom: 20px;
}

.media-section {
  margin-bottom: 20px;
}

.contenido-imagen {
  max-width: 100%;
  height: 200px;
  width: 200px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.concepto-section {
  margin-top: 20px;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background-color: #f9f9f9;
}
</style>
