/** MODULO BASE PARA COPIAR Y PEGAR
 * SI NO PUEDES NI ESO YA VENDE CARAMELOS NO MAS
 * BY JORDAN 11-02-2022*/

 export default {
    name: 'quoted',
    component: () => import(/* webpackChunkName: "module" */ '../layouts/ModuleLayout.vue'),
    children: [
        {
            path: '/quoted/new',
            name: 'newquoted',
            component: () => import(/* webpackChunkName: "newmodule" */ '../views/FormModule.vue'),
        },
        {
            path: '/quoted/update',
            name: 'updatequoted',
            component: () => import(/* webpackChunkName: "updatemodule" */ '../views/FormModule.vue'),
            props:true
        },
        {
            path: '/quoted/listar',
            name: 'listquoted',
            component: () => import(/* webpackChunkName: "listmodule" */ '../views/ListModule.vue'),
        },
    ]
}