window.Vue = require('vue').default;
import App from './view/App';

import VueRouter from 'vue-router';
import router from './router';
import
store
from './store';
import './filter/filter';
import draggable from 'vuedraggable'
import BootstrapVue from 'bootstrap-vue';
import axios from "axios";
import InterceptorSetup from './interceptor';

require('./bootstrap');
Vue.use(BootstrapVue);
Vue.use(VueRouter);
Vue.component('draggable', draggable);
Vue.mixin({
  methods: {
    toastSuccess: function(content) {
      this.$bvToast.toast(content, {
        title: `SUCCESS!`,
        headerClass: 'font-weight-bold',
        variant: 'success',
        solid: true,
        autoHideDelay: 1000,
        toaster: 'b-toaster-top-right'
      })
    },
    toastError: function(content) {
      this.$bvToast.toast(content, {
        title: `ERROR!`,
        headerClass: 'font-weight-bold',
        variant: 'danger',
        solid: true,
        autoHideDelay: 1000,
        toaster: 'b-toaster-top-right'
      })
    }
  }
});
InterceptorSetup()
const app = new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
});
