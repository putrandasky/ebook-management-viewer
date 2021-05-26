window.Vue = require('vue').default;
import App from './view/App';

import VueRouter from 'vue-router';
import router from './router';
import {
  store
} from './store/index';
import BootstrapVue from 'bootstrap-vue';
var VueTouch = require('vue-touch')
Vue.use(VueTouch, {
  name: 'v-touch'
})

require('./bootstrap');
Vue.use(BootstrapVue);
Vue.use(VueRouter);



const app = new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
});
