<script setup>
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { sweetalert } from '../composables/sweetAlert';
import { ref, reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { Cursos } from '../store/cursos';
import Examen from '../components/Examen.vue';

const cursoStore = Cursos();
const route = useRoute();
const sweetAlert = sweetalert();

const curso = reactive({
  cursoName: '',
  cursoDescripcion: '',
  cursoRequisito: '',
  cursoValor: '',
  cursoContenido: [],
  cursoExamen: []
});
const mostrarExamen = ref(false); // Controla la visualización del examen

// Función para cargar los datos del curso
const loadCurso = async (id) => {
  const response = await cursoStore.getCurso(id);
  console.log(response);
  if (response.success) {
    Object.assign(curso, {
      ...response.data,
      cursoContenido: JSON.parse(response.data.cursoContenido),
      cursoExamen: JSON.parse(response.data.cursoExamen),
    });
  } else {
    sweetAlert.errorAlert('Error', response.message);
  }
};

// Cargar el curso al montar el componente

// Traemos la prop claseId
const props = defineProps({
  claseId: {
    type: String,
    required: true,
  },
  pasado: {
    type: String,
    required: true,
  },
});

onMounted(async () => {
  const closeLoading = sweetAlert.ShowLoading(); // Muestra loading.

  try {
    await loadCurso(props.claseId); // Espera a que se cargue el curso.
  } catch (error) {
    console.error('Error al cargar el curso:', error);
    sweetAlert.showError('Error al cargar el curso.');
  } finally {
    closeLoading(); // Cierra el loading después de que se complete la carga.
  }
});
</script>

<template>
  <Header />
  <div class="curso-container mx-auto max-w-4xl p-6 md:p-12 bg-white shadow-lg rounded-xl animate-fadeIn">
    <h1 class="text-3xl md:text-4xl font-bold text-center text-green-700 mb-8">{{ curso.cursoName }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
      <div class="info-card">
        <h2 class="font-semibold text-xl mb-2">Requisitos</h2>
        <p class="text-gray-600">{{ curso.cursoRequisito }}</p>
      </div>
      <div class="info-card">
        <h2 class="font-semibold text-xl mb-2">Descripcion del Curso</h2>
        <p class="text-green-600 text-2xl font-bold">{{ curso.cursoDescripcion }}</p>
      </div>
    </div>

    <div
      v-for="(block, index) in curso.cursoContenido"
      :key="index"
      class="content-block mb-12 p-6 bg-white shadow-lg rounded-xl animate-slideUp"
    >
      <h2 class="text-2xl font-semibold mb-4 text-green-700">{{ block.titulo }}</h2>
      <img
        v-if="block.media"
        :src="block.media"
        alt="Media"
        class="w-full h-64 object-cover rounded-lg shadow-md mb-4"
      />
      <p class="text-gray-600">{{ block.concepto }}</p>
    </div>

      <!-- Mostrar el botón solo si pasado es false -->
      <button
      v-if="pasado === 'false'"
      @click="mostrarExamen = true"
      class="btn-examen mt-8"
    >
      Resolver Examen
    </button>

    <Examen v-if="mostrarExamen" :examen="curso.cursoExamen" @cerrar="mostrarExamen = false" />
  </div>
  <Footer />
</template>

<style scoped>
/* Animaciones */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.animate-fadeIn {
  animation: fadeIn 0.6s ease-in-out;
}

.animate-slideUp {
  animation: slideUp 0.6s ease-in-out;
}

/* Contenedor principal */
.curso-container {
  background-image: linear-gradient(to right, #f0f4f8, #ffffff);
  max-width: 800px;
  margin: 0 auto;
  padding: 3rem 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Tarjetas de información */
.info-card {
  background-color: #f9fafb;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  margin-bottom: 1rem;
}

.info-card:hover {
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Bloques de contenido */
.content-block {
  margin-bottom: 2rem;
  padding: 2rem;
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Imagen del contenido */
.content-media {
  width: 100%;
  max-height: 300px;
  object-fit: cover;
  margin-bottom: 1rem;
}

/* Botón de examen */
.btn-examen {
  display: block;
  width: 100%;
  background-color: #38a169;
  color: white;
  font-weight: bold;
  padding: 1rem;
  border-radius: 8px;
  transition: background-color 0.3s, transform 0.2s;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.btn-examen:hover {
  background-color: #2f855a;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.btn-examen:active {
  transform: translateY(1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Ajuste de espaciado */
.curso-container h1 {
  margin-bottom: 1rem;
}

.grid {
  gap: 2rem;
}
</style>

