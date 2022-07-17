require('./bootstrap');

window.Vue = require('vue').default;
import { ZiggyVue } from 'ziggy';
import { Ziggy } from './ziggy';

Vue.use(ZiggyVue, Ziggy);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);



const app = new Vue({
    el: '#app',
});
