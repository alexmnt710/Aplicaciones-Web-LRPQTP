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
            'categoriaImagen' => 'https://ecuador.unir.net/wp-content/uploads/sites/8/2022/06/diverse-office-enthusiastic-white-it-programmer-working-on-desktop-picture-id1354205065.jpg',  // Genera una URL de imagen aleatoria
        ];
    }
}
