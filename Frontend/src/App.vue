<script setup>
import { RouterView, useRouter } from 'vue-router';
import { ref, onMounted } from 'vue';
import { Sesion } from './store/sesion';
import { sweetalert } from './composables/sweetAlert'; // Importa correctamente tu archivo de SweetAlert
import { Categoria } from './store/categoria';
import { Cursos } from './store/cursos';

const sesionStore = Sesion();
const categoriaStore = Categoria();
const cursoStore = Cursos();
const sweetAlert = sweetalert(); // Llama a la función sweetalert
const router = useRouter();

// Bandera para controlar el renderizado del componente hijo
const isChildMounted = ref(false);

onMounted(async () => {
  const closeLoading = sweetAlert.ShowLoading();

  try {
    // Ejecuta las llamadas en paralelo
    await Promise.all([sesionStore.getSesion(), categoriaStore.getCategorias(),cursoStore.getCursosHome()]);
  } catch (error) {
    sweetAlert.errorAlert('Error', 'Error al obtener la sesión');
    console.error(error);
  } finally {
    closeLoading();
    isChildMounted.value = true;
  }
});
</script>

<template>
  <div class="app" style="height: 100vh">
    <!-- Mostrar RouterView solo cuando isChildMounted sea true -->
    <RouterView v-if="isChildMounted" />
  </div>
</template>

<style scoped>
.app {
  height: 100%;
  margin: 0;
  padding: 0;
  color: rgb(52, 143, 213);
}
</style>

