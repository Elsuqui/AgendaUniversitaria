
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import Vuex from 'vuex'
import PortalVue from 'portal-vue';
import VueSweetalert2 from 'vue-sweetalert2';
import evento from "./store/evento";

require('./bootstrap');

window.Vue = require('vue');

Vue.use(Vuex);
Vue.use(PortalVue);
Vue.use(VueSweetalert2);


const store = new Vuex.Store({
    evento
});


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component("calendar", require("./components/Calendar.vue"));
Vue.component("listado-eventos", require("./components/ListadoEventos"));
Vue.component("asignacion-materias", require("./components/AsignacionMaterias"));
Vue.component("notificaciones-pendientes", require("./components/NotificacionesPendientes"));
Vue.component("reprogramar-eventos", require("./components/ReprogramarEventos"));

const app = new Vue({
    el: '#app',
    store
});
