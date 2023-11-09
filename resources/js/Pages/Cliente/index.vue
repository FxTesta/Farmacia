<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import SideBar from '@/Components/SideBar.vue';
import { PencilIcon, UserAddIcon, SearchIcon } from "@heroicons/vue/outline";
import Delete from '@/Pages/Cliente/delete.vue';
import { ref, watch } from "vue";
import Pagination from '@/Components/Pagination.vue';
import _ from 'lodash';

const props = defineProps({
    cliente: Object,
    filters: Object,
});

let search = ref(props.filters.search);

watch(search, _.debounce(function (value) {
    router.get('/cliente', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

</script>
<template>
    <Head title="Dashboard" />

    <SideBar />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="flex uppercase font-bold text-xl text-gray-800 leading-tight">Clientes</h2>
        </template>

        <div class="py-12">

            <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 ml-16">
                <div class="-mt-10">
                    <div class="flex justify-end">
                        <!-- <span class="pt-2 pr-6">hola</span>-->
                        <div class="inline-flex space-x-2 mb-2 mt-2 mr-2">
                            <div class="mt-1">
                                <Link :href="`/crear-cliente/`" as="button"
                                    class="hover:bg-gray-300 ring-2 focus:ring-set-2 ring-cyan-400 rounded-full">
                                <UserAddIcon class="h-6 w-6 inline mb-1 ml-2 rounded-full" />
                                <a class="text-sm font-medium rounded-md mr-3"> Crear CLiente </a>
                                </Link>
                            </div>
                            <div class="relative flex items-center  focus-within:text-gray-400">
                                <SearchIcon class="w-5 h-5 absolute ml-3 pointer-events-none" />
                                <input id="searchcliente" v-model="search" type="text" placeholder="Buscar Clientes" autocomplete="off"
                                    aria-label="Buscar Clientes"
                                    class="pr-3 pl-10 py-1 text-sm font-medium text-gray-700 bg-cyan-100 rounded-2xl border-none ring-2 ring-cyan-400 focus:ring-cyan-300 focus:ring-2" />
                            </div>
                        </div>
                    </div>
                </div>
                <span>{{ }}</span>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="px-10 pb-10 pt-2 overflow-y-auto">

                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-slate-300 text-gray-700 text-left">
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Cédula</th>
                                    <th>RUC</th>
                                    <th>DV</th>
                                    <th>Dirección</th>
                                    <th>Descuento</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                    
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-400 divide-opacity-30">
                                <tr v-for="clientes in cliente.data">
                                    <td class="text-gray-700 py-4">{{ clientes.id }}</td>
                                    <td class="text-gray-700 py-4">{{ clientes.name }}</td>
                                    <td class="text-gray-700 py-4">{{ clientes.cedula }}</td>
                                    <td class="text-gray-700 py-4">{{ clientes.ruc }}</td>
                                    <td class="text-gray-700 py-4">{{ clientes.dv }}</td>
                                    <td class="text-gray-700 py-4">{{ clientes.direccion }}</td>
                                    <td class="text-gray-700 py-4">{{ clientes.descuento }}</td>
                                    <td class="py-4 inline-flex">{{ clientes.estado }}</td>
                                    <td class="py-4">
                                        <div class="inline-flex">
                                            <Link :href="`/editar-cliente/${clientes.id}`" as="button"
                                                class="w-8 h-8 t hover:bg-black/30 rounded-md grid place-content-center">
                                            <PencilIcon class="w-6 h-6" />
                                            </Link>

                                            <div>
                                                <Delete :cliente="clientes" :key="clientes.id" />
                                            </div>
                                            <div>
                                                <Link :href="`/cliente/listar/${clientes.id}`" as="button"
                                                    class="text-white font-bold bg-cyan-500 hover:bg-cyan-600 rounded-xl grid place-content-center">
                                                <button class="px-2 py-1">
                                                    Historial
                                                </button>
                                                 </Link>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                <div v-if="cliente.data.length <= 0" class="p-4">
                                    <div class="absolute left-2/4 -translate-x-1/2"
                                        >
                                        <span class="font-serif text-xl text-slate-500 uppercase">no hay clientes</span>
                                    </div>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--PAGINACION-->
                <Pagination :links="cliente.links" class="mt-6" />

            </div>
        </div>
    </AuthenticatedLayout>
</template>
