<template>
  <nav class="navbar navbar-top navbar-expand navbar-dark bg-default">
    <div class="container-fluid">
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav align-items-center">
          <li class="nav-item">
            <a type="button" id="sidebarCollapse" class="nav-link" href="#" @click.prevent="showSideBar">
              <i class="fas fa-align-left"></i>
            </a>
          </li>
          <li class="nav-item">
            <router-link class="navbar-brand d-inline-block text-left" :to="{ name: 'homelayout' }">{{ appName }}</router-link>
          </li>
        </ul>

        <ul class="navbar-nav align-items-center  ml-auto">
          <SysMenu :userLogged="userLogged" />
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import menuList from '../data'
import SysMenu from '../components/SysMenu'

export default {
  components: {
    SysMenu,
  },
  data() {
    return {
      menu: menuList,
    }
  },
  props: {
    userLogged: {
      type: Object,
    },
    appName: {
      type: String,
      default: ""
    },
  },
  methods:{
    showSideBar(){
      $('#sidenav-main').toggleClass('active');
      $('.overlay').addClass('active');
    }
  }
}
</script>