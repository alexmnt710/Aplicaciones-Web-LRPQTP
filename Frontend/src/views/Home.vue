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
   if (sesionStore.userRole === 'admin') {
    // Redirigir a 'homead' si el usuario es un administrador
    router.push({ name: 'homead' });
  } else {
    // Redirigir a 'home' si no es administrador (opcional)
    router.push({ name: 'home' });
  }
  //  console.log(cursoStore.cursos);
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

<!-- Gustavin -->
<section v-if="!sesionStore.sesion" class="cta-section">
    <div class="container">
      <div class="row align-items-center">
        <!-- Texto principal -->
        <div class="col-md-6">
          <h1>Estudia gratis y certifícate con +6,000 cursos online</h1>
          <p>Acelera tu futuro con nuestros cursos certificados.</p>
          <button class="btn btn-custom">Crear cuenta gratis</button>
          <p class="cta-disclaimer">*Sin datos de tarjetas y sin llamadas de ventas.</p>
        </div>
        <!-- Imagen -->
        <div class="col-md-6">
          <img src="/src/assets/img/gustavinHome.png" alt="Certificado" class="cta-image img-fluid">
        </div>
      </div>
    </div>
  </section>


<!-- carrusel -->
<div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
    <!-- Indicadores de carrusel -->
    <div class="carousel-indicators">
      <button
        type="button"
        data-bs-target="#carouselExample"
        v-for="(curso, index) in cursos"
        :key="curso.cursoId"
        :data-bs-slide-to="index"
        :class="{ active: index === 0 }"
        aria-current="index === 0 ? 'true' : 'false'"
        aria-label="Slide"
      ></button>
    </div>

    <!-- Diapositivas del carrusel -->
    <div class="carousel-inner">
      <div
        v-for="(curso, index) in cursos"
        :key="curso.cursoId"
        :class="['carousel-item', { active: index === 0 }]"
      >
        <div class="d-flex align-items-center" style="min-height: 400px;">
          <div class="container">
            <div class="row">
              <!-- Contenido izquierdo -->
              <div class="col-md-6 d-flex flex-column justify-content-center">
                <h2>{{ curso.cursoName }}</h2>
                <p>{{ curso.cursoDescripcion }}</p>
                <p><strong>Precio:</strong> ${{ curso.cursoValor }}</p>
                <p>
                  <strong>Categoría:</strong> {{ curso.categoria.categoriaName }}
                  <strong>Dificultad:</strong> {{ curso.nivel.nivelName }} 
                </p>
                
                <!-- Botón para mostrar requisitos -->
                <button class="btn btn-custom" @click="mostrarRequisitos(curso.cursoRequisito)">
                  Ver Requisitos
                </button>
                <br>
                <button class="btn btn-custom" @click="irAVistaCurso(curso.cursoId)">
                  Ver Curso
                </button>
                <br>
              </div>

              <!-- Imagen del curso -->
              <div class="col-md-6">
                <img
                  :src="curso.cursoImagen || 'ruta/imagen/por/defecto.jpg'"
                  class="d-block w-100 img-fluid"
                  alt="Imagen del curso"
                />
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
/* Fondo del body que coincide con el header */
body {
  background-color: #0f3d28; /* Fondo verde oscuro */
}

/* Carrusel */
/* Carrusel */
.carousel-inner {
  min-height: 400px;
  background-color: #0f3d28; /* Fondo del carrusel */
}

.carousel-item {
  transition: transform 0.6s ease-in-out;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
  background-color: rgb(2, 2, 2); /* Controles en blanco para contraste */
}

.carousel-indicators [data-bs-target] {
  background-color: white; /* Indicadores en blanco */
}

/* Textos del Carrusel */
h2 {
  color: #a4dbac; /* Títulos en verde claro */
  font-size: 1.8rem;
}

p {
  color: #d3d3d3; /* Texto en gris claro */
  font-size: 1rem;
}

p strong {
  color: #f1f1f1; /* Texto destacado en blanco */
}

/* Botones del carrusel */
.btn {
  background-color: #3ecf8e; /* Botón verde claro */
  color: white;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 5px;
  font-size: 1.1rem;
  margin-top: 0.5rem;
}

.btn:hover {
  background-color: white; /* Hover del botón */
  color: #0f3d28; /* Texto en verde oscuro al hacer hover */
}

/* Ajuste de las imágenes del carrusel */
.carousel-item img {
  width: 100%;
  height: auto;
  object-fit: cover;
}

/* Texto del CTA (llamada a la acción) */
.cta-section {
  background-color: #0f3d28; /* Fondo verde oscuro */
  padding: 4rem 0;
}

.cta-section h1,
.cta-section p {
  color: white; /* Texto en blanco para buen contraste */
}

.cta-disclaimer {
  font-size: 0.85rem;
  color: #ccc; /* Texto más claro */
}

/* Imagen dentro del carrusel */
.carousel-item img {
  width: 100%;
  height: auto;
  object-fit: cover;
}

/* Diseño responsivo para pantallas pequeñas */
@media (max-width: 768px) {
  .carousel-inner {
    min-height: 300px;
  }

  .cta-section h1 {
    font-size: 1.8rem;
  }

  .cta-section p {
    font-size: 1rem;
  }

  .cta-button {
    font-size: 1rem;
    padding: 0.5rem 1rem;
  }

  .carousel-item img {
    max-height: 250px;
  }

  /* Ajustar las columnas para que se apilen en pantallas pequeñas */
  .cta-section .row,
  .carousel .row {
    flex-direction: column;
    text-align: center;
  }

  .col-md-6 {
    width: 100%; /* Ocupa el 100% en pantallas pequeñas */
  }

  .carousel-indicators {
    bottom: -30px; /* Ajusta la posición de los indicadores */
  }
}
</style>


