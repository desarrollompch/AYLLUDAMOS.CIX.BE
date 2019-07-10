
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');
window.Cleave = require('cleave.js');
window.inside = require('point-in-polygon');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//components plugins
Vue.component('component-cleave-js', require('./components/plugins/CleaveComponent.vue'));
Vue.component('search-persona', require('./components/plugins/SearchPersona.vue'));
Vue.component('search-user', require('./components/plugins/SearchUser.vue'));


//components
Vue.component('tipo-persona-list', require('./components/tipo-persona/List.vue'));
Vue.component('tipo-persona-create', require('./components/tipo-persona/Create.vue'));
Vue.component('tipo-persona-edit', require('./components/tipo-persona/Edit.vue'));
Vue.component('tipo-persona-form', require('./components/tipo-persona/partial/FormPartial.vue'));

Vue.component('territorio-vecinal-list', require('./components/territorio-vecinal/List.vue'));
Vue.component('territorio-vecinal-create', require('./components/territorio-vecinal/Create.vue'));
Vue.component('territorio-vecinal-edit', require('./components/territorio-vecinal/Edit.vue'));
Vue.component('territorio-vecinal-form', require('./components/territorio-vecinal/partial/FormPartial.vue'));
Vue.component('territorio-view-map', require('./components/territorio-vecinal/ViewMap'));

Vue.component('urbanizacion-list', require('./components/urbanizacion/List.vue'));
Vue.component('urbanizacion-create', require('./components/urbanizacion/Create.vue'));
Vue.component('urbanizacion-edit', require('./components/urbanizacion/Edit.vue'));
Vue.component('urbanizacion-form', require('./components/urbanizacion/partial/FormPartial.vue'));

Vue.component('nivel-ciudadano-list', require('./components/nivel-ciudadano/List.vue'));
Vue.component('nivel-ciudadano-create', require('./components/nivel-ciudadano/Create.vue'));
Vue.component('nivel-ciudadano-edit', require('./components/nivel-ciudadano/Edit.vue'));
Vue.component('nivel-ciudadano-form', require('./components/nivel-ciudadano/partial/FormPartial.vue'));

Vue.component('rol-list', require('./components/rol/List.vue'));
Vue.component('rol-create', require('./components/rol/Create.vue'));
Vue.component('rol-edit', require('./components/rol/Edit.vue'));
Vue.component('rol-form', require('./components/rol/partial/FormPartial.vue'));

Vue.component('nacionalidad-list', require('./components/nacionalidad/List.vue'));
Vue.component('nacionalidad-create', require('./components/nacionalidad/Create.vue'));
Vue.component('nacionalidad-edit', require('./components/nacionalidad/Edit.vue'));
Vue.component('nacionalidad-form', require('./components/nacionalidad/partial/FormPartial.vue'));

Vue.component('persona-list', require('./components/persona/List.vue'));
Vue.component('persona-inactive', require('./components/persona/Inactive.vue'));
Vue.component('persona-create', require('./components/persona/Create.vue'));
Vue.component('persona-edit', require('./components/persona/Edit.vue'));
Vue.component('persona-form', require('./components/persona/partial/FormPartial.vue'));

Vue.component('user-list', require('./components/user/List.vue'));
Vue.component('user-create', require('./components/user/Create.vue'));
Vue.component('user-edit', require('./components/user/Edit.vue'));
Vue.component('user-form', require('./components/user/partial/FormPartial.vue'));

Vue.component('estado-incidente-list', require('./components/estado-incidente/List.vue'));
Vue.component('estado-incidente-create', require('./components/estado-incidente/Create.vue'));
Vue.component('estado-incidente-edit', require('./components/estado-incidente/Edit.vue'));
Vue.component('estado-incidente-form', require('./components/estado-incidente/partial/FormPartial.vue'));

Vue.component('tipo-incidente-list', require('./components/tipo-incidente/List.vue'));
Vue.component('tipo-incidente-create', require('./components/tipo-incidente/Create.vue'));
Vue.component('tipo-incidente-edit', require('./components/tipo-incidente/Edit.vue'));
Vue.component('tipo-incidente-form', require('./components/tipo-incidente/partial/FormPartial.vue'));

Vue.component('nivel-agua-list', require('./components/nivel-agua/List.vue'));
Vue.component('nivel-agua-create', require('./components/nivel-agua/Create.vue'));
Vue.component('nivel-agua-edit', require('./components/nivel-agua/Edit.vue'));
Vue.component('nivel-agua-form', require('./components/nivel-agua/partial/FormPartial.vue'));

Vue.component('tipo-obstaculo-list', require('./components/tipo-obstaculo/List.vue'));
Vue.component('tipo-obstaculo-create', require('./components/tipo-obstaculo/Create.vue'));
Vue.component('tipo-obstaculo-edit', require('./components/tipo-obstaculo/Edit.vue'));
Vue.component('tipo-obstaculo-form', require('./components/tipo-obstaculo/partial/FormPartial.vue'));

Vue.component('incidente-list', require('./components/incidente/List.vue'));
Vue.component('incidente-attention', require('./components/incidente/Attention.vue'));
Vue.component('incidente-create', require('./components/incidente/Create.vue'));
Vue.component('incidente-edit', require('./components/incidente/Edit.vue'));
Vue.component('incidente-detalle', require('./components/incidente/Detalle.vue'));
Vue.component('incidente-detalleatencion', require('./components/incidente/DetalleAtencion.vue'));
Vue.component('incidente-form', require('./components/incidente/partial/FormPartial.vue'));

Vue.component("dashboard-front", require("./components/dashboard/Front.vue"));

Vue.component('alcalde-vecinal-list', require('./components/alcalde-vecinal/List.vue'));
Vue.component('alcalde-vecinal-create', require('./components/alcalde-vecinal/Create.vue'));
Vue.component('alcalde-vecinal-edit', require('./components/alcalde-vecinal/Edit.vue'));
Vue.component('alcalde-vecinal-form', require('./components/alcalde-vecinal/partial/FormPartial.vue'));

Vue.component('comite-gestion-list', require('./components/comite-gestion/List.vue'));
Vue.component('comite-gestion-create', require('./components/comite-gestion/Create.vue'));
Vue.component('comite-gestion-edit', require('./components/comite-gestion/Edit.vue'));
Vue.component('comite-gestion-form', require('./components/comite-gestion/partial/FormPartial.vue'));

Vue.component('directorio-list', require('./components/directorio/List.vue'));
Vue.component('directorio-create', require('./components/directorio/Create.vue'));
Vue.component('directorio-edit', require('./components/directorio/Edit.vue'));
Vue.component('directorio-form', require('./components/directorio/partial/FormPartial.vue'));

Vue.component('actividad-puntuacion-list', require('./components/actividad-puntuacion/List.vue'));
Vue.component('actividad-puntuacion-create', require('./components/actividad-puntuacion/Create.vue'));
Vue.component('actividad-puntuacion-edit', require('./components/actividad-puntuacion/Edit.vue'));
Vue.component('actividad-puntuacion-form', require('./components/actividad-puntuacion/partial/FormPartial.vue'));

Vue.component('coordinacion-list', require('./components/coordinacion/List.vue'));
Vue.component('coordinacion-create', require('./components/coordinacion/Create.vue'));
Vue.component('coordinacion-edit', require('./components/coordinacion/Edit.vue'));
Vue.component('coordinacion-form', require('./components/coordinacion/partial/FormPartial.vue'));

Vue.component('configuracion-list', require('./components/configuracion/List.vue'));
Vue.component('configuracion-create', require('./components/configuracion/Create.vue'));
Vue.component('configuracion-edit', require('./components/configuracion/Edit.vue'));
Vue.component('configuracion-form', require('./components/configuracion/partial/FormPartial.vue'));

Vue.component('notificacion-list', require('./components/notificacion/List.vue'));
Vue.component('log-list', require('./components/log/List.vue'));

Vue.component("ubicacion-list", require('./components/ubicacion/List'));

Vue.component('botonpanico-create', require('./components/botonpanico/Create.vue'));

const app = new Vue({
    el: '#app'
});
