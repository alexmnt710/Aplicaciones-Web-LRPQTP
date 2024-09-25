<script setup>
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { Cursos } from '../store/cursos';
import {ref, onMounted} from 'vue';
import { Sesion } from '../store/sesion';

const sesionStore = Sesion();
const cursoStore = Cursos();
onMounted(async () => {
    await cursoStore.getCursos(sesionStore.token);
    console.log(cursoStore.cursos);
});


</script>
<template>
    <Header></Header>
    
    <div>
        <h2>    vista crud cursos
        </h2>
    </div>
    
    <Footer></Footer>
</template>
<style scoped>
    
</style>