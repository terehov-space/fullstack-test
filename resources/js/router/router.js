import VueRouter from "vue-router";
import Login from "../components/Login";
import Register from "../components/Register";
import Vue from "vue";
import Board from "../components/Board";

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        {
            path: '/',
            name: 'Login',
            component: Login,
        },
        {
            path: '/register',
            name: 'Registration',
            component: Register,
        },
        {
            path: '/boards',
            name: 'Board',
            component: Board
        }
    ],
});
