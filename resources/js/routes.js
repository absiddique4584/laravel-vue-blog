
import AdminHome from './components/Dashboard'
import AdminUser from './components/Users'
import AdminProfile from './components/Profile'
import AdminDeveloper from './components/Developer'


export const routes = [
    {
        path: '/dashboard',
        component: AdminHome
    },

    {
        path: '/users',
        component: AdminUser
    },

    {
        path: '/profile',
        component: AdminProfile
    },

    {
        path: '/developer',
        component: AdminDeveloper
    },
]
