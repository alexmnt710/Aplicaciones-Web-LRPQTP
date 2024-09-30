<script setup>
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { Categoria } from '../store/categoria';
import { ref, onMounted } from 'vue';
import { sweetalert } from '../composables/sweetAlert';
import { Sesion } from '../store/sesion';

const categoriaStore = Categoria();
const sesionStore = Sesion();
const sweetAlert = sweetalert();

const categorias = ref([]); // Lista de categorías
const showModal = ref(false);
const isEditing = ref(false);
const selectedCategoria = ref(null);

// Variables para crear/editar una categoría
const newCategoria = ref({
  categoriaName: '',
  categoriaDescripcion: '',
  categoriaImagenUrl: '' // URL para la imagen
});

// --- Funciones CRUD ---

// Función para cargar todas las categorías
const loadCategorias = async () => {
  try {
    await categoriaStore.getCategorias();
    categorias.value = categoriaStore.categoria;
  } catch (error) {
    console.error('Error loading categories:', error);
  }
};

// Función para abrir el modal para crear una categoría
const openCreateModal = () => {
  isEditing.value = false;
  resetForm();
  showModal.value = true;
};

// Función para abrir el modal para editar una categoría
const openEditModal = (categoria) => {
  isEditing.value = true;
  selectedCategoria.value = categoria;
  newCategoria.value = {
    categoriaName: categoria.categoriaName,
    categoriaDescripcion: categoria.categoriaDescripcion,
    categoriaImagenUrl: categoria.categoriaImagen // Prellenar con la URL existente
  };
  showModal.value = true;
};

// Función para resetear el formulario
const resetForm = () => {
  newCategoria.value = {
    categoriaName: '',
    categoriaDescripcion: '',
    categoriaImagenUrl: ''
  };
};

// Función para crear una nueva categoría
const createCategoria = async () => {
  const formData = new FormData();
  formData.append('categoriaName', newCategoria.value.categoriaName);
  formData.append('categoriaDescripcion', newCategoria.value.categoriaDescripcion);
  formData.append('categoriaImagenUrl', newCategoria.value.categoriaImagenUrl); // Añadir URL de la imagen

  try {
    const response = await categoriaStore.crearCategoria(sesionStore.token, formData);
    if (response.success) {
      sweetAlert.successAlert('Éxito', 'La categoría ha sido creada correctamente.');
      showModal.value = false;
      loadCategorias();
    } else {
      sweetAlert.errorAlert('Error', response.message);
    }
  } catch (error) {
    console.error('Error creating category:', error);
    sweetAlert.errorAlert('Error', 'Hubo un problema al crear la categoría.');
  }
};

// Función para actualizar una categoría
const updateCategoria = async () => {
  const formData = new FormData();
  formData.append('categoriaName', newCategoria.value.categoriaName);
  formData.append('categoriaDescripcion', newCategoria.value.categoriaDescripcion);
  formData.append('categoriaImagenUrl', newCategoria.value.categoriaImagenUrl); // Añadir URL de la imagen

  try {
    const response = await categoriaStore.updateCategoria(sesionStore.token, formData, selectedCategoria.value.categoriaId);
    if (response.success) {
      sweetAlert.successAlert('Éxito', 'La categoría ha sido actualizada correctamente.');
      showModal.value = false;
      loadCategorias();
    } else {
      sweetAlert.errorAlert('Error', response.message);
    }
  } catch (error) {
    console.error('Error updating category:', error);
    sweetAlert.errorAlert('Error', 'Hubo un problema al actualizar la categoría.');
  }
};

// Función para guardar la categoría (creación o actualización)
const saveCategoria = () => {
  if (isEditing.value) {
    updateCategoria();
  } else {
    createCategoria();
  }
};

// Función para eliminar una categoría
const deleteCategoria = async (categoriaId) => {
  const confirm = await sweetAlert.confirmAlert('Confirmar', '¿Estás seguro de que deseas eliminar esta categoría?');
  if (confirm) {
    try {
      await categoriaStore.deleteCategoria(sesionStore.token, categoriaId);
      sweetAlert.successAlert('Éxito', 'La categoría ha sido eliminada correctamente.');
      loadCategorias();
    } catch (error) {
      console.error('Error deleting category:', error);
      sweetAlert.errorAlert('Error', 'Hubo un problema al eliminar la categoría.');
    }
  }
};

onMounted(() => {
  loadCategorias();
});
</script>

<template>
  <Header />

  <div class="container mt-5">
    <h2>Gestión de Categorías</h2>

    <!-- Botón para abrir el modal de crear categoría -->
    <div class="mb-3">
      <button class="btn btn-primary" @click="openCreateModal">Crear Categoría</button>
    </div>

    <!-- Tabla de categorías -->
    <table class="table table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Imagen</th>
          <th>Creada en</th>
          <th>Actualizada en</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="categoria in categorias" :key="categoria.categoriaId">
          <td>{{ categoria.categoriaId }}</td>
          <td>{{ categoria.categoriaName }}</td>
          <td>{{ categoria.categoriaDescripcion }}</td>
          <td><img :src="categoria.categoriaImagen" alt="Imagen" width="50"></td>
          <td>{{ categoria.created_at }}</td>
          <td>{{ categoria.updated_at }}</td>
          <td>
            <button class="btn btn-primary btn-sm mx-1" @click="openEditModal(categoria)">Editar</button>
            <button class="btn btn-danger btn-sm mx-1" @click="deleteCategoria(categoria.categoriaId)">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal para crear/editar categoría -->
    <div v-if="showModal" class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0, 0, 0, 0.5);">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEditing ? 'Editar Categoría' : 'Crear Categoría' }}</h5>
            <button type="button" class="btn-close" @click="showModal = false"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveCategoria">
              <div class="mb-3">
                <label for="categoriaName" class="form-label">Nombre de la Categoría</label>
                <input v-model="newCategoria.categoriaName" type="text" id="categoriaName" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="categoriaDescripcion" class="form-label">Descripción</label>
                <textarea v-model="newCategoria.categoriaDescripcion" id="categoriaDescripcion" class="form-control" required></textarea>
              </div>
              <div class="mb-3">
                <label for="categoriaImagenUrl" class="form-label">URL de la Imagen</label>
                <input v-model="newCategoria.categoriaImagenUrl" type="text" id="categoriaImagenUrl" class="form-control" required>
              </div>
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
.table th, .table td {
  text-align: center;
  vertical-align: middle;
}

.modal.show {
  display: block;
}

.modal-dialog {
  max-width: 600px;
}
</style>
