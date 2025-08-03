export default [
    {
        titulo:'Mantenimiento',
        icon:'fas fa-tools', // Cambiado de tasks a tools
        gate: 'management_access',
        options:[
            {
                name:'Clientes',
                icon:'fas fa-users', // Cambiado a users
                gate: 'customers_access',
                nameroute:'listcustomers'
            },
            {
                name:'Usuarios',
                icon:'fas fa-user', // Cambiado a users
                gate: 'users_access',
                nameroute:'listusers'
            },
            {
                name:'Distribuidores',
                icon:'fas fa-cart-plus', // Cambiado a suppliers
                gate: 'suppliers_access',
                nameroute:'listsuppliers'
            },
        ]
    },
    {
        titulo:'Operaciones',
        icon:'fas fa-briefcase',
        options:[
            {
                name:'Pedidos',
                icon:'fas fa-shopping-cart', // Cambiado a shopping-cart
                gate: 'orders_access',
                nameroute:'listorders'
            },
            {
                name:'Cotización',
                icon:'fas fa-file-invoice-dollar', // Cambiado a file-invoice-dollar
                gate: 'quoted_access',
                nameroute:'listquoted'
            },
            {
                name:'Producción',
                icon:'fas fa-industry', // Cambiado a industry
                gate: 'production_access',
                nameroute:'listproduction'
            }
        ]
    },
    {
        titulo:'Reportes',
        icon:'fas fa-chart-bar', // Cambiado a chart-bar
        options:[
            {
                name:'R. Distribuidores',
                icon:'fas fa-truck', // Cambiado a truck
                gate: 'distributors_access',
                nameroute:'listdistributors'
            },
            {
                name:'Pedidos',
                icon:'fas fa-truck', // Cambiado a truck
                gate: 'feedbacks_access',
                nameroute:'listfeedbacks'
            }
        ]
    },
]