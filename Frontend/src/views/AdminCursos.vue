<script setup>
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import Pagination from '../components/Pagination.vue'; 
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
const selectedCurso = ref(null);
const paginationData = ref({
  current_page: 1,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null
});

const showModal = ref(false); 
const showCreateModal = ref(false);
const showUpdateModal = ref(false);  // Modal para actualizar curso

// Variables para crear o actualizar un curso
const newCurso = ref({
  cursoName: '',
  cursoDescripcion: '',
  cursoNivelId: '',
  cursoValor: '',
  cursoRequisito: '',
  cursoCategoriaId: '',
  contenido: [] 
});

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

// Abrir modal para creación de curso
const openCreateModal = () => {
  limpiarFormulario();
  showCreateModal.value = true;
};

// Cerrar cualquier modal
const closeModal = () => {
  showCreateModal.value = false;
  showUpdateModal.value = false; 
};

// Limpiar el formulario de curso
const limpiarFormulario = () => {
  newCurso.value = {
    cursoName: '',
    cursoDescripcion: '',
    cursoNivelId: '',
    cursoValor: '',
    cursoRequisito: '',
    cursoCategoriaId: '',
    contenido: []
  };
};

// Función para crear curso
const createCurso = async () => {
  if (newCurso.value.cursoValor < 0) {
    sweetAlert.errorAlert('Número Inválido', 'El valor del curso no puede ser menor a 0.');
    return;
  }

  try {
    const formData = new FormData();
    formData.append('cursoName', newCurso.value.cursoName);
    formData.append('cursoDescripcion', newCurso.value.cursoDescripcion);
    formData.append('cursoNivelId', newCurso.value.cursoNivelId);
    formData.append('cursoValor', newCurso.value.cursoValor);
    formData.append('cursoRequisito', newCurso.value.cursoRequisito);
    formData.append('cursoCategoriaId', newCurso.value.cursoCategoriaId);
    formData.append('cursoContenido', JSON.stringify(newCurso.value.contenido));
    formData.append('createdBy', sesionStore.user.userName);

    await cursoStore.crearCurso(sesionStore.token, formData);
    sweetAlert.successAlert('Éxito', 'El curso se ha creado correctamente.');
    closeModal();
    loadCursos();
  } catch (error) {
    sweetAlert.errorAlert('Error', 'Hubo un problema al crear el curso.');
  }
};

// Función para cargar datos de un curso al modal de actualización
// Función para cargar datos de un curso al modal de actualización
const openUpdateModal = (curso) => {
  selectedCurso.value = curso;

  // Verificar si cursoContenido ya es un objeto o es un JSON válido
  let parsedContenido;
  try {
    parsedContenido = typeof curso.cursoContenido === 'string' 
      ? JSON.parse(curso.cursoContenido) 
      : curso.cursoContenido;
  } catch (e) {
    console.error('Error parsing cursoContenido:', e);
    parsedContenido = []; // Si hay error, inicializamos con un array vacío
  }

  // Cargar los datos en el formulario
  newCurso.value = { 
    ...curso, 
    contenido: parsedContenido || [] 
  };

  // Mostrar el modal de actualización
  showUpdateModal.value = true;
};


// Función para actualizar el curso
const updateCurso = async () => {
  if (newCurso.value.cursoValor < 0) {
    sweetAlert.errorAlert('Número Inválido', 'El valor del curso no puede ser menor a 0.');
    return;
  }

  try {
    const formData = new FormData();
    formData.append('cursoName', newCurso.value.cursoName);
    formData.append('cursoDescripcion', newCurso.value.cursoDescripcion);
    formData.append('cursoNivelId', newCurso.value.cursoNivelId);
    formData.append('cursoValor', newCurso.value.cursoValor);
    formData.append('cursoRequisito', newCurso.value.cursoRequisito);
    formData.append('cursoCategoriaId', newCurso.value.cursoCategoriaId);
    formData.append('cursoContenido', JSON.stringify(newCurso.value.contenido));

    await cursoStore.updateCurso(sesionStore.token, selectedCurso.value.cursoId, formData);
    sweetAlert.successAlert('Éxito', 'El curso se ha actualizado correctamente.');
    closeModal();
    loadCursos();
  } catch (error) {
    sweetAlert.errorAlert('Error', 'Hubo un problema al actualizar el curso.');
  }
};

// Función para eliminar curso
const deleteCurso = async (cursoId) => {
  try {
    const confirm = await sweetAlert.confirmAlert('Eliminar curso', '¿Estás seguro de que deseas eliminar este curso?');
    if (confirm.isConfirmed) {
      await cursoStore.deleteCurso(sesionStore.token, cursoId);
      sweetAlert.successAlert('Éxito', 'El curso ha sido eliminado.');
      loadCursos();
    }
  } catch (error) {
    sweetAlert.errorAlert('Error', 'Hubo un problema al eliminar el curso.');
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

  <div class="container-fluid mt-5">
    <h2 class="text-center">Gestión de Cursos</h2>

    <div class="d-flex justify-content-end mb-3">
      <button class="btn btn-primary" @click="openCreateModal">Crear Curso</button>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="thead-dark">
          <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Categoría</th>
            <th>Creador</th>
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
            <td>{{ curso.createdBy }}</td>
            <td>
              <button class="btn btn-primary btn-sm" @click="openUpdateModal(curso)">
                <i class="bi bi-pencil"></i> Editar
              </button>
              <button class="btn btn-danger btn-sm" @click="deleteCurso(curso.cursoId)">
                <i class="bi bi-trash"></i> Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Pagination
      :currentPage="paginationData.current_page"
      :lastPage="paginationData.last_page"
      :prevPageUrl="paginationData.prev_page_url"
      :nextPageUrl="paginationData.next_page_url"
      @pageChange="loadCursos"
    />

    <!-- Modal para crear curso -->
    <div v-if="showCreateModal" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.5);">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Crear Curso</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
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
              <div class="mb-3">
                <label for="cursoNivelId" class="form-label">Nivel del Curso</label>
                <select v-model="newCurso.cursoNivelId" id="cursoNivelId" class="form-control" required>
                  <option value="" disabled>Seleccione un nivel</option>
                  <option v-for="nivel in categoriaStore.nivel" :key="nivel.nivelId" :value="nivel.nivelId">{{ nivel.nivelName }}</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="cursoValor" class="form-label">Valor del Curso</label>
                <input v-model="newCurso.cursoValor" type="number" id="cursoValor" class="form-control" required min="0">
              </div>
              <div class="mb-3">
                <label for="cursoRequisito" class="form-label">Requisito</label>
                <textarea v-model="newCurso.cursoRequisito" id="cursoRequisito" class="form-control" required></textarea>
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
              <button type="submit" class="btn btn-primary">Guardar Curso</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para actualizar curso -->
    <div v-if="showUpdateModal" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.5);">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Actualizar Curso</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateCurso">
              <!-- Campos iguales a los del formulario de crear curso -->
              <div class="mb-3">
                <label for="cursoName" class="form-label">Nombre del Curso</label>
                <input v-model="newCurso.cursoName" type="text" id="cursoName" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="cursoDescripcion" class="form-label">Descripción</label>
                <textarea v-model="newCurso.cursoDescripcion" id="cursoDescripcion" class="form-control" required></textarea>
              </div>
              <div class="mb-3">
                <label for="cursoNivelId" class="form-label">Nivel del Curso</label>
                <select v-model="newCurso.cursoNivelId" id="cursoNivelId" class="form-control" required>
                  <option value="" disabled>Seleccione un nivel</option>
                  <option v-for="nivel in categoriaStore.nivel" :key="nivel.nivelId" :value="nivel.nivelId">{{ nivel.nivelName }}</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="cursoValor" class="form-label">Valor del Curso</label>
                <input v-model="newCurso.cursoValor" type="number" id="cursoValor" class="form-control" required min="0">
              </div>
              <div class="mb-3">
                <label for="cursoRequisito" class="form-label">Requisito</label>
                <textarea v-model="newCurso.cursoRequisito" id="cursoRequisito" class="form-control" required></textarea>
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
              <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <Footer />
</template>


<style scoped>
.table-responsive {
  margin-top: 1rem;
}

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
  max-width: 100%;
}

@media (min-width: 768px) {
  .modal-dialog {
    max-width: 600px;
  }
}
</style>
