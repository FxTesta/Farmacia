<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import SideBar from '@/Components/SideBar.vue';
import {PlusCircleIcon, SearchIcon} from "@heroicons/vue/outline";
//import Delete from '@/Pages/Producto/delete.vue';
import { ref, watch } from "vue"; 
import Pagination from '@/Components/Pagination.vue';
import _ from 'lodash';
import CrearOrden from '@/Pages/ProductosFaltantes/crearordendecompra.vue';
import Cantidad from '@/Pages/ProductosFaltantes/cantidad.vue';

const props = defineProps({
producto: Object,
filters: Object,
user: Object,
});
 
let search = ref(props.filters.search);

watch(search, _.debounce(function (value) {
    console.log('disparado');
    router.get('/productosfaltantes', { search: value}, {
        preserveState: true,
        replace: true
    });
}, 300));


</script>
<script>

export default {
 methods: {
   GenerarOrdeen() {
      const url = '/auditoria';
        window.open(url, '_blank');
  },
 },
};

</script>
<template>
    <Head title="Faltantes" />

    <SideBar />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="flex uppercase font-bold text-xl text-gray-800 leading-tight">Productos Faltantes</h2>
        </template>

        <div class="py-12">

            <div class="max-w-screen-2xl mx-auto sm:px-6  ml-16">
                <div class="-mt-10">
                    <div class="flex justify-end">
                       <div class="inline-flex space-x-2 mb-2 mt-2 mr-2">
                       <CrearOrden :user="props.user"/>
                     
                            
                            <div
                                class="relative flex items-center  focus-within:text-gray-400"
                                >
                                <SearchIcon
                                    class="w-5 h-5 absolute ml-3 pointer-events-none"
                                />
                                <input
                                    id="search"
                                    v-model="search"
                                    type="text"
                                    placeholder="Buscar Productos"
                                    autocomplete="off"
                                    aria-label="Buscar Productos"
                                    class="pr-3 pl-10 py-1 text-sm font-medium text-gray-700 bg-cyan-100 rounded-2xl border-none ring-2 ring-cyan-400 focus:ring-cyan-300 focus:ring-2"
                                />
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                   
                    <div class="px-10 pb-10 pt-2 overflow-y-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-slate-300 text-gray-700 text-left">
                                    <th class="px-2">ID</th>                                    
                                    <th class="px-2">Codigo de barras</th>
                                    <th class="px-2">Marca</th>
                                    <th class="px-2">Laboratorio</th>
                                    <th class="px-2">Stock Actual</th>
                                    <th class="px-2">Stock Minimo</th>
                                    <th class="px-2">Estado</th>
                                    <th class="px-2">Seleccionar</th>
                                
                                    
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-400 divide-opacity-30">
                                <tr v-for="productos in producto.data" :key="productos.id" >                                   
                    
                                        <td  class="text-gray-700 py-4 px-2 ">{{productos.id}}</td>                                        
                                        <td  class="text-gray-700 py-4 px-2">{{productos.codigo}}</td>
                                        <td  class="text-gray-700 py-4 px-2">{{productos.marca}}</td>
                                        <td  class="text-gray-700 py-4 px-2">{{productos.laboratorio}}</td>
                                        <td  class="text-gray-700 py-4 px-2">{{productos.stock}}</td>
                                        <td  class="text-gray-700 py-4 px-2">{{productos.stockmin}}</td>
                                        <td  class="text-white py-4 px-2">
                                            <div v-if="productos.estado=='Faltante'" class="bg-red-500 rounded-md p-1 text-center">
                                                {{productos.estado}}
                                            </div>
                                            <div v-if="productos.estado=='En proceso'" class="bg-green-700 rounded-md p-1 text-center">
                                                {{productos.estado}}
                                            </div>
                                        </td>
                                        <td >
                                            <div v-if="productos.estado=='Faltante'" class="flex justify-center">
                                                <Cantidad :productos="productos" :key="productos.id"/>                                                    
                                            </div>
                                                
                                        </td>
                                     
                                </tr>
                                <div v-if="producto.data.length <= 0" class="p-4">
                                    <div class="absolute left-2/4 -translate-x-1/2"
                                        >
                                        <span class="font-serif text-xl text-slate-500 uppercase">no hay productos en stock minimo</span>
                                    </div>
                                </div>
                            </tbody>
                            
                        </table>
                        
                        
                    </div>
                    
                </div>
                <!--PAGINACION-->
                <Pagination :links="producto.links" class="mt-6" />
                
            </div>
        </div>
    </AuthenticatedLayout>


</template>
