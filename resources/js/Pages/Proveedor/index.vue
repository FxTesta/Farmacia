<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import SideBar from '@/Components/SideBar.vue';
import { PencilIcon, UserAddIcon, SearchIcon } from "@heroicons/vue/outline";
import Delete from '@/Pages/Proveedor/delete.vue';
import { ref, watch } from "vue";
import Pagination from '@/Components/Pagination.vue';
import _ from 'lodash';

const props = defineProps({
    proveedor: Object,
    filters: Object,
});

let search = ref(props.filters.search);

watch(search, _.debounce(function (value) {
    console.log('disparado');
    router.get('/proveedor', { search: value }, {
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
            <h2 class="flex uppercase font-bold text-xl text-gray-800 leading-tight">Proveedores</h2>
        </template>

        <div class="py-12">

            <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 ml-16">
                <div class="-mt-10">
                    <div class="flex justify-end">
                        <div class="inline-flex space-x-2 mb-2 mt-2 mr-2">
                            <div class="mt-1">
                                <Link :href="`/crear-proveedor/`" as="button"
                                    class="hover:bg-gray-300 ring-2 focus:ring-set-2 ring-cyan-400 rounded-full">
                                <UserAddIcon class="h-6 w-6 inline mb-1 ml-2 rounded-full" />
                                <a class="text-sm font-medium rounded-md mr-3"> Registrar Proveedor </a>
                                </Link>
                            </div>
                            <div class="relative flex items-center  focus-within:text-gray-400">
                                <SearchIcon class="w-5 h-5 absolute ml-3 pointer-events-none" />
                                <input id="searchproveedor" v-model="search" type="text" placeholder="Buscar Proveedores" autocomplete="off"
                                    aria-label="Buscar Proveedores"
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
                                    <th>ID</th>
                                    <th>Empresa</th>
                                    <th>RUC</th>
                                    <th>DV</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Contacto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-400 divide-opacity-30">
                                <tr v-for="proveedores in proveedor.data">
                                    <td class="text-gray-700 py-4">{{ proveedores.id }}</td>
                                    <td class="text-gray-700 py-4">{{ proveedores.empresa }}</td>
                                    <td class="text-gray-700 py-4">{{ proveedores.ruc }}</td>
                                    <td class="text-gray-700 py-4">{{ proveedores.dv }}</td>
                                    <td class="text-gray-700 py-4">{{ proveedores.name }}</td>
                                    <td class="py-4 inline-flex">{{ proveedores.direccion }}</td>
                                    <td class="text-gray-700 py-4">{{ proveedores.telefono }}</td>
                                    <td class="py-4">
                                        <div class="inline-flex">
                                            <Link :href="`/editar-proveedor/${proveedores.id}`" as="button"
                                                class="w-8 h-8 t hover:bg-black/30 rounded-md grid place-content-center">
                                            <PencilIcon class="w-6 h-6" />
                                            </Link>

                                            <div>
                                                <Delete :proveedor="proveedores" :key="proveedores.id" />
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                <div v-if="proveedor.data.length <= 0" class="p-4">
                                    <div class="absolute left-2/4 -translate-x-1/2">
                                        <span class="font-serif text-xl text-slate-500 uppercase">no hay proveedores</span>
                                    </div>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--PAGINACION-->
                <Pagination :links="proveedor.links" class="mt-6" />

            </div>
        </div>
    </AuthenticatedLayout>
</template>
