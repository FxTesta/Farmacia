<script setup>
import { ref } from 'vue'
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue'

const isOpen = ref(false)

function closeModal() {
  isOpen.value = false
}
function openModal() {
  isOpen.value = true
}
</script>
<style>
.div-izquierdo {
  float: left; /* Hace que el primer div flote a la izquierda */
  width: 50%; /* Controla el ancho del primer div */
}

.div-derecho {
  float: left; /* Hace que el segundo div flote a la izquierda */
  width: 50%; /* Controla el ancho del segundo div */
}
</style>
<template>
  <div>
    <button type="button" @click="openModal"
      class="rounded-md bg-black bg-opacity-20 px-4 py-2 text-sm font-medium text-white hover:bg-opacity-30 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75">
      Orden de compra
    </button>
  </div>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-10">
      <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
        leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-black bg-opacity-25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95">
            <DialogPanel
              class="w-full max-w-7xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">

              <div class="mt-2">
              
              </div>
              <div>
                <div class="div-izquierdo">
                  <p class="text-black 16px">
                  Detalles del proveedor:
                </p>
                <p>
                  Email:
                </p>
                <p>
                  Telefono:
                </p>
                </div>
                <div class="div-derecho">
                  <p>
                    Detalle de la orden de compra:
                  </p>
                  <p>
                    Orden#:
                  </p>
                  <p>
                    Fecha:
                  </p>
                </div>
              </div>


              <table class="min-w-full">
                <thead>
                  <tr class="border-b border-slate-300 bg-blue-700 text-white text-left">
                    <th class="px-2">ID</th>
                    <th class="px-2">Categoria</th>
                    <th class="px-2">Descripcion</th>
                    <th class="px-2">Marca</th>
                    <th class="px-2">Laboratorio</th>
                    <th class="px-2">Vencimiento</th>
                    <th class="px-2">Codigo de barras</th>
                    <th class="px-2">Stock Actual</th>
                    <th class="px-2">Stock Minimo</th>
                    <th class="px-2">Acción</th>


                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-400 divide-opacity-30">
                  <!-- <tr v-for="productos in producto.data" :key="productos.id" :class="{'bg-green-500 text-white':productos.marca==='illum'}" >                                   
                    
                                        <td class="text-gray-700 py-4 px-2 ">{{productos.id}}</td>
                                        <td class="text-gray-700 py-4 px-2">{{productos.categoria}}</td>
                                        <td class="text-gray-700 py-4 px-2">{{productos.descripcion}}</td>
                                        <td class="text-gray-700 py-4 px-2">{{productos.marca}}</td>
                                        <td class="text-gray-700 py-4 px-2">{{productos.laboratorio}}</td>
                                        <td class="text-gray-700 py-4 px-2">{{productos.vencimiento}}</td>
                                        <td class="text-gray-700 py-4 px-2">{{productos.codigo}}</td>
                                        <td class="text-gray-700 py-4 px-2">{{productos.stock}}</td>
                                        <td class="text-gray-700 py-4 px-2">{{productos.stockmin}}</td>
                                     
                                </tr>
                                <div v-if="producto.data.length <= 0" class="p-4">
                                    <div class="absolute left-2/4 -translate-x-1/2"
                                        >
                                        <span class="font-serif text-xl text-slate-500 uppercase">no hay productos en stock minimo</span>
                                    </div>
                                </div>-->
                </tbody>

              </table>




              <div class="mt-4">
                <button type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="closeModal">
                  Got it, thanks!
                </button>

              </div>

            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
  
  