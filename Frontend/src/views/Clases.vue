<script setup>
import { ref, onMounted, defineProps} from 'vue'
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { Cursos } from '../store/cursos';
import { sweetalert } from '../composables/sweetAlert';


const cursoStore = Cursos();

const claseId = defineProps(['claseId']);

onMounted(() => {
    console.log(claseId.claseId);
    cursoStore.getCurso(claseId.claseId).then(() => {
        console.log(cursoStore.cursoIndividual);
    });
});



</script>

<template>
<Header></Header>

<div>
    <h2>    vista clases
    </h2>
</div>

<Footer></Footer>
</template>

<style scoped>

</style>