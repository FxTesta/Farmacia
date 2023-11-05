<script setup>
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue';
import { PlusIcon,TrashIcon } from "@heroicons/vue/outline";
import BuscarProveedor from "@/Pages/Compra/BuscarProveedor.vue"
import { ref, onMounted } from 'vue';
import Delete from "@/Pages/ProductosFaltantes/delete.vue"
import axios from 'axios';


const isOpen = ref(false)

function closeModal() {
  isOpen.value = false
}
function openModal() {
  isOpen.value = true
}
//obtener fecha actual
const fechaActual = ref('');

onMounted(() => {
  obtenerFechaActual();
});
const obtenerFechaActual = () => {
  const fecha = new Date();
  const options = { year: 'numeric', month: 'numeric', day: 'numeric' };
  fechaActual.value = fecha.toLocaleDateString('es-ES', options);
};

let proveedor=ref();
function loadProveedor(query, setOptions) {
  fetch("http://127.0.0.1:8000/proveedores?query=" + query)
    .then(response => response.json())
    .then(results => {
      setOptions(
        results.map(proveedores => {
          return {
            value: proveedores.id,
            label: proveedores.empresa,
            telefono: proveedores.telefono,
            email: proveedores.email,
          };
        })
      );
    });
}

//recuperamos los productos seleccionados para la orden de compra
const productos = ref([]);

const obtenerProductos = () => {
  axios.get('/orden')
    .then(response => {
      productos.value = response.data.productos;
    })
    .catch(error => {
      console.error('Error al obtener productos:', error);
    });
};

onMounted(() => {
  obtenerProductos();
});

</script>

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
<template>
  <div>
    <button type="button" @click="openModal"
      class="rounded-md bg-green-700 hover:bg-green-500 px-4 py-2 text-sm font-medium text-white ">
      Generar Orden de Compra
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
                  <h2 class="font-bold">
                    Detalles del proveedor:
                    <div class="flex space-x-2">
                      <BuscarProveedor placeholder="Buscar Proveedor..." :load-options="loadProveedor"
                        v-model="proveedor" />
                    </div>
                  </h2>
                  <h2 class="font-bold">
                    Email:{{proveedor?.email}}
                  </h2>



                  <h2 class="font-bold">
                    Telefono: {{proveedor?.telefono}}
                  </h2>
                </div>
                <div class="div-derecho">
                  <h2 class="font-bold">
                    Detalle de la orden de compra:
                  </h2>
                  <h2 class="font-bold">
                    Orden#:
                  </h2>
                  <span class="font-bold">Fecha:

                    {{ fechaActual }}

                  </span>
                </div>
              </div>


              <table class="min-w-full">
                <thead>
                  <tr class="border-b border-slate-300 bg-red-700 text-white text-left">
                    <th class="px-2">Cantidad</th>
                    <th class="px-2">Codigo de barras</th>
                    <th class="px-2">Estado</th>
                    <th class="px-2">Marca</th>
                    <th class="px-2">Laboratorio</th>
                    <th class="px-2">Acción</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-400 divide-opacity-30">
                  <tr v-for="producto in productos" :key="producto.id">
                    <td class="text-gray-700 py-4 px-2">{{ producto.cantidad }}</td>
                    <td class="text-gray-700 py-4 px-2">{{ producto.codigo }}</td>
                    <td class="text-gray-700 py-4 px-2">{{ producto.estado }}</td>
                    <td class="text-gray-700 py-4 px-2">{{ producto.marca }}</td>
                    <td class="text-gray-700 py-4 px-2">{{ producto.laboratorio }}</td>
                    <td>
                      <Delete :producto="producto" :key="producto.id" />
                    </td>
                  </tr>
                </tbody>
              </table>

              <tr>
                <td>
                  <div class=" flex items-center  focus-within:text-white">

                    <button type="button"
                      class=" pl-8 inline-flex justify-center rounded-md border border-transparent bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                      @click="closeModal">
                      Confirmar Orden
                    </button>
                    <button
                  type="button"
                  class="inline-flex rounded-md border border-gray-500 bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="closeModal"
                >
                  Cancelar
                </button>
                  </div>
                </td>
              </tr>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
  
  