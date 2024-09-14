import { defineStore } from 'pinia'
export const Sesion = defineStore('sesionStore',{
    state: ()=>(
        {
            sesion: false,
            user: null,
            url: import.meta.env.VITE_API_URL,
        }
    ),
    actions:{
        async getSesion(){
            try {
                const response = await fetch(`${this.url}/`,{
                    method: 'GET',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                    },
                    credentials:'include',
                });
                this.sesion= true;
                this.user = response.data;
                return;
            } catch (error) {
                this.sesion = false;
                return error;
            }
        }
    },

})