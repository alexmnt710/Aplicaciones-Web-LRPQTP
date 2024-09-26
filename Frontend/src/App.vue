<script setup>
import { RouterLink, RouterView, useRouter } from 'vue-router';
import { onMounted } from 'vue';
import { Sesion } from './store/sesion';
import { sweetalert } from './composables/sweetAlert'; // Importa correctamente tu archivo de SweetAlert

const sesionStore = Sesion();
const sweetAlert = sweetalert(); // Llama a la función sweetalert

const router = useRouter();

    // coso anterior
// onMounted(async () => {
//   // abrir un cuadro de loading(sweetAlert) y cuando termine la sesion lo cierre 
//   await sesionStore.getSesion();
//   console.log(sesionStore.rol);
// });

onMounted(async () => {
  // Mostrar alerta de cargando
  const closeLoading = sweetAlert.ShowLoading();

  try {
    // Esperar a que se obtenga la sesión
    await sesionStore.getSesion();

    // Ver el rol obtenido
    console.log(sesionStore.rol);
  } catch (error) {
    // Mostrar alerta de error en caso de fallo
    sweetAlert.errorAlert('Error', 'Error al obtener la sesión');
    console.error(error);
  } finally {
    // Cerrar la alerta de cargando cuando termine la operación
    closeLoading();
  }
});
</script>

<template>
  <div class="app" style="height: 100vh">
    <RouterView />
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
