import { defineStore } from 'pinia'
import Docentes from '../views/Docentes.vue';
export const User = defineStore('userStore',{
    state: ()=>(
        {
            user: [],
            url: import.meta.env.VITE_API_URL,
            docentes: [],
        }
    ),
    actions:{
        async registrar(formData){
            try {
                console.log(formData);  
                const response = await fetch(`${this.url}/postUser`,{
                    method: 'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                    },
                    credentials:'include',
                    body: JSON.stringify(formData),
                });
                const data = await response.json()
                return data;
            } catch (error) {
                return error;
            }
        },
        async getDocentes(token, page){
            try {
                const response = await fetch(`${this.url}/getDocentes?page=${page}`,{
                    method: 'GET',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    credentials:'include',
                });
                const data = await response.json()
                console.log(data);
                this.docentes = data;
            } catch (error) {
                return error;
            }
        },
        async createDocente(token, formData){
            console.log(formData);
            try {
                const response = await fetch(`${this.url}/postDocente`,{
                    method: 'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    credentials:'include',
                    body: formData,
                });
                const data = await response.json()
                
                return data;
            } catch (error) {
                return error;
            }
        },
    },

})