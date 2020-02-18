
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import { Form, HasError, AlertError } from 'vform'

window.Form = Form;

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)


// import Datetime from 'vue-datetime'
// import 'vue-datetime/dist/vue-datetime.css'
// Vue.use(Datetime)

// import VueTimepicker from 'vue2-timepicker'
// Vue.use(VueTimepicker)

import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';

Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import NotificationsPush from './components/NotificationsPush';
import SendNotification from './components/SendNotification';
import ImageUpload from './components/ImageUpload';
import SentMessages from './components/SentMessages';


import swal from 'sweetalert2';
window.swal = swal;
 
const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
  window.toast = toast;

  window.Fire = new Vue();


// Vue.component('send-notification', require('./components/SendNotification.vue'));

const app = new Vue({
    el: '#noti',

    components: {
        NotificationsPush, SendNotification, ImageUpload, SentMessages
    }
});
