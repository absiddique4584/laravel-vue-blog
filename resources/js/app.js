

require('./bootstrap');

window.Vue = require('vue');

import moment from 'moment';
import VueRouter from 'vue-router'
Vue.use(VueRouter)

import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);

window.Fire = new Vue();

// ES6 Modules or TypeScript
import Swal from 'sweetalert2'
window.Swal = Swal;


const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})
window.Toast = Toast;


//Progress bar
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: '#dc3545',
    height: '3px'
})

import {routes} from './routes';
//let routes = [
//    { path: '/dashboard', component: require('./components/Dashboard.vue') },
//    { path: '/users', component: require('./components/Users.vue') },
//    { path: '/profile', component: require('./components/Profile.vue') }
//]

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('admin-main', require('./components/admin/AdminMaster.vue').default);


//vform
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

const router = new VueRouter({
    routes, // short for `routes: routes`
    mode: 'hash',

})

Vue.filter('uptext', function (text) {
  // return text.toUpperCase();
    return text.charAt(0).toUpperCase() + text.slice(1)
})


Vue.filter('myDate', function (date) {
    return moment(date).format("MMM Do YYYY");
})

//Vue Passport Component


Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);



const app = new Vue({
    el: '#app',
    router,


});
