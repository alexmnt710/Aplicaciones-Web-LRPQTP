<script setup>
import { ref, onMounted  } from 'vue'
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { Cursos } from '../store/cursos';

const cursoStore = Cursos();
onMounted(async () => {
   await cursoStore.getCursosHome();
   console.log(cursoStore.cursos);
});


</script>

<script>
// esto es solo para simular los datos, se debe manejar todo en setup
export default {
  data() {
    return {
      categories: ['Python', 'Microsoft Excel', 'Ciencias de la información', 'Amazon AWS', ''],
      courses: [
        { id: 1, title: 'Python Practicando. Desde 0 hasta Desarrollador en Python', instructor: 'Álvaro Chirou', rating: 4.7, reviews: '21,359', price: '74,99 US$', image: 'ruta/de/imagen1.jpg' },
        { id: 2, title: 'Universidad Python - Cero a Experto - Actualizado', instructor: 'Ubaldo Acosta', rating: 4.7, reviews: '16,620', price: '74,99 US$', image: 'ruta/de/imagen2.jpg' },
        { id: 3, title: 'Python para no matemáticos', instructor: 'Academia Apps', rating: 4.5, reviews: '3,440', price: '59,99 US$', image: 'ruta/de/imagen3.jpg' },
        { id: 4, title: 'Curso Python - Desde cero para principiantes', instructor: 'Jorge Alberto', rating: 4.6, reviews: '3,486', price: '49,99 US$', image: 'ruta/de/imagen4.jpg' },
      ],
    };
  },
};
</script>
<template>
<Header></Header>

<div class="home-container">
    <section class="header-banner">
      <h1>Una amplia selección de cursos</h1>
      
    </section>

    <section class="courses-section">
      

      <div class="courses-grid">
        <div class="course-card" v-for="course in courses" :key="course.id">
          <img :src="course.image" :alt="course.title" class="course-image" />
          <h3>{{ course.title }}</h3>
          <p>{{ course.instructor }}</p>
          <p class="course-rating">{{ course.rating }} ★ ({{ course.reviews }} reviews)</p>
          <p class="course-price">{{ course.price }}</p>
        </div>
      </div>
    </section>
  </div>
<Footer></Footer>
</template>

<style scoped>
/* Estilos del contenedor principal */
.home-container {
  background-color: #f0f0f0; /* Color de fondo general */
  padding: 2rem;
  color: #0f3d28; /* Color del texto principal */
}

/* Sección de la cabecera con el título principal */
.header-banner {
  text-align: center;
  margin-bottom: 2rem;
}

.header-banner h1 {
  font-size: 2.5rem;
  margin-bottom: 0.5rem;
}

.header-banner p {
  font-size: 1.2rem;
  margin-bottom: 1.5rem;
}

.course-categories {
  display: flex;
  justify-content: center;
  gap: 1.5rem;
  list-style: none;
  padding: 0;
}

.course-categories li {
  padding: 0.5rem 1rem;
  background-color: #3ecf8e; /* Color verde del header */
  color: white;
  border-radius: 20px;
  cursor: pointer;
}

/* Sección de cursos destacados */
.courses-section {
  background-color: white;
  padding: 2rem;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.course-header {
  text-align: center;
  margin-bottom: 2rem;
}

.course-header h2 {
  font-size: 1.8rem;
  color: #0f3d28; /* Color principal */
}

.course-header p {
  font-size: 1.1rem;
  margin-bottom: 1rem;
}

.explore-button {
  padding: 0.7rem 1.5rem;
  background-color: #3ecf8e; /* Botón en verde */
  color: white;
  border: none;
  border-radius: 25px;
  cursor: pointer;
}

.explore-button:hover {
  background-color: #35b67b;
}

/* Grid de cursos */
.courses-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}

.course-card {
  background-color: #ffffff;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.course-image {
  width: 100%;
  border-radius: 10px;
  margin-bottom: 1rem;
}

.course-card h3 {
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
}

.course-card p {
  margin: 0.5rem 0;
  color: #0f3d28;
}

.course-rating {
  font-weight: bold;
  color: #3ecf8e;
}

.course-price {
  font-size: 1.1rem;
  font-weight: bold;
}
</style><style scoped>

</style>
