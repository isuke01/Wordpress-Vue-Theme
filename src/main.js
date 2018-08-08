// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import WPVUE from './wpvue'
import Vue from 'vue';
import router from './router';
import lazySizes from 'lazysizes';
import { REST, API } from './axios';
import { store } from './store';
import matchMedia from 'matchmedia-polyfill';
import { sync } from 'vuex-router-sync'

import App from './App'


const unsync = sync(store, router)

Vue.prototype.$rest = REST;
Vue.prototype.$api = API;
Vue.prototype.$lazy = lazySizes;
Vue.config.productionTip = false;



Vue.use(matchMedia);

/* eslint-disable no-new */
new Vue({
  el: '#wp-app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
