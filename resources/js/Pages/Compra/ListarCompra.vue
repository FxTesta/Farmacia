<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import SideBar from '@/Components/SideBar.vue';
import { SearchIcon, DocumentAddIcon } from "@heroicons/vue/outline";
import { ref, watch } from "vue";
import Pagination from '@/Components/Pagination.vue';

import _ from 'lodash';

const props = defineProps({
    lista: Object,
    filters: Object,
});

let search = ref(props.filters.search);

watch(search, _.debounce(function (value) {
    router.get('/compra/listar', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

</script>
<template>
    <Head title="Lista Compras" />

    <SideBar />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="flex uppercase font-bold text-xl text-gray-800 leading-tight">Compras realizadas</h2>
        </template>

        <div class="py-12">

            <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 ml-16">
                <div class="-mt-10">
                    <div class="flex justify-end">
                        <!-- <span class="pt-2 pr-6">hola</span>-->
                        <div class="inline-flex space-x-2 mb-2 mt-2 mr-2">
<!--                             <div class="mt-1">
                                <Link :href="`/nota-credito/`" as="button"
                                    class="hover:bg-gray-300 ring-2 focus:ring-set-2 ring-cyan-400 rounded-full">
                                <DocumentAddIcon class="h-6 w-6 inline mb-1 ml-2 rounded-full" />
                                <a class="text-sm font-medium rounded-md mr-3"> Crear Nota Credito </a>
                                </Link>
                            </div> -->
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
                                    <th>ID compra</th>
                                    <th>Proveedor</th>
                                    <th>Factura</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-400 divide-opacity-30">
                                <tr v-for="factura_compras in lista.data">
                                    <td class="py-3">{{ factura_compras.id }}</td>
                                    <td class="py-3">{{ factura_compras.proveedor_nombre }}</td>
                                    <td class="py-3">{{ factura_compras.nrofactura }}</td>
                                    <td class="py-3">{{ factura_compras.preciototal }}</td>
                                    <td class="py-3">{{ factura_compras.fechafactura }}</td>
                                    <td class="py-4">
                                        <div class="inline-flex space-x-2">
                                        <Link :href="`/compra/listar/detalle/${factura_compras.id}`" as="button"
                                                    class="text-white font-bold bg-cyan-500 hover:bg-cyan-600 rounded-xl grid place-content-center">
                                                <button class="px-2 py-1">
                                                    Detalle
                                                </button>
                                        </Link>
                                        <div>
                                           <!--  Nota Credito -->
                                        </div>
                                        </div>
                                    </td>
                                </tr>
                                <!--div se muestra en caso que no hayan registros-->
                                <div v-if="lista.data.length <= 0" class="p-3">
                                    <div class="absolute left-2/4 -translate-x-1/2">
                                        <span class="font-serif text-xl text-slate-500 uppercase">sin compras</span>
                                    </div>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--PAGINACION-->
                <Pagination :links="lista.links" class="mt-6" />

            </div>
        </div>
    </AuthenticatedLayout>
</template>
