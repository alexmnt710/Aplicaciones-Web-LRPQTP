<script setup>
import { ref, onMounted } from 'vue';
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import Pagination from '../components/Pagination.vue';
import { Sesion } from '../store/sesion';

const sesionStore = Sesion(); 

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

const docentes = ref([]); // Lista de docentes
const paginationData = ref({
  current_page: 1,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null,
});

// Funciones para abrir y cerrar el modal
const openModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

// Función para enviar datos (actualmente solo muestra en consola)
const submitForm = () => {
  const token = sesionStore.token; // Obtener el token de la sesión
  const formDataObj = new FormData();
  
  // Añadir los campos al FormData
  formDataObj.append('userNombres', formData.value.userNombres);
  formDataObj.append('userApellidos', formData.value.userApellidos);
  formDataObj.append('userName', formData.value.userName);
  formDataObj.append('userCorreo', formData.value.userCorreo);
  formDataObj.append('password', formData.value.password);
  formDataObj.append('confirmPassword', formData.value.confirmPassword);
  formDataObj.append('userWordKey', formData.value.userWordKey);

  // Mostrar el token y el contenido del FormData en la consola
  console.log('Token:', token);
  for (let pair of formDataObj.entries()) {
    console.log(`${pair[0]}: ${pair[1]}`);
  }
};

// Cargar la lista de docentes
const loadDocentes = async (page = 1) => {
  try {
    await docentesStore.getDocentes(page); // Método que trae la lista de docentes desde el store
    docentes.value = docentesStore.docentes.data; // Asignar los docentes al array

    // Configurar los datos de la paginación
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
const deleteDocente = async (docenteId) => {
  const confirm = await sweetAlert.confirmAlert('Eliminar docente', '¿Estás seguro de que deseas eliminar este docente?');
  if (confirm) {
    try {
      await docentesStore.deleteDocente(docenteId); // Método del store para eliminar un docente
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
  loadDocentes();
});
</script>

<template>
  <Header />

  <!-- Modal para crear un docente  -->
  <div class="container mt-5">
    <button class="btn btn-primary" @click="openModal">Crear Docente</button>

    <div v-if="showModal" class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0, 0, 0, 0.5);">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Ingrese al Nuevo Docente</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitForm">
              <div class="row mb-3">
                <div class="col">
                  <label for="userNombres" class="form-label">Nombres</label>
                  <input v-model="formData.userNombres" type="text" id="userNombres" class="form-control" required />
                </div>
                <div class="col">
                  <label for="userApellidos" class="form-label">Apellidos</label>
                  <input v-model="formData.userApellidos" type="text" id="userApellidos" class="form-control" required />
                </div>
              </div>
              <div class="mb-3">
                <label for="userName" class="form-label">Nombre de Usuario</label>
                <input v-model="formData.userName" type="text" id="userName" class="form-control" required />
              </div>
              <div class="mb-3">
                <label for="userCorreo" class="form-label">Correo</label>
                <input v-model="formData.userCorreo" type="email" id="userCorreo" class="form-control" required />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input v-model="formData.password" type="password" id="password" class="form-control" required />
              </div>
              <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirmar Contraseña</label>
                <input v-model="formData.confirmPassword" type="password" id="confirmPassword" class="form-control" required />
              </div>
              <div class="mb-3">
                <label for="userWordKey" class="form-label">Palabra Clave</label>
                <input v-model="formData.userWordKey" type="text" id="userWordKey" class="form-control" required />
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeModal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Enviar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Lista de docentes -->
  <div class="container-fluid mt-5">
    <h2 class="text-center">Gestión de Docentes</h2>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="thead-dark">
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
            <td>{{ docente.id }}</td>
            <td>{{ docente.userName }}</td>
            <td>{{ docente.nombres }} {{ docente.apellidos }}</td>
            <td>{{ docente.correoElectronico }}</td>
            <td>
              <button class="btn btn-primary btn-sm">
                <i class="bi bi-pencil"></i> Editar
              </button>
              <button class="btn btn-danger btn-sm" @click="deleteDocente(docente.id)">
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

  <Footer />
</template>

<style scoped>
.modal.show {
  display: block;
}
.modal-dialog {
  max-width: 600px;
}
</style>
