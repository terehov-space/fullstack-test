<template>
    <div class="center">
        <b-form @submit="onSubmit">
            <b-form-group
                label="Your Name:"
                label-for="input-name"
            >
                <b-form-input
                    id="input-name"
                    v-model="form.name"
                    required
                    placeholder="Enter name"
                ></b-form-input>
            </b-form-group>

            <b-form-group
                label="Email address:"
                label-for="input-email"
            >
                <b-form-input
                    id="input-email"
                    v-model="form.email"
                    type="email"
                    required
                    placeholder="Enter email"
                ></b-form-input>
            </b-form-group>

            <b-form-group label="Your Password:" label-for="input-password">
                <b-form-input
                    id="input-password"
                    v-model="form.password"
                    type="password"
                    required
                    placeholder="Enter password"
                ></b-form-input>
            </b-form-group>

            <b-button type="submit" variant="primary">Sign Up</b-button>
            <b-button type="submit" variant="primary" @click="goToLogin">Sign In</b-button>
        </b-form>
    </div>
</template>

<script>
export default {
    name: "Login",
    data: function () {
        return {
            form: {
                name: '',
                email: '',
                password: '',
            }
        };
    },
    methods: {
        onSubmit(evt) {
            evt.preventDefault();

            this.$http.post('http://127.0.0.1:8000/api/auth/register', this.form).then((response) => {
                localStorage.setItem('token', response.data.access_token);
                this.$http.defaults.headers.Authorization = 'bearer ' + response.data.access_token;
                this.$store.dispatch('checkLoggedIn');
            }).catch((e) => {
                state.userData =  null;
            });
        },
        goToLogin(evt) {
            evt.preventDefault();

            this.$router.push('/');
        }
    },
    mounted() {
        this.$store.dispatch('checkLoggedIn');
    }
}
</script>

<style scoped>
.center {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}
</style>
