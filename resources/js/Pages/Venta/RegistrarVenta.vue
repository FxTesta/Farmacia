<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { XIcon, PlusCircleIcon, SearchIcon } from "@heroicons/vue/outline";

</script>
<template>
    <Head title="Ventas" />
    <Layout>
        <template #header>
            <h2 class="flex uppercase font-bold text-xl text-gray-800 leading-tight">Ventas</h2>
        </template>
        <div class="max-h-full flex">
            <div class="ml-16 grid grid-rows-6 grid-cols-5 gap-4 mt-4 mb-3 px-3 pb-2">
                <!--div que contiene cabecera factura-->
                <div
                    class="flex flex-col col-end-5 col-start-1 row-start-1 row-end-4 bg-white rounded-md border border-black">
                    <div class="flex flex-row w-full p-3 mt-4 space-x-5">
                        <div class="space-x-1">
                            <label for="usuario" class="font-medium text-sm">Usuario:</label>
                            <input disabled type="text" id="usuario" name="usuario"
                                class="rounded-md w-[130px] h-[28px] bg-slate-200 text-slate-500">
                        </div>
                        <div class="space-x-1">
                            <label for="codigo" class="font-medium text-sm">Codigo:</label>
                            <input disabled type="text" id="codigo" name="codigo"
                                class="rounded-md w-[80px] h-[28px] bg-slate-200 text-slate-500">
                        </div>
                        <div class="space-x-1 -mt-1">
                            <label for="formadepago" class="font-medium text-sm">Forma de pago:</label>
                            <select id="formadepago" value="" class="rounded-md w-[150px] h-[35px] p-1">
                                <option disabled value="">Seleccione</option>
                                <option>Efectivo</option>
                                <option>Tarjeta Debito</option>
                                <option>Tarjeta Credito</option>
                            </select>
                        </div>
                        <div class="space-x-1">
                            <label for="fechafactura" class="font-medium text-sm">Fecha Factura:</label>
                            <input type="date" id="fechafactura" name="fechafactura"
                                class="rounded-md w-[130px] h-[28px] p-1">
                        </div>
                    </div>
                    <div class="flex flex-row w-full p-3 space-x-5 mt-2">
                        <div class="space-x-1">
                            <label for="cliente" class="font-medium text-sm">Cliente:</label>
                            <input type="text" id="cliente" name="cliente" class="rounded-md w-[130px] h-[28px]">
                        </div>
                        <div class="space-x-1 -mt-1">
                            <label for="comprobante" class="font-medium text-sm">Comprobante:</label>
                            <select id="comprobante" value="" class="rounded-md w-[150px] h-[35px] p-1">
                                <option disabled value="">Seleccione</option>
                                <option>Factura</option>
                                <option>Ticket</option>
                            </select>
                        </div>
                        <div class="space-x-1">
                            <label for="nrofactura" class="font-medium text-sm">Numero Factura:</label>
                            <input disabled type="number" id="nrofactura" name="nrofactura"
                                class="rounded-md w-[140px] h-[28px] bg-slate-200 text-slate-500">
                        </div>
                    </div>
                    <span class="mt-0 ml-2 uppercase font-bold text-lg">Producto</span>
                    <div
                        class="border-2 bg-gradient-to-b from-[#938E8E] to-[#524F4F] border-[#000000] rounded-md h-full overflow-hidden items-center flex">
                        <div class="flex flex-row text-left space-x-3 w-full justify-center mb-3">
                            <div
                                class=" mt-7 w-8 h-8 bg-white hover:bg-blue-300 hover:text-blue-700 text-blue-400 ring-1 focus:ring-set-2 ring-black rounded-full grid place-content-center">
                                <button type="button">
                                    <SearchIcon class="w-6 h-6" />
                                </button>
                            </div>
                            <div class="flex flex-col space-y-1">
                                <label for="codigobarra" class="font-inter font-bold text-white text-base font-shadow">Codigo
                                    de
                                    barras</label>
                                <input id="codigodebarra" type="number"
                                    class="font-inter text-base rounded-md border border-black bg-white w-[135px] p-1">
                            </div>
                            <div class="flex flex-col space-y-1">
                                <label for="descripcion"
                                    class="font-inter font-bold text-white text-base font-shadow">Descripcion</label>
                                <input id="descripcion" type="text"
                                    class="font-inter text-base rounded-md border border-black bg-white w-[270px] p-1">
                            </div>
                            <div class="flex flex-col space-y-1">
                                <label for="stock" class="font-inter font-bold text-white text-base font-shadow">Stock</label>
                                <input id="stock" type="number"
                                    class="font-inter text-base rounded-md border border-black bg-white w-[70px] p-1">
                            </div>
                            <div class="flex flex-col space-y-1">
                                <label for="precio"
                                    class="font-inter font-bold text-white text-base font-shadow">Precio</label>
                                <input id="precio" type="number"
                                    class="font-inter text-base rounded-md border border-black bg-white w-[120px] p-1">
                            </div>
                            <div class="flex flex-col space-y-1">
                                <label for="cantidad"
                                    class="font-inter font-bold text-white text-base font-shadow">Cantidad</label>
                                <input id="cantidad" type="number"
                                    class="font-inter text-base rounded-md border border-black bg-white w-[70px] p-1">
                            </div>
                            <div class="flex flex-col space-y-1">
                                <label for="total" class="font-inter font-bold text-white text-base font-shadow">Total</label>
                                <input id="total" type="number"
                                    class="font-inter text-base rounded-md border border-black bg-white w-[120px] p-1">
                            </div>
                            <div class="mt-7">
                                <button
                                    class="bg-white w-8 h-8 hover:bg-green-300  hover:text-green-700 text-green-500 ring-2 focus:ring-set-2 ring-blue-400 rounded-md grid place-content-center">
                                    <PlusCircleIcon class="w-7 h-7" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--div que contiene detalle factura-->
                <div
                    class="flex-1 row-start-4 row-end-7 col-end-5 col-start-1 bg-white overflow-hidden rounded-md border border-black">
                    <div class="h-full">
                        <div class="max-h-full flex flex-col">
                            <div class="px-3 mt-2 overflow-y-auto">

                                <table class="min-w-full">
                                    <thead>
                                        <tr class="text-left uppercase">
                                            <th>ID</th>
                                            <th>C. Barras</th>
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Dto.</th>
                                            <th>Importe</th>
                                            <th>...</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-400 divide-opacity-30">
                                        <tr v-for="item in Array.from({ length: 20 })">
                                            <td class="text-gray-700 py-4"> 1</td>
                                            <td class="text-gray-700 py-4">123123</td>
                                            <td class="text-gray-700 py-4 uppercase">dolanet</td>
                                            <td class="text-gray-700 py-4">20.000</td>
                                            <td class="text-gray-700 py-4">2</td>
                                            <td class="text-gray-700 py-4">15</td>
                                            <td class="text-gray-700 py-4">40.000</td>
                                            <td class="py-4">
                                                <div class="inline-flex">
                                                    <div>
                                                        <button
                                                            class="w-8 h-8 t hover:text-red-700 text-red-500 rounded-md grid place-content-center">
                                                            Remover
                                                        </button>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--div que contiene detalles de la venta-->
                <div class="col-start-5 row-start-1 row-end-7 bg-white overflow-hidden rounded-md border border-black">
                    <div class="flex flex-col justify-between h-full">
                        <div class="space-y-5">
                            <div class="flex justify-center mt-5">
                                <button
                                    class="px-2 py-2 bg-gradient-to-t from-[#2eff1b] to-[#3bca2f] rounded-md border border-black">
                                    <span class="font-bold text-2xl text-white uppercase italic"
                                        style="-webkit-text-stroke: 1px black;">realizar venta</span>
                                </button>
                            </div>
                            <div class="space-y-5">
                                <div class="ml-2 inline-flex space-x-2">
                                    <span class="block text-xl font-medium text-gray-700 uppercase">
                                        p. total:
                                    </span>
                                    <span class="text-xl font-bold text-red-500 uppercase">100.000 GS</span>
                                </div>
                                <div class="ml-2 inline-flex space-x-2">
                                    <label for="desc"
                                        class="block text-xl font-medium text-gray-700 uppercase">desc:</label>
                                    <input id="desc" type="number" placeholder="..."
                                        class=" w-[30%] h-8 rounded-md placeholder-slate-400 bg-white text-gray-600 border border-black">
                                </div>
                                <div class="ml-2 inline-flex space-x-2">
                                    <label for="iva" class="block text-xl font-medium text-gray-700 uppercase">iva:</label>
                                    <input id="iva" type="number" placeholder="..."
                                        class=" w-[30%] h-8 rounded-md placeholder-slate-400 bg-white text-gray-600 border border-black">
                                </div>
                                <div class="ml-2 pt-4 inline-flex space-x-2">
                                    <input id="tarjeta" type="checkbox">
                                    <label for="tarjeta" class="block text-sm font-bold text-black ">Paga con tarjeta
                                        +5%
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="py-2 space-y-4">
                            <div class="ml-2 inline-flex space-x-2">
                                <label for="pagacon" class="block text-xl font-medium text-gray-700 uppercase">Paga
                                    con:</label>
                                <input id="pagacon" type="number" placeholder="..."
                                    class=" w-[50%] h-8 rounded-md placeholder-slate-400 bg-white text-gray-600 border border-black">
                            </div>
                            <div class="ml-2 inline-flex space-x-2">
                                <span class="block text-xl font-medium text-gray-700 uppercase">
                                    cambio:
                                </span>
                                <span class="text-xl font-bold text-blue-500 uppercase">23.000 GS</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style>
thead {
    background-color: white;
    position: sticky;
    top: 0;
    z-index: 2;
}


th {
    position: relative;

}

/* Pseudo-elemento ::before para crear un borde inferior */
th::before {
    content: '';
    position: absolute;
    bottom: -2px;
    /* Ajusta el valor según el grosor del borde */
    left: 0;
    width: 100%;
    height: 2px;
    /* Grosor del borde */
    background-color: #6b7280;
    /* Color del borde */
}
</style>