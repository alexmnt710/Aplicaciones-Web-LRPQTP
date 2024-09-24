<script setup>
import { ref } from 'vue';
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { Sesion } from '../store/sesion.js';
import { useRouter } from 'vue-router';
import { User } from '../store/users.js';
import { sweetalert } from '../composables/sweetAlert';

const sesionStore = Sesion();
const router = useRouter();
const userStore = User();
const { successAlert, errorAlert, ShowLoading } = sweetalert(); 

const formData = ref({
  userNombres: '',
  userApellidos: '',
  userName: '',
  userCorreo: '',
  password: '',
  confirmPassword: '',
  userWordKey: ''
});

const isPasswordVisible = ref(false); // Estado para la visibilidad de la contraseña
const isConfirmPasswordVisible = ref(false); // Estado para la visibilidad de la confirmación de contraseña

const register = async () => {
  if (formData.value.password !== formData.value.confirmPassword) {
    errorAlert('Error', 'Las contraseñas no coinciden');
    return;
  }

  const closeLoading = ShowLoading();

  try {
    const response = await userStore.registrar(formData.value);
    closeLoading(); 
    if (response.success) {
      successAlert('Registro exitoso', response.message);
      router.push({ name: 'Login' });
    } else {
      const errorMessages = Object.values(response.errors)
        .flat()
        .map(error => `<p>${error}</p>`) 
        .join(''); 
      errorAlert('Error en el registro', `Por favor revise los campos: ${errorMessages}`, true); 
    }
  } catch (error) {
    closeLoading(); 
    console.error('Error en el registro:', error);
    errorAlert('Error', 'Ocurrió un error al crear la cuenta');
  }
};

const togglePasswordVisibility = () => {
  isPasswordVisible.value = !isPasswordVisible.value;
};

const toggleConfirmPasswordVisibility = () => {
  isConfirmPasswordVisible.value = !isConfirmPasswordVisible.value;
};

const goToLogin = () => {
  router.push({ name: 'Login' });
};
</script>

<template>
  <Header></Header>
  <div class="register-container">
    <div class="register-box">
      <h2>Regístrate</h2>

      <form @submit.prevent="register">
        <div class="form-group-inline">
          <div class="form-group">
            <label for="userNombres">Nombres</label>
            <input type="text" id="userNombres" v-model="formData.userNombres" required />
          </div>
          <div class="form-group">
            <label for="userApellidos">Apellidos</label>
            <input type="text" id="userApellidos" v-model="formData.userApellidos" required />
          </div>
        </div>

        <div class="form-group">
          <label for="userName">Nombre de usuario</label>
          <input type="text" id="userName" v-model="formData.userName" required />
        </div>

        <div class="form-group">
          <label for="userCorreo">Correo electrónico</label>
          <input type="email" id="userCorreo" v-model="formData.userCorreo" required />
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

        <div class="form-group">
          <label for="confirmPassword">Confirmar contraseña</label>
          <div class="password-wrapper">
            <input
              :type="isConfirmPasswordVisible ? 'text' : 'password'"
              id="confirmPassword"
              v-model="formData.confirmPassword"
              required
            />
            <button type="button" @click="toggleConfirmPasswordVisibility" aria-label="Toggle confirm password visibility">
              <i :class="isConfirmPasswordVisible ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
            </button>
          </div>
        </div>

        <div class="form-group">
          <label for="userWordKey">Palabra clave</label>
          <input type="text" id="userWordKey" v-model="formData.userWordKey" />
        </div>

        <button type="submit" class="register-button">Crear cuenta</button>
      </form>

      <p class="has-account">
        ¿Ya tienes una cuenta?
        <a @click="goToLogin">Inicia sesión</a>
      </p>
    </div>
  </div>
  <Footer></Footer>
</template>

<style scoped>
/* Contenedor principal del registro */
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #0f3d28; /* Fondo verde oscuro */
  padding: 2rem;
}

.register-box {
  background-color: white;
  padding: 2rem;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  width: 400px;
}

.register-box h2 {
  margin-bottom: 2rem;
  font-size: 1.8rem;
  color: #0f3d28;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #0f3d28;
}

.form-group-inline {
  display: flex;
  justify-content: space-between;
}

.form-group-inline .form-group {
  width: 48%;
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

.register-button {
  width: 100%;
  padding: 0.8rem;
  background-color: #3ecf8e;
  color: white;
  border: none;
  border-radius: 25px;
  font-size: 1.2rem;
  cursor: pointer;
  margin-top: 1rem;
}

.register-button:hover {
  background-color: #35b67b;
}

.has-account {
  text-align: center;
  margin-top: 1rem;
  color: #0f3d28;
}

.has-account a {
  color: #3ecf8e;
  cursor: pointer;
}
</style>
