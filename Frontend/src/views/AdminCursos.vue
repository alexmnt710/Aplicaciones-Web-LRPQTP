<script setup>
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import Pagination from '../components/Pagination.vue'; // Componente de paginación
import { Cursos } from '../store/cursos';
import { ref, onMounted } from 'vue';
import { Sesion } from '../store/sesion';
import { Categoria } from '../store/categoria';
import { sweetalert } from '../composables/sweetAlert';

const sweetAlert = sweetalert();
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

const selectedCurso = ref(null);
const showModal = ref(false);
const showCreateModal = ref(false);

// Variables para crear un nuevo curso
const newCurso = ref({
  cursoName: '',
  cursoDescripcion: '',
  cursoNivelId: '',
  cursoValor: '',
  cursoRequisito: '',
  cursoCategoriaId: '',
  contenido: [] // Array para contener los bloques de contenido
});

// Función para añadir un nuevo bloque de contenido
const addContentBlock = () => {
  newCurso.value.contenido.push({
    titulo: '',
    media: '',
    concepto: ''
  });
};

// Función para eliminar un bloque de contenido
const removeContentBlock = (index) => {
  newCurso.value.contenido.splice(index, 1);
};

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

// Función para abrir el modal de creación de curso
const openCreateModal = () => {
  showCreateModal.value = true;
};

// Función para crear un curso con SweetAlert
// Función para crear un curso con SweetAlert
const createCurso = async () => {
  if (newCurso.value.cursoValor < 0) {
    sweetAlert.errorAlert('Número Inválido', 'El valor del curso no puede ser menor a 0.');
    return;
  }

  try {
    // Crear un solo objeto FormData
    const formData = new FormData();

    // Añadir todos los campos del curso al FormData
    formData.append('cursoName', newCurso.value.cursoName);
    formData.append('cursoDescripcion', newCurso.value.cursoDescripcion);
    formData.append('cursoNivelId', newCurso.value.cursoNivelId);
    formData.append('cursoValor', newCurso.value.cursoValor);
    formData.append('cursoRequisito', newCurso.value.cursoRequisito);
    formData.append('cursoCategoriaId', newCurso.value.cursoCategoriaId);
    formData.append('createdBy', sesionStore.user.userName); // Añadir el campo createdBy

    // Convertir el contenido del curso a una cadena JSON y añadirlo al FormData
    formData.append('contenido', JSON.stringify(newCurso.value.contenido));

    // Mostrar el contenido del FormData en la consola para depuración
    for (let pair of formData.entries()) {
      console.log(pair[0] + ': ' + pair[1]);
    }

    // Llamada al store para crear el curso
    await cursoStore.crearCurso(sesionStore.token, formData);

    sweetAlert.successAlert('Éxito', 'El curso se ha creado correctamente.');
    showCreateModal.value = false;
    loadCursos();
  } catch (error) {
    sweetAlert.errorAlert('Error', 'Hubo un problema al crear el curso.');
  }
};



  onMounted(async () => {
    await loadCursos();
    await categoriaStore.getCategorias();
    await categoriaStore.getNiveles();

   

  });
</script>

<template>
  <Header />

  <div class="container mt-5">
    <h2>Gestión de Cursos</h2>
    
    <!-- Botón para abrir el modal de crear curso -->
    <div class="mb-3">
      <button class="btn btn-primary" @click="openCreateModal">Crear Curso</button>
    </div>
    
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

  <!-- Modal para crear un nuevo curso -->
  <div v-if="showCreateModal" class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crear Curso</h5>
          <button type="button" class="btn-close" @click="showCreateModal = false"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="createCurso">
            <div class="mb-3">
              <label for="cursoName" class="form-label">Nombre del Curso</label>
              <input v-model="newCurso.cursoName" type="text" id="cursoName" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="cursoDescripcion" class="form-label">Descripción</label>
              <textarea v-model="newCurso.cursoDescripcion" id="cursoDescripcion" class="form-control" required></textarea>
            </div>
            <!-- select niveles -->
            <div class="mb-3">
              <label for="cursoNivelId" class="form-label">Nivel del Curso</label>
              <select v-model="newCurso.cursoNivelId" id="cursoNivelId" class="form-control" required>
                <option value="" disabled>Seleccione un nivel</option>
                <!-- Itera sobre los niveles para mostrar las opciones -->
                <option v-for="nivel in categoriaStore.nivel" :key="nivel.nivelId" :value="nivel.nivelId">
                  {{ nivel.nivelName }}
                </option>
              </select>
            </div>

            <div class="mb-3">
              <label for="cursoValor" class="form-label">Precio</label>
              <input v-model="newCurso.cursoValor" type="number" id="cursoValor" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="cursoRequisito" class="form-label">Requisitos</label>
              <input v-model="newCurso.cursoRequisito" type="text" id="cursoRequisito" class="form-control">
            </div>
            <div class="mb-3">
              <label for="cursoCategoriaId" class="form-label">Categoría</label>
              <select v-model="newCurso.cursoCategoriaId" id="cursoCategoriaId" class="form-control" required>
                <option value="" disabled>Seleccione una categoría</option>
                <option v-for="categoria in categoriaStore.categoria" :key="categoria.categoriaId" :value="categoria.categoriaId">
                  {{ categoria.categoriaName }}
                </option>
              </select>
            </div>
            <!-- Sección para añadir contenido -->
            <h5>Contenido del Curso</h5>
            <div v-for="(block, index) in newCurso.contenido" :key="index" class="content-block mb-3">
              <div class="mb-3">
                <label :for="'titulo-' + index" class="form-label">Título</label>
                <input v-model="block.titulo" :id="'titulo-' + index" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label :for="'media-' + index" class="form-label">Imagen/Video URL</label>
                <input v-model="block.media" :id="'media-' + index" type="text" class="form-control">
              </div>
              <div class="mb-3">
                <label :for="'concepto-' + index" class="form-label">Concepto</label>
                <textarea v-model="block.concepto" :id="'concepto-' + index" class="form-control" required></textarea>
              </div>
              <button type="button" class="btn btn-danger" @click="removeContentBlock(index)">Eliminar</button>
              <hr>
            </div>
            <button type="button" class="btn btn-secondary" @click="addContentBlock">Añadir Bloque de Contenido</button>

            <!-- Footer del Modal -->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="showCreateModal = false">Cerrar</button>
              <button type="submit" class="btn btn-primary">Crear Curso</button>
            </div>
          </form>
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

/* Estilos para hacer que el modal se pueda scrollear */
.modal-dialog-scrollable {
  max-height: 90vh; /* Ajusta la altura del modal */
}

.modal-body {
  overflow-y: auto; /* Habilita el scroll en la parte del cuerpo */
  max-height: 65vh; /* Limita la altura del cuerpo del modal */
  padding: 1.5rem;
}

/* Estilo general del modal */
.modal.show {
  display: block;
}

.modal-dialog {
  max-width: 600px;
}
</style>