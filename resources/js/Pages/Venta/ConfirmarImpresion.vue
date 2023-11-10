<script setup>
import { ref, computed, watch, onMounted } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from "@headlessui/vue";
import { SearchIcon } from "@heroicons/vue/outline";

const isOpen = ref(false);

function closeModal() {
  isOpen.value = false;
}
function openModal() {
  isOpen.value = true;
}

const confirmaImpresion = (event) => {
  console.log("entro en popover impresion");
  openModal();
};

onMounted(() => {
  window.addEventListener("imprimir", confirmaImpresion);
});

const facturaventaid = ref();

const fetchData = async () => {
  try {
    const response = await fetch("/obtenerfacturaid");
    if (response.ok) {
      const data = await response.json();
      facturaventaid.value = data;
    } else {
      console.error("Error al obtener el valor máximo");
    }
  } catch (error) {
    console.error("Error de red:", error);
  }
};

function imprimirFactura() {
  fetchData().then(() => {
    const url = "/venta/listar/detalle/" + facturaventaid.value;
    window.open(url, "_blank");
    closeModal();
  });
}
</script>

<template>
  <!--   <div class="flex justify-center mt-5">
    <button
      @click="onSubmit"
      class="px-2 py-2 bg-gradient-to-t from-[#2eff1b] to-[#3bca2f] rounded-md border border-black"
    >
      <span
        class="font-bold text-2xl text-white uppercase italic"
        style="-webkit-text-stroke: 1px black"
        >realizar venta</span
      >
    </button>
  </div> -->

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
              class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle
                as="h3"
                class="text-lg font-medium leading-6 text-gray-900"
              >
                Desea Imprimir la Factura?
              </DialogTitle>

              <div class="mt-4 inline-flex space-x-3">
                <div>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="imprimirFactura"
                  >
                    Imprimir
                  </button>
                </div>
                <div>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-300 focus-visible:ring-offset-2"
                    @click="closeModal"
                  >
                    Cancelar
                  </button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
