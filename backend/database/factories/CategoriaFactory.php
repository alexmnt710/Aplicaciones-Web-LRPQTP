<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    public function definition()
    {
        // Lista de categorías realistas con su descripción e imagen correspondiente
        $categorias = [
            [
                'name' => 'Informática',
                'descripcion' => 'Cursos relacionados con la programación y las tecnologías de la información.',
                'imagen' => 'https://example.com/informatica.jpg',
            ],
            [
                'name' => 'Deportes',
                'descripcion' => 'Cursos relacionados con actividades deportivas y entrenamiento físico.',
                'imagen' => 'https://example.com/deportes.jpg',
            ],
            [
                'name' => 'Marketing',
                'descripcion' => 'Cursos sobre estrategias de marketing digital y tradicional.',
                'imagen' => 'https://example.com/marketing.jpg',
            ],
            [
                'name' => 'Diseño Gráfico',
                'descripcion' => 'Cursos sobre diseño visual y herramientas de edición gráfica.',
                'imagen' => 'https://example.com/diseno_grafico.jpg',
            ],
            [
                'name' => 'Idiomas',
                'descripcion' => 'Cursos para aprender diferentes idiomas y mejorar la comunicación.',
                'imagen' => 'https://example.com/idiomas.jpg',
            ],
        ];

        // Seleccionar una categoría al azar
        $categoria = $this->faker->randomElement($categorias);

        return [
            'categoriaName' => $categoria['name'],  // Nombre realista de la categoría
            'categoriaDescripcion' => $categoria['descripcion'],  // Descripción acorde a la categoría
            'categoriaImagen' => $categoria['imagen'],  // Imagen representativa de la categoría
        ];
    }
}
