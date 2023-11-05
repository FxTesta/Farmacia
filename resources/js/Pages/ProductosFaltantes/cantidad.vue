
<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Popover, PopoverButton, PopoverPanel, PopoverOverlay } from '@headlessui/vue';
import {PlusCircleIcon} from "@heroicons/vue/outline";

const props = defineProps({
    productos: Object
});

const form = useForm({
    stock: "",
});

function onSubmit(closePopover) {
    form.put(route('producto.crearorden',{productos: props.productos.id}),{
        onSuccess: () => {
            closePopover();
        },
    });
    
}

</script>

<template>
    <Popover v-slot="{ open }" class="">
                                <PopoverButton
                                    :class="open ? 'bg-white/30 text-blue-600' : ''"
                                    class="w-8 h-8 text-black hover:bg-black/30 rounded-md grid place-content-center"
                                >
                                <PlusCircleIcon class="w-7 h-7" />
                                </PopoverButton>
                                <PopoverOverlay class="z-10 fixed inset-0 bg-blue opacity-60" />

                                <transition
                                    enter-active-class="transition duration-200 ease-out"
                                    enter-from-class="translate-y-1 opacity-0"
                                    enter-to-class="translate-y-0 opacity-100"
                                    leave-active-class="transition duration-150 ease-in"
                                    leave-from-class="translate-y-0 opacity-100"
                                    leave-to-class="translate-y-1 opacity-0"
                                >
                                    <PopoverPanel
                                    :focus="true"
                                    v-slot="{close}"
                                    class="absolute left-1/2 z-10 w-screen max-w-sm -translate-x-1/3 transform px-4 sm:px-0 "
                                    >
                                        <div
                                            class="p-3 bg-cyan-100 border border-gray-100 overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5"
                                        >

                                        <form @submit.prevent="onSubmit(close)">
                                            <label class="block text-sm text-gray-800 mb-2 font-bold" 
                                                for="stock">Cantidad a solicitar: </label>
                                            
                                            <div>
                                                <input 
                                                    name="stock"
                                                    id="stock"
                                                    type="number" 
                                                    v-model="form.stock"
                                                    class="border border-gray-300 placeholder-gray-400 rounded-md mt-1 block w-full bg-white text-black text-sm shadow-sm"                                                    
                                                    placeholder="cantidad">
                                                
                                                    
                                            </div>
                                            
                                            
                                            <div class="flex justify-end mt-5">
                                                <button 
                                                    class="px-4 py-2 font-medium shadow-sm text-white rounded-md text-sm bg-green-700 hover:bg-green-500 focus:ring-1 focus:ring-offset-1 focus:ring-gray-200 focus:outline-none "
                                                    >Agregar a la orden
                                                </button>
                                                
                                            </div>
                                        </form>
                                        
                                        </div>
                                    </PopoverPanel>
                                </transition>
                            </Popover>
</template>