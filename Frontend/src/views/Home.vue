<script setup>
import { ref, onMounted } from 'vue';
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { Cursos } from '../store/cursos';
import { sweetalert } from '../composables/sweetAlert';
import { useRouter } from 'vue-router';
import { Sesion } from '../store/sesion';

const sweetAlert = sweetalert(); 
const cursoStore = Cursos();
const sesionStore = Sesion();  
const cursos = ref([]);
const router = useRouter();  

onMounted(async () => {
   await cursoStore.getCursosHome();
   cursos.value = cursoStore.cursos; 
   console.log(cursoStore.cursos);
});


const mostrarRequisitos = (cursoRequisito) => {
  sweetAlert.showAlert("Requisitos", cursoRequisito || "No hay requisitos especificados");
}

// coso para ir al coso con sesion
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
</script>

<template>
  <Header />

  <section class="cta-section">
    <div class="container">
      <div class="row align-items-center">
        <!-- Texto principal -->
        <div class="col-md-6">
          <h1>Estudia gratis y certifícate con +6,000 cursos online</h1>
          <p>Acelera tu futuro con nuestros cursos certificados.</p>
          <button class="cta-button">Crear cuenta gratis</button>
          <p class="cta-disclaimer">*Sin datos de tarjetas y sin llamadas de ventas.</p>
        </div>
        <!-- Imagen -->
        <div class="col-md-6">
          <!-- <img src="@/assets/img/gustavinHome.png" alt="Certificado" class="cta-image"> -->
          <img src="/src/assets/img/gustavinHome.png" alt="Certificado" class="cta-image img-fluid">
        </div>
      </div>
    </div>
  </section>

  <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
    <!-- Indicadores de carrusel -->
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExample" v-for="(curso, index) in cursos" :key="curso.cursoId" :data-bs-slide-to="index" :class="{ active: index === 0 }" aria-current="index === 0 ? 'true' : 'false'" aria-label="Slide"></button>
    </div>

    <!-- Diapositivas del carrusel -->
    <div class="carousel-inner">
      <div v-for="(curso, index) in cursos" :key="curso.cursoId" :class="['carousel-item', { active: index === 0 }]">
        <div class="d-flex align-items-center" style="min-height: 400px;">
          <div class="container">
            <div class="row">
              <!-- Contenido izquierdo -->
              <div class="col-md-6 d-flex flex-column justify-content-center">
                <h2>{{ curso.cursoName }}</h2>
                <p>{{ curso.cursoDescripcion }}</p>
                <p><strong>Precio:</strong> ${{ curso.cursoValor }}</p>
                <p><strong>Categoría:</strong> {{ curso.categoria.categoriaName }}</p>
                <!-- Botón para mostrar requisitos -->
                <button class="btn btn-primary" @click="mostrarRequisitos(curso.cursoRequisito)">
                  Ver Requisitos
                </button>
                <br>
                <button class="btn btn-primary" @click="irAVistaCurso(curso.cursoId)">
                  Ver Curso
                </button>
              </div>

              <!-- Imagen del curso -->
              <div class="col-md-6">
                <img :src="curso.cursoImagen || 'ruta/imagen/por/defecto.jpg'" class="d-block w-100 img-fluid" alt="Imagen del curso">
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Controles de navegación -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <Footer />
</template>
<style scoped>
.carousel-inner {
  min-height: 400px;
}

.carousel-item {
  transition: transform 0.6s ease-in-out;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
  background-color: black;
}

.carousel-indicators [data-bs-target] {
  background-color: black;
}

.container {
  max-width: 1100px;
}
/* Estilos para la sección de llamada a la acción */
.cta-section {
  background-color: #f0f8ff; /* Fondo suave similar al de la imagen */
  padding: 4rem 0;
}

.cta-section h1 {
  font-size: 2.5rem;
  font-weight: bold;
  color: #0f3d28; /* Color azul oscuro */
}

.cta-section p {
  font-size: 1.2rem;
  color: #0f3d28;
}

.cta-button {
  background-color: #3ecf8e;
  color: white;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 5px;
  font-size: 1.2rem;
  margin-top: 1rem;
  cursor: pointer;
}

.cta-button:hover {
  background-color: #0f3d28;
}

.cta-disclaimer {
  font-size: 0.85rem;
  color: #666;
  margin-top: 0.5rem;
}

.cta-image {
  max-width: 100%;
  height: auto; /* Se adapta automáticamente a la pantalla */
  display: block;
}

/* Para garantizar que las imágenes y el contenido del carrusel sean adaptables */
.carousel-inner {
  min-height: 400px;
}

/* Ajusta las imágenes y el contenido del carrusel */
.carousel-item img {
  width: 100%;
  height: auto; /* Se adapta al tamaño del contenedor */
  object-fit: cover;
}

/* Ocultar ciertos elementos en pantallas pequeñas y ajustar otros */
@media (max-width: 768px) {
  .carousel-inner {
    min-height: 300px; /* Reduce el tamaño mínimo del carrusel en pantallas pequeñas */
  }

  .cta-section h1 {
    font-size: 1.8rem; /* Reduce el tamaño del título en móviles */
  }

  .cta-section p {
    font-size: 1rem;
  }

  .cta-button {
    font-size: 1rem;
    padding: 0.5rem 1rem; /* Reduce el tamaño del botón */
  }

  /* Ocultar el texto de los slides en pantallas pequeñas si es necesario */
  .carousel-caption {
    font-size: 0.8rem;
  }

  .carousel-item img {
    max-height: 250px; /* Limita la altura de la imagen */
  }

  /* Ajusta las columnas para que se apilen en pantallas pequeñas */
  .cta-section .row,
  .carousel .row {
    flex-direction: column; /* Apila las columnas en pantallas pequeñas */
    text-align: center; /* Centra el texto */
  }

  .col-md-6 {
    width: 100%; /* Ocupa el 100% en pantallas pequeñas */
  }

  .carousel-indicators {
    bottom: -30px; /* Ajusta la posición de los indicadores en pantallas pequeñas */
  }
}

</style>