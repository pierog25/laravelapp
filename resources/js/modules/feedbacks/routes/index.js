/** MODULO BASE PARA COPIAR Y PEGAR
 * SI NO PUEDES NI ESO YA VENDE CARAMELOS NO MAS
 * BY JORDAN 11-02-2022*/

 export default {
    name: 'feedbacks',
    component: () => import(/* webpackChunkName: "module" */ '../layouts/ModuleLayout.vue'),
    children: [
        {
            path: '/feedbacks/new',
            name: 'newfeedbacks',
            component: () => import(/* webpackChunkName: "newmodule" */ '../views/FormModule.vue'),
        },
        {
            path: '/feedbacks/update',
            name: 'updatefeedbacks',
            component: () => import(/* webpackChunkName: "updatemodule" */ '../views/FormModule.vue'),
            props:true
        },
        {
            path: '/feedbacks/listar',
            name: 'listfeedbacks',
            component: () => import(/* webpackChunkName: "listmodule" */ '../views/ListModule.vue'),
        }
    ]
}