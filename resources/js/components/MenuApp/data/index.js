export default [
    {
        titulo:'Mantenimiento',
        icon:'fas fa-tasks',
        gate: 'management_access',
        options:[
        ]
    },
    {
        titulo:'Operaciones',
        icon:'fas fa-briefcase',
        options:[
            {
                name:'Clientes',
                icon:'fas fa-sitemap',
                gate: 'customers_access',
                nameroute:'listcustomers'
            },
            {
                name:'Pedidos',
                icon:'fas fa-sitemap',
                gate: 'orders_access',
                nameroute:'listorders'
            },
        ]
    },
    {
        titulo:'Configuración',
        icon:'fas fa-cog',
        options:[
        ]
    },
]