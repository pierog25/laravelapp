/** MODULO BASE PARA COPIAR Y PEGAR
 * SI NO PUEDES NI ESO YA VENDE CARAMELOS NO MAS
 * BY JORDAN 11-02-2022*/

 export default {
    name: 'module',
    component: () => import(/* webpackChunkName: "module" */ '../layouts/ModuleLayout.vue'),
    children: [
        {
            path: '/module/new',
            name: 'newmodule',
            component: () => import(/* webpackChunkName: "newmodule" */ '../views/FormModule.vue'),
        },
        {
            path: '/module/update',
            name: 'updatemodule',
            component: () => import(/* webpackChunkName: "updatemodule" */ '../views/FormModule.vue'),
            props:true
        },
        {
            path: '/module/listar',
            name: 'listmodule',
            component: () => import(/* webpackChunkName: "listmodule" */ '../views/ListModule.vue'),
        },
    ]
}