import { defineStore } from 'pinia'
export const Categoria = defineStore('categoriaStore',{
    state: ()=>(
        {
            categoria: [],
            nivel: [],
            url: import.meta.env.VITE_API_URL,
        }
    ),
    actions:{
        async getCategorias(){
            const response = await fetch (`${this.url}/getCategorias`,{
                method:'GET',
                headers:{
                    'Content-Type':'application/json',
                    'Accept': 'application/json',
                },
                credentials:'include',
            })
            const data = await response.json()
            this.categoria = data

        },
        async getNiveles(){
            const response = await fetch (`${this.url}/getNivel`,{
                method:'GET',
                headers:{
                    'Content-Type':'application/json',
                    'Accept': 'application/json',
                },
                credentials:'include',
            })
            const data = await response.json()
            this.nivel = data
        },

    },
    
})