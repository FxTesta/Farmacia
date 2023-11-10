<template>
    <div class="cerrar-caja">
      <h1>Cerrar Caja</h1>
      <form @submit.prevent="cerrarCaja">
        <div>
          <label for="monto">Monto de Cierre:</label>
          <input type="number" id="monto" v-model="montoCierre" required>
        </div>
        <button type="submit">Cerrar Caja</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  
  const montoCierre = ref(0);
  
  const cerrarCaja = async () => {
    try {
      const response = await axios.put('/api/ruta-para-cerrar-caja', { monto: montoCierre.value });
      // Manejar la respuesta
      console.log(response);
    } catch (error) {
      // Manejar el error
      console.error(error);
    }
  };
  </script>
  