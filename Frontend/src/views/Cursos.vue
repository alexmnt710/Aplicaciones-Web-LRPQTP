<script setup>
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { Cursos } from '../store/cursos';
import { ref, onMounted } from 'vue';
import { Sesion } from '../store/sesion';
import { Categoria } from '../store/categoria';
import Pagination from '../components/Pagination.vue';
import { useRouter } from 'vue-router';

const router = useRouter();  
const categoriaStore = Categoria();
const sesionStore = Sesion();
const cursoStore = Cursos();
const cursos = ref([]);
const paginationData = ref({
  current_page: 1,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null
});

// Función para cargar cursos
const loadCursos = async (pageUrl = null) => {
  await cursoStore.getCursos(sesionStore.token, pageUrl);
  cursos.value = cursoStore.cursos.data;
  paginationData.value = {
    current_page: cursoStore.cursos.current_page,
    last_page: cursoStore.cursos.last_page,
    prev_page_url: cursoStore.cursos.prev_page_url,
    next_page_url: cursoStore.cursos.next_page_url
  };
};

// Método para obtener la imagen de la categoría asociada al curso
const getCategoriaImagen = (categoriaId) => {
  const categoria = categoriaStore.categorianormal.find(cat => cat.categoriaId === categoriaId);
  return categoria ? categoria.categoriaImagen : 'default-image.jpg';
};

const irAVistaCurso = (cursoId) => {
  if (!sesionStore.sesion) {
    sweetAlert.showAlert(
      "Debes iniciar sesión",
      "Por favor, inicia sesión para acceder a los cursos."
    );
  } else {
    router.push(`/clases/${cursoId}`);
  }
}

onMounted(async () => {
  const closeLoading = sweetAlert.ShowLoading();
  await loadCursos();
  console.log('Cursos:', cursos.value);
  await categoriaStore.getCategoria();
  console.log(categoriaStore.categorianormal);
  closeLoading();
});
</script>
<template>
  <Header />

  <div class="container my-5">
    <h2>Lista de Cursos</h2>

    <!-- Recorre los cursos y muestra su información -->
    <div v-if="cursos.length">
      <div class="curso-card row align-items-center mb-4" v-for="curso in cursos" :key="curso.cursoId">
        <!-- Imagen del curso obtenida desde la categoría -->
        <div class="col-md-4">
          <img :src="getCategoriaImagen(curso.cursoCategoriaId)" class="curso-imagen img-fluid rounded" alt="Imagen del curso" />
        </div>

        <!-- Información del curso -->
        <div class="col-md-8">
          <h3 class="curso-titulo">{{ curso.cursoName }}</h3>
          <p class="curso-descripcion">{{ curso.cursoDescripcion }}</p>
          <p class="curso-instructor">{{ curso.createdBy }}</p>
          <p class="curso-precio">{{ curso.cursoValor }} US$</p> 
          <button class="btn btn-custom" @click="irAVistaCurso(curso.cursoId)">
            Ver Curso
          </button>
        </div>
      </div>

      <!-- Paginación -->
      <Pagination
        :currentPage="paginationData.current_page"
        :lastPage="paginationData.last_page"
        :prevPageUrl="paginationData.prev_page_url"
        :nextPageUrl="paginationData.next_page_url"
        @pageChange="loadCursos"
      />
    </div>
  </div>

  <Footer />
</template>




<style scoped>
.curso-card {
  border-bottom: 1px solid #ddd;
  padding-bottom: 20px;
}

.curso-imagen {
  max-width: 100%;
  height: auto;
}

.curso-titulo {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.curso-descripcion {
  font-size: 1rem;
  color: #555;
}

.curso-instructor {
  font-size: 0.875rem;
  color: #888;
  margin-bottom: 1rem;
}

.curso-precio {
  font-size: 1.25rem;
  font-weight: bold;
}

.pagination {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

button {
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
}

button:disabled {
  background-color: #ccc;
}
</style>
