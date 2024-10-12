<script setup>
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { useRouter } from 'vue-router';
import { Sesion } from '../store/sesion';
import { Transacciones} from '../store/transacciones';
import { ref, onMounted,watch } from 'vue';
import { sweetalert } from '../composables/sweetAlert';

const sesionStore = Sesion();
const router = useRouter();
const transaccionStore = Transacciones();
const sweetAlert = sweetalert();

onMounted(async () => {
  await transaccionStore.getTransaccion(sesionStore.token);
});

const selectedView = ref('misCursos');

watch(selectedView, async (newView) => {
  const closeLoading = sweetAlert.ShowLoading();
  switch (newView) {
    case 'misCursos':
      await transaccionStore.getTransaccion(sesionStore.token);
      console.log(transaccionStore.transaccion);
      break;
    case 'cursosAprobados':
      await transaccionStore.getCursosPagados(sesionStore.token);
      break;
    case 'cursosInscribirse':
      await transaccionStore.getCursosPorInscribirse(sesionStore.token);
      
      break;
  }
  closeLoading();
});

// Cambiar entre las opciones de vista
const setView = (view) => {
  selectedView.value = view;
};

// Función para eliminar un curso
const eliminarCurso = async (cursoId) => {
  console.log("Eliminar curso con ID:", cursoId);
   const response = await sweetAlert.confirmAlert("Atención", "¿Estás seguro de eliminar este curso?");
   if(response){
      const response2 = await transaccionStore.deleteTransaccion(sesionStore.token, cursoId);
     if(response2.success == true){
       sweetAlert.successAlert("Curso eliminado", response.message);
        await transaccionStore.getTransaccion(sesionStore.token);
     }else{
        sweetAlert.errorAlert("Error", response.message);
      }
   }else{
     return;
   }
};

// Función para editar un curso
const editarCurso = (cursoId) => {
  console.log("Editar curso con ID:", cursoId);
  // Lógica de edición del curso
};
</script>

<template>
<Header></Header>
  <div class="perfil-container">
    <!-- Título -->
    <h2 class="text-center">Transacciones</h2>

    <!-- Opciones de la vista -->
    <div class="view-options">
      <button :class="{ active: selectedView === 'misCursos' }" @click="setView('misCursos')">
        Cursos Pendientes
      </button>
      <button :class="{ active: selectedView === 'cursosAprobados' }" @click="setView('cursosAprobados')">
        Cursos Pagados
      </button>
      <!-- <button :class="{ active: selectedView === 'cursosInscribirse' }" @click="setView('cursosInscribirse')">
        Cursos por Inscribirse
      </button> -->
    </div>

    <!-- Contenido dinámico según la opción seleccionada -->
    <div class="crud-content">
      <!-- Vista de "Mis Cursos" -->
      <div v-if="selectedView === 'misCursos'">
        <h3>Procesos Pendientes</h3>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre del Curso</th>
                <th>Estudiante</th>
                <th>Estado</th>
                <th>Valor</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="curso in transaccionStore.transaccion" :key="curso.claseId">
                <td>{{ curso.claseId }}</td>
                <td>{{ curso.curso.cursoName }}</td>
                <td>{{ curso.usuario.userName }}</td>
                <td>{{ curso.relVerificacion ? 'Pagado' : 'Pendiente' }}</td>
                <td>{{ curso.curso.cursoValor }}$</td>
                <td>
                  <button class="btn btn-warning btn-sm mx-1" @click="editarCurso(curso.claseId)">Editar</button>
                  <button class="btn btn-danger btn-sm mx-1" @click="eliminarCurso(curso.claseId)">Eliminar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Vista de "Cursos Aprobados" -->
      <div v-if="selectedView === 'cursosAprobados'">
        <h3>Procesos Pagados</h3>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre del Curso</th>
                <th>Usuario</th>
                <th>Verificación</th>
                <th>Valor</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="curso in transaccionStore.transaccion" :key="curso.cursoId">
                <td>{{ curso.claseId }}</td>
                <td>{{ curso.curso.cursoName }}</td>
                <td>{{ curso.usuario.userName }}</td>
                <td>{{ curso.relVerificacion ? 'Pagado' : 'No Verificado' }}</td>
                <td>{{ curso.curso.cursoValor }}$</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Vista de "Cursos por Inscribirse" -->
      <div v-if="selectedView === 'cursosInscribirse'">
        <h3>Cursos por Inscribirse</h3>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre del Curso</th>
                <th>Usuario</th>
                <th>Verificación</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="curso in transaccionStore.transaccion" :key="curso.cursoId">
                <td>{{ curso.cursoId }}</td>
                <td>{{ curso.curso.cursoName }}</td>
                <td>{{ curso.usuario.userName }}</td>
                <td>{{ curso.relVerificacion ? 'Verificado' : 'No Verificado' }}</td>
                <td>
                  <button class="btn btn-info btn-sm mx-1">Inscribirse</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <Footer></Footer>
</template>

<style scoped>
.perfil-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.text-center {
  text-align: center;
}

/* Estilos para las opciones de vista */
.view-options {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-bottom: 2rem;
}

.view-options button {
  padding: 0.5rem 1.5rem;
  border: none;
  background-color: #f0f0f0;
  color: #333;
  cursor: pointer;
  border-radius: 5px;
  transition: background-color 0.3s ease-in-out;
}

.view-options button.active {
  background-color: #3ecf8e;
  color: white;
}

.view-options button:hover {
  background-color: #0f3d28;
  color: white;
}

.crud-content {
  margin-top: 2rem;
}

/* Estilos para la tabla */
.table-responsive {
  margin-top: 1rem;
}

.table th, .table td {
  text-align: center;
  vertical-align: middle;
  padding: 1rem;
}

.btn-sm {
  padding: 0.5rem;
  font-size: 0.85rem;
}
</style>