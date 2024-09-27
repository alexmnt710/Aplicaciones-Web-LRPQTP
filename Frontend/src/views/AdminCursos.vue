<script setup>
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import Pagination from '../components/Pagination.vue'; // Componente de paginación
import { Cursos } from '../store/cursos';
import { ref, onMounted } from 'vue';
import { Sesion } from '../store/sesion';
import { Categoria } from '../store/categoria';


const categoriaStore = Categoria();
const sesionStore = Sesion();
const cursoStore = Cursos();
const cursos = ref([]);
const paginationData = ref({
  current_page: 1,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null
});

const selectedCurso = ref(null); // Variable para guardar el curso seleccionado
const showModal = ref(false); // Controla si el modal está visible

const loadCursos = async (pageUrl = null) => {
  await cursoStore.getCursos(sesionStore.token, pageUrl);
  cursos.value = cursoStore.cursos.data;
  paginationData.value = {
    current_page: cursoStore.cursos.current_page,
    last_page: cursoStore.cursos.last_page,
    prev_page_url: cursoStore.cursos.prev_page_url,
    next_page_url: cursoStore.cursos.next_page_url
  };
};

// Función para abrir el modal con los detalles del curso
const viewCurso = (curso) => {
  selectedCurso.value = curso; // Guarda el curso seleccionado
  showModal.value = true; // Muestra el modal
};

onMounted(async () => {
  await loadCursos();
  console.log(categoriaStore.categoria)
  console.log(categoriaStore.nivel)
});
</script>

<template>
  <Header />

  <div class="container mt-5">
    <h2>Gestión de Cursos</h2>
    
    <table class="table table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>Id</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Precio</th>
          <th>Categoría</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="curso in cursos" :key="curso.cursoId">
          <td>{{ curso.cursoId }}</td>
          <td>{{ curso.cursoName }}</td>
          <td>{{ curso.cursoDescripcion }}</td>
          <td>{{ curso.cursoValor }}</td>
          <td>{{ curso.cursoCategoriaId }}</td>
          <td>
            <!-- Botón para ver los detalles del curso -->
            <button class="btn btn-success btn-sm mx-1" @click="viewCurso(curso)">
              <i class="bi bi-eye"></i>
            </button>
            <button class="btn btn-primary btn-sm mx-1">
              <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-danger btn-sm mx-1">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Componente de Paginación -->
    <Pagination
      :currentPage="paginationData.current_page"
      :lastPage="paginationData.last_page"
      :prevPageUrl="paginationData.prev_page_url"
      :nextPageUrl="paginationData.next_page_url"
      @pageChange="loadCursos"
    />
  </div>

  <!-- Modal para ver los detalles del curso -->
  <div v-if="showModal" class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalles del Curso</h5>
          <button type="button" class="btn-close" @click="showModal = false"></button>
        </div>
        <div class="modal-body">
          <p><strong>ID:</strong> {{ selectedCurso?.cursoId }}</p>
          <p><strong>Nombre:</strong> {{ selectedCurso?.cursoName }}</p>
          <p><strong>Descripción:</strong> {{ selectedCurso?.cursoDescripcion }}</p>
          <p><strong>Precio:</strong> {{ selectedCurso?.cursoValor }}</p>
          <p><strong>Categoría:</strong> {{ selectedCurso?.cursoCategoriaId }}</p>
          <p><strong>Requisitos:</strong> {{ selectedCurso?.cursoRequisito }}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="showModal = false">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <Footer />
</template>

<style scoped>
.table th, .table td {
  text-align: center;
  vertical-align: middle;
}

/* Estilos para el modal */
.modal.show {
  display: block;
}

.modal-dialog {
  max-width: 600px;
}

.modal-body {
  padding: 1.5rem;
}
</style>
