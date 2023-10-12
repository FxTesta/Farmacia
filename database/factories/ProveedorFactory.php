<?php

namespace Database\Factories;

use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProveedorFactory extends Factory
{
    protected $model = Proveedor::class;

    public function definition(): array
    {

        return [
            'empresa' => $this->faker->company,
            'name' => $this->faker->name,
            'ruc' => $this->faker->unique()->numerify('##########'), // 10 digits
            'dv' => $this->faker->optional()->numerify('##'),
            'direccion' => $this->faker->address,
            'barrio' => $this->faker->optional()->word,  // Puedes cambiar 'word' por lo que corresponda
            'callelateral' => $this->faker->optional()->word,  // Puedes cambiar 'word' por lo que corresponda
            'referencia' => $this->faker->optional()->word,  // Puedes cambiar 'word' por lo que corresponda
            'telefono' => $this->faker->numerify('##########'),
            'email' => $this->faker->optional()->safeEmail,
        ];
        
    }

    private function calculateDV(string $ruc, int $basemax = 11): string
    {
        if (trim($ruc) === '') {
            return '';
        }

        $rucStr = strval($ruc);
        $numero_al = "";
        $k = 2;
        $total = 0;

        for ($i = 0; $i < strlen($rucStr); $i++) {
            $caracter = strtoupper($rucStr[$i]);
            $caracterAscii = ord($caracter);

            if ($caracterAscii >= 48 && $caracterAscii <= 57) { // ASCII 0-9
                $numero_al .= $caracter;
            } else {
                $numero_al .= strval($caracterAscii);
            }
        }

        for ($i = strlen($numero_al); $i > 0; $i--) {
            if ($k > $basemax) {
                $k = 2;
            }

            $digito = $numero_al[$i - 1];
            $numero_aux = intval($digito);
            $total += $numero_aux * $k;
            $k++;
        }

        $resto = $total % 11;
        $digitoVerificador = ($resto > 1) ? 11 - $resto : 0;

        return strval($digitoVerificador);
    }
}
