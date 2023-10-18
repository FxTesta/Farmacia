<script setup>
import { ref, computed, watch} from 'vue'
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
} from '@headlessui/vue'
import {SearchIcon} from "@heroicons/vue/outline";
import _ from 'lodash';
import ClickLogo from "@/Components/ClickLogo.vue";

const emit = defineEmits(["sendProduct"]);

const props = defineProps({

options: {
  type: Array,
  default: () => [],
},

loadOptions: Function,

});


const options = ref(props.options);

//Con el "throttle" para evitar muchas peticiones al servidor

/* let query = ref('');
let isLoading = ref(false); //throttle
watch(query, _.debounce(function (q) {
  
  if(props.loadOptions){
    
    props.loadOptions(q, (results) => {
      options.value = results;
      if(props.modelValue && !options.value.some(o => {
        return o.value === props.modelValue?.value;
      })
      
      ) {

        options.value.unshift(props.modelValue)
      }
      
    })

  }
},

300
)); */


// watch sin el 'throttle' o 'debounce', directo, ocasiona muchas peticiones al servidor
let query = ref('')
let filteredResults = ref([]);
const isLoading = ref(false);

//watch que analiza cambios de "query" ingresado en el buscador
watch(query, (q) => {
  if (props.loadOptions) { //checkea que exista la función "loadOptions" que trae los datos de la BD
    isLoading.value = true; //estado "Cargando..." verdadero, antes de que se ejecute la función
    props.loadOptions(q, (results) => { //carga la función "loadOptions" recibiendo de parametro "q" que es el query y (results) es la salida
      options.value = results; // se carga en options los resultados de la busqueda de la bd
      filterResults(q);//se llama a la función enviando como parametro el query
      isLoading.value = false; //pongo el estado "Cargando..." en falso luego de que se terminara la carga de datos
    });
  }
});

//función que filtra los resultados del "query" ingresado
function filterResults(q) {
  filteredResults.value = q === '' //iguala la variable reactiva a los resultados del filtro que se van a obtener
    ? [] //si el query esta vacio lo iguala a un empty array
    : options.value.filter((option) => //en otro caso ":" carga lo contenido en "filteredResults" con los datos filtrados
        option.marca
          .toLowerCase() //pone en minusculas
          .replace(/\s+/g, '') //quita espacios
          .includes(q.toLowerCase().replace(/\s+/g, '')) || //incluye el valor del query para compararlo
        option.codigo || option.droga //ayuda a que también filtre los resultados por marca, codigo de barra o droga
      );
}

//valor computed que ayuda a desplegar los datos filtrados una vez que esten listos.
let filteredOptions = computed(() => filteredResults.value);

const isOpen = ref(false)

function closeModal() {
  isOpen.value = false
}
function openModal() {
  isOpen.value = true
}

const selectedIndex = ref();

//guarda en una variable el producto seleccionado de la tabla
const selectedProduct = ref();

const selectProduct = (index) => {
  selectedIndex.value = index;
  selectedProduct.value = filteredOptions.value[index];
   
  emit("sendProduct", selectedProduct.value);//envia el producto al componente padre al hacer click al producto
  isOpen.value = false; //cierra el buscador jeje
  query.value = null; //resetea el campo de busqueda al seleccionar el producto
  selectedProduct.value = null; //resetea la variable donde se guardo el producto enviado
  selectedIndex.value = null; //resetea el index
};


const highlightRow = (index) => {
  selectedIndex.value = index;
};

const unhighlightRow = () => {
  selectedIndex.value = null;
};


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
                      
                      v-model="query"
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
               <span>{{ selectedProduct?.marca  }}</span>
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
                           <tbody v-if="query" class="divide-y divide-gray-400 divide-opacity-30">
                              <tr 
                                v-for="(productos, index) in filteredOptions" :key="productos.id" 
                                @mouseenter="highlightRow(index)"
                                @mouseleave="unhighlightRow(index)"
                                @click = "selectProduct(index)"
                                class="cursor-pointer" 
                                :class="{ 'bg-blue-500 text-white': index === selectedIndex }">
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
                              <div v-if="filteredOptions.length === 0 && !isLoading" class="p-4 py-16">
                                  <div class="absolute left-2/4 -translate-x-1/2"
                                      >
                                      <span class="font-serif text-xl text-slate-500 uppercase">no hay productos</span>
                                  </div>
                              </div>
                              <div v-if="isLoading && filteredOptions.length === 0" class="p-4 py-16">
                                  <div class="absolute left-2/4 -translate-x-1/2"
                                      >
                                      <span class=" text-xl text-black font-blod uppercase italic">Cargando...</span>
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
                   </div>
                   <div class="sticky bottom-0 flex flex-row justify-center bg-gray-400 py-1 items-center border-t-2 border-black/20">
                      <ClickLogo class="w-10 h-10 fill-current text-blue-500" />
                    <div class="-ml-1 mt-2">
                      <span class="font-inter text-white font-shadow"><span class="text-blue-300">Click izquierdo</span> para seleccionar</span>
                    </div>
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