<script setup>
import { reactive, ref, computed } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const props = defineProps({
  examen: {
    type: Array,
    required: true
  }
});

const respuestas = reactive([]); // Array para almacenar las respuestas del usuario
const examenCompletado = ref(false); // Controla si el examen ha sido enviado
const currentSlide = ref(0); // Controla la pregunta actual en el carrusel

// Función para avanzar a la siguiente pregunta
const siguientePregunta = () => {
  if (currentSlide.value < props.examen.length - 1) {
    currentSlide.value++;
  }
};

// Función para retroceder a la pregunta anterior
const anteriorPregunta = () => {
  if (currentSlide.value > 0) {
    currentSlide.value--;
  }
};

// Función para enviar el examen
const enviarExamen = () => {
  const payload = props.examen.map((pregunta, index) => ({
    pregunta: pregunta.pregunta,
    opciones: pregunta.opciones,
    respuestaUsuario: respuestas[index] || '', // Si no se responde, enviamos vacío
  }));

  // Mostrar en la consola el objeto con las respuestas
  console.log('Examen enviado:', payload);

  //redireccionar a la pagina de inicio
    router.push('/');

  examenCompletado.value = true; // Marcar el examen como completado
};

// Computed para saber si estamos en la última pregunta
const enUltimaPregunta = computed(() => currentSlide.value === props.examen.length - 1);
</script>

<template>
  <div class="modal-overlay">
    <div class="modal-content">
      <h2 class="text-center mb-4">Examen</h2>

      <div v-if="!examenCompletado">
        <div class="diapositiva">
          <h3>{{ examen[currentSlide].pregunta }}</h3>

          <div v-for="(opcion, optIndex) in examen[currentSlide].opciones" :key="optIndex" class="opcion-block">
            <input
              type="radio"
              :name="'pregunta-' + currentSlide"
              :value="opcion"
              v-model="respuestas[currentSlide]"
            />
            <label>{{ opcion }}</label>
          </div>
        </div>

        <div class="botones-navegacion">
          <button v-if="currentSlide > 0" @click="anteriorPregunta" class="btn btn-secondary">
            Anterior
          </button>
          <button v-if="!enUltimaPregunta" @click="siguientePregunta" class="btn btn-primary">
            Siguiente
          </button>
          <button v-else @click="enviarExamen" class="btn btn-success">
            Enviar Examen
          </button>
        </div>
      </div>

      <div v-else class="resultado text-center">
        <h3>Examen completado</h3>
        <p>¡Gracias por completar el examen!</p>
      </div>

      <button class="btn btn-danger mt-4" @click="$emit('cerrar')">Cerrar</button>
    </div>
  </div>
</template>

<style scoped>
/* Estilos del modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 2rem;
  border-radius: 10px;
  max-width: 600px;
  width: 90%;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.diapositiva {
  margin-bottom: 1.5rem;
}

.opcion-block {
  margin-bottom: 0.5rem;
}

.botones-navegacion {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
}

.btn {
  flex: 1;
  margin: 0.5rem;
  padding: 0.75rem;
  border-radius: 5px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.2s;
}

.btn-primary {
  background-color: #3182ce;
  color: white;
}

.btn-primary:hover {
  background-color: #2b6cb0;
}

.btn-secondary {
  background-color: #a0aec0;
  color: white;
}

.btn-secondary:hover {
  background-color: #718096;
}

.btn-success {
  background-color: #38a169;
  color: white;
}

.btn-success:hover {
  background-color: #2f855a;
}

.btn-danger {
  background-color: #e53e3e;
  color: white;
}

.btn-danger:hover {
  background-color: #c53030;
}
</style>


