import Vue from 'vue';
import Router from 'vue-router';
import store from "@/admin/store/index";
import pipeline from "@/admin/router/pipeline";

import auth from "@/admin/middleware/auth";
import admin from "@/admin/middleware/admin";
import guest from "@/admin/middleware/guest";

import AppAuth from '../view/Auth/App.vue'
import Login from '../view/Auth/Login.vue'
import Users from '../view/Auth/Users.vue'
import Profile from '../view/Auth/Profile.vue'
import AppEbook from '../view/Ebook/App.vue'
import Ebooks from '../view/Ebook/Ebooks/Ebooks.vue'
import Chapters from '../view/Ebook/Chapters/Chapters.vue'
import Pages from '../view/Ebook/Pages/Pages.vue'
import PageDetail from '../view/Ebook/PageDetail/PageDetail.vue'

import NotFound from "@/admin/components/NotFound";

Vue.use(Router);


const routes = [{
    path: '/',
    redirect: 'ebooks',
    component: AppEbook,
    children: [{
        path: '/ebooks',
        name: 'ebooks',
        meta: {
          middleware: [auth, admin]
        },
        component: Ebooks,
      },
      {
        path: '/ebook/:slug',
        name: 'chapters',
        component: Chapters,
        meta: {
          middleware: [auth, admin]
        },
      },
      {
        path: '/ebook/:slug/chapter/:chapterId',
        name: 'pages',
        component: Pages,
        meta: {
          middleware: [auth, admin]
        },
      },
      {
        path: '/ebook/:slug/page/:pageId',
        name: 'pageDetail',
        component: PageDetail,
        meta: {
          middleware: [auth, admin]
        },
      },

    ]

  },
  {
    path: '/auth',
    redirect: 'login',
    component: AppAuth,
    children: [{
        path: 'login',
        name: 'login',
        component: Login,
        meta: {
          middleware: [guest]
        },
      },
      {
        path: 'users',
        name: 'users',
        component: Users,
        meta: {
          middleware: [auth, admin]
        },
      },
      {
        path: 'profile',
        name: 'profile',
        component: Profile,
        meta: {
          middleware: [auth, admin]
        },
      },

    ]
  },
  {
    path: "/:catchAll(.*)",
    name: "notFound",
    component: NotFound,

  },
]

const router = new Router({
  mode: "history",
  base: '/admin',
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return {
        x: 0,
        y: 0
      };
    }
  },
});

router.beforeEach((to, from, next) => {
  const middleware = to.meta.middleware;
  const context = {
    to,
    from,
    next,
    store
  };

  if (!middleware) {
    return next();
  }

  middleware[0]({
    ...context,
    next: pipeline(context, middleware, 1),
  });
});


router.mode = 'html5'

export default router
