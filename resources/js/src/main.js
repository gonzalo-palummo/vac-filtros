import Vue from 'vue';
import App from './App.vue';

// Vuesax Component Framework
import Vuesax from 'vuesax';

Vue.use(Vuesax);

// axios
import axios from './Repository.js';
Vue.prototype.$http = axios;

// Theme Configurations
import '../themeConfig.js';

// ACL
import acl from './acl/acl';

// Globally Registered Components
import './globalComponents.js';

// Vue Router
import router from './router';

// Vuex Store
import store from './store/store';

// i18n
import i18n from './i18n/i18n';

// Vuexy Admin Filters
import './filters/filters';

// Clipboard
import VueClipboard from 'vue-clipboard2';
Vue.use(VueClipboard);

// Tour
import VueTour from 'vue-tour';
Vue.use(VueTour);
require('vue-tour/dist/vue-tour.css');

// VeeValidate
import VeeValidate, { Validator } from 'vee-validate';
import es from 'vee-validate/dist/locale/es';
Validator.localize({ es });
Vue.use(VeeValidate, {
  locale: 'es',
});

// Google Maps
import * as VueGoogleMaps from 'vue2-google-maps';
Vue.use(VueGoogleMaps, {
  load: {
    // Add your API key here
    key: 'AIzaSyB4DDathvvwuwlwnUu7F4Sow3oU22y5T1Y',
    libraries: 'places', // This is required if you use the Auto complete plug-in
  },
});

// Vuejs - Vue wrapper for hammerjs
import { VueHammer } from 'vue2-hammer';
Vue.use(VueHammer);

// PrismJS
import 'prismjs';

// Feather font icon
require('@assets/css/iconfont.css');

// Vue select css
// Note: In latest version you have to add it separately
// import 'vue-select/dist/vue-select.css';

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  i18n,
  acl,
  render: h => h(App),
}).$mount('#app');
