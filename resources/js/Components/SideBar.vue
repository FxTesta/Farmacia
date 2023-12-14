<script>
import { SidebarMenu } from 'vue-sidebar-menu'
import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'
import '@fortawesome/fontawesome-free/css/all.css'
export default {
  components: {
    SidebarMenu
  },
  data() {
    return {
      menu: [
        {
          header: 'FarmaSoft',
          hiddenOnCollapse: true
        },
        {
          href: '/dashboard',
          title: 'Inicio',
          icon: 'fa-solid fa-house-chimney-medical'
                 
        },

        {
          href: '/caja',
          title: 'Cajas',
          icon: 'fa-solid fa-cash-register'
        },


        {
          href: '/compra',
          title: 'Compras',
          icon: 'fa-sharp fa-solid fa-shop',
          child: [
            {
              href: '/compra/listar',
              title: 'Listar Compras',
              icon: 'fa fa-list'
            },
            {
              href: '/compra/ordencompra',
              title: 'Orden de compra', 
              icon: 'fa fa-list'
            }
          ]
        },
        {
          href: '/venta',
          title: 'Ventas',
          icon: 'fa-sharp fa-solid fa-cart-shopping',
          
          child: [
            {
              href: '/venta/listar',
              title: 'Listar Ventas',
              icon: 'fa fa-list'
            }
          ]
        },
        
        {
          href: '/cliente',
          title: 'Clientes',
          icon: 'fa fa-user'
        },
        
        {
          href: '/producto',
          title: 'Productos',
          icon: 'fa-sharp fa-solid fa-flask',
          child: [
            {
              href: '/productosfaltantes',
              title: 'Productos Faltantes',
              icon: 'fa-solid fa-bars'
            }
          ]
          
        },/*

                {
          href: '/compras',
          title: 'Compras',
          icon: 'fa-sharp fa-solid fa-cart-shopping'
          
        },*/

        {
          href: '/proveedor',
          title: 'Proveedores',
          icon: 'fa-solid fa-truck-field',
          /*child: [
            {
              href: '/charts/sublink',
              title: 'Sub Link',
              icon: 'fa fa-user',
            }
          ]*/
        },
      
        {
          href: '/configuracion',
          title: 'Configuración',
          icon: 'fa-sharp fa-solid fa-gear',
        
          
        },


      ],
      
      collapsed: {
        type: Boolean,
        default: false
      },
      theme: {
    type: String,
    default: ''
  },
    }
  }
}
</script>
<template>
  <sidebar-menu :link-component-name="'InertiaLink'" :menu="menu" :collapsed="true"/>
</template>