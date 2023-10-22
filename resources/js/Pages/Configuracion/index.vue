<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import SideBar from '@/Components/SideBar.vue';
import { PencilIcon, AdjustmentsIcon } from "@heroicons/vue/outline";
import _ from 'lodash';

const props = defineProps({
    configuracion: Object,
});

</script>
<template>
    <Head title="Configuracion" />

    <SideBar />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="flex uppercase font-bold text-xl text-gray-800 leading-tight">Configuracion</h2>
        </template>

        <div class="py-12">

            <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 ml-16">
                <div class="-mt-10">
                    <div class="flex justify-end">
                        <div class="inline-flex space-x-2 mb-2 mt-2 mr-2">
                            <div v-if="props.configuracion.length === 0" class="mt-1" >
                                <Link :href="`/crear-configuracion/`" as="button"
                                    class="hover:bg-gray-300 ring-2 focus:ring-set-2 ring-cyan-400 rounded-full">
                                <AdjustmentsIcon class="h-6 w-6 inline mb-1 ml-2 rounded-full" />
                                <a class="text-sm font-medium rounded-md mr-3"> Establecer configuracion </a>
                                </Link>
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
                                    <th>Numero de Factura</th>
                                    <th>Numero de Timbrado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-400 divide-opacity-30">
                                <tr v-for="configuraciones in configuracion">
                                    <td class="text-gray-700 py-4">{{ configuraciones.nrofactura }}</td>
                                    <td class="text-gray-700 py-4">{{ configuraciones.timbrado }}</td>
                                    <td class="py-4">
                                        <div class="inline-flex">
                                            <Link :href="`/editar-configuracion/${configuraciones.id}`" as="button"
                                                class="w-8 h-8 t hover:bg-black/30 rounded-md grid place-content-center">
                                            <PencilIcon class="w-6 h-6" />
                                            </Link>
                                        </div>

                                    </td>
                                </tr>
<!--                                 <div v-if="cliente.data.length <= 0" class="p-4">
                                    <div class="absolute left-2/4 -translate-x-1/2"
                                        >
                                        <span class="font-serif text-xl text-slate-500 uppercase">no hay clientes</span>
                                    </div>
                                </div> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
