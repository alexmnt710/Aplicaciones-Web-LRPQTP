import { defineStore } from 'pinia'
export const Sesion = defineStore('sesionStore',{
    state: ()=>(
        {
            sesion: false,
            user: [],
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
                this.sesion= true;
                const data = await response.json()
                if (data.success){
                    this.user = data.user;
                    
                    return data.message;
                }else{
                    this.sesion = false;
                    return data.message;
                }
                return;
            } catch (error) {
                this.sesion = false;
                return error;
            }s
        }
    },

})