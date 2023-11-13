<script setup>
import { ref, watch } from 'vue'
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue'
import {SearchIcon} from "@heroicons/vue/outline";
import { Head, Link, router } from '@inertiajs/vue3';
import _ from 'lodash';

const emit = defineEmits(["sendProduct"]);

const props = defineProps({
producto: Object,
filters: Object,
});

let search = ref(props.filters.search);

watch(search, _.throttle(function (value) {
    router.get('/prueba', { search: value}, {
        preserveState: true,
        replace: true
    });
}, 200));

const isOpen = ref(false)

function closeModal() {
  isOpen.value = false
}
function openModal() {
  isOpen.value = true
}

//guarda en una variable el producto seleccionado de la tabla
const selectedProduct = ref();
  const selectProduct = (productos) => {
    selectedProduct.value = productos;
  };

//envia el producto al componente padre cuando se presiona "Enter"
const seleccionProducto = () => {

  search.value = null;
  isOpen.value = false; //cierra el buscador jeje
  emit("sendProduct", selectedProduct.value); // envia el "emit" al componente padre "@send-product"

}
</script>

<template>
    <div class="">
      <button
        type="button"
        @click="openModal"
        class="rounded-md bg-black bg-opacity-20 px-4 py-2 text-sm font-medium text-white hover:bg-opacity-30 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
      >
        Buscar Producto
      </button>
    </div>
    <TransitionRoot appear :show="isOpen" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>
  
        <div class="fixed inset-0">
          <div
            class="flex max-h-full justify-center p-4 text-center"
          >
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="w-full max-w-7xl transform overflow-hidden rounded-2xl bg-white text-left align-middle shadow-xl transition-all"
              >
          
              <div class="bg-gray-300 shadow-sm sm:rounded-lg overflow-y-auto h-full">
                <div class="flex flex-row sticky top-0 bg-gray-300">
                  <div
                    class="flex items-center p-2 focus-within:text-gray-400"
                    >
                    <SearchIcon
                      class="w-5 h-5 absolute ml-3 pointer-events-none"
                    />
                    <input
                      id="searchproducto"
                      v-model="search"
                      type="text"
                      placeholder="Buscar Productos"
                      autocomplete="off"
                      aria-label="Buscar Productos"
                      class="pr-3 pl-10 py-1 text-sm font-medium text-gray-700 bg-cyan-100 rounded-2xl border-none ring-2 ring-cyan-400 focus:ring-cyan-300 focus:ring-2"
                    />
                  </div>
                  <div class="p-2 ">
                    <span class="text-xl uppercase underline font-bold font-inter italic">Listado de Productos</span>
                  </div>
                  <span>{{ selectedProduct?.marca }}</span>
                </div>
                   <div class="px-2 mb-6">
                       <table class="min-w-full">
                           <thead>
                               <tr class="sticky top-10 text-left bg-gray-300">
                                   <th class="px-2">ID</th>
                                   <th class="px-2">Codigo de barras</th>
                                   <th class="px-2">Marca</th>
                                   <th class="px-2">Droga</th>
                                   <th class="px-2">Descripcion</th>
                                   <th class="px-2">Receta</th>
                                   <th class="px-2">Laboratorio</th>
                                   <th class="px-2">Vencimiento</th>
                                   <th class="px-2">Precio Venta</th>
                                   <th class="px-2">Stock</th>
                               </tr>
                           </thead>
                           <tbody v-if="search" class="divide-y divide-gray-400 divide-opacity-30">
                               <tr v-for="productos in producto.data" :key="productos.id" @click="selectProduct(productos)" @keyup.enter="seleccionProducto(productos)" tabindex="0" class="cursor-pointer" :class="{ 'bg-blue-500 text-white': productos === selectedProduct }">
                                   <td class="px-2 py-2">{{productos.id}}</td>
                                   <td class="px-2 py-2">{{productos.codigo}}</td>
                                   <td class="px-2 py-2">{{productos.marca}}</td>
                                   <td class="px-2 py-2">{{productos.droga}}</td>
                                   <td class="px-2 py-2">{{productos.descripcion}}</td>
                                   <td class="px-2 py-2">{{productos.venta}}</td>
                                   <td class="px-2 py-2">{{productos.laboratorio}}</td>
                                   <td class="px-2 py-2">{{productos.vencimiento}}</td>
                                   <td class="px-2 py-2">{{productos.precioventa}}</td>
                                   <td class="px-2 py-2">{{productos.stock}}</td>
                               </tr>
                               <div v-if="producto.data.length <= 0" class="p-4 py-16">
                                   <div class="absolute left-2/4 -translate-x-1/2"
                                       >
                                       <span class="font-serif text-xl text-slate-500 uppercase">no hay productos</span>
                                   </div>
                               </div>
                           </tbody>
                           <tbody v-else>
                            <div class="p-4 py-16">
                                   <div class="absolute left-2/4 -translate-x-1/2"
                                       >
                                      <span class="font-inter text-xl text-gray-700 uppercase">Realiza una Busqueda por 
                                        <span class="font-bold text-black">nombre</span>, 
                                        <span class="font-bold text-black">marca</span> o 
                                        <span class="font-bold text-black">droga</span> 
                                      </span>
                                   </div>
                               </div>
                           </tbody>
                           
                       </table>
                       
                       <!--PAGINACION-->
                    
                   </div>
                   
               </div>
               
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </template>

<style>

thead {
    background-color: white;
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