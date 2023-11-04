<script setup>
import Layout from "@/Layouts/Layout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { XIcon, PlusCircleIcon, SearchIcon } from "@heroicons/vue/outline";
import { ref, computed, watch, onMounted } from "vue";
import BuscarProducto from "@/Pages/Venta/BuscarProducto.vue";
import BuscarCliente from "@/Pages/Venta/BuscarCliente.vue";
import error from "@/Stores/error";
import ConfirmarImpresion from "@/Pages/Venta/ConfirmarImpresion.vue";

//función para buscar de la bd, recibe el query de busqueda y setOptions devuelve el resultado
function loadProducto(query, setOptions) {
  fetch("http://127.0.0.1:8000/buscarproductoventa?query=" + query)
    .then((response) => response.json())
    .then((results) => {
      setOptions(
        results.map((producto) => {
          return {
            id: producto.id,
            marca: producto.marca,
            codigo: producto.codigo,
            stock: producto.stock,
            droga: producto.droga,
            descripcion: producto.descripcion,
            precioventa: producto.precioventa,
            venta: producto.venta,
            vencimiento: producto.vencimiento,
            laboratorio: producto.laboratorio,
            descuento: producto.descuento,
            iva: producto.iva,
            estante: producto.estante,
          };
        })
      );
    });
}

function loadCliente(query, setOptions) {
  fetch("http://127.0.0.1:8000/buscarcliente?query=" + query)
    .then((response) => response.json())
    .then((results) => {
      setOptions(
        results.map((cliente) => {
          return {
            value: cliente.id,
            label: cliente.name,
            cedula: cliente.cedula,
          };
        })
      );
    });
}

const props = defineProps({
  user: Object,
  configuracion: Object,
});

//variable reactiva donde se recibe el producto
let producto = ref();

//variable retorna codigo de barra que extrae de "producto"
let productoid = computed(() => producto.value?.id);

//variable retorna codigo de barra que extrae de "producto"
let codigobarra = computed(() => producto.value?.codigo);

//variable retorna marca que extrae de "producto"
let marca = computed(() => producto.value?.marca);

//variable retorna stock que extrae de "producto"
let stock = computed(() => producto.value?.stock);

//variable retorna iva que extrae de "producto"
let iva = computed(() => producto.value?.iva);

//variable retorna descuento que extrae de "producto"
let descuento = ref();

//variable retorna precioventa que extrae de "producto"
let precioventa = ref();

//utilizo watch para la variable "precioventa" en vez de computed por que necesito que sea editable
watch(
  producto, // variable que quiero observar los cambios
  (nuevoProducto) => {
    if (nuevoProducto) {
      precioventa.value = nuevoProducto.precioventa; //si cambia lo contenido en "producto" se actualiza "precioventa"
      descuento.value = nuevoProducto.descuento; //si cambia lo contenido en "producto" se actualiza "descuento"
    }
  }
);

//variable que contiene el precio de venta ya con el descuento establecido
let preciodescuento = computed(() => {
  if (producto.value === null) {
    return null;
  }

  //verifica si el producto tiene descuento
  if (descuento.value) {
    const precioNumerico = parseFloat(precioventa.value);
    const descuentoNumerico = parseFloat(descuento.value);

    if (!isNaN(precioNumerico) && !isNaN(descuentoNumerico)) {
      const descuento = Math.round((descuentoNumerico / 100) * precioNumerico);
      return precioNumerico - descuento;
    } else {
      return 0;
    }
  } else {
    //si el producto no tiene descuento preciodescuento = precioventa
    return precioventa.value;
  }
});

let cantidad = ref();

//calcula el total en la selección de producto
const total = computed(() => {
  const precioNumerico = parseFloat(preciodescuento.value);
  const cantidadNumerica = parseFloat(cantidad.value);

  // Verificar si los valores son numéricos antes de calcular el producto
  if (!isNaN(precioNumerico) && !isNaN(cantidadNumerica)) {
    return precioNumerico * cantidadNumerica;
  } else {
    return 0; // Otra opción sería retornar null o un mensaje de error
  }
});

//recibe el producto seleccionado en el buscado
const productoEncontrado = (event) => {
  producto.value = event;
};

//variable reactiva donde se recibe el cliente
let cliente = ref();

let clienteid = computed(() => cliente.value?.value); //variable contiene cliente id para guardar en la bd

let clientenombre = computed(() => cliente.value?.label); //variable contiene cliente nombre para guardar en la bd

//variable contiene el prefijo de factura guardado en "Configuración"
let prefijofactura = computed(() => {
  if (props.configuracion.length > 0) {
    return props.configuracion[0].nrofactura;
  } else {
    return "asignar";
  }
});

//variable contiene el timbrado guardado en "Configuración"
let timbrado = computed(() => {
  if (props.configuracion.length > 0) {
    return props.configuracion[0].timbrado;
  } else {
    return null;
  }
});

let valorMaximo = ref(); //tiene los últimos 7 digitos de la última factura

/* onMounted(async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/obtenerfactura'); //obtengo el valor "max" de factura
    if (response.ok) {
      const data = await response.json();
      let longuitud = Object.keys(data).length; //longuitud de valor recibido para checkear
        if(longuitud > 0) //si longuitud es > 0 quiere decir que "data" no es null entonces asigna el valor
        {
          valorMaximo.value = data.slice(-7); // extrae los últimos 7 digitos de la última factura
        }
        
    } else {
      console.error('Error al obtener el valor máximo');
    }
  } catch (error) {
    console.error('Error de red:', error);
  }
}); */

const fetchData = async () => {
  try {
    const response = await fetch("http://127.0.0.1:8000/obtenerfactura");
    if (response.ok) {
      const data = await response.json();
      let longuitud = Object.keys(data).length;
      if (longuitud > 0) {
        valorMaximo.value = data.slice(-7);
      }
    } else {
      console.error("Error al obtener el valor máximo");
    }
  } catch (error) {
    console.error("Error de red:", error);
  }
};

onMounted(fetchData);

const asignarFacturaContinuacion = () => {
  if (valorMaximo.value) {
    //si existe esta wea entonces suma +1 para la siguiente factura
    const numero = parseInt(valorMaximo.value, 10); //el "10" es para indicar que es base decimal
    const valorMaximoMasUno = ref(
      (numero + 1).toString().padStart(valorMaximo.value.length, "0")
    );
    return valorMaximoMasUno.value;
    //basicamente convertí en un entero para sumarle +1, después convierto en string again y relleno con el caracter "0" para poder concatenar con prefijofactura
  } else {
    return "0000001"; //si no existe "valorMaximo" quiere decir que es la primera factura entonces asigna eso.
  }
};

//variable que contiene el resto de la factura, osea lo que se concatena con el prefijo
let facturacontinuacion = computed(() => {
  return asignarFacturaContinuacion();
});

let nrofactura = computed(
  () => `${prefijofactura.value}-${facturacontinuacion.value}`
); //variable concatena prefijofactura + facturacontinuacion

//array que va a contener el listado de los productos a ser vendidos
const arrayProductos = ref([]);

//se establecen los campos del array y el valor que van a tomar
const agregarProducto = () => {
  //condición para que no este ningún campo nulo para poder guardar los datos en "arrayProductos"

  if (
    productoid.value !== null &&
    codigobarra.value !== null &&
    marca.value !== null &&
    total.value !== 0 &&
    precioventa.value !== null &&
    cantidad.value !== null &&
    preciodescuento.value !== null &&
    productoid.value !== undefined &&
    codigobarra.value !== undefined &&
    marca.value !== undefined &&
    precioventa.value !== undefined &&
    cantidad.value !== undefined &&
    preciodescuento.value !== undefined &&
    productoid.value !== "" &&
    preciodescuento.value !== "" &&
    codigobarra.value !== "" &&
    marca.value !== "" &&
    precioventa.value !== "" &&
    cantidad.value !== "" &&
    cantidad.value > 0
  ) {
    //se cargan los datos en el array
    arrayProductos.value.push({
      productoid: productoid.value,
      codigobarra: codigobarra.value,
      iva: iva.value,
      marca: marca.value,
      preciopublico: precioventa.value,
      descuento: descuento.value,
      preciodescuento: preciodescuento.value,
      cantidad: cantidad.value,
      total: total.value,
    });

    //una vez cargado los datos se reestablecen los campos para la siguiente carga.
    descuento.value = null;
    cantidad.value = null;
    producto.value = null;

    //después de agregar el producto envia un evento a "BuscarProducto" para enfocar nuevamente el boton para buscar Producto
    /*    const event1 = new CustomEvent('enviar-ref');
    window.dispatchEvent(event1); */
  } else {
    console.log("No entro");
  }
};

//elimina lo contenido en el array
const eliminarProducto = (index) => {
  const productoEliminado = arrayProductos.value[index];
  const mensaje = "Remover " + productoEliminado.marca + "?";

  if (window.confirm(mensaje)) {
    arrayProductos.value.splice(index, 1);
  }
};

// Función para calcular la suma de la propiedad 'total' de los objetos en arrayProductos
const calcularSumaTotal = () => {
  return arrayProductos.value.reduce(
    (total, producto) => total + producto.total,
    0
  );
};

//calcula el total de la compra sin descuento
const calcularPrecioPublico = () => {
  return arrayProductos.value.reduce(
    (total, producto) => total + (producto.preciopublico * producto.cantidad),
    0
  );
};

//Variable que retorna "calcularPrecioPublico"
let totalpreciopublico = computed(() => {
  return calcularPrecioPublico();
});

let montoahorrado = computed(() => {
  let totaldescuentonumerico = parseFloat(preciototal.value);
  let totalpreciopubliconumerico = parseFloat(totalpreciopublico.value);

  if (!isNaN(totaldescuentonumerico) && !isNaN(totalpreciopubliconumerico)) {
    return totalpreciopubliconumerico - totaldescuentonumerico;
  } else {
    return 0;
  }
});

//calcula sumatoria de gravadas 5%
const calcularExenta = () => {
  return arrayProductos.value
    .filter((producto) => producto.iva === 0)
    .reduce((total, producto) => total + producto.total, 0);
};

let exenta = computed(() => {
  return calcularExenta();
});
//calcula sumatoria de gravadas 5%
const calcularCinco = () => {
  return arrayProductos.value
    .filter((producto) => producto.iva === 5)
    .reduce((total, producto) => total + producto.total, 0);
};

let gravadascinco = computed(() => {
  return calcularCinco();
});

//calcula sumatoria de gravadas 10%
const calcularDiez = () => {
  return arrayProductos.value
    .filter((producto) => producto.iva === 10)
    .reduce((total, producto) => total + producto.total, 0);
};

let gravadasdiez = computed(() => {
  return calcularDiez();
});

let ivacinco = computed(() => {
  let gravadascinconumerico = parseFloat(gravadascinco.value);
  if (!isNaN(gravadascinconumerico)) {
    return Math.round(gravadascinconumerico / 21);
  } else {
    return 0;
  }
});

let ivadiez = computed(() => {
  let gravadasdieznumerico = parseFloat(gravadasdiez.value);
  if (!isNaN(gravadasdieznumerico)) {
    return Math.round(gravadasdieznumerico / 11);
  } else {
    return 0;
  }
});

let ivatotal = computed(() => {
  let ivatotalnumerico = parseFloat(ivadiez.value + ivacinco.value);
  if (!isNaN(ivatotalnumerico)) {
    return ivatotalnumerico;
  } else {
    return 0;
  }
});

//variable retorna suma del campo total para obtener "P. TOTAL"
let preciototal = computed(() => {
  return calcularSumaTotal();
});

let importemixto = ref();

const importeTarjeta = computed (() => {
  let totalnumerico = parseFloat(preciototal.value);
  let importenumerico = parseFloat(importemixto.value);
  if(!isNaN(totalnumerico) && !isNaN(importenumerico)){
    return totalnumerico - importenumerico;
  }else{
    return null
  }
})

//valida cantidad que no sea un valor negativo
const validarCantidad = () => {
  if (cantidad.value < 0) {
    cantidad.value = 0;
  }
};

//variable que guarda el monto con que paga el cliente
let pagacon = ref();

//valida que "PAGA CON" no sea un valor negativo
const validarPagacon = () => {
  if (pagacon.value < 0) {
    pagacon.value = 0;
  }
};

let cambio = computed(() => {
  const pagaconNumerico = parseFloat(pagacon.value);
  const totalNumerica = parseFloat(preciototal.value);

  // Verificar si los valores son numéricos antes de calcular el producto
  if (!isNaN(pagaconNumerico) && !isNaN(totalNumerica)) {
    return pagaconNumerico - totalNumerica;
  } else {
    return 0; // Otra opción sería retornar null o un mensaje de error
  }
});

const formatearNumero = (numero) => {
  return numero.toLocaleString("es", { useGrouping: true });
};

function mindate() {
  return new Date().toISOString().split("T")[0];
}

function addError(message) {
  error.add1({
    message1: message,
  });
}

let form = useForm({
  usuario: props.user.name,
  codigo: props.user.id,
  formadepago: "",
  nrotransaccion: "",
  importemixto: importemixto,
  tarjetamixto: importeTarjeta,
  fechafactura: mindate(),
  clienteid: clienteid,
  clientenombre: clientenombre,
  comprobante: "",
  nrofactura: nrofactura,
  timbrado: timbrado,
  exenta: exenta,
  gravadascinco: gravadascinco,
  gravadasdiez: gravadasdiez,
  ivacinco: ivacinco,
  ivadiez: ivadiez,
  ivatotal: ivatotal,
  pagacon: pagacon,
  cambio: cambio,
  preciototal: preciototal,
  montoahorrado: montoahorrado,
  arrayProductos: arrayProductos.value, //array con la lista de productos comprados
});

//función que guarda la venta realizada en la base de datos
function onSubmit() {
  // Verificar si arrayProductos es nulo o vacío

  if (form.formadepago === "") {
    addError("Seleccionar Forma de Pago");
  }

  if(!importemixto.value && form.formadepago === "Mixto"){
    addError("Ingrese importe Efectivo");
    return;
  }

  if (
    form.formadepago === "Efectivo" &&
    pagacon.value < preciototal.value &&
    pagacon.value !== ""
  ) {
    addError("El pago no cubre la cuenta");
    return;
  }

  if (form.formadepago === "Efectivo" && !pagacon.value) {
    addError("Ingresar 'PAGA CON'");
    return;
  }

  if (
    (form.formadepago === "Tarjeta Debito" ||
      form.formadepago === "Tarjeta Credito" ||
      form.formadepago === "Mixto") &&
    form.nrotransaccion === ""
  ) {
    addError("Ingrese Nro transacción de Tarjeta");
    return;
  }

  if (form.comprobante === "") {
    addError("Seleccionar Comprobante");
  }

  if (!cliente.value) {
    addError("Seleccionar Cliente");
  }

  if (!arrayProductos.value || arrayProductos.value.length === 0) {
    // Realizar alguna acción en caso de que arrayProductos sea nulo o vacío
    addError("No hay productos");
    return; // Salir de la función onSubmit sin hacer la solicitud POST
  }

  form.post(route("venta.store"), {
    onSuccess: () => {
      arrayProductos.value.splice(0); //resetea el array después de guardar en la BD
      producto.value = null; //resetea la variable reactiva (let producto = ref();) después de guardar los campos en la bd
      cliente.value = null; //resetea la variable reactiva (let cliente = ref();) después de guardar los campos en la bd
      descuento.value = null;
      cantidad.value = null;
      importemixto.value = null;
      form.formadepago = "";
      form.comprobante = "";
      form.nrotransaccion = "";
      pagacon.value = null;
      fetchData();

      const event = new CustomEvent("imprimir");
      window.dispatchEvent(event);
    },
  });
}
</script>
<template>
  <Head title="Ventas" />
  <Layout>
    <ConfirmarImpresion />
    <template #header>
      <h2 class="flex uppercase font-bold text-xl text-gray-800 leading-tight">
        Ventas
      </h2>
    </template>
    <div class="max-h-full flex">
      <div class="ml-16 grid grid-rows-6 grid-cols-5 gap-4 mt-4 mb-3 px-3 pb-2">
        <!--div que contiene cabecera factura-->
        <div
          class="flex flex-col col-end-5 col-start-1 row-start-1 row-end-4 bg-white rounded-md border border-black"
        >
          <div class="flex flex-row w-full p-3 mt-4 space-x-5">
            <div class="space-x-1">
              <label for="usuario" class="font-medium text-sm">Usuario:</label>
              <input
                disabled
                type="text"
                id="usuario"
                name="usuario"
                v-model="form.usuario"
                class="rounded-md w-[130px] h-[28px] bg-slate-200 text-slate-500"
              />
            </div>
            <div class="space-x-1">
              <label for="codigo" class="font-medium text-sm">Codigo:</label>
              <input
                disabled
                type="text"
                id="codigo"
                name="codigo"
                v-model="form.codigo"
                class="rounded-md w-[80px] h-[28px] bg-slate-200 text-slate-500"
              />
            </div>
            <div class="space-x-1 -mt-1">
              <label for="formadepago" class="font-medium text-sm"
                >Forma de pago:</label
              >
              <select
                id="formadepago"
                v-model="form.formadepago"
                class="rounded-md w-[150px] h-[35px] p-1"
              >
                <option disabled value="">Seleccione</option>
                <option>Efectivo</option>
                <option>Tarjeta Debito</option>
                <option>Tarjeta Credito</option>
                <option>Mixto</option>
              </select>
            </div>
            <!--             <div class="space-x-1">
              <label for="fechafactura" class="font-medium text-sm"
                >Fecha Factura:</label
              >
              <input
                disabled
                type="date"
                id="fechafactura"
                name="fechafactura"
                v-model="form.fechafactura"
                class="bg-slate-200 text-slate-500 rounded-md w-[130px] h-[28px] p-1"
              />
            </div> -->
            <div
              v-if="
                form.formadepago === 'Tarjeta Debito' ||
                form.formadepago === 'Tarjeta Credito' ||
                form.formadepago === 'Mixto'
              "
              class="space-x-1"
            >
              <label for="nrotransaccion" class="font-medium text-sm"
                >Nro Transaccion:</label
              >
              <input
                type="number"
                id="nrotransaccion"
                name="nrotransaccion"
                v-model="form.nrotransaccion"
                class="rounded-md p-1 w-[140px] h-[28px] bg-white text-slate-500"
              />
            </div>
          </div>
          <div class="flex flex-row w-full p-3 space-x-5 mt-1 items-center">
            <div class="space-x-1 inline-flex">
              <span class="font-medium text-sm mt-3">Cliente:</span>
              <BuscarCliente
                placeholder="Buscar Cliente..."
                :load-options="loadCliente"
                v-model="cliente"
              />
            </div>
            <div class="space-x-1">
              <label for="comprobante" class="font-medium text-sm"
                >Comprobante:</label
              >
              <select
                id="comprobante"
                v-model="form.comprobante"
                class="rounded-md w-[150px] h-[35px] p-1"
              >
                <option disabled value="">Seleccione</option>
                <option>Factura</option>
                <option>Ticket</option>
              </select>
            </div>
            <div
              v-if="form.formadepago === 'Mixto'"
              class="space-x-1"
            >
              <label for="importemixto" class="font-medium text-sm"
                >Importe Efectivo:</label
              >
              <input
                type="number"
                id="importemixto"
                name="importemixto"
                v-model="importemixto"
                class="rounded-md p-1 w-[100px] h-[28px] bg-white text-slate-500"
              />
            </div>
            <!--  <div class="space-x-1">
              <label for="nrofactura" class="font-medium text-sm"
                >Numero Factura:</label
              >
              <input
                disabled
                type="text"
                id="nrofactura"
                v-model="nrofactura"
                name="nrofactura"
                class="rounded-md p-1 w-[140px] h-[28px] bg-slate-200 text-slate-500"
              />
            </div>
            <div class="space-x-1">
              <label for="timbrado" class="font-medium text-sm"
                >Timbrado:</label
              >
              <input
                disabled
                type="number"
                id="timbrado"
                name="timbrado"
                v-model="form.timbrado"
                class="rounded-md w-[100px] h-[28px] bg-slate-200 text-slate-500"
              />
            </div> -->
          </div>
          <span class="mt-0 ml-2 uppercase font-bold text-lg">Producto</span>
          <div
            class="border-2 bg-gradient-to-b from-[#938E8E] to-[#524F4F] border-[#000000] rounded-md h-full overflow-hidden items-center flex"
          >
            <div
              class="flex flex-row text-left space-x-3 w-full justify-center mb-3"
            >
              <BuscarProducto
                :load-options="loadProducto"
                @send-product="productoEncontrado"
              />
              <div class="flex flex-col space-y-1">
                <label
                  for="codigodebarra"
                  class="font-inter font-bold text-white text-base font-shadow"
                  >Codigo de barras</label
                >
                <input
                  id="codigodebarra"
                  type="number"
                  v-model="codigobarra"
                  disabled
                  class="font-inter text-base rounded-md border border-black bg-gray-300 w-[135px] p-1"
                />
              </div>
              <div class="flex flex-col space-y-1">
                <label
                  for="descripcion"
                  class="font-inter font-bold text-white text-base font-shadow"
                  >Descripcion</label
                >
                <input
                  id="descripcion"
                  type="text"
                  v-model="marca"
                  disabled
                  class="font-inter text-base rounded-md border border-black bg-gray-300 w-[270px] p-1"
                />
              </div>
              <div class="flex flex-col space-y-1">
                <label
                  for="stock"
                  class="font-inter font-bold text-white text-base font-shadow"
                  >Stock</label
                >
                <input
                  id="stock"
                  type="number"
                  v-model="stock"
                  disabled
                  class="font-inter text-base rounded-md border border-black bg-gray-300 w-[70px] p-1"
                />
              </div>
              <div class="flex flex-col space-y-1">
                <label
                  for="precio"
                  class="font-inter font-bold text-white text-base font-shadow"
                  >Precio Venta</label
                >
                <input
                  id="precio"
                  type="number"
                  v-model="preciodescuento"
                  disabled
                  class="font-inter text-base rounded-md border border-black bg-gray-300 w-[120px] p-1"
                />
              </div>
              <div class="flex flex-col space-y-1">
                <label
                  for="descuento"
                  class="font-inter font-bold text-white text-base font-shadow"
                  >Dto</label
                >
                <input
                  id="descuento"
                  type="number"
                  v-model="descuento"
                  disabled
                  class="font-inter text-base rounded-md border border-black bg-gray-300 w-[50px] p-1"
                />
              </div>
              <div class="flex flex-col space-y-1">
                <label
                  for="cantidad"
                  class="font-inter font-bold text-white text-base font-shadow"
                  >Cantidad</label
                >
                <input
                  id="cantidad"
                  type="number"
                  v-model="cantidad"
                  autocomplete="off"
                  @input="validarCantidad"
                  class="font-inter text-base rounded-md border border-black bg-white w-[70px] p-1"
                />
              </div>
              <div class="flex flex-col space-y-1">
                <label
                  for="total"
                  class="font-inter font-bold text-white text-base font-shadow"
                  >Total</label
                >
                <input
                  id="total"
                  type="number"
                  v-model="total"
                  disabled
                  class="font-inter text-base rounded-md border border-black w-[120px] bg-gray-300 p-1"
                />
              </div>
              <div class="mt-7">
                <button
                  type="button"
                  @click="agregarProducto"
                  class="bg-white w-8 h-8 hover:bg-green-300 hover:text-green-700 text-green-500 ring-2 focus:ring-set-2 ring-blue-400 rounded-md grid place-content-center"
                >
                  <PlusCircleIcon class="w-7 h-7" />
                </button>
              </div>
            </div>
          </div>
        </div>
        <!--div que contiene detalle factura-->
        <div
          class="flex-1 row-start-4 row-end-7 col-end-5 col-start-1 bg-white overflow-hidden rounded-md border border-black"
        >
          <div class="h-full">
            <div class="max-h-full flex flex-col">
              <div class="px-3 mt-2 overflow-y-auto h-screen">
                <table class="min-w-full">
                  <thead>
                    <tr class="text-left uppercase">
                      <th>ID</th>
                      <th>C. Barras</th>
                      <th>IVA</th>
                      <th>Producto</th>
                      <th>Precio Pub.</th>
                      <th>Dto.</th>
                      <th>Precio Dto.</th>
                      <th>Cantidad</th>
                      <th>Importe</th>
                      <th>...</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-400 divide-opacity-30">
                    <tr
                      v-for="(producto, index) in arrayProductos"
                      :key="index"
                      class="text-left"
                    >
                      <td class="text-gray-700 py-4">
                        {{ producto.productoid }}
                      </td>
                      <td class="text-gray-700 py-4">
                        {{ producto.codigobarra }}
                      </td>
                      <td class="text-gray-700 py-4">{{ producto.iva }}%</td>
                      <td class="text-gray-700 py-4 uppercase">
                        {{ producto.marca }}
                      </td>
                      <td class="text-gray-700 py-4">
                        {{ producto.preciopublico }}
                      </td>
                      <td class="text-gray-700 py-4">
                        {{ producto.descuento }}
                      </td>
                      <td class="text-gray-700 py-4">
                        {{ producto.preciodescuento }}
                      </td>
                      <td class="text-gray-700 py-4">
                        {{ producto.cantidad }}
                      </td>
                      <td class="text-gray-700 py-4">{{ producto.total }}</td>
                      <td class="py-4">
                        <div class="inline-flex">
                          <div>
                            <button
                              @click="eliminarProducto(index)"
                              class="w-8 h-8 t hover:text-red-700 text-red-500 rounded-md grid place-content-center"
                            >
                              <XIcon class="w-6 h-6" />
                            </button>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!--div que contiene detalles de la venta-->
        <div
          class="col-start-5 row-start-1 row-end-7 bg-white overflow-hidden rounded-md border border-black"
        >
          <div class="flex flex-col justify-between h-full">
            <div class="space-y-5">
              <div class="flex justify-center mt-5">
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
              </div>
              <div class="space-y-5 flex flex-col">
                <div class="ml-2 inline-flex space-x-2">
                  <span
                    class="block text-xl font-medium text-gray-700 uppercase"
                  >
                    p. total:
                  </span>
                  <span class="text-xl font-bold text-red-500 uppercase"
                    >{{ formatearNumero(preciototal) }} GS</span
                  >
                </div>
                <!--                 <div class="ml-2 inline-flex space-x-2">
                  <label
                    for="desc"
                    class="block text-xl font-medium text-gray-700 uppercase"
                    >desc:</label
                  >
                  <input
                    id="desc"
                    type="number"
                    placeholder="..."
                    class="w-[30%] h-8 rounded-md placeholder-slate-400 bg-white text-gray-600 border border-black"
                  />
                </div> -->
                <!-- <div class="ml-2 inline-flex space-x-2">
                  <span
                    for="exenta"
                    class="block text-base font-medium text-gray-700 uppercase"
                    >Exentas:</span
                  >
                  <span class="text-base text-slate-500 uppercase"
                    >{{ formatearNumero(exenta) }}
                  </span>
                </div>
                <div class="ml-2 inline-flex space-x-2">
                  <span
                    for="gravadascinco"
                    class="block text-base font-medium text-gray-700 uppercase"
                    >gravadas 5%:</span
                  >
                  <span class="text-base text-slate-500 uppercase"
                    >{{ formatearNumero(gravadascinco) }}
                  </span>
                </div>
                <div class="ml-2 inline-flex space-x-2">
                  <span
                    for="gravadasdiez"
                    class="block text-base font-medium text-gray-700 uppercase"
                    >gravadas 10%:</span
                  >
                  <span class="text-base text-slate-500 uppercase"
                    >{{ formatearNumero(gravadasdiez) }}
                  </span>
                </div>
                <div class="ml-2 inline-flex space-x-2">
                  <span
                    for="ivacinco"
                    class="block text-base font-medium text-gray-700 uppercase"
                    >iva 5%:</span
                  >
                  <span class="text-base text-slate-500 uppercase">{{
                    formatearNumero(ivacinco)
                  }}</span>
                </div>
                <div class="ml-2 inline-flex space-x-2">
                  <span
                    for="ivadiez"
                    class="block text-base font-medium text-gray-700 uppercase"
                    >iva 10%:</span
                  >
                  <span class="text-base text-slate-500 uppercase">{{
                    formatearNumero(ivadiez)
                  }}</span>
                </div>
                <div class="ml-2 inline-flex space-x-2">
                  <span
                    for="ivatotal"
                    class="block text-base font-medium text-gray-700 uppercase"
                    >iva total:</span
                  >
                  <span class="text-base text-slate-500 uppercase">{{
                    formatearNumero(ivatotal)
                  }}</span>
                </div> -->
                <div class="ml-2 inline-flex space-x-2">
                  <span
                    for="ahorrado"
                    class="block text-base font-medium text-gray-700 uppercase"
                    >ahorrado:</span
                  >
                  <span class="text-base text-slate-500 uppercase">{{
                    formatearNumero(montoahorrado)
                  }} Gs</span>
                </div>
                <!--                 <div class="ml-2 pt-4 inline-flex space-x-2">
                  <input id="tarjeta" type="checkbox" />
                  <label
                    for="tarjeta"
                    class="block text-sm font-bold text-black"
                    >Paga con tarjeta +5%
                  </label>
                </div> -->
              </div>
            </div>
            <div v-if="form.formadepago === 'Efectivo'" class="py-2 space-y-4">
              <div class="ml-2 inline-flex space-x-2">
                <label
                  for="pagacon"
                  class="block text-xl font-medium text-gray-700 uppercase"
                  >Abono:</label
                >
                <input
                  id="pagacon"
                  type="number"
                  v-model="pagacon"
                  @input="validarPagacon"
                  placeholder="..."
                  class="w-[50%] h-8 rounded-md placeholder-slate-400 bg-white text-gray-600 border border-black"
                />
              </div>
              <div class="ml-2 inline-flex space-x-2">
                <span class="block text-xl font-medium text-gray-700 uppercase">
                  cambio:
                </span>
                <span class="text-xl font-bold text-blue-500 uppercase"
                  >{{ formatearNumero(cambio) }} Gs</span
                >
              </div>
            </div>
            <div v-else class="py-2 space-y-4">
              <div class="ml-2 inline-flex space-x-2">
                <label
                  for="pagacon"
                  class="block text-xl font-medium text-gray-700 uppercase"
                  >Abono:</label
                >
                <input
                  disabled
                  id="pagacon"
                  type="number"
                  v-model="pagacon"
                  @input="validarPagacon"
                  placeholder="..."
                  class="w-[50%] h-8 rounded-md placeholder-slate-400 bg-slate-300 text-gray-600 border border-black"
                />
              </div>
              <div class="ml-2 inline-flex space-x-2">
                <span class="block text-xl font-medium text-gray-700 uppercase">
                  cambio:
                </span>
                <span
                  class="line-through text-xl font-bold text-blue-500 uppercase"
                  >{{ formatearNumero(cambio) }} Gs</span
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<style>
thead {
  background-color: white;
  position: sticky;
  top: 0;
  z-index: 2;
}

th {
  position: relative;
}

/* Pseudo-elemento ::before para crear un borde inferior */
th::before {
  content: "";
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
