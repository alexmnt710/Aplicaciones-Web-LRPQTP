import { defineStore } from 'pinia'
export const User = defineStore('userStore',{
    state: ()=>(
        {
            user: [],
            url: import.meta.env.VITE_API_URL,
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
                console.log(data);
                return data;
            } catch (error) {
                return error;
            }
        }
    },

})