/** MODULO BASE PARA COPIAR Y PEGAR
 * SI NO PUEDES NI ESO YA VENDE CARAMELOS NO MAS
 * BY JORDAN 11-02-2022*/

export default {
    name: 'users',
    component: () => import(/* webpackChunkName: "module" */ '../layouts/ModuleLayout.vue'),
    children: [
        {
            path: '/users/new',
            name: 'newusers',
            component: () => import(/* webpackChunkName: "newmodule" */ '../views/FormModule.vue'),
        },
        {
            path: '/users/update',
            name: 'updateusers',
            component: () => import(/* webpackChunkName: "updatemodule" */ '../views/FormModule.vue'),
            props:true
        },
        {
            path: '/users/listar',
            name: 'listusers',
            component: () => import(/* webpackChunkName: "listmodule" */ '../views/ListModule.vue'),
        },
    ]
}