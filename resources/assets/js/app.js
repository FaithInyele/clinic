
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('../sass/custom/jquery-steps/jquery.steps');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

Vue.component('ticket_start', require('./components/ticket_start.vue'));

Vue.component('ticket', require('./components/ticket.vue'));

Vue.component('chemist', require('./components/chemist.vue'));

Vue.component('issuedticket', require('./components/issueTicket.vue'));

Vue.component('atlab', require('./components/atLab.vue'));

Vue.component('listusers', require('./components/listUsers.vue'));

const app = new Vue({
    el: '#app',
    data: {
        clientEdit: false,
        startTicket: false
    }
});


//other js files

require('../sass/custom/accordion');

require('../sass/custom/tags/bootstrap-tagsinput');

require('metismenu');

require('../sass/custom/onoffcanvas/onoffcanvas');

require('../sass/custom/screenfull/screenfull');

require('../sass/custom/core');

require('../sass/custom/app');

require('../sass/custom/style-switcher');

//require('../sass/custom/form-wizard/jquery.backstretch');

//require('../sass/custom/form-wizard/scripts');