
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('../sass/custom/jquery-steps/jquery.steps');

import VueRouter from 'vue-router';

const router = new VueRouter({
    routes: [
        { path: '/', component: require('./components/inpatient.vue') },
        { path: '/AddServices', component: require('./components/ticket_start.vue') }
    ]
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

Vue.component('home', require('./components/home.vue'));

Vue.component('ticket_start', require('./components/ticket_start.vue'));

Vue.component('ticket', require('./components/ticket.vue'));

Vue.component('chemist', require('./components/chemist.vue'));

Vue.component('issuedticket', require('./components/issueTicket.vue'));

Vue.component('atdoctor', require('./components/atDoctor.vue'));

Vue.component('atlab', require('./components/atLab.vue'));

Vue.component('listusers', require('./components/listUsers.vue'));

Vue.component('listclients', require('./components/listClients.vue'));

Vue.component('labresource', require('./components/labResource.vue'));

Vue.component('chemistresource', require('./components/chemistResource.vue'));

Vue.component('preferences', require('./components/preferences.vue'));

Vue.component('nurseresource', require('./components/nurseStation.vue'));

Vue.component('payments', require('./components/payments.vue'));

Vue.component('sidebar', require('./components/sidebar.vue'));

Vue.component('inpatient', require('./components/inpatient.vue'));
const app = new Vue({
    router,
    el: '#app',
    data: {
        clientEdit: false,
        startTicket: false
    }
});



//other js files

require('../sass/custom/accordion');

//require('../sass/custom/tags/bootstrap-tagsinput');

require('metismenu');

require('../sass/custom/onoffcanvas/onoffcanvas');

require('../sass/custom/screenfull/screenfull');

require('../sass/custom/core');

require('../sass/custom/app');

require('../sass/custom/style-switcher');

//require('../sass/custom/form-wizard/jquery.backstretch');

//require('../sass/custom/form-wizard/scripts');