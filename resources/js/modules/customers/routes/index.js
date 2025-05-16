/** MODULO BASE PARA COPIAR Y PEGAR
 * SI NO PUEDES NI ESO YA VENDE CARAMELOS NO MAS
 * BY JORDAN 11-02-2022*/

 export default {
    name: 'customers',
    component: () => import(/* webpackChunkName: "module" */ '../layouts/ModuleLayout.vue'),
    children: [
        {
            path: '/customers/new',
            name: 'newcustomers',
            component: () => import(/* webpackChunkName: "newmodule" */ '../views/FormModule.vue'),
        },
        {
            path: '/customers/update',
            name: 'updatecustomers',
            component: () => import(/* webpackChunkName: "updatemodule" */ '../views/FormModule.vue'),
            props:true
        },
        {
            path: '/customers/listar',
            name: 'listcustomers',
            component: () => import(/* webpackChunkName: "listmodule" */ '../views/ListModule.vue'),
        },
    ]
}