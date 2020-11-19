<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="info">
            <b-navbar-brand href="#">Trello</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto" v-if="userData">
                    <b-nav-item-dropdown right>
                        <!-- Using 'button-content' slot -->
                        <template #button-content>
                            <em>{{ userData.name }}</em>
                        </template>
                        <b-dropdown-item href="#" @click="logout">Logout</b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
        <b-container fluid>
            <b-row class="d-flex flex-row flex-nowrap overflow-auto">
                <b-card
                    v-for="item in boardsData"
                    :key="item.id"
                    :title="item.title"
                    class="col-xs-6 col-sm-5 col-md-4 col-lg-3"
                >
                    <b-card-text>
                        <b-card
                            v-if="item.tasks"
                            v-for="task in item.tasks"
                            :key="task.id"
                            :title="task.title"
                        >
                        </b-card>
                    </b-card-text>

                    <b-button href="#" variant="primary" @click="openAddTaskForm(item.id)">Add new task</b-button>
                </b-card>
                <b-card
                    title="Add new board"
                    class="col-xs-6 col-sm-5 col-md-4 col-lg-3 board"
                >
                    <b-card-text>
                        <b-form @submit="addBoard">
                            <b-form-group
                                label="Title:"
                                label-for="input-title"
                            >
                                <b-form-input
                                    id="input-title"
                                    v-model="form.board.title"
                                    required
                                    placeholder="Enter title"
                                ></b-form-input>
                            </b-form-group>
                            <b-form-group
                                label="Sort:"
                                label-for="input-sort"
                            >
                                <b-form-input
                                    id="input-sort"
                                    v-model="form.board.sort"
                                    placeholder="Enter sort"
                                ></b-form-input>
                            </b-form-group>
                            <b-button type="submit" variant="primary" @submit="addBoard">Add board</b-button>
                        </b-form>
                    </b-card-text>
                </b-card>
            </b-row>
        </b-container>

        <b-modal id="add-task" title="Add new task" @hidden="resetTaskForm" @ok="addTask">
            <b-form @submit="addTask">
                <b-form-group
                    label="Title:"
                    label-for="input-title"
                >
                    <b-form-input
                        id="input-title"
                        v-model="form.task.title"
                        required
                        placeholder="Enter title"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Sort:"
                    label-for="input-sort"
                >
                    <b-form-input
                        id="input-sort"
                        v-model="form.task.sort"
                        placeholder="Enter sort"
                    ></b-form-input>
                </b-form-group>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
export default {
    name: "Board",
    data: function () {
        return {
            form: {
                board: {
                    title: null,
                    sort: null,
                },
                task: {
                    title: null,
                    sort: null,
                    board_id: null,
                }
            }
        };
    },
    computed: {
        userData() {
            return this.$store.state.userData;
        },
        boardsData() {
            return this.$store.state.boards;
        },
    },
    methods: {
        logout() {
            localStorage.removeItem('token');
            this.$http.defaults.headers.Authorization = "";
            this.$store.dispatch('checkLoggedIn');
        },
        addBoard(evt) {
            evt.preventDefault();
            console.log('addBoard');
            this.$http.post('http://127.0.0.1:8000/api/boards', this.form.board).then((response) => {
                this.resetAddBoard();

                this.$store.dispatch('getBoards');
            });
        },
        resetAddBoard()
        {
            this.form.board.title = "";
            this.form.board.sort = 100;
        },
        openAddTaskForm(board_id)
        {
            this.form.task.board_id = board_id;
            this.$bvModal.show('add-task')
        },
        resetTaskForm()
        {
            this.form.task.title = null;
            this.form.task.sort = 100;
            this.form.task.board_id = null;
        },
        addTask() {
            this.$http.post('http://127.0.0.1:8000/api/tasks', this.form.task).then((response) => {
                this.resetTaskForm();

                this.$store.dispatch('getBoards');
            });

            this.$bvModal.hide('add-task');
        },
    },
    mounted() {
        this.$store.dispatch('checkLoggedIn');

        this.$store.dispatch('getBoards');

        this.resetAddBoard();

        this.resetTaskForm();
    }
}
</script>

<style scoped>
.board {
    max-height: 300px;
}
</style>
