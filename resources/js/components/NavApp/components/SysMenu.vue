<template>
  <li class="nav-item dropdown">
    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <div class="media align-items-center">
          <span class="avatar avatar-sm rounded-circle" v-if="profile_photo">
            <img alt="Image placeholder" :src="profile_photo">
          </span>
        <div class="media-body  ml-2">
          <span class="mb-0 text-sm  font-weight-bold">
            {{ user_name }} <i
              class="fas fa-caret-down"></i></span>
        </div>
      </div>
    </a>
    <a class="nav-link nav-link-icon" href="#" id="navbar-default_dropdown_1" role="button" data-toggle="dropdown"
       aria-haspopup="true" aria-expanded="false">
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
      <div class="dropdown-header noti-title">
        <h6 class="text-overflow m-0">Bienvenido!</h6>
      </div>
      <a class="dropdown-item" href="#"> <i class="fas fa-user"></i> Perfil</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="#" @click.prevent="logout"><i class="fas fa-times"></i> Cerrar sesión</a>
    </div>
  </li>
</template>
<script>
export default {
  props: {
    userLogged: {
      type: Object,
    },
  },
  computed:{
    profile_photo: function () {
      return (this.userLogged.profile_photo)?JSON.parse(this.userLogged.profile_photo)[0].name:'https://i.pinimg.com/736x/96/8d/16/968d16cfb682b4f6e7d9dc6a19b84ba5.jpg'
    },
    user_name: function () {
      return (this.userLogged.name.toUpperCase()) + (this.user_role? ' - ' + this.user_role : '');
    },
    user_role: function () {
      return (this.userLogged.roles.length>0)?(this.userLogged.roles[0].name).toUpperCase():'';
    }
  },
  methods: {
    logout() {
      axios.post("logout").then(response => {
        this.$router.go('/login')
      }).catch(error => {
        window.location.href = '/login'
        console.log(error)
      })
    }
  }
}
</script>
