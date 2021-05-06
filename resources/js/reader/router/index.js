import Vue from 'vue';
import Router from 'vue-router';
import Reader from '../view/Reader'

import NotFound from '../components/state/NotFound'

// import AppUserAdmin from '../views/user/AppAdmin.vue'
// import AppUserAwardee from '../views/user/AppAwardee.vue'
// import AppUserPatron from '../views/user/AppPatron.vue'


Vue.use(Router);

const router = new Router({
  mode: 'history',
  //   history: true,
  //   hashbang: false,
  base: '/reader',
  //   linkActiveClass: 'open active',
  // scrollBehavior: () => ({
  //   y: 0
  // }),
  routes: [{
      path: "/not-found",
      name: "notFound",
      component: NotFound,

    },
    {
      path: '/:slug',
      name: 'base',
      component: Reader,
    },
    {
      path: '/:slug/:page',
      name: 'page',
      component: Reader,
    },


  ]
});
router.mode = 'html5'

export default router
