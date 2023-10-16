<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { XIcon, PlusCircleIcon, SearchIcon } from "@heroicons/vue/outline";
import BuscarProducto from '@/Pages/Pruebas/BuscarProducto copy.vue';
import { ref } from 'vue'



let producto = ref();

const productoEncontrado = (event) => {

    producto.value = event;
    console.log(producto.value);
}

function loadProducto(query, setOptions) {
    fetch("http://127.0.0.1:8000/buscarproductoventa?query=" + query)
        .then(response => response.json())
        .then(results => {
            setOptions(
                results.map(producto => {
                    return {
                        value: producto.id,
                        label: producto.marca,
                        codigo: producto.codigo,
                        stock: producto.stock,
                        droga: producto.droga,
                        descripcion: producto.descripcion,
                        precioventa: producto.precioventa,
                        venta: producto.venta,
                        vencimiento: producto.vencimiento,
                        laboratorio: producto.laboratorio,
                    };
                })
            );
        });
}

</script>
<template>
    <Head title="Pruebas" />
    <div class="min-h-screen w-screen bg-black">
        <div class="p-4 flex flex-col space-y-4 py-3">
           <!-- <div
                                class="w-8 h-8 bg-white hover:bg-blue-300 hover:text-blue-700 text-blue-400 ring-1 focus:ring-set-2 ring-black rounded-full grid place-content-center">
                                <button type="button">
                                    <SearchIcon class="w-6 h-6" />
                                </button>
                            </div>-->
            <BuscarProducto :load-options="loadProducto"  @send-product="productoEncontrado"/>
            <span v-if="producto" class="text-white">{{producto.label}}</span>
            <div>
                <label class="text-white block" for="codigodebarra">Codigo de barra</label>
                <input class="bg-gray-800 text-white" type="text" id="codigodebarra">
            </div>
            <div>
                <label class="text-white block" for="descripcion">Descripcion</label>
                <input class="bg-gray-800 text-white" type="text" id="descripcion">
            </div>
            <div>
                <label class="text-white block" for="stock">Stock</label>
                <input class="bg-gray-800 text-white" type="text" id="stock">
            </div>
            <div>
                <label class="text-white block" for="precio">Precio</label>
                <input class="bg-gray-800 text-white" type="text" id="precio">
            </div>
            <div>
                <label class="text-white block" for="cantidad">Cantidad</label>
                <input class="bg-gray-800 text-white" type="text" id="cantidad">
            </div>
            <div>
                <label class="text-white block" for="total">Total</label>
                <input class="bg-gray-800 text-white" type="text" id="total">
            </div>
            <div>
                <button class="px-2 py-2 bg-gray-800">
                    <span class="text-white">Guardar</span>
                </button>
            </div>
        </div>
    </div>
</template>