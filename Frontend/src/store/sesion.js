import { defineStore } from 'pinia'
export const Sesion = defineStore('sesionStore',{
    state: ()=>(
        {
            sesion: false,
            user: [],
            token: [],
            url: import.meta.env.VITE_API_URL,
        }
    ),
    actions:{
        async getSesion(){
            try {
                this.user = localStorage.getItem('user');
                this.token = localStorage.getItem('token');

                if (this.user != null && this.token != null){
                    this.sesion = true;
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
                    localStorage.setItem('token', JSON.stringify(data.token));
                    return data.message;
                }else{
                    this.sesion = false;
                    return data.message;
                }
            } catch (error) {
                this.sesion = false;
                return error;
            }
        }
    },

})