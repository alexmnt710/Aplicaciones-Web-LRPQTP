import { defineStore } from 'pinia'
export const Cursos = defineStore('cursoStore',{
    state: ()=>(
        {
            cursos : [],
            cursoIndividual: [],
            url: import.meta.env.VITE_API_URL,
        }
    ),
    actions:{
        //get de cursos home
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
        },
        //Get de curso individual
        async getCurso(id) {
            try {
                const response = await fetch(`${this.url}/courses/${id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                });
                if (!response.ok) {
                    throw new Error(`Error ${response.status}: ${response.statusText}`);
                }
        
                const data = await response.json();
                this.cursoIndividual = data;
            } catch (error) {
                console.error('Error fetching course:', error);
            }
        },
        //get de cursos global
        async getCursos(token, page, search) {
            console.log(page);
            const response = await fetch (`${this.url}/getCursos?page=${page}`,{
                //&search=${search}
                method:'GET',
                headers:{
                    'Content-Type':'application/json',
                    'Accept': 'application/json',
                    Authorization: `Bearer ${token}`,
                },
                credentials:'include',
            })
            const data = await response.json()
            this.cursos = data
        },
        async crearCurso(token, formData) {
            try {
              const response = await fetch(`${this.url}/postCurso`, {
                method: 'POST',
                headers: {
                  'Accept': 'application/json', // Mantén este encabezado para aceptar JSON como respuesta
                  Authorization: `Bearer ${token}`, // El token de autorización
                },
                credentials: 'include',
                body: formData, // Enviar FormData directamente
              });
          
              const data = await response.json();
              console.log(data);
          
              // Verificar si el servidor responde con éxito
              if (!response.ok) {
                throw new Error(data.message || 'Error al crear el curso');
              }
          
              return data;
            } catch (error) {
              console.error('Error creating course:', error);
              throw error; // Lanzar el error para manejarlo en el componente
            }
          }
               
    },
    
})