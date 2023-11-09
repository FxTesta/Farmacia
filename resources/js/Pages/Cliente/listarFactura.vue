<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import SideBar from '@/Components/SideBar.vue';
import { SearchIcon, EyeIcon } from "@heroicons/vue/outline";
import { ref, watch } from "vue";
import Pagination from '@/Components/Pagination.vue';

import _ from 'lodash';

const props = defineProps({
    cliente: Object,
    facturaVenta: Object,
    filters: Object,
});

let search = ref(props.filters.search);

watch(search, _.debounce(function (value) {
    router.get(`/cliente/listar/${props.cliente.id}`, { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

</script>
<template>
    <Head title="Historial del cliente" />

    <SideBar />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="flex uppercase font-bold text-xl text-gray-800 leading-tight">Ventas por clientes</h2>
        </template>

        <div class="py-12">
            <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 ml-16">
                <div class="-mt-10">

                    <div class="flex justify-between">
                        <!-- <span class="pt-2 pr-6">hola</span>-->
                        <div class="mt-2">
                            <Link class="w-8 h-8 bg-black/20 hover:bg-black/30 rounded-md grid place-content-center"
                                as="button" :href="route('cliente')">
                            <ArrowLeftIcon class="w-5 h-5" />
                            </Link>
                        </div>
                        <div class="inline-flex space-x-2 mb-2 mt-2 mr-2">

                            <div class="relative flex items-center  focus-within:text-gray-400">
                                <SearchIcon class="w-5 h-5 absolute ml-3 pointer-events-none" />
                                <input id="searchcompra" v-model="search" type="text" placeholder="Buscar Ventas"
                                    autocomplete="off" aria-label="Buscar Ventas"
                                    class="pr-3 pl-10 py-1 text-sm font-medium text-gray-700 bg-cyan-100 rounded-2xl border-none ring-2 ring-cyan-400 focus:ring-cyan-300 focus:ring-2" />
                            </div>
                        </div>
                     
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="px-10 pb-10 pt-2 overflow-y-auto">

                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-slate-300 text-gray-700 text-left">
                                    <th>ID venta</th>
                                    <th>Cliente</th>
                                    <th>Factura</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-400 divide-opacity-30">
                                <tr v-for="factura_ventas in facturaVenta.data">
                                    <td class="py-3">{{ factura_ventas.id }}</td>
                                    <td class="py-3">{{ factura_ventas.cleinte_nombre }}</td>
                                    <td class="py-3">{{ factura_ventas.nrofactura }}</td>
                                    <td class="py-3">{{ factura_ventas.preciototal }}</td>
                                    <td class="py-3">{{ factura_ventas.fechafactura }}</td>
                                    <td class="py-4">
                                        <Link :href="`/venta/listar/detalle/${factura_ventas.id}`" as="button"
                                            class="text-white font-bold bg-cyan-500 hover:bg-cyan-600 rounded-xl grid place-content-center">
                                        <button class="px-2 py-1">
                                            Detalle
                                        </button>
                                        </Link>
                                    </td>
                                </tr>
                                <!--div se muestra en caso que no hayan registros-->
                                <div v-if="facturaVenta.data.length <= 0" class="p-3">
                                    <div class="absolute left-2/4 -translate-x-1/2">
                                        <span class="font-serif text-xl text-slate-500 uppercase">sin registros</span>
                                    </div>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--PAGINACION-->
                <Pagination :links="facturaVenta.links" class="mt-6" />

            </div>
        </div>
    </AuthenticatedLayout>
</template>
