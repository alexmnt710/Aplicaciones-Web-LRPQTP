<script setup>
import { ref } from 'vue'
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';

</script>

<template>
<Header></Header>

<div>
    <h2>    Este sera el home, al no tener informacion el footer se encuentra en la parte superior, pero al momento de llenarlo bajara al final de la pagina
    </h2>
</div>

<Footer></Footer>
</template>

<style scoped>

</style>
