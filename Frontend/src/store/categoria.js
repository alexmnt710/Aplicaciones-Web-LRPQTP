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
        async getCategorias(page=1){
            const response = await fetch (`${this.url}/getCategorias?page=${page}`,{
                method:'GET',
                headers:{
                    'Content-Type':'application/json',
                    'Accept': 'application/json',
                },
                credentials:'include',
            })
            const data = await response.json()
            this.categoria = data;

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
        async crearCategoria(token, formData){
            try {
                console.log(formData);
                const response = await fetch(`${this.url}/postCategoria`, {
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                    body: formData,
                });
                const data = await response.json();
                console.log(data);
                this.getCategorias();
                return data;
            } catch (error) {
                console.error('Error creating category:', error);
            }
        },
        async updateCategoria(token, formData, id){
            try {
                const response = await fetch(`${this.url}/updateCategoria/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    body: JSON.stringify(formData)
                });
                const data = await response.json();
                console.log(data);
                this.getCategorias();
                return data;
            } catch (error) {
                console.error('Error updating category:', error);
            }
        },
        async deleteCategoria(token, id){
            try {
                console.log(id);
                const response = await fetch(`${this.url}/deleteCategoria/${id}`, {
                    method: 'DELETE',
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                const data = await response.json();
                console.log(data);
                this.getCategorias();
                return data;
            } catch (error) {
                console.error('Error deleting category:', error);
            }
        }

    },
    
})