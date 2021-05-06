import axios from 'axios';
import
store
from './store';

export default function setup() {

  axios.interceptors.response.use(response => {
      return response
    },
    function(error) {
      let self = this
      if (
        error.response && [401, 419].includes(error.response.status) &&
        store.getters["auth/authUser"] &&
        !store.getters["auth/guest"]
      ) {
        store.dispatch("auth/logout");
        store.commit('auth/SET_TOAST', [true, 'GOOD BYE', 'You are log out'])
      }
      return Promise.reject(error);

    })
}
