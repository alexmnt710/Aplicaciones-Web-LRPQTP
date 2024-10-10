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
const showModal = ref(false);
const isEditing = ref(false);
const paginationData = ref({
  current_page: 1,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null
});

// Variables para crear/editar un curso
const newCurso = ref({
  cursoName: '',
  cursoDescripcion: '',
  cursoNivelId: '',
  cursoValor: '',
  cursoRequisito: '',
  cursoCategoriaId: '',
  createdBy: '',
  contenido: []
});

// Función para abrir el modal para crear un curso
const openCreateModal = () => {
  isEditing.value = false;
  resetForm();
  showModal.value = true;
};

// Función para abrir el modal para editar un curso

const openEditModal = (curso) => {
  isEditing.value = true;
  selectedCurso.value = curso;

  // Crear una copia profunda del curso seleccionado para evitar referencias y garantizar que contenido sea un array
  newCurso.value = {
    cursoName: curso.cursoName,
    cursoDescripcion: curso.cursoDescripcion,
    cursoNivelId: curso.cursoNivelId,
    cursoValor: curso.cursoValor,
    cursoRequisito: curso.cursoRequisito,
    cursoCategoriaId: curso.cursoCategoriaId,
    createdBy: curso.createdBy,
    contenido: Array.isArray(curso.cursoContenido) ? JSON.parse(JSON.stringify(curso.cursoContenido)) : [] // Validar que sea un array
  };

  showModal.value = true;
};


// Función para resetear el formulario
const resetForm = () => {
  newCurso.value = {
    cursoName: '',
    cursoDescripcion: '',
    cursoNivelId: '',
    cursoValor: '',
    cursoRequisito: '',
    cursoCategoriaId: '',
    createdBy: '',
    contenido: []
  };
};

// Función para añadir un nuevo bloque de contenido

const addContentBlock = () => {
  if (!Array.isArray(newCurso.value.contenido)) {
    newCurso.value.contenido = []; // Garantizar que siempre sea un array
  }
  newCurso.value.contenido.push({
    titulo: '',
    media: '',
    concepto: ''
  });
};

// Función para eliminar un bloque de contenido
const removeContentBlock = (index) => {
  if (Array.isArray(newCurso.value.contenido)) {
    newCurso.value.contenido.splice(index, 1);
  }
};


// Función para crear un curso
const createCurso = async () => {
  if (newCurso.value.cursoValor < 0) {
    sweetAlert.errorAlert('Número Inválido', 'El valor del curso no puede ser menor a 0.');
    return;
  }

  const formData = new FormData();
  formData.append('cursoName', newCurso.value.cursoName);
  formData.append('cursoDescripcion', newCurso.value.cursoDescripcion);
  formData.append('cursoNivelId', newCurso.value.cursoNivelId);
  formData.append('cursoValor', newCurso.value.cursoValor);
  formData.append('cursoRequisito', newCurso.value.cursoRequisito);
  formData.append('cursoCategoriaId', newCurso.value.cursoCategoriaId);
  formData.append('createdBy', sesionStore.user.userName);
  formData.append('cursoContenido', JSON.stringify(newCurso.value.contenido));

  // Mostrar en consola el contenido de formData
  for (let pair of formData.entries()) {
    console.log(pair[0] + ': ' + pair[1]);
  }

  try {
    const response = await cursoStore.crearCurso(sesionStore.token, formData);
    if (response.success) {
      sweetAlert.successAlert('Éxito', 'El curso se ha creado correctamente.');
      showModal.value = false;
      loadCursos();
    } else {
      sweetAlert.errorAlert('Error', response.message);
    }
  } catch (error) {
    sweetAlert.errorAlert('Error', 'Hubo un problema al crear el curso.');
  }
};

// Función para actualizar un curso
const updateCurso = async () => {
  // Construir el objeto JSON con los datos del curso
  const formData = {
    cursoName: newCurso.value.cursoName,
    cursoDescripcion: newCurso.value.cursoDescripcion,
    cursoNivelId: newCurso.value.cursoNivelId,
    cursoValor: newCurso.value.cursoValor,
    cursoRequisito: newCurso.value.cursoRequisito,
    cursoCategoriaId: newCurso.value.cursoCategoriaId,
    createdBy: newCurso.value.createdBy,
    cursoContenido: Array.isArray(newCurso.value.contenido) ? newCurso.value.contenido : []
  };

  // Mostrar en consola el contenido de formData para depuración
  console.log('Datos enviados:', formData);

  try {
    const response = await cursoStore.updateCurso(sesionStore.token, selectedCurso.value.cursoId, formData);
    if (response.success) {
      sweetAlert.successAlert('Éxito', 'El curso ha sido actualizado correctamente.');
      showModal.value = false;
      loadCursos();
    } else {
      // Extraer y concatenar errores
      let errorMessages = response.message; // Mensaje general
      if (response.errors) {
        // Extraer los errores del objeto 'errors'
        errorMessages += '\n' + Object.keys(response.errors).map(key => `${response.errors[key].join(', ')}`).join('\n');
      }
      
      sweetAlert.errorAlert('Error', errorMessages);
    }
  } catch (error) {
    console.error('Error updating curso:', error);
    sweetAlert.errorAlert('Error', 'Hubo un problema al actualizar el curso.');
  }
};


// Función para guardar el curso (creación o actualización)
const saveCurso = () => {
  if (isEditing.value) {
    updateCurso();
  } else {
    createCurso();
  }
};

// Función para cargar cursos
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

// Función para eliminar un curso
const deleteCurso = async (cursoId) => {
  const confirm = await sweetAlert.confirmAlert('Eliminar curso', '¿Estás seguro de que deseas eliminar este curso?');
  if (confirm) {
    try {
      await cursoStore.deleteCurso(sesionStore.token, cursoId);
      sweetAlert.successAlert('Éxito', 'El curso ha sido eliminado.');
      loadCursos();
    } catch (error) {
      sweetAlert.errorAlert('Error', 'Hubo un problema al eliminar el curso.');
    }
  }
};

onMounted(async () => {
  const closeLoading = sweetAlert.ShowLoading();
  await loadCursos();
  console.log('Cursos:', cursos.value);
  await categoriaStore.getCategoria();
  console.log(categoriaStore.categorianormal);
  await categoriaStore.getNiveles();
  closeLoading();
});
</script>

<template>
  <Header />

  <div class="crud-container">
    <h2 class="crud-title">Gestión de Cursos</h2>

    <div class="action-bar">
      <button class="btn btn-primary" @click="openCreateModal">
        <i class="bi bi-plus-circle"></i> Crear Curso
      </button>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
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
              <button class="btn btn-warning btn-sm" @click="openEditModal(curso)">
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

    <!-- Modal para crear/editar un curso -->
    <div v-if="showModal" class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0, 0, 0, 0.5);">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEditing ? 'Editar Curso' : 'Crear Curso' }}</h5>
            <button type="button" class="btn-close" @click="showModal = false"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveCurso">
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
                  <option v-for="categoria in categoriaStore.categorianormal" :key="categoria.categoriaId" :value="categoria.categoriaId">
                    {{ categoria.categoriaName }}
                  </option>
                </select>
              </div>

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

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="showModal = false">Cerrar</button>
                <button type="submit" class="btn btn-primary">{{ isEditing ? 'Actualizar' : 'Crear' }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <Footer />
</template>

<style scoped>
/* Contenedor principal */
.crud-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  background-color: #f8f9fa; /* Fondo más claro para la sección del CRUD */
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Título del CRUD */
.crud-title {
  text-align: center;
  font-size: 2rem;
  color: #0f3d28;
  margin-bottom: 2rem;
}

/* Barra de acciones */
.action-bar {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 1.5rem;
}

/* Tabla */
.table-responsive {
  margin-top: 1rem;
}

.table {
  background-color: white;
}

.table th, .table td {
  text-align: center;
  vertical-align: middle;
  padding: 1rem;
}

.table th {
  background-color: #0f3d28;
  color: white;
}

.table td {
  background-color: #f0f7f4;
}

/* Botones */
.btn-sm {
  padding: 0.4rem 0.8rem;
  font-size: 0.9rem;
}

/* Modal */
.modal-dialog-scrollable {
  max-height: 90vh;
}

.modal-body {
  overflow-y: auto;
  max-height: 65vh;
  padding: 1.5rem;
}

.modal.show {
  display: block;
}

.modal-dialog {
  max-width: 600px;
}

@media (min-width: 768px) {
  .modal-dialog {
    max-width: 600px;
  }
}
</style>
