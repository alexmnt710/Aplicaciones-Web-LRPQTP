<script setup>
import { ref } from 'vue';
import Header from '../components/Public/header.vue';
import Footer from '../components/Public/footer.vue';
import { Sesion } from '../store/sesion.js';
import { useRouter } from 'vue-router';
import { User } from '../store/users.js';

const sesionStore = Sesion();
const router = useRouter();
const userStore = User();

// Datos del formulario de registro
const formData = {
  userNombres: '',
  userApellidos: '',
  userName: '',
  userCorreo: '',
  password: '',
  confirmPassword: '',
  userWordKey: ''
};

const register = async () => {
  
  if (formData.password !== formData.confirmPassword) {
    alert('Las contraseñas no coinciden');
    return;
  }

  // Intentar registrar al usuario
  try {
    const response = await userStore.registrar(formData);
    console.log(response);

  } catch (error) {
    console.error('Error en el registro:', error);
    alert('Ocurrió un error al crear la cuenta');
  }
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
          <input type="password" id="password" v-model="formData.password" required />
        </div>

        <div class="form-group">
          <label for="confirmPassword">Confirmar contraseña</label>
          <input type="password" id="confirmPassword" v-model="formData.confirmPassword" required />
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
  background-color: #f0f0f0;
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
