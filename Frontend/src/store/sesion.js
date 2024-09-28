import { defineStore } from 'pinia';

export const Sesion = defineStore('sesionStore', {
  state: () => ({
    sesion: false,
    user: null, // Cambiado a null para que sea un objeto más tarde
    token: null,
    rol: null,
    url: import.meta.env.VITE_API_URL,
  }),
  actions: {
    async getSesion() {
      try {
        // Recuperar datos del localStorage y parsear user
        const userData = localStorage.getItem('user');
        this.user = userData ? JSON.parse(userData) : null; // Asegurarse de que es un objeto
        this.token = localStorage.getItem('token');
        this.rol = localStorage.getItem('rol');

        if (this.user !== null && this.token !== null) {
          await this.checkSesion(this.token);
          return;
        } else {
          this.sesion = false;
          return;
        }
      } catch (error) {
        this.sesion = false;
        return error;
      }
    },
    async login(formData) {
      try {
        const response = await fetch(`${this.url}/login`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          credentials: 'include',
          body: JSON.stringify(formData),
        });
        const data = await response.json();
        if (data.success === true) {
          localStorage.setItem('user', JSON.stringify(data.user));
          localStorage.setItem('token', data.token);
          localStorage.setItem('rol', data.rol);
          this.user = data.user; // Asigna el objeto directamente al estado
          this.token = data.token;
          this.rol = data.rol;
          this.sesion = true;
          return data;
        } else {
          this.sesion = false;
          return data;
        }
      } catch (error) {
        this.sesion = false;
        return error;
      }
    },
    async logout() {
      try {
        const response = await fetch(`${this.url}/logout`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          credentials: 'include',
        });
        const data = await response.json();
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        localStorage.removeItem('rol');
        this.user = null; // Restablecer el estado
        this.token = null;
        this.rol = null;
        this.sesion = false;
        return data.message;
      } catch (error) {
        return error;
      }
    },
    async checkSesion(token) {
      try {
        const response = await fetch(`${this.url}/checksesion`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            Authorization: `Bearer ${token}`,
          },
          credentials: 'include',
        });
        const data = await response.json();
        if (data.success === true) {
          this.sesion = true;
        } else {
          this.logout();
        }
      } catch (error) {
        this.sesion = false;
        return error;
      }
    },
  },
});
