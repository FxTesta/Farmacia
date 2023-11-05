<script setup>
//import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Layout from '@/Layouts/Layout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    detallefact: Object,
});
</script>
<template>
    <Head title="DETALLE DE LA COMPRA" />
    <Layout>
        <template #header>
            <h2 class="flex uppercase font-bold text-xl text-gray-800 leading-tight">DETALLE DE LA COMPRA</h2>
        </template>
        <div class="flex flex-col h-full ml-16">

            <div class="px-4  pt-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 w-fit border  border-blue-500">
                    <div class="overflow-y-auto">


                        <div class="mt-2 ml-4 inline-flex space-x-2">
                            <div class="flex space-x-2">
                                <InputLabel for="usuario" value="Usuario:" class="text-gray-600 mt-2 " />

                                <TextInput id="usuario" type="text" class="mt-1 w-40 h-8 bg-gray-200 text-gray-600"
                                    v-model="props.detallefact.username" disabled />
                            </div>

                            <div class="flex space-x-2">
                                <InputLabel for="codigo" value="Codigo:" class="text-gray-600 mt-2" />
                                <TextInput id="codigo" type="number" v-model="props.detallefact.users_id"
                                    class="mt-1 bg-gray-200 text-gray-600 sm:rounded-lg w-1/3 h-8" disabled />
                            </div>
                        </div>

                        <div class="mt-4 ml-4">

                            <div class="inline-flex space-x-2">

                                <div class="flex space-x-2">
                                    <span class="block font-medium text-sm text-gray-700 mt-2">Proveedor</span>
                                    <TextInput id="proveedor" type="text" class="mt-1 w-40 h-8 bg-gray-200 text-gray-600"
                                        v-model="props.detallefact.proveedor_nombre" disabled />
                                </div>

                                <div class="flex space-x-2">
                                    <InputLabel for="nrofactura" value="Nro Factura:" class="text-gray-600 mt-2 " />

                                    <TextInput id="nrofactura" type="number" class="mt-1 w-40 h-8 bg-gray-200 text-gray-600"
                                        v-model="props.detallefact.nrofactura" required autocomplete="nrofactura" />
                                </div>

                                <div class="flex space-x-2">
                                    <InputLabel for="fechafactura" value="Fecha Factura:" class="text-gray-600 mt-2 " />

                                    <TextInput id="fechafactura" type="date" class="mt-1 w-40 h-8 bg-gray-200 text-gray-600"
                                        v-model="props.detallefact.fechafactura" required autocomplete="fechafactura" />
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-hidden">
                <div class="h-full px-4 pb-4 pt-4">
                    <div class=" bg-white max-h-full flex flex-col rounded-md border border-blue-500">

                        <div class="px-10 pb-10 pt-2 overflow-y-auto">

                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-slate-300 text-gray-700 text-left">
                                        <th>ID</th>
                                        <th>C. Barras</th>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Importe</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-400 divide-opacity-30">
                                    <tr v-for="factura_compras in detallefact.detallefactura">
                                        <td class="text-gray-700 py-4"> {{ factura_compras.producto_id }} </td>
                                        <td class="text-gray-700 py-4">{{ factura_compras.codigo }}</td>
                                        <td class="text-gray-700 py-4">{{ factura_compras.marca }}</td>
                                        <td class="text-gray-700 py-4">{{ factura_compras.preciocompra }}</td>
                                        <td class="text-gray-700 py-4">{{ factura_compras.cantidad }}</td>
                                        <td class="text-gray-700 py-4">{{ factura_compras.total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>