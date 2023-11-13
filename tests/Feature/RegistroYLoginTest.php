<?php

namespace tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegistroYLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_página_de_registro(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_página_de_login(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_creando_usuario_de_prueba_para_loguearse(): void
    {
        $user = User::factory()->create([
            'username' => 'creando',
            'password' => Hash::make('otrapassword'),
        ]);

        $response = $this->post('/login', [
            'username' => 'creando',
            'password' => ('otrapassword'),
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
    

    public function test_error_al_intentar_loguearse_con_usuario_no_registrado(): void
    {
        $response = $this->post('/login', [
            'username' => 'corre', 
            'password' => 'wrong-password',
        ]);
    
        $response->assertSessionHasErrors();
    }

}
