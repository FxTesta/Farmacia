<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import SideBar from '@/Components/SideBar.vue';
import { PencilIcon, UserAddIcon, SearchIcon, FolderAddIcon } from "@heroicons/vue/outline";
import { ref, watch } from "vue";
import Pagination from '@/Components/Pagination.vue';
import _ from 'lodash';
import { Dialog, TransitionRoot, DialogPanel, DialogOverlay } from '@headlessui/vue';


const form = useForm({
  monto: null,
});


function saveOpeningAmount() {
    form.post('/caja', {
        onSuccess: () => {
            toggleModal();
            form.reset('monto');
        },
    });
}

const props = defineProps({
    caja: Object,
});

const isModalOpen = ref(false);

function toggleModal() {
    isModalOpen.value = !isModalOpen.value;
}

async function closeCaja(cajaId) {
    await form.post(`/caja/close/${cajaId}`);
}
</script>


<template>
    <Head title="Cajas" />

    <SideBar />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="flex uppercase font-bold text-xl text-gray-800 leading-tight ">Cajas</h2>
        </template>

        <div class="py-12">

            <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 ml-16">
                <div class="flex justify-start ">
                    <div class="bg-white p-4 rounded shadow-lg text-left">
                        <h3 class="text-xl font-bold mb-2 ">Caja</h3>
                        <hr class="mb-2">
                        <button @click="toggleModal" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Abrir Caja
                        </button>
                    </div>
                </div>
            </div>
            <TransitionRoot as="template" :show="isModalOpen">
                <Dialog as="div" class="relative z-10" @close="toggleModal">
                    <DialogOverlay class="fixed inset-0 bg-black bg-opacity-30" />

                    <div class="fixed inset-0 flex items-center justify-center p-4">
                        <DialogPanel
                            class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <Dialog.Title as="h3" class="text-lg font-bold leading-6 text-gray-900">
                                Registrar la cantidad inicial
                            </Dialog.Title>
                            <div class="mt-2">

                                <p class="text-sm font-medium text-gray-700 mb-4">
                                    Apertura
                                </p>
                                <hr class="mb-2">
                                <label for="monto-inicial" class="block text-sm font-medium text-gray-700">
                                    Monto de apertura (Gs.)
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <form @submit.prevent="saveOpeningAmount">
                                        <input id="monto" v-model="form.monto" type="number"
                                            class="block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" required>

                                        <div class="mt-4 flex justify-end space-x-3">
                                            <button type="button"
                                                class="inline-flex justify-center rounded-md border border-transparent px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                                @click="toggleModal">
                                                Cerrar
                                            </button>
                                            <button type="submit"
                                                class="inline-flex justify-center rounded-md border border-transparent px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                                                Guardar
                                            </button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </DialogPanel>
                    </div>
                </Dialog>
            </TransitionRoot>

            <span>{{ }}</span>
            <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 ml-16 mt-5">
                <div class="flex justify-start">
                    <div class="bg-white p-4 rounded shadow-lg text-left">
                        <h3 class="text-xl font-bold mb-2">Registros</h3>
                        <hr class="mb-2">
                        <div class="px-10 pb-10 pt-2 overflow-y-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-slate-300 text-gray-700 text-center">
                                        <th class="px-4 py-2">ID</th>
                                        <th class="px-4 py-2">Usuario</th>
                                        <th class="px-4 py-2">Monto</th>
                                        <th class="px-4 py-2">Fecha Apertura</th>
                                        <th class="px-4 py-2">Fecha Cierre</th>
                                        <th class="px-4 py-2">Estado</th>
                                        <th class="px-4 py-2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-400 divide-opacity-30">
                                    <tr v-for="cajas in caja">
                                        <td class="border px-4 py-2">{{ cajas.id }}</td>
                                        <td class="border px-4 py-2">{{ cajas.username }}</td>
                                        <td class="border px-4 py-2">{{ cajas.monto }}</td>
                                        <td class="border px-4 py-2">{{ cajas.fecha_apertura }}</td>
                                        <td class="border px-4 py-2">{{ cajas.fecha_cierre }}</td>
                                        <td class="border px-4 py-2">{{ cajas.estado }}</td>
                                        <td class="border px-4 py-2">
                                            <button @click="closeCaja(cajas.id)" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                                Cerrar Caja
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <Pagination :links="caja.links" class="mt-6" />
        </div>
    </AuthenticatedLayout>
</template>
