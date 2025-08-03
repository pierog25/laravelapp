<template>
  <!-- Sidebar -->
  <nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white active"
       id="sidenav-main">
    <div class="scroll-wrapper scrollbar-inner" style="position: relative;">
      <div class="scrollbar-inner scroll-content"
           style="height: 913px; margin-bottom: 0px; margin-right: 0px; max-height: none;">

        <div class="sidenav-header  align-items-center">
          <router-link class="navbar-brand d-inline-block" :to="{ name: 'homelayout' }">{{ appName }}</router-link>
          <button type="button" class="close p-2" id="dismiss" @click.prevent="dismissMenu">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="navbar-inner">

          <div class="collapse navbar-collapse" id="sidenav-collapse-main">

            <ul class="navbar-nav">
              <ListItem v-for="(item, index) in menu" @closePanel="dismissMenu" :title="item.titulo" :icon="item.icon" :options="item.options" :key="index+'items'"/>
            </ul>

          </div>
        </div>
      </div>
      <div class="scroll-element scroll-x">
        <div class="scroll-element_outer">
          <div class="scroll-element_size"></div>
          <div class="scroll-element_track"></div>
          <div class="scroll-bar" style="width: 0px;"></div>
        </div>
      </div>
      <div class="scroll-element scroll-y">
        <div class="scroll-element_outer">
          <div class="scroll-element_size"></div>
          <div class="scroll-element_track"></div>
          <div class="scroll-bar" style="height: 0px;"></div>
        </div>
      </div>
    </div>
  </nav>
</template>
<script>

import menuList from '../data'
import ListItem from '../components/ListItem'

export default {
  components: {
    ListItem,
  },
  data() {
    return {
      menuList,
      menu:[]
    }
  },
  props:{
    appName:{
      type:String,
      default:""
    }
  },
  methods: {
    dismissMenu(){
      // hide sidebar
      $('#sidenav-main').addClass('active');
      // hide overlay
      $('.overlay').removeClass('active');
    },
    filtrarMenuPorPermisos(menuList, permisosUsuario) {
      return menuList
          .map(menu => {
            // Filtrar subopciones según permisos
            const opcionesFiltradas = menu.options.filter(opcion =>
                permisosUsuario.includes(opcion.gate)
            );

            // Mostrar solo los menús con al menos una opción visible
            if (opcionesFiltradas.length > 0) {
              return {
                ...menu,
                options: opcionesFiltradas
              };
            }

            return null; // No mostrar este grupo de menú
          })
          .filter(menu => menu !== null);
    },
    async getPermissions() {
      //consumir esta api con axios /api/permission
      try {
        const response = await axios.get('/permission');
        const permisos = response.data.data; // debe ser un array de strings
        this.menu = this.filtrarMenuPorPermisos(menuList, permisos);
        console.log(this.menu, "menu")
      } catch (error) {
        console.error('Error fetching permissions:', error);
      }
    }
  },
  async mounted() {
    await this.getPermissions();
    $('#dismiss, .overlay').on('click', function () {
      // hide sidebar
      $('#sidenav-main').addClass('active');
      // hide overlay
      $('.overlay').removeClass('active');
    });
  },
}
</script>
<style scoped>
#sidenav-main {
  max-width: 250px;
}

#sidenav-main.active {
  margin-left: -250px;
}
@media (max-width: 1199.98px){
  .sidenav {
    transform: translateX(0px) !important;
  }
}
</style>