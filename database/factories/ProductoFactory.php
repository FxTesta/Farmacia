<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;


class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        // Fecha aleatoria para vencimiento y alerta
        $vencimiento = $this->faker->dateTimeBetween('now', '+2 years');
        $alerta = Carbon::instance($vencimiento)->subMonths($this->faker->numberBetween(1, 6));
        $categorias = [
            'A - Tracto alimentario y metabolismo',
            'B - Sangre y órganos hematopoyéticos',
            'C - Sistema cardiovascular',
            'D - Dermatológicos',
            'G - Sistema genitourinario y hormonas sexuales',
            'H - Preparados hormonales sistémicos, excluyendo hormonas sexuales e insulinas',
            'J - Antiinfecciosos para uso sistémico',
            'L - Antineoplásicos e inmunomoduladores',
            'M - Sistema musculoesquelético',
            'N - Sistema nervioso',
            'P - Antiparasitarios, insecticidas y repelentes',
            'R - Sistema respiratorio',
            'S - Órganos de los sentidos',
            'V - Varios',
            'W - Cosméticos',
            'X - Alimentos y dietéticos',
            'Otro',
        ];
        

        return [
            'categoria' => $this->faker->randomElement($categorias), // Selecciona una categoría aleatoria del array.
            'descripcion' => $this->faker->sentence,
            'marca' => $this->faker->word,
            'droga' => $this->faker->word,
            'venta' => $this->faker->randomElement(['Libre', 'Con receta']),
            'laboratorio' => $this->faker->optional()->company,
            'vencimiento' => $vencimiento,
            'alerta' => $alerta,
            'codigo' => $this->faker->unique()->numberBetween(1000000, 9999999),
            'precioventa' => $this->faker->randomFloat(2, 1, 1000),
            'preciocompra' => $this->faker->randomFloat(2, 1, 1000),
            'stock' => $this->faker->optional()->numberBetween(1, 100),
            'stockmin' => $this->faker->optional()->numberBetween(1, 10),
            'descuento' => $this->faker->optional()->numberBetween(1, 100),
            'presentacion' => $this->faker->word,
            'estante' => $this->faker->word,
        ];
    }
}
