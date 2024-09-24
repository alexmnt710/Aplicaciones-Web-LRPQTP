<script setup>
import { ref } from 'vue';
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { Sesion } from '../store/sesion.js';
import { useRouter } from 'vue-router';
import { sweetalert } from '../composables/sweetAlert'; // Importa SweetAlert

const sesionStore = Sesion();
const router = useRouter();
const { ShowLoading, errorAlert, successAlert } = sweetalert(); // Usamos las funciones de SweetAlert

// Variables del formulario
const formData = ref({
  userName: '',
  password: '',
});

const isPasswordVisible = ref(false); // Estado para controlar la visibilidad de la contraseña

const login = async () => {
  const closeLoading = ShowLoading('Verificando', 'Espere un momento...');
  const response = await sesionStore.login(formData.value);
  closeLoading();

  if (response.success === true) {
    successAlert('Inicio de sesión exitoso', 'Bienvenido de nuevo!');
    router.push({ name: 'Home' }); 
  } else {
    errorAlert('Error al iniciar sesión', 'Usuario o contraseña incorrectos');
  }
};

const togglePasswordVisibility = () => {
  isPasswordVisible.value = !isPasswordVisible.value; // Alternar visibilidad
};

const goToRegister = () => {
  router.push({ name: 'Register' }); // Redirigir a la página de registro
};
</script>

<template>
    <Header></Header>
    <div class="login-container">
      <div class="login-box">
        <h2>Inicia sesión en tu cuenta</h2>
  
        <form @submit.prevent="login">
          <div class="form-group">
            <label for="userName">Nombre de usuario</label>
            <input type="text" id="userName" v-model="formData.userName" required />
          </div>
  
          <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="password-wrapper">
              <input
                :type="isPasswordVisible ? 'text' : 'password'"
                id="password"
                v-model="formData.password"
                required
              />
              <button type="button" @click="togglePasswordVisibility" aria-label="Toggle password visibility">
                <i :class="isPasswordVisible ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
              </button>
            </div>
          </div>
  
          <button type="submit" class="login-button">Iniciar sesión</button>
        </form>
  
        <a href="#" class="forgot-password">He olvidado la contraseña</a>
        <p class="has-no-account">
            ¿No tienes una cuenta? 
            <a @click="goToRegister">Regístrate</a>
        </p>
      </div>
  
      <div class="login-image">
        <img src="/src/assets/img/gustavinlogin.png" class="cta-image img-fluid" />
      </div>
    </div>
    <Footer></Footer>
</template>

<style scoped>
/* Contenedor principal del login */
.login-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  min-height: 100vh;
  background-color: #0f3d28; /* Fondo verde oscuro */
  padding: 2rem;
}

.login-box {
  background-color: white;
  padding: 2rem;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  width: 400px;
}

.login-box h2 {
  margin-bottom: 2rem;
  font-size: 1.8rem;
  color: #0f3d28; /* Color oscuro del header */
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #0f3d28;
}

.form-group input {
  width: 100%;
  padding: 0.7rem;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 1rem;
}

.password-wrapper {
  position: relative;
}

.password-wrapper button {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  color: #3ecf8e; /* Color del ícono */
}

.login-button {
  width: 100%;
  padding: 0.8rem;
  background-color: #3ecf8e; /* Color verde del header */
  color: white;
  border: none;
  border-radius: 25px;
  font-size: 1.2rem;
  cursor: pointer;
  margin-top: 1rem;
}

.login-button:hover {
  background-color: #35b67b; /* Hover del botón */
}

.forgot-password {
  display: block;
  margin-top: 1rem;
  color: #0f3d28;
  text-align: center;
}

.has-no-account {
  text-align: center;
  margin-top: 1rem;
  color: #0f3d28;
}

.has-no-account a {
  color: #3ecf8e;
  cursor: pointer;
}

.login-image {
  width: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-image img {
  width: 100%;
  max-width: 200px;
}
</style>
