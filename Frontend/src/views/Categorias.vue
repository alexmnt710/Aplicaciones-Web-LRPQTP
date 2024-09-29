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
  categoriaImagen: ''
});

// Función para cargar categorías
const loadCategorias = async () => {
  await categoriaStore.getCategorias();
  categorias.value = categoriaStore.categoria;
};

// Función para abrir el modal de creación/edición de categorías
const openModal = (categoria = null) => {
  isEditing.value = !!categoria;
  selectedCategoria.value = categoria;
  if (categoria) {
    newCategoria.value = { ...categoria }; // Llenar con datos de la categoría seleccionada
  } else {
    newCategoria.value = { categoriaName: '', categoriaDescripcion: '', categoriaImagen: '' };
  }
  showModal.value = true;
};

// Función para crear o editar una categoría
const saveCategoria = async () => {
  try {
    const formData = {
      categoriaName: newCategoria.value.categoriaName,
      categoriaDescripcion: newCategoria.value.categoriaDescripcion,
      categoriaImagen: newCategoria.value.categoriaImagen
    };

    if (isEditing.value) {
      // Editar categoría
      console.log('formData', formData);
      const response = await categoriaStore.updateCategoria(sesionStore.token, formData, selectedCategoria.value.categoriaId);
      if (response.success == true) {
        sweetAlert.successAlert('Éxito', 'La categoría ha sido actualizada correctamente.');
      }else{
        sweetAlert.errorAlert('Error', response.message);
      }
    } else {
      // Crear nueva categoría
      console.log('formData', formData);
      const response = await categoriaStore.crearCategoria(sesionStore.token, formData);
      if (response.success == true) {
      sweetAlert.successAlert('Éxito', response.message);
      }
    }
    showModal.value = false;
    loadCategorias();
  } catch (error) {
    sweetAlert.errorAlert('Error', 'Hubo un problema al guardar la categoría.');
  }
};

// Función para eliminar una categoría
const deleteCategoria = async (categoriaId) => {
  const confirm = await sweetAlert.confirmAlert('Confirmar', '¿Estás seguro de que deseas eliminar esta categoría?');
  if (confirm) {
    await categoriaStore.deleteCategoria(sesionStore.token, categoriaId);
    sweetAlert.successAlert('Éxito', 'La categoría ha sido eliminada correctamente.');
    loadCategorias();
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
      <button class="btn btn-primary" @click="openModal">Crear Categoría</button>
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
            <button class="btn btn-primary btn-sm mx-1" @click="openModal(categoria)">Editar</button>
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
                <label for="categoriaImagen" class="form-label">Imagen (URL)</label>
                <input v-model="newCategoria.categoriaImagen" type="text" id="categoriaImagen" class="form-control">
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
