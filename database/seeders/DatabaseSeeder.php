<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*\App\Models\User::factory(10)->create();

            \App\Models\User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                ]);
            */

        $this->call(AdminSeeder::class);

       
        Producto::factory(50)->create();
        Cliente::factory(50)->create();
        Proveedor::factory(50)->create();
    }
}
