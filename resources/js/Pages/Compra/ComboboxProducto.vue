<script setup>
import { ref, computed, watch } from 'vue'
import {
  Combobox,
  ComboboxInput,
  ComboboxButton,
  ComboboxOptions,
  ComboboxOption,
  TransitionRoot,
} from '@headlessui/vue'
import { CheckIcon, ChevronDownIcon } from '@heroicons/vue/solid'

defineEmits(['update:modelValue']);

const props = defineProps({

  modelValue: Object,

  options: {
    type: Array,
    default: () => [],
  },
  
  loadOptions: Function,

  placeholder : {
    type: String,
    default: 'Buscar...'
  }

});

const options = ref(props.options);
const isLoading = ref(false);

let query = ref('')
watch(query, (q) => {
  if(props.loadOptions){
    isLoading.value = true;
    props.loadOptions(q, (results) => {
      options.value = results;


      if(props.modelValue && !options.value.some(o => {
        return o.value === props.modelValue?.value;
      })
      

      ) {

        options.value.unshift(props.modelValue)
      }


      isLoading.value = false;
    })
  }
},

{immediate: true}

);

let filteredOptions = computed(() =>
  query.value === ''
    ? options.value
    : options.value.filter((option) =>
        option.label
          .toLowerCase()
          .replace(/\s+/g, '')
          .includes(query.value.toLowerCase().replace(/\s+/g, '')) ||
        option.codigo
        
      )
)

const formatearNumero = (numero) => {
    return numero.toLocaleString();
};
</script>

<template>
      <Combobox 
        by="value"
        :model-value="props.modelValue" 
        @update:model-value="value => $emit('update:modelValue',value)">
        <div class="relative mt-1">
          <div
            class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm"
          >
            <ComboboxInput
              autoComplete="off"
              :placeholder="placeholder"
              class="w-full uppercase border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
              :displayValue="(option) => option?.label"
              @change="query = $event.target.value"
            />
            <ComboboxButton
              class="absolute inset-y-0 right-0 flex items-center pr-2"
            >
              <ChevronDownIcon
                class="h-5 w-5 text-gray-400"
                aria-hidden="true"
              />
            </ComboboxButton>
          </div>
          <TransitionRoot
            leave="transition ease-in duration-100"
            leaveFrom="opacity-100"
            leaveTo="opacity-0"
            @after-leave="query = ''"
          >
            <ComboboxOptions
            v-if="query"
              class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
            >
              <div
                v-if="filteredOptions.length === 0 && !isLoading"
                class="relative cursor-default select-none py-2 px-4 text-gray-700"
              >
                Nada encontrado.
              </div>

              <div
                v-if="isLoading"
                class="relative cursor-default select-none py-2 px-4 text-gray-700"
              >
                Cargando...
              </div>

              <template v-if="!isLoading">  
                <ComboboxOption
                  v-for="option in filteredOptions"
                  as="template"
                  :key="option.value"
                  :value="option"
                  v-slot="{ selected, active }"
                >
                  <li
                    class="relative cursor-default select-none py-2 pl-10 pr-4 border-b border-slate-300"
                    :class="{
                      'bg-teal-600 text-white': active,
                      'text-gray-900': !active,
                    }"
                  >
                  <div class="inline-flex space-x-3">
                    <div class="flex space-x-2">
                      <span class=" font-serif underline uppercase">Nombre:</span>
                      <span
                        class="block truncate uppercase -mt-1"
                        :class="{ 'font-medium text-green-400 text-base': selected, 'font-bold text-base': !selected }"
                      >
                      {{ option.label }}
                      </span>
                      <span class="text-blue-400 ">││</span>
                    </div>
                    <div class="flex space-x-2">
                        <span class=" font-serif underline uppercase">Presentacion:</span>
                        <span
                          class="block truncate uppercase -mt-1"
                          :class="{ 'font-medium text-green-400 text-base': selected, 'font-bold text-base': !selected }"
                        >
                        {{ option.presentacion }}
                        </span>
                        <span class="text-blue-400 ">││</span>
                    </div>
                    <div class="flex space-x-2">
                        <span class=" font-serif underline uppercase">Precio Publico:</span>
                        <span
                          class="block truncate uppercase -mt-1"
                          :class="{ 'font-medium text-green-400 text-base': selected, 'font-bold text-base': !selected }"
                        >
                        {{ formatearNumero(option.precioventa) }}
                        </span>
                    </div>
                  </div>
                    <span
                      v-if="selected"
                      class="absolute inset-y-0 left-0 flex items-center pl-3"
                      :class="{ 'text-white': active, 'text-teal-600': !active }"
                    >
                      <CheckIcon class="h-5 w-5" aria-hidden="true" />
                    </span>
                  </li>
                </ComboboxOption>
              </template>
            </ComboboxOptions>
          </TransitionRoot>
        </div>
      </Combobox>
  </template>
  