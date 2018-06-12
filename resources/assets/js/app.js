
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));


// const app = new Vue({
//     el: '#app'
// });

let check = new Vue({
  el: "#checkout",
  data: {
    message: 'Hello world',
    price: 32,
    quantity: 1,
    title: 'he from vue'
  },
  methods: {
  //   pricer: function() {
  //     return this.price* this.quantity;
  // }
},
  computed: {
    totalPrice: function() {
      return Math.floor(this.quantity) * this.price;
    }
  },
  // mounted() {
  //   console.log(foo);
  // },
  // watch: {
  //   price: function(newValue) {
  //     console.log(newValue);
  //   }
  // }
});
