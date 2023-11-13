<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Popover, PopoverButton, PopoverPanel, PopoverOverlay } from '@headlessui/vue';
import {DocumentSearchIcon} from "@heroicons/vue/outline";






</script>
<script>

export default {
    
    data() {
    return {
      fecha: ''
    };
  },
  methods: {
    
    
    enviarDatos() {
        const fecha = this.fecha; // Variable que deseas pasar
       // const fecha = new Date(this.fecha).toISOString().slice(0, 10);
       // const url = `auditoria?fecha=${fecha}`;
        //console.log(this.fecha);
        //console.log(fecha);
        //console.log(url);
        axios.post('auditoria', { valor: this.fecha })
        .then(response => {
          // Maneja la respuesta del backend si es necesario
          //console.log('Respuesta del servidor:', response.data);
        })
        .catch(error => {
          // Maneja cualquier error que ocurra durante la solicitud
          //console.error('Error:', error);
        });


      //  axios.get(url)
      //  .then(data => {
           
          // Manejar la respuesta del servidor si es necesario
     //   })
     //   .catch(error => {
            // Manejar errores si ocurren
      //   });
    },
    generarPDF() {
        const url = '/generar-pdf';
        window.open(url, '_blank');
    },
  },
  
};
</script>
<template>
    <Popover v-slot="{ open }" class="">
                                <PopoverButton
                                    :class="open ? 'bg-white/30 text-blue-600' : ''"
                                    class="w-8 h-8 text-black hover:bg-black/30 rounded-md grid place-content-center"
                                >
                                    <DocumentSearchIcon  class="w-6 h-6"/>
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

                                        <form @submit.prevent="generarPDF()">
                                            <label class="block text-sm text-gray-800 mb-2 font-bold" 
                                                for="fechaa">Fecha desde: </label>
                                            
                                            <div>
                                                <input 
                                                    name="fecha"
                                                    id="fecha"
                                                    type="date" 
                                                    v-model="fecha"
                                                    class="border border-gray-300 placeholder-gray-400 rounded-md mt-1 block w-full bg-white text-black text-sm shadow-sm"                                                    
                                                    >
                                                   
                                                    
                                            </div>

                                            <!---<label class="block text-sm text-gray-800 mb-2 font-bold" 
                                                for="fecha">Hasta: </label>
                                            
                                            <div>
                                                <input 
                                                    name="fechahasta"
                                                    id="fechahasta"
                                                    type="date" 
                                                    v-model="form.fechahasta"
                                                    class="border border-gray-300 placeholder-gray-400 rounded-md mt-1 block w-full bg-white text-black text-sm shadow-sm"                                                    
                                                    >
                                                   
                                                    
                                            </div>-->
                                            
                                            
                                            <div class="flex justify-end mt-5">
                                                <button 
                                                    class="px-4 py-2 font-medium shadow-sm text-black rounded-md text-sm bg-green-400 hover:bg-green-500 focus:ring-1 focus:ring-offset-1 focus:ring-gray-200 focus:outline-none "    
                                                    @click="enviarDatos()">Reporte Auditoria
                                                </button>
                                            </div>
                                        </form>
                                        
                                        </div>
                                    </PopoverPanel>
                                </transition>
                            </Popover>
</template>