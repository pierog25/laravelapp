/** MODULO BASE PARA COPIAR Y PEGAR
 * SI NO PUEDES NI ESO YA VENDE CARAMELOS NO MAS
 * BY JORDAN 11-02-2022*/

 export default {
    name: 'orders',
    component: () => import(/* webpackChunkName: "module" */ '../layouts/ModuleLayout.vue'),
    children: [
        {
            path: '/orders/new',
            name: 'neworders',
            component: () => import(/* webpackChunkName: "newmodule" */ '../views/FormModule.vue'),
        },
        {
            path: '/orders/update',
            name: 'updateorders',
            component: () => import(/* webpackChunkName: "updatemodule" */ '../views/FormModule.vue'),
            props:true
        },
        {
            path: '/orders/listar',
            name: 'listorders',
            component: () => import(/* webpackChunkName: "listmodule" */ '../views/ListModule.vue'),
        },
    ]
}