import Vue from 'vue'
import Router from 'vue-router'
import Meta from 'vue-meta'
import NProgress from 'nprogress'

import homeRouter from '../modules/home/routes/index.js'
import moduleRouter from '../modules/module_base/routes/index.js'
import customersRouter from '../modules/customers/routes/index.js'
import ordersRouter from '../modules/orders/routes/index.js'
import quotedRouter from '../modules/quoted/routes/index.js'
import productionRouter from '../modules/production/routes/index.js'
import distributorsRouter from '../modules/distributors/routes/index.js'
import feedbacksRouter from '../modules/feedbacks/routes/index.js'
import usersRouter from '../modules/users/routes/index.js'
import suppliersRouter from '../modules/suppliers/routes/index.js'

Vue.use(Meta)
Vue.use(Router)

const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import(/* webpackChunkName: "home" */ '../layouts/AppLayout.vue'),
        children: [
            {
                path: '/',
                ...homeRouter
            },
            {
                path: '/module',
                ...moduleRouter
            },
            {
                path: '/customers',
                ...customersRouter
            },
            {
                path: '/orders',
                ...ordersRouter
            },
            {
                path: '/quoted',
                ...quotedRouter
            },
            {
                path: '/production',
                ...productionRouter
            },
            {
                path: '/distributors',
                ...distributorsRouter
            },
            {
                path: '/feedbacks',
                ...feedbacksRouter
            },
            {
                path: '/users',
                ...usersRouter
            },
            {
                path: '/suppliers',
                ...suppliersRouter
            },
        ],
        props: true
    },
    {
        path: '*',
        component: {template: '<h2>Error 404</h2>'}
    }
]

const router = new Router({
    routes,
    scrollBehavior(to, from, savedPosition) {
        return {x: 0, y: 0}
    }
})

router.beforeResolve((to, from, next) => {
    if (to.path) {
        NProgress.start()
    }
    next()
})

router.afterEach((to, from) => {
    NProgress.done()
})

export default router