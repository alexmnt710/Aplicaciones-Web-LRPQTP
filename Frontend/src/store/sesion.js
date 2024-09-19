import { defineStore } from 'pinia'
export const Sesion = defineStore('sesionStore',{
    state: ()=>(
        {
            sesion: false,
            user: [],
            token: null,
            rol: null,
            url: import.meta.env.VITE_API_URL,
        }
    ),
    actions:{
        async getSesion(){
            try {
                this.user = localStorage.getItem('user');
                this.token = localStorage.getItem('token');
                this.rol = localStorage.getItem('rol');
                if (this.user != null && this.token != null){
                    const response = await this.checkSesion(this.token);
                    return;
                }else{
                    this.sesion = false;
                    return;
                }

                return;
            } catch (error) {
                this.sesion = false;
                return error;
            }
        },
        async login(formData){
            try {
                console.log(formData);  
                const response = await fetch(`${this.url}/login`,{
                    method: 'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                    },
                    credentials:'include',
                    body: JSON.stringify(formData),
                });
                const data = await response.json()
                console.log(data);
                if (data.success == true){
                    localStorage.setItem('user', JSON.stringify(data.user));
                    localStorage.setItem('token', data.token);
                    localStorage.setItem('rol', data.rol);
                    return data.message;
                }else{
                    this.sesion = false;
                    return data.message;
                }
            } catch (error) {
                this.sesion = false;
                return error;
            }
        },
        async logout(){
            try {
                const response = await fetch(`${this.url}/logout`, {
                    method: 'GET',
                    headers: {
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                });
                const data = await response.json();
                localStorage.removeItem('user');
                localStorage.removeItem('token');
                localStorage.removeItem('rol');
                this.sesion = false;

                return data.message;
            } catch (error) {
                return error;
            }
        },
        async checkSesion(token){
            try {
                const response = await fetch(`${this.url}/checksesion`, {
                    method: 'GET',
                    headers: {
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    credentials: 'include',
                });
                const data = await response.json();
                if (data.success == true){
                    this.sesion = true;
                }else{
                    this.logout();
                }
                
            } catch (error) {
                this.sesion = false;
                return error;
            }
        }

    },

})