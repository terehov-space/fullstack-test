import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import router from './../router/router';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        userData: null
    },
    mutations: {
        SET_USERDATA(state, payload) {
            router.push('/boards');
            state.userData = payload;
        }
    },
    actions: {
        checkLoggedIn(context)
        {
            axios.post('http://127.0.0.1:8000/api/auth/me').then((response) => {
                this.commit('SET_USERDATA', response.data);
            }).catch((e) => {

            });
        }
    }
})
