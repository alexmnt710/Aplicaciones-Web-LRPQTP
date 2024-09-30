<script setup>
import { RouterView, useRouter } from 'vue-router';
import { ref, onMounted } from 'vue';
import { Sesion } from './store/sesion';
import { sweetalert } from './composables/sweetAlert'; // Importa correctamente tu archivo de SweetAlert
import { Categoria } from './store/categoria';

const sesionStore = Sesion();
const categoriaStore = Categoria();
const sweetAlert = sweetalert(); // Llama a la función sweetalert
const router = useRouter();

// Bandera para controlar el renderizado del componente hijo
const isChildMounted = ref(false);

onMounted(async () => {
  // Mostrar alerta de cargando
  const closeLoading = sweetAlert.ShowLoading();

  try {
    // Esperar a que se obtenga la sesión
    await sesionStore.getSesion();
    await categoriaStore.getCategorias();
    // console.log (sesionStore.rol);
    // console.log (sesionStore.user.userName);
    // console.log (categoriaStore.categoria)

    // Simula alguna operación adicional, por ejemplo cargar categorías
    console.log('Operación adicional realizada.');
  } catch (error) {
    // Mostrar alerta de error en caso de fallo
    sweetAlert.errorAlert('Error', 'Error al obtener la sesión');
    console.error(error);
  } finally {
    // Cerrar la alerta de cargando cuando termine la operación
    closeLoading();

    // Permitir que se monte el componente hijo (RouterView)
    isChildMounted.value = true;
  }
});
</script>

<template>
  <div class="app" style="height: 100vh">
    <!-- Solo muestra RouterView cuando isChildMounted es true -->
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
