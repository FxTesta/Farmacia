<script setup>
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
} from "@headlessui/vue";
import { ref } from "vue";
import Delete from "@/Pages/ProductosFaltantes/delete.vue";

const isOpen = ref(false);

function closeModal() {
  isOpen.value = false;
}
function openModal() {
  isOpen.value = true;
}

const props = defineProps({
  ordencompra: Object,
});


</script>
<template>
  <div>
    <button
      type="button"
      @click="openModal"
      class="rounded-md bg-green-700 hover:bg-green-500 px-4 py-2 text-sm font-medium text-white"
    >
      Detalle
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

      <div class="fixed inset-0 overflow-y-auto">
        <div
          class="flex min-h-full items-center justify-center p-4 text-center"
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
              class="ml-16 w-full max-w-7xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <div>
                <div class="div-izquierdo mb-4 space-y-4">
                  <h2 class="font-bold">
                    Detalles del proveedor:
                    {{  }}
                  </h2>
                  <div class="flex flex-col space-y-2">
                    <div class="font-bold inline-flex space-x-2">
                      <span>Nombre:</span>
                      <div class="text-red-500">{{ props.ordencompra.proveedornombre }}</div>
                    </div>
                    <div class="font-bold inline-flex space-x-2">
                      <span>Email:</span>
                      <div class="text-red-500">{{ props.ordencompra.proveedoremail  }}</div>
                    </div>

                    <h2 class="font-bold inline-flex space-x-2">
                      <span> Telefono: </span> 
                      <h1 class="text-red-500">{{ props.ordencompra.proveedortelefono }}</h1>
                    </h2>
                  </div>
                </div>
                <div class="div-derecho flex flex-col">
                  <h2 class="font-bold inline-flex space-x-2">
                    <span>Fecha:</span>
                    <h1 class="text-red-500">
                      {{ props.ordencompra.fecha }}
                    </h1>
                  </h2>
                  <h2 class="font-bold inline-flex space-x-2">
                    <span>#Orden:</span>
                    <h1 class="text-red-500">
                      {{ props.ordencompra.id }}
                    </h1>
                  </h2>
                </div>
              </div>

              <table class="min-w-full">
                <thead>
                  <tr
                    class="border-b border-slate-300 bg-red-700 text-white text-left"
                  >
                    <th class="px-2">Cantidad</th>
                    <th class="px-2">Codigo de barras</th>
                    <th class="px-2">Estado</th>
                    <th class="px-2">Marca</th>
                    <th class="px-2">Laboratorio</th>
                    <!-- <th class="px-2">Acción</th> -->
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-400 divide-opacity-30">
                  <tr v-for="producto in ordencompra.detalle_orden_compra" :key="producto.id">
                    <td class="text-gray-700 py-4 px-2">
                      {{ producto.cantidad }}
                    </td>
                    <td class="text-gray-700 py-4 px-2">
                      {{ producto.codigo }}
                    </td>
                    <td class="text-gray-700 py-4 px-2">
                      {{ producto.estado }}
                    </td>
                    <td class="text-gray-700 py-4 px-2">
                      {{ producto.marca }}
                    </td>
                    <td class="text-gray-700 py-4 px-2">
                      {{ producto.laboratorio }}
                    </td>
<!--                     <td>
                      <Delete :producto="producto" :key="producto.id" />
                    </td> -->
                  </tr>
                </tbody>
              </table>

            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<style>
.div-izquierdo {
  float: left;
  /* Hace que el primer div flote a la izquierda */
  width: 50%;
  /* Controla el ancho del primer div */
}

.div-derecho {
  float: left;
  /* Hace que el segundo div flote a la izquierda */
  width: 50%;
  /* Controla el ancho del segundo div */
}
</style>
