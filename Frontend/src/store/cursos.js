import { defineStore } from 'pinia'
export const Cursos = defineStore('cursoStore',{
    state: ()=>(
        {
            cursos : [],
            url: import.meta.env.VITE_API_URL,
        }
    ),
    actions:{
        async getCursosHome(){
            const response = await fetch (`${this.url}/courses`,{
                method:'GET',
                headers:{
                    'Content-Type':'application/json',
                    'Accept': 'application/json',
                },
                credentials:'include',
            })
            const data = await response.json()
            this.cursos = data
        }
    },



})