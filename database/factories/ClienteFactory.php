<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClienteFactory extends Factory
{

    protected $model = Cliente::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'cedula' => $this->faker->unique()->randomNumber(8),
            'ruc' => $this->faker->unique()->randomNumber(8),
            'dv' => $this->faker->randomDigit,
            'direccion' => $this->faker->address,
            'barrio' => $this->faker->city,
            'callelateral' => $this->faker->streetName,
            'referencia' => $this->faker->sentence,
            'telefono' => $this->faker->numerify('##########'),
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
