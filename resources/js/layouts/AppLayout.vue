<template>
  <div class="wrapper">
    <AppMenu :userLogged="userLogged" :appName="appName"></AppMenu>
    <div id="content">
      <NavApp :userLogged="userLogged" :appName="appName"></NavApp>
      <router-view :userLogged="userLogged" :appName="appName"></router-view>
      <FooterPage></FooterPage>
    </div>
    <!-- Dark Overlay element -->
    <div class="overlay"></div>
  </div>
</template>

<script>
import NavApp from '../components/NavApp/layout/NavLayout'
import AppMenu from '../components/MenuApp/layout/MenuLayout'
import FooterPage from '../components/FooterPage'

export default {
  components: {
    NavApp,
    AppMenu,
    FooterPage
  },
  data() {
    return {
      userLogged: this.$attrs.userlogged,
      appName: this.$attrs.appname,
    }
  },
  methods:{
    async connectionIO(){

      const user_id = this.userLogged.id;
      const socket_url = 'http://143.198.239.58:9500/';
      const socket = await io.connect(socket_url, {'query':{'user_id':user_id,'origin':this.appName}});//'forceNew': true,

      socket.on('user_connected',function (data) {
        console.log(data,'user_connected')
      })
      socket.on('user_disconnected',function (data) {
        console.log(data,'user_disconnected')
      })

      // const payload = {}
      //
      // await socket.emit('new-message', payload, function (resp) {
      //   // console.log('Usuarios conectados',resp);
      // });
    }
  },
  async mounted() {
    /** HABILITAR EN CASO SE QUIERA USAR SOCKETS*/
    // await this.connectionIO()
  },
  watch:{
    // $route: {
    //   handler(){
    //     axios.get('abilities').then(response => {
    //       this.$ability.update([
    //         {subject:'all',actions:response.data.data}
    //       ])
    //     })
    //   },
    //   immediate:true
    // }
  }
}
</script>
<style scoped>
.overlay {
  top: 0;
  display: none;
  position: fixed;
  /* full screen */
  width: 100vw;
  height: 100vh;
  /* transparent black */
  background: rgba(0, 0, 0, 0.7);
  /* middle layer, i.e. appears below the sidebar */
  z-index: 998;
  opacity: 0;
  /* animate the transition */
  transition: all 0.5s ease-in-out;
}

/* display .overlay when it has the .active class */
.overlay.active {
  display: block;
  opacity: 1;
}
</style>