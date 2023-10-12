<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { XIcon, PlusCircleIcon } from "@heroicons/vue/outline";
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import BuscarProveedor from "@/Pages/Compra/BuscarProveedor.vue"
import BuscarProducto from "@/Pages/Compra/BuscarProducto.vue"
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import error from '@/Stores/error'


//para obtener el usuario logueado, name y id
const props = defineProps({
    user: Object,
});

//variable que recibe los datos del proveedor
let proveedor = ref();

//variable que va a recibir los detalles del producto seleccionado
let producto = ref('');

//ref para enfocar al siguiente campo presionando Enter hasta cargar el producto 
const primero = ref();
const segundo = ref();
const miBoton = ref();

//evento recibe el producto buscado del componente "BuscarProducto"
const recibirProducto = (event) => {
    // La variable 'producto' del componente hijo se recibe aquí
    producto.value = event.detail;

    //enfoca al campo "Precio" después de seleccionar Producto
    primero.value.focus();
};

onMounted(() => {
    // Escucha el evento personalizado 'enviar-producto' del componente hijo
    window.addEventListener('enviar-producto', recibirProducto);
});

//variables campo del arrayProductos
const precio = ref('');
const cantidad = ref('');

//calcula el "importe" por producto
const total = computed(() => {
    const precioNumerico = parseFloat(precio.value);
    const cantidadNumerica = parseFloat(cantidad.value);

    // Verificar si los valores son numéricos antes de calcular el producto
    if (!isNaN(precioNumerico) && !isNaN(cantidadNumerica)) {
        return precioNumerico * cantidadNumerica;
    } else {
        return 0; // Otra opción sería retornar null o un mensaje de error
    }
});

//Array donde se guardan la lista de productos comprados
const arrayProductos = ref([]);

// Función para calcular la suma de la propiedad 'total' de los objetos en arrayProductos
const calcularSumaTotal = () => {
    return arrayProductos.value.reduce((total, producto) => total + producto.total, 0);
};

//variable retorna suma del campo total para obtener "P. TOTAL"
let preciototal = computed(() => {
    return calcularSumaTotal();


})

//variable retorna id que extrae de producto
let productoid = computed(() => {
    return producto.value === ""
        ? null
        :

        producto.value?.value;


})

//variable retorna marca que extrae de producto
let marca = computed(() => {
    return producto.value === ""
        ? null
        :

        producto.value?.label;


})

//variable retorna stock que extrae de producto
let stock = computed(() => {
    return producto.value === ""
        ? null
        :

        producto.value?.stock;


})

//variable retorna codigo de barra que extrae de producto
let codigobarra = computed(() => {
    return producto.value === ""
        ? null
        :

        producto.value?.codigo;


})

//variable retorna id que extrae de proveedor
let proveedorid = computed(() => {
    return proveedor.value === ""
        ? null
        :

        proveedor.value?.value;


})

//variable retorna id que extrae de proveedor
let proveedornombre = computed(() => {
    return proveedor.value === ""
        ? null
        :

        proveedor.value?.label;


})






//funcion que busca el proveedor y retorna los datos
function loadProveedor(query, setOptions) {
    fetch("http://127.0.0.1:8000/proveedores?query=" + query)
        .then(response => response.json())
        .then(results => {
            setOptions(
                results.map(proveedores => {
                    return {
                        value: proveedores.id,
                        label: proveedores.empresa,
                    };
                })
            );
        });
}


//se establecen los campos del array y el valor que van a tomar
const agregarProducto = () => {

    //condición para que no este ningún campo nulo para poder guardar los datos en "arrayProductos"

    if (productoid.value !== null && codigobarra.value !== null && marca.value !== null && total.value !== 0 && precio.value !== null && cantidad.value !== null
        && productoid.value !== undefined && codigobarra.value !== undefined && marca.value !== undefined && precio.value !== undefined && cantidad.value !== undefined
        && productoid.value !== '' && codigobarra.value !== '' && marca.value !== '' && precio.value !== '' && cantidad.value !== '' && cantidad.value > 0 && precio.value > 0) {

        //se cargan los datos en el array
        arrayProductos.value.push({
            productoid: productoid.value, codigobarra: codigobarra.value, marca: marca.value, precio: precio.value,
            cantidad: cantidad.value, total: total.value
        });

        //una vez cargado los datos se reestablecen los campos para la siguiente carga.
        precio.value = null;
        cantidad.value = null;
        producto.value = null;

        //después de agregar el producto envia un evento a "BuscarProducto" para enfocar nuevamente el boton para buscar Producto
        const event1 = new CustomEvent('enviar-ref');
        window.dispatchEvent(event1);

    }
    else {
        console.log('No entro');
    }
};

//elimina lo contenido en el array
const eliminarProducto = (index) => {
    const productoEliminado = arrayProductos.value[index];
    const mensaje = 'Remover ' + productoEliminado.marca + '?';

    if (window.confirm(mensaje)) {
        arrayProductos.value.splice(index, 1);
    }
};


//función que obtiene el día de la fecha
function mindate() {
    return new Date().toISOString().split('T')[0]
}



//cuenta lo contenido en arrayProductos para verificar si es nulo
const contarProductos = () => {
    return arrayProductos.value.length;
};



let form = useForm({
    usuario: props.user.name,
    codigo: props.user.id,
    proveedorid: proveedorid,
    proveedornombre: proveedornombre,
    nrofactura: '',
    fechafactura: mindate(),
    total: preciototal,
    arrayProductos: arrayProductos.value, //array con la lista de productos comprados

});

// Función para formatear el número con punto decimal
const formatearNumero = (numero) => {
    return numero.toLocaleString();
};

//función que guarda la comra realizada en la base de datos
function onSubmit() {
    // Verificar si arrayProductos es nulo o vacío

    if (!proveedor.value) {

        addError('Seleccionar Proveedor');
    }

    if (!arrayProductos.value || arrayProductos.value.length === 0) {
        // Realizar alguna acción en caso de que arrayProductos sea nulo o vacío
        console.error('El array de productos está vacío o nulo');
        addError('No hay productos');
        return; // Salir de la función onSubmit sin hacer la solicitud POST
    }

    form.post(route('compra.store'), {
        onSuccess: () => {
            arrayProductos.value.splice(0); //resetea el array después de guardar en la BD
            producto.value = null;//resetea la variable reactiva (let producto = ref();) después de guardar los campos en la bd
            proveedor.value = null;//resetea la variable reactiva (let proveedor = ref();) después de guardar los campos en la bd
            form.nrofactura = ''; //resetea
            form.fechafactura = mindate(); //resetea a la fecha del día después de guardar los campos en la bd
        },
    });
}


//SEGURO QUE QUIERE RECARGAR LA PAGINA?
// Función para mostrar un mensaje de confirmación personalizado al recargar la página
const confirmReload = (event) => {
    event.preventDefault(); // Prevenir la recarga inmediata
    event.returnValue = "Se perderán los cambios no guardados. ¿Estás seguro?"; // No muestra el mensaje personalizado por politicas de los Navegadores
};

// Agregar el evento beforeunload cuando se monta el componente
onMounted(() => {
    window.addEventListener('beforeunload', confirmReload);
});

// Eliminar el evento beforeunload cuando el componente se desmonta
onBeforeUnmount(() => {
    window.removeEventListener('beforeunload', confirmReload);
});

function addError(message) {
    error.add1({
        message1: message
    })
}


  const focusNext = (inputNumber) => {
  if (inputNumber === 2 && segundo.value && precio.value !== '' && precio.value !== null) {
    segundo.value.focus();
  } else if (inputNumber === 3 && miBoton.value && cantidad.value !== '' && cantidad.value !== null) {
    miBoton.value.focus();
  }else if (precio.value === '' || precio.value === null) {
    showError.value = true;
  }else if (cantidad.value === '' || cantidad.value === null) {
    showError2.value = true;
  }
  
};

//validación si campo "Precio" y "Cantidad" están vacios
const showError = ref(false);
const showError2 = ref(false);

const validateInput = () => {
  if (precio.value.trim() === '') {
  } else {
    showError.value = false;
  }
};
const validateInput2 = () => {
  if (cantidad.value.trim() === '') {
  } else {
    showError2.value = false;
  }
};

</script>
<template>
    <Head title="Comprar" />
    <Layout>
        <template #header>
            <h2 class="flex uppercase font-bold text-xl text-gray-800 leading-tight">Compras</h2>
        </template>
        <div class="flex flex-col h-full ml-16">

            <div class="px-4 pt-4">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div>
                      <!--  <form @submit.prevent="">
                            <Popover>
                            <PopoverButton as="div" class="border w-fit border-blue-300 hover:bg-blue-500" >
                                <button ref="primero" >botoncito pa</button>
                                
                            </PopoverButton>
                            </Popover>-->
                        <!--<BuscarProducto ref="primero"/>
                        <TextInput ref="inputField" @keyup.enter="focusNextInput(2)" type="text"/>
                        <TextInput ref="inputField2" @keyup.enter="focusButton" type="text"/>
                        <button ref="boton" @click="focusInput" >Hola</button>
                        </form>-->


                        <form @submit.prevent="onSubmit()">
                            <div class="mt-2 ml-4 inline-flex space-x-2">
                                <div class="flex space-x-2">
                                    <InputLabel for="usuario" value="Usuario:" class="text-gray-600 mt-2 " />

                                    <TextInput id="usuario" type="text" class="mt-1 w-40 h-8 bg-gray-200 text-gray-600"
                                        v-model="form.usuario" required autocomplete="usuario" disabled />


                                    <!--<InputError class="mt-2" :message="form.errors.marca" />-->
                                </div>
                                <div class="flex space-x-2">
                                    <InputLabel for="codigo" value="Codigo:" class="text-gray-600 mt-2" />
                                    <TextInput id="codigo" type="number" v-model="form.codigo"
                                        class="mt-1 bg-gray-200 text-gray-600 sm:rounded-lg w-1/3 h-8" disabled />


                                    <!--<InputError class="mt-2" :message="form.errors.venta" />-->
                                </div>
                            </div>

                            <div class="mt-4 ml-4">

                                <div class="inline-flex space-x-2">

                                    <div class="flex space-x-2">
                                        <span class="block font-medium text-sm text-gray-700 mt-2">Proveedor</span>
                                        <BuscarProveedor placeholder="Buscar Proveedor..." :load-options="loadProveedor"
                                            v-model="proveedor" />
                                    </div>

                                    <div class="flex space-x-2">
                                        <InputLabel for="nrofactura" value="Nro Factura:" class="text-gray-600 mt-2 " />

                                        <TextInput id="nrofactura" type="number"
                                            class="mt-1 w-40 h-8 bg-gray-200 text-gray-600" v-model="form.nrofactura"
                                            required autocomplete="nrofactura" />


                                        <!--<InputError class="mt-2" :message="form.errors.marca" />-->
                                    </div>

                                    <div class="flex space-x-2">
                                        <InputLabel for="fechafactura" value="Fecha Factura:" class="text-gray-600 mt-2 " />

                                        <TextInput id="fechafactura" type="date"
                                            class="mt-1 w-40 h-8 bg-gray-200 text-gray-600" v-model="form.fechafactura"
                                            required autocomplete="fechafactura" />


                                        <!--<InputError class="mt-2" :message="form.errors.marca" />-->
                                    </div>


                                </div>
                            </div>
                            <div class="flex justify-end pr-3">
                                <div class=" w-fit mr-2">
                                    <button class="hover:bg-gray-300 ring-2 focus:ring-set-2 ring-cyan-400 rounded-md">
                                        <i class="h-6 w-6 inline mb-1 ml-2 fa-sharp fa-solid fa-floppy-disk" />
                                        <a class="text-sm font-bold rounded-md mr-3 uppercase">registrar compra</a>

                                    </button>
                                </div>

                                <div class=" w-fit border-2 border-gray-500">
                                    <span class="text-black font-bold text-xl pr-3">P. TOTAL:</span>
                                    <span class="text-red-500 font-bold text-xl pr-3">{{ formatearNumero(form.total)
                                    }}</span>
                                </div>
                            </div>
                        </form>
                        <div class="-mt-4">
                            <span class="uppercase font-bold text-base p-3">producto</span>
                            <div class="border-4 border-blue-500 rounded-md">
                                <div class="p-2 pt-4 pb-4">
                                    <form @submit.prevent="">
                                        <div class="inline-flex">
                                            <div class="mt-7">
                                                <BuscarProducto/>
                                            </div>
                                            <div class="ml-2">
                                                <InputLabel for="codigobarra" value="Codigo de Barras"
                                                    class="text-gray-600 " />
                                                <TextInput required placeholder="Seleccione Pr..." id="codigobarra"
                                                    type="number" v-model="codigobarra"
                                                    class="placeholder-slate-400  uppercase mt-1 bg-gray-200 text-gray-600 sm:rounded-lg w-36"
                                                    disabled />
                                                <!--<InputError class="mt-2" :message="form.errors.venta" />-->
                                            </div>
                                            <div class="ml-4">
                                                <InputLabel for="descripcion" value="Descripción" class="text-gray-600 " />
                                                <TextInput required placeholder="Seleccione Producto..." id="descripcion"
                                                    type="text" v-model="marca"
                                                    class="placeholder-slate-400 uppercase mt-1 bg-gray-200 text-gray-600 sm:rounded-lg w-96"
                                                    disabled />
                                                <!--<InputError class="mt-2" :message="form.errors.venta" />-->
                                            </div>
                                            <div class="ml-4">
                                                <InputLabel for="stock" value="Stock" class="text-gray-600 " />
                                                <TextInput required id="stock" type="number" v-model="stock" placeholder="..."
                                                    class="mt-1 bg-gray-200 text-gray-600 sm:rounded-lg w-20" disabled />
                                                <!--<InputError class="mt-2" :message="form.errors.venta" />-->
                                            </div>
                                            <div class="ml-4">
                                                <InputLabel for="precio" value="Precio" class="text-gray-600 " />
                                                <TextInput ref="primero" @keydown.enter="focusNext(2)"  id="precio" type="number" v-model="precio" 
                                                class="mt-1 bg-gray-200 text-gray-600 sm:rounded-lg w-32" @input="validateInput" />
                                                <InputError v-if="precio < 0" class="mt-2"
                                                    message="Ingrese Valor Positivo" />
                                                    <p v-if="showError" class="text-red-600">Por favor, rellena este campo.</p>
                                            </div>
                                            <div class="ml-4">
                                                <InputLabel  for="cantidad" value="Cantidad" class="text-gray-600 " />
                                                <TextInput ref="segundo" @keydown.enter="focusNext(3)"  id="cantidad" type="number" v-model="cantidad"
                                                    class="mt-1 bg-gray-200 text-gray-600 sm:rounded-lg w-20" @input="validateInput2"/>
                                                <InputError v-if="cantidad < 0" class="mt-2"
                                                    message="Ingrese Valor Positivo" />
                                                <p v-if="showError2" class="text-red-600">Por favor, rellena este campo.</p>
                                            </div>
                                            <div class="ml-8">
                                                <InputLabel for="total" value="TOTAL" />
                                                <TextInput  id="total" type="number" v-model="total"
                                                    class="mt-1 bg-gray-200 text-gray-600 sm:rounded-lg" disabled />
                                                <!--<InputError class="mt-2" :message="form.errors.venta" />-->
                                            </div>


                                            <div class="mt-7 ml-3">
                                                <button @click="agregarProducto"
                                                    ref="miBoton"
                                                    class="w-8 h-8 hover:bg-green-300  hover:text-green-700 text-green-500 ring-2 focus:ring-set-2 ring-blue-400 rounded-md grid place-content-center">
                                                    <PlusCircleIcon class="w-7 h-7" />
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-hidden">
                <div class="h-full px-4 pb-4 pt-4">
                    <div class=" bg-white max-h-full flex flex-col rounded-md border border-blue-500">

                        <div class="px-2 py-3 overflow-y-auto">

                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-slate-300 text-gray-700 text-left">
                                        <th>ID</th>
                                        <th>C. Barras</th>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Importe</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-400 divide-opacity-30">
                                    <tr v-for="(producto, index) in arrayProductos" :key="index">
                                        <td class="text-gray-700 py-4"> {{ producto.productoid }} </td>
                                        <td class="text-gray-700 py-4">{{ producto.codigobarra }}</td>
                                        <td class="text-gray-700 py-4 uppercase">{{ producto.marca }}</td>
                                        <td class="text-gray-700 py-4">{{ formatearNumero(producto.precio) }}</td>
                                        <td class="text-gray-700 py-4">{{ producto.cantidad }}</td>
                                        <td class="text-gray-700 py-4">{{ formatearNumero(producto.total) }}</td>
                                        <td class="py-4">
                                            <div class="inline-flex">
                                                <div>
                                                    <button @click="eliminarProducto(index)"
                                                        class="w-8 h-8 t hover:text-red-700 text-red-500 rounded-md grid place-content-center">
                                                        <XIcon class="w-6 h-6" />
                                                    </button>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                    <!--div se muestra en caso que no hayan registros-->
                                    <div v-if="!contarProductos()" class="p-5">
                                        <div class="absolute left-2/4 -translate-x-1/2 -mt-2">
                                            <span class="font-serif text-xl text-slate-500 uppercase">sin registros</span>
                                        </div>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</Layout></template>