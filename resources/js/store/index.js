import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import router from './../router/router';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        userData: null,
        boards: null,
    },
    mutations: {
        SET_USERDATA(state, payload) {
            state.userData = payload;
            if (router.currentRoute.path != '/boards')
                router.push('/boards');
        },
        SET_BOARDS(state, payload) {
            state.boards = payload;
        }
    },
    actions: {
        checkLoggedIn(context) {
            axios.post('http://127.0.0.1:8000/api/auth/me').then((response) => {
                this.commit('SET_USERDATA', response.data);
            }).catch((e) => {
                if (router.currentRoute.path != '/')
                    router.push({name: 'Login'});
            });
        },
        getBoards(context) {
            axios.get('http://127.0.0.1:8000/api/boards/').then((response) => {
                this.commit('SET_BOARDS', response.data);
            }).catch((e) => {
                if (router.currentRoute.path != '/')
                    router.push({name: 'Login'});
            });
        }
    }
})
