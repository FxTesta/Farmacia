<?php

namespace tests\Feature;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProductoControllerTest extends TestCase
{
    //use RefreshDatabase;  // Refrescar la base de datos después de cada prueba

    public function test_loguearse_y_probar_controlador_productos(): void
    {

        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => ('password'),
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);

        //PROBAR LA PAGINA DE PRODUCTOS
        $response1 = $this->get('/producto');
        $response1->assertStatus(200);

        //PROBAR LA PAGINA DE CREAR PRODUCTOS
        $response2 = $this->get('/crear-producto');
        $response2->assertStatus(200);

        // Define el valor máximo permitido para el ID
        $maxId = 100;

        // Obtiene el último ID utilizado en la tabla de productos (ajusta el nombre de la tabla según sea necesario)
        $lastId = DB::table('productos')->max('id');

        // Verifica si el último ID utilizado supera el valor máximo permitido
        if ($lastId >= $maxId) {
            // Se lanzará una excepción
            throw new \Exception("Se ha alcanzado el límite máximo de IDs para los productos.");
        }

        // Incrementa el último ID para la siguiente inserción
        $nextId = $lastId + 1;

        //PROBAR LA CREACION DE UN PRODUCTO
        $this->withoutExceptionHandling(); // parar ver errores detallados durante la prueba

        $data = [
            'id' => $nextId, // Aquí se utiliza el ID incrementado
            'categoria' => 'A - Tracto alimentario y metabolismo',
            'descripcion' => 'Descripción del producto',
            'marca' => 'Marca del producto',
            'droga' => 'Droga del producto', 
            'venta' => 'Libre',
            'laboratorio' => 'Laboratorio del producto',
            'vencimiento' => '2023-12-01',
            'alerta' => '2023-11-01',
            'codigo' => 1234567,
            'precioventa' => 100,
            'preciocompra' => 80,
            'stock' => 10,
            'stockmin' => 2,
            'estante' => 'Estante 1',
            'presentacion' => 'Comprimidos',
            'descuento' => 5,
        ];

        $response3 = $this->post(route('producto.store'), $data);

        $response3->assertStatus(302); // Redirección, si todo va bien (en este caso, a la página de inicio)
  
        $this->assertDatabaseHas('productos', $data); // Verifica que los datos estén en la base de datos

        //PROBAR LA pagina de EDICION DE UN PRODUCTO
        $response4 = $this->get("/editar-producto/{$nextId}");
        $response4->assertStatus(200);

        //PROBAR LA EDICION DE UN PRODUCTO
        $data = [
            'id' => $nextId, // Aquí se utiliza el ID incrementado
            'categoria' => 'A - Tracto alimentario y metabolismo',
            'descripcion' => 'Descripción del producto',
            'marca' => 'Marca del producto',
            'droga' => 'Droga del producto',
            'venta' => 'Libre',
            'laboratorio' => 'Laboratorio del producto',
            'vencimiento' => '2023-12-01',
            'alerta' => '2023-11-01',
            'codigo' => 1234567,
            'precioventa' => 100,
            'preciocompra' => 80,
            'stock' => 10,
            'stockmin' => 2,
            'estante' => 'Estante 1',
            'presentacion' => 'Comprimidos',
            'descuento' => 5,
        ];

        $response5 = $this->put("/editar-producto/{$nextId}", $data);

        $response5->assertStatus(302); // Redirección, si todo va bien (en este caso, a la página de inicio)

        //PROBAR LA ELIMINACION DE UN PRODUCTO
        $response6 = $this->delete("/producto/delete/{$nextId}");
        $response6->assertStatus(302); // Redirección, si todo va bien (en este caso, a la página de inicio)

        

    }

    public function test_creando_multiples_productos(): void
    {
    //Loguearse

    $response = $this->post('/login', [
        'username' => 'admin',
        'password' => 'password', // Password as string, not enclosed in parenthesis.
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);

    // Crear 100 Productos
    Producto::factory()->count(100)->create();

    // Asegurarse que los productos se han creado correctamente
    $this->assertCount(100, Producto::all());
    }

}
