<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Categoria;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Curso>
 */
class CursoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cursoName' => $this->faker->words(3, true),  // Nombre del curso con 3 palabras
            'cursoDescripcion' => $this->faker->paragraph(),  // Una descripción aleatoria
            'cursoNivelId' => $this->faker->randomElement([1, 2, 3, 4]),  // Elegir un nivelId existente
            'cursoValor' => $this->faker->randomFloat(2, 100, 1000),  // Valor del curso aleatorio entre 100 y 1000
            'cursoRequisito' => $this->faker->sentence(),  // Requisito del curso como una oración aleatoria
            'cursoContenido' => $this->faker->text(200),  // Contenido del curso como un texto aleatorio de 200 caracteres
            'createdBy' => $this->faker->name(),  // Nombre del creador del curso
            'cursoCategoriaId' => Categoria::factory(),  // Genera un ID de categoría usando el factory del modelo Categoria
        ];
    }
}
