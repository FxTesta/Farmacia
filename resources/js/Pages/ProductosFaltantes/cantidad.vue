<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import { ref, computed, watch, onMounted } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from "@headlessui/vue";

import { PlusCircleIcon } from "@heroicons/vue/outline";

const isOpen = ref(false);

function closeModal() {
  isOpen.value = false;
}
function openModal() {
  isOpen.value = true;
}

const props = defineProps({
  productos: Object,
});

const form = useForm({
  stock: "",
});

function onSubmit() {
  form.put(route("producto.crearorden", { productos: props.productos.id }), {
    onSuccess: () => {
      isOpen.value = false;
    },
  });
}
</script>

<template>
  <button
    type="button"
    @click="openModal"
    class="w-8 h-8 text-black hover:bg-black/30 rounded-md grid place-content-center"
  >
    <PlusCircleIcon class="w-7 h-7" />
  </button>
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
              class="w-full max-w-md transform overflow-hidden rounded-2xl bg-cyan-100 p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle
                as="h3"
                class="text-lg font-medium leading-6 text-gray-900"
              >
              Cantidad a solicitar
            </DialogTitle>

              <div class="mt-4 space-x-3">
                <form @submit.prevent="onSubmit(close)">
                  <div>
                    <input
                      name="stock"
                      id="stock"
                      type="number"
                      v-model="form.stock"
                      class="border border-gray-300 placeholder-gray-400 rounded-md mt-1 block w-full bg-white text-black text-sm shadow-sm"
                      placeholder="cantidad"
                    />
                  </div>

                  <div class="flex justify-end mt-5">
                    <button
                      class="px-4 py-2 font-medium shadow-sm text-white rounded-md text-sm bg-green-700 hover:bg-green-500 focus:ring-1 focus:ring-offset-1 focus:ring-gray-200 focus:outline-none"
                    >
                      Agregar a la orden
                    </button>
                  </div>
                </form>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
