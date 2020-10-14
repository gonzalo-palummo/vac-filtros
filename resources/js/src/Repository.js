import axios from 'axios';
import Vue from 'vue';
import router from '@/router';
import store from './store/store';

const $eventBus = new Vue();
Vue.prototype.$eventBus = $eventBus;
const baseURL = 'api';

const repository = axios.create({
  baseURL,
});

const token = store.state.AppActiveUser.token
  ? 'Bearer ' + store.state.AppActiveUser.token
  : null;

if (token) {
  repository.defaults.headers.common['Authorization'] = token;
}

repository.interceptors.request.use(
  function(config) {
    return config;
  },
  function(error) {
    return Promise.reject(error);
  }
);

function removeSidebars() {
  const sidebars = document.getElementsByClassName('vs-content-sidebar');
  sidebars.forEach(sidebar => {
    sidebar.remove();
  });
}

repository.interceptors.response.use(
  function(response) {
    return response;
  },
  function(error) {
    console.log('response error: ', error.response);
    if (error.response.status === 422) {
      const [firstErrorKey] = Object.keys(error.response.data.data);
      $eventBus.$emit('show-message', {
        color: 'warning',
        text: error.response.data.data[firstErrorKey][0],
      });
    } else if (error.response.status === 404) {
      // redirect to 404
      removeSidebars();
      router.push('/pages/404');
    } else if (error.response.status === 500) {
      removeSidebars();
      router.push('/pages/500');
    } else if (error.response.status === 401) {
      if (error.response.data.message !== 'Incorrect email and password') {
        removeSidebars();
        router.push('/pages/unauthorized');
      }
    }

    return Promise.reject(error);
  }
);

export default repository;
