<?php

namespace tests\Feature;

use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class ClienteControllerTest extends TestCase
{
    use RefreshDatabase;  // Refrescar la base de datos después de cada prueba

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\AdminSeeder::class);
        // para ejecutar todos los seeders
        // $this->seed();
    }

    public function test_creando_multiples_clientes(): void
    {
    // Crear Usuario y Loguearse

    $response = $this->post('/login', [
        'username' => 'admin',
        'password' => 'password', 
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);

    // Crear 100 Cliente
    Cliente::factory()->count(100)->create();

    // Asegurarse que los clientes se han creado correctamente
    $this->assertCount(100, Cliente::all());
    }

}
