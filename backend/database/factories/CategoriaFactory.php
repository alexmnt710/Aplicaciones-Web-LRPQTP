<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'categoriaName' => $this->faker->word(),  // Genera un nombre aleatorio de una palabra
            'categoriaDescripcion' => $this->faker->sentence(),  // Genera una descripción aleatoria de una oración
            'categoriaImagen' => $this->faker->imageUrl(),  // Genera una URL de imagen aleatoria
        ];
    }
}
