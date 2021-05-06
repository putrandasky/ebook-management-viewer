window.Vue = require('vue').default;
import App from './view/App';

import VueRouter from 'vue-router';
import router from './router';
import {
  store
} from './store/index';
import BootstrapVue from 'bootstrap-vue';
import VueMq from 'vue-mq';
var VueTouch = require('vue-touch')
Vue.use(VueTouch, {
  name: 'v-touch'
})

require('./bootstrap');
Vue.use(BootstrapVue);
Vue.use(VueRouter);
Vue.use(VueMq, {
  breakpoints: {
    sm: 576,
    md: 768,
    lg: 992,
    xs: 1200
  }
});


const app = new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
});
