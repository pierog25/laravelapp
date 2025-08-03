
import Vue from 'vue'
import Router from 'vue-router'
// import { abilitiesPlugin } from '@casl/vue';
// import ability from './services/ability';
import router from './router'
import VueMoment from 'vue-moment';
import * as Alerts from './plugins/utils/alerts'
import './plugins'
import './plugins/components'
import Panel from './plugins/utils/panel.js';
import VarGlobal from './plugins/utils/var_global.js';

window.Vue = require('vue');

Vue.use(VueMoment)
// Vue.use(abilitiesPlugin,ability)
require('vue-multiselect');

window.Panel = Panel;
window.var_globals_http = new VarGlobal();
Vue.component('seguimiento-pedido', require('./views/SeguimientoPedido.vue').default);
Vue.component('formulario-experiencia', require('./views/FormularioExperiencia.vue').default);
//Vue.component('example-component', require('./components/ExampleComponent.vue').default);


const app = new Vue({
    el: '#app',
    router,
});
