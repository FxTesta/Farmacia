<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import SideBar from '@/Components/SideBar.vue';
import { SearchIcon, DocumentAddIcon } from "@heroicons/vue/outline";
import { ref, watch } from "vue";
import Pagination from '@/Components/Pagination.vue';
import DetalleOrdenCompra from '@/Pages/Compra/OrdenCompra/DetalleOrdenCompra.vue';
import CambiarEstado from './CambiarEstado.vue';

import _ from 'lodash';

const props = defineProps({
    ordencompra: Object,
    filters: Object,
});

let search = ref(props.filters.search);

watch(search, _.debounce(function (value) {
    router.get('/compra/ordencompra', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

</script>
<template>
    <Head title="Orden Compra" />

    <SideBar />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="flex uppercase font-bold text-xl text-gray-800 leading-tight">Ordenes de Compra</h2>
        </template>

        <div class="py-12">

            <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 ml-16">
                <div class="-mt-10">
                    <div class="flex justify-end">
                        <div class="inline-flex space-x-2 mb-2 mt-2 mr-2">
                            <div class="relative flex items-center  focus-within:text-gray-400">
                                <SearchIcon class="w-5 h-5 absolute ml-3 pointer-events-none" />
                                <input id="searchcompra" v-model="search" type="text" placeholder="Buscar Compras"
                                    autocomplete="off" aria-label="Buscar Compras"
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
                                    <th>#Orden</th>
                                    <th>Usuario</th>
                                    <th>Proveedor</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-400 divide-opacity-30">
                                <tr v-for="orden_compra in ordencompra.data">
                                    <td class="py-3">{{ orden_compra.id }}</td>
                                    <td class="py-3">{{ orden_compra.username }}</td>
                                    <td class="py-3">{{ orden_compra.proveedornombre }}</td>
                                    <td class="py-3 text-white">
                                        <div>
                                        <span
                                        class="bg-blue-700 rounded-md p-1 text-center"
                                        :class="{ 'bg-green-400 text-white': orden_compra.estado === 'Finalizado' }"
                                            >{{ orden_compra.estado }}
                                        </span>
                                            
                                        </div>
                                    </td>
                                    <td class="py-3">{{ orden_compra.fecha }}</td>
                                    <td class="py-4">
                                        <div class="inline-flex space-x-2 items-center">
                                            <DetalleOrdenCompra :ordencompra="orden_compra"/>
                                            <div v-if="orden_compra.estado !== 'Finalizado'">
                                                <CambiarEstado :ordencompra="orden_compra"/>
                                            </div>
                                        </div>
                                        
                                    </td>
                                </tr>
                                <!--div se muestra en caso que no hayan registros-->
                                <div v-if="ordencompra.data.length <= 0" class="p-3">
                                    <div class="absolute left-2/4 -translate-x-1/2">
                                        <span class="font-serif text-xl text-slate-500 uppercase">sin ordenes</span>
                                    </div>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--PAGINACION-->
                <Pagination :links="ordencompra.links" class="mt-6" />

            </div>
        </div>
    </AuthenticatedLayout>
</template>
