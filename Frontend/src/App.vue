<script setup>
import { RouterLink,RouterView, useRouter } from 'vue-router';
import { onMounted } from 'vue';
import { Sesion } from './store/sesion';

const sesionStore = Sesion();

const router = useRouter();
onMounted(async () => {
  await sesionStore.getSesion();
  console.log(sesionStore.sesion)

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
