<template>
    <div class="abrir-caja">
      <h1>Abrir Caja</h1>
      <form @submit.prevent="abrirCaja">
        <div>
          <label for="monto">Monto de Apertura:</label>
          <input type="number" id="monto" v-model="montoApertura" required>
        </div>
        <button type="submit">Abrir Caja</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  
  const montoApertura = ref(0);
  
  const abrirCaja = async () => {
    try {
      const response = await axios.post('/api/ruta-para-abrir-caja', { monto: montoApertura.value });
      // Manejar la respuesta
      console.log(response);
    } catch (error) {
      // Manejar el error
      console.error(error);
    }
  };
  </script>
  