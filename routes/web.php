<?php

use Dompdf\Dompdf;
use Inertia\Inertia;
use App\Models\StockAudit;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PruebacomboController;
use App\Http\Controllers\StockAuditController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\VentaController;

//hola prueba
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


//CLIENTES
Route::controller(ClienteController::class)->middleware('auth')->group(function () {

    Route::get('/cliente', 'index')->name('cliente');
    Route::get('/crear-cliente', 'create')->name('cliente.create');
    Route::post('/cliente', 'store')->name('cliente.store');
    Route::get('/editar-cliente/{cliente_id}', 'edit')->name('cliente.edit');
    Route::put('/editar-cliente/{cliente}', 'update')->name('cliente.update');
    Route::delete('/cliente/delete/{cliente}', 'destroy')->name('cliente.destroy');
});


//Productos
Route::controller(ProductoController::class)->middleware('auth')->group(function () {
   
    Route::get('/producto', 'index')->name('producto');
  //  Route::get('/producto/pdf', 'pdf')->name('producto.pdf');
    Route::get('/crear-producto', 'create')->name('producto.create');
    Route::post('/producto', 'store')->name('producto.store');
    Route::get('/editar-producto/{producto_id}', 'edit')->name('producto.edit');
    Route::put('/editar-producto/{producto}', 'update')->name('producto.update');
    Route::put('/producto/{productos}', 'updatestock')->name('producto.updatestock');
    Route::delete('/producto/delete/{producto}', 'destroy')->name('producto.destroy');


});


//PROVEEDORES
Route::controller(ProveedorController::class)->middleware('auth')->group(function () {

    Route::get('/proveedor', 'index')->name('proveedor');
    Route::get('/crear-proveedor', 'create')->name('proveedor.create');
    Route::post('/proveedor', 'store')->name('proveedor.store');
    Route::get('/editar-proveedor/{proveedor_id}', 'edit')->name('proveedor.edit');
    Route::put('/editar-proveedor/{proveedor}', 'update')->name('proveedor.update');
    Route::delete('/proveedor/delete/{proveedor}', 'destroy')->name('proveedor.destroy');

});

//COMPRAS
Route::controller(CompraController::class)->middleware('auth')->group(function () {

    Route::get('/compra', 'registarCompra')->name('compra');

    Route::get('/proveedores', 'buscarProveedor')->name('buscarproveedor');
    Route::get('/buscarproducto', 'buscarProducto')->name('buscarproducto');
    Route::post('/guardarcompra', 'store')->name('compra.store');

    //Listar compras
    Route::get('/compra/listar', 'listarCompras')->name('listarcompras');

    Route::get('/compra/listar/detalle/{detallefact}', 'detalles')->name('detalle');
});

//VENTAS
Route::controller(VentaController::class)->middleware('auth')->group(function () {

    Route::get('/venta', 'registarVenta')->name('venta');
    Route::get('/buscarproductoventa', 'buscarProducto')->name('buscarproductoventa');
    Route::get('/buscarcliente', 'buscarCliente')->name('buscarcliente');
    Route::get('/obtenerfactura', 'obtenerFactura')->name('obtenerfactura'); //obtiene el valor maximo de la factura
    Route::post('/guardarventa', 'store')->name('venta.store');


    //Route::get('/proveedores', 'buscarProveedor')->name('buscarproveedor');
    //Route::get('/buscarproducto', 'buscarProducto')->name('buscarproducto');
    //Route::post('/guardarcompra', 'store')->name('compra.store');

    //Listar compras
    //Route::get('/compra/listar', 'listarCompras')->name('listarcompras');

    //Route::get('/compra/listar/detalle/{detallefact}', 'detalles')->name('detalle');
});


//CONFIGURACIONES (PARA PREFIJO DE FACTURA Y TIMBRADO)
Route::controller(SettingController::class)->middleware('auth')->group(function () {

    Route::get('/configuracion', 'index')->name('configuracion');
    Route::get('/crear-configuracion', 'create')->name('configuracion.create');
    Route::post('/configuracion', 'store')->name('configuracion.store');
    Route::get('/editar-configuracion/{configuracion_id}', 'edit')->name('configuracion.edit');
    Route::put('/editar-configuracion/{configuracion}', 'update')->name('configuracion.update');
});


//AUDITORIA
Route::controller(StockAuditController::class)->middleware('auth')->group(function () {

    Route::post('/auditoria', 'index')->name('auditoria');
    

});



Route::get('/generar-pdf' , function () {
    
      
    // Crear una instancia de Dompdf
    $dompdf = new Dompdf();

    $productos= StockAudit::all();
    // Renderizar la vista Blade que deseas convertir a PDF
    //aca cambieeee
    $html = view('template',compact('productos'))->render();

    // Cargar el contenido HTML en Dompdf
    $dompdf->loadHtml($html);

    // (Opcional) Configurar opciones de Dompdf, como tamaño de papel y orientación
    $dompdf->setPaper('a5', 'landscape');

    // Renderizar el contenido HTML a PDF
    $dompdf->render();

    // Generar y mostrar el PDF en el navegador
    $dompdf->stream('archivo.pdf', ['Attachment' => false]);
    
});
//Route::get('/auditoria', function(){ $products=StockAudit::query('id', 'DESC')->get(); return view('template', compact('products')); })
//para qie funcione instalar composer require dompdf/dompdf




//dominio:farmauno.rf.gd




Route::controller(PruebaController::class)->middleware('auth')->group(function () {

    Route::get('/prueba', 'index')->name('prueba');
    //Route::get('/buscarproductoventa', 'buscarProducto')->name('buscarproductoventa');

    //Route::get('/proveedores', 'buscarProveedor')->name('buscarproveedor');
    //Route::get('/buscarproducto', 'buscarProducto')->name('buscarproducto');
    //Route::post('/guardarcompra', 'store')->name('compra.store');

    //Listar compras
    //Route::get('/compra/listar', 'listarCompras')->name('listarcompras');

    //Route::get('/compra/listar/detalle/{detallefact}', 'detalles')->name('detalle');
});
