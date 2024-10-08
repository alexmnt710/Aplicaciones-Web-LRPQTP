<script setup>
import { ref, onMounted } from 'vue';
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import Pagination from '../components/Pagination.vue';
import { sweetalert } from '../composables/sweetAlert';
import { Sesion } from '../store/sesion';
import { User } from '../store/users';

const sesionStore = Sesion();
const docentesStore = User();
const sweetAlert = sweetalert();
const token = sesionStore.token;

const formData = ref({
  userNombres: '',
  userApellidos: '',
  userName: '',
  userCorreo: '',
  password: '',
  confirmPassword: '',
  userWordKey: ''
});

const showModal = ref(false);
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const passwordMatch = ref(true);
const isEditing = ref(false); // Variable para controlar si estamos editando
const selectedDocenteId = ref(null); // Almacenar el ID del docente seleccionado

const docentes = ref([]);
const paginationData = ref({
  current_page: 1,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null,
});

// Funciones para abrir y cerrar el modal
const openModal = () => {
  showModal.value = true;
  isEditing.value = false; // Resetear a estado de creación
  resetForm();
};

const openEditModal = (docente) => {
  showModal.value = true;
  isEditing.value = true; // Establecer a estado de edición
  selectedDocenteId.value = docente.userId; // Almacenar el ID del docente seleccionado

  // Llenar el formulario con los datos del docente seleccionado
  formData.value = {
    userNombres: docente.userNombres,
    userApellidos: docente.userApellidos,
    userName: docente.userName,
    userCorreo: docente.userCorreo,
    password: '',
    confirmPassword: '',
    userWordKey: docente.userWordKey

  };
};

const closeModal = () => {
  showModal.value = false;
  resetForm(); // Limpiar el formulario al cerrar el modal
};

// Función para verificar si las contraseñas coinciden
const checkPasswordMatch = () => {
  passwordMatch.value = formData.value.password === formData.value.confirmPassword;
};

// Resetear el formulario
const resetForm = () => {
  formData.value = {
    userNombres: '',
    userApellidos: '',
    userName: '',
    userCorreo: '',
    password: '',
    confirmPassword: '',
    userWordKey: ''
  };
  selectedDocenteId.value = null; // Limpiar el ID del docente seleccionado
};

// Función para guardar el docente (creación o actualización)
const saveDocente = () => {
  if (isEditing.value) {
    updateDocente();
  } else {
    createDocente();
  }
};


// Función para enviar datos (crear o editar)
// Función para crear un docente
const createDocente = async () => {
  checkPasswordMatch();
  if (!passwordMatch.value) {
    sweetAlert.errorAlert('Error', 'Las contraseñas no coinciden.');
    return;
  }

  const formDataObj = new FormData();
  formDataObj.append('userNombres', formData.value.userNombres || '');
  formDataObj.append('userApellidos', formData.value.userApellidos || '');
  formDataObj.append('userName', formData.value.userName || '');
  formDataObj.append('userCorreo', formData.value.userCorreo || '');
  formDataObj.append('password', formData.value.password || '');
  formDataObj.append('confirmPassword', formData.value.confirmPassword || '');
  formDataObj.append('userWordKey', formData.value.userWordKey || '');

  try {
    const response = await docentesStore.createDocente(token, formDataObj);
    if (response.success) {
      sweetAlert.successAlert('Éxito', 'El docente ha sido creado correctamente.');
      showModal.value = false;
      loadDocentes();
    } else {
      const errorMessages = Object.values(response.errors).flat().map(error => `<p>${error}</p>`).join('');
      sweetAlert.errorAlert('Error en el registro', `Por favor revise los campos: ${errorMessages}`, true);
    }
  } catch (error) {
    console.error('Error:', error);
    sweetAlert.errorAlert('Error', 'Hubo un problema al crear el docente.');
  }
};

// Función para actualizar un docente
// Función para actualizar un docente
const updateDocente = async () => {
  checkPasswordMatch();
  if (!passwordMatch.value) {
    sweetAlert.errorAlert('Error', 'Las contraseñas no coinciden.');
    return;
  }

  // Construir el objeto JSON con los datos del docente
  const updatedData = {
    userNombres: formData.value.userNombres || '',
    userApellidos: formData.value.userApellidos || '',
    userName: formData.value.userName || '',
    userCorreo: formData.value.userCorreo || '',
    password: formData.value.password || '',
    confirmPassword: formData.value.confirmPassword || '',
    userWordKey: formData.value.userWordKey || ''
  };
  console.log('token:', token);
  console.log('Datos enviados:', updatedData);
  console.log('Id:', selectedDocenteId.value);

  try {
    const response = await docentesStore.updateDocente(token, updatedData, selectedDocenteId.value);
    if (response.success) {
      sweetAlert.successAlert('Éxito', 'El docente ha sido actualizado correctamente.');
      showModal.value = false;
      loadDocentes();
    } else {
      const errorMessages = Object.values(response.errors).flat().map(error => `<p>${error}</p>`).join('');
      sweetAlert.errorAlert('Error en la actualización', `Por favor revise los campos: ${errorMessages}`, true);
    }
  } catch (error) {
    console.error('Error:', error);
    sweetAlert.errorAlert('Error', 'Hubo un problema al actualizar el docente.');
  }
};





// Cargar la lista de docentes
const loadDocentes = async (page = 1) => {
  try {
    await docentesStore.getDocentes(token, page);
    docentes.value = docentesStore.docentes.data;
    paginationData.value.current_page = docentesStore.docentes.current_page;
    paginationData.value.last_page = docentesStore.docentes.last_page;
    paginationData.value.prev_page_url = docentesStore.docentes.prev_page_url;
    paginationData.value.next_page_url = docentesStore.docentes.next_page_url;
  } catch (error) {
    console.error('Error al cargar los docentes:', error);
    sweetAlert.errorAlert('Error', 'Hubo un problema al cargar la lista de docentes.');
  }
};

// Función para manejar el cambio de página
const handlePageChange = (page) => {
  loadDocentes(page);
};

// Función para eliminar un docente
const deleteDocente = async (userId) => {
  const confirm = await sweetAlert.confirmAlert('Eliminar docente', '¿Estás seguro de que deseas eliminar este docente?');
  if (confirm) {
    try {
      await docentesStore.deleteDocente(token, userId);
      sweetAlert.successAlert('Éxito', 'El docente ha sido eliminado correctamente.');
      loadDocentes();
    } catch (error) {
      console.error('Error al eliminar el docente:', error);
      sweetAlert.errorAlert('Error', 'Hubo un problema al eliminar el docente.');
    }
  }
};

// Cargar los docentes cuando el componente se monte
onMounted(() => {
  const closeLoading = sweetAlert.ShowLoading();
  loadDocentes();
  closeLoading();
});
</script>

<template>
  <Header />

  <!-- Contenedor principal con un ancho máximo -->
  <div class="container mt-5">
    <div class="content-box">
      <!-- Título -->
      <h2 class="text-center">Gestión de Docentes</h2>

      <!-- Barra de acciones para crear docente -->
      <div class="action-bar mb-4 text-end">
        <button class="btn btn-primary" @click="openModal">
          <i class="bi bi-plus-circle"></i> Crear Docente
        </button>
      </div>

      <!-- Modal para crear/editar un docente -->
      <div v-if="showModal" class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ isEditing ? 'Editar Docente' : 'Crear Docente' }}</h5>
              <button type="button" class="btn-close" @click="closeModal"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="saveDocente">
                <div class="mb-3">
                  <label for="userNombres" class="form-label">Nombres</label>
                  <input v-model="formData.userNombres" type="text" id="userNombres" class="form-control" required />
                </div>
                <div class="mb-3">
                  <label for="userApellidos" class="form-label">Apellidos</label>
                  <input v-model="formData.userApellidos" type="text" id="userApellidos" class="form-control" required />
                </div>
                <div class="mb-3">
                  <label for="userName" class="form-label">Nombre de Usuario</label>
                  <input v-model="formData.userName" type="text" id="userName" class="form-control" required />
                </div>
                <div class="mb-3">
                  <label for="userCorreo" class="form-label">Correo Electrónico</label>
                  <input v-model="formData.userCorreo" type="email" id="userCorreo" class="form-control" required />
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Contraseña</label>
                  <div class="input-group">
                    <input :type="showPassword ? 'text' : 'password'" v-model="formData.password" id="password" class="form-control" @input="checkPasswordMatch" />
                    <button class="btn btn-outline-secondary" type="button" @click="showPassword = !showPassword">
                      <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                    </button>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="confirmPassword" class="form-label">Confirmar Contraseña</label>
                  <div class="input-group">
                    <input :type="showConfirmPassword ? 'text' : 'password'" v-model="formData.confirmPassword" id="confirmPassword" class="form-control" @input="checkPasswordMatch" />
                    <button class="btn btn-outline-secondary" type="button" @click="showConfirmPassword = !showConfirmPassword">
                      <i :class="showConfirmPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                    </button>
                  </div>
                  <div v-if="!passwordMatch" class="text-danger">Las contraseñas no coinciden</div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" @click="closeModal">Cerrar</button>
                  <button type="submit" class="btn btn-primary">{{ isEditing ? 'Actualizar' : 'Crear' }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Lista de docentes -->
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre de Usuario</th>
              <th>Nombres</th>
              <th>Correo Electrónico</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="docente in docentes" :key="docente.id">
              <td>{{ docente.userId }}</td>
              <td>{{ docente.userName }}</td>
              <td>{{ docente.userNombres }} {{ docente.userApellidos }}</td>
              <td>{{ docente.userCorreo }}</td>
              <td>
                <button class="btn btn-warning btn-sm mx-1" @click="openEditModal(docente)">
                  <i class="bi bi-pencil"></i> Editar
                </button>
                <button class="btn btn-danger btn-sm mx-1" @click="deleteDocente(docente.userId)">
                  <i class="bi bi-trash"></i> Eliminar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Componente de Paginación -->
      <Pagination
        :currentPage="paginationData.current_page"
        :lastPage="paginationData.last_page"
        :prevPageUrl="paginationData.prev_page_url"
        :nextPageUrl="paginationData.next_page_url"
        @pageChange="handlePageChange"
      />
    </div>
  </div>

  <Footer />
</template>

<style scoped>
/* Contenedor principal */
.container {
  max-width: 1200px;
  margin: 0 auto;
}

/* Contenedor de contenido */
.content-box {
  background-color: #ffffff;
  padding: 2rem;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
.modal.show {
  display: block;
}

.modal-dialog {
  max-width: 600px;
}

.modal-dialog-scrollable {
  max-height: 90vh;
}

.modal-body {
  overflow-y: auto;
  max-height: 65vh;
  padding: 1.5rem;
}
</style>
