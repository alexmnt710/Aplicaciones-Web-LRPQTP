import { defineStore } from 'pinia'
export const User = defineStore('userStore',{
    state: ()=>(
        {
            user: [],
            url: import.meta.env.VITE_API_URL,
        }
    ),
    actions:{
        
    },

})