/** MODULO BASE PARA COPIAR Y PEGAR
 * SI NO PUEDES NI ESO YA VENDE CARAMELOS NO MAS
 * BY JORDAN 11-02-2022*/

export default {
    name: 'homelayout',
    component: () => import(/* webpackChunkName: "module" */ '../layouts/HomeLayout.vue'),
    children: [
        /*{
            path: '/module/new',
            name: 'newmodule',
            component: () => import(/* webpackChunkName: "newmodule" * / '../views/FormModule.vue'),
        },*/
    ]
}