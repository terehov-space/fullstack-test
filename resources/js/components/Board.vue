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
            <b-row>
                <b-card
                    v-for="item in boardsData"
                    :key="item.id"
                    class="col-xs-6 col-sm-5 col-md-4 col-lg-3"
                >
                    <b-card-title @click="openEditBoardForm(item)">
                        {{ item.title }}
                    </b-card-title>

                    <b-card-text>
                            <draggable class="list-group" tag="ul" v-model="item.tasks" v-bind="dragOptions" :move="onMove" v-on:add="onAdd"
                                       :sort="false" @start="isDragging=true" @end="isDragging=false">
                                <transition-group type="transition" :name="'flip-list'" v-bind:board="item.id">
                                    <li class="list-group-item" v-for="task in item.tasks" :key="task.id" @click="openEditTaskForm(task)" v-bind:task="task.id" v-bind:sort="task.sort">
                                        {{task.title}}
                                    </li>
                                </transition-group>
                            </draggable>
                    </b-card-text>

                    <b-button href="#" variant="primary" @click="openAddTaskForm(item.id)">Add</b-button>
                    <b-button href="#" variant="danger" @click="deleteBoard(item)">Delete</b-button>
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

        <b-modal id="edit-task" title="Edit task" @hidden="resetTaskForm" @ok="updateTask">
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

        <b-modal id="edit-board" title="Edit board" @hidden="resetBoardForm" @ok="updateBoard">
            <b-form @submit="updateBoard">
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
            </b-form>
        </b-modal>
    </div>
</template>

<script>
import draggable from 'vuedraggable'

export default {
    name: "Board",
    components: {
        draggable
    },
    data: function () {
        return {
            form: {
                board: {
                    id: null,
                    title: null,
                    sort: null,
                },
                task: {
                    id: null,
                    title: null,
                    sort: null,
                    board_id: null,
                }
            },
            editable: true,
            isDragging: false,
            delayedDragging: false
        };
    },
    computed: {
        userData() {
            return this.$store.state.userData;
        },
        boardsData() {
            return this.$store.state.boards;
        },
        dragOptions() {
            return {
                animation: 0,
                group: "description",
                disabled: !this.editable,
                ghostClass: "ghost",
            };
        },
        listString() {
            return JSON.stringify(this.list, null, 2);
        },
        list2String() {
            return JSON.stringify(this.list2, null, 2);
        }
    },
    methods: {
        logout() {
            localStorage.removeItem('token');
            this.$http.defaults.headers.Authorization = "";
            this.$store.dispatch('checkLoggedIn');
        },

        addBoard(evt) {
            evt.preventDefault();

            this.$http.post('http://127.0.0.1:8000/api/boards', this.form.board).then((response) => {
                this.resetBoardForm();

                this.$store.dispatch('getBoards');
            });
        },
        updateBoard() {
            this.$http.post('http://127.0.0.1:8000/api/boards/' + this.form.board.id, this.form.board).then((response) => {
                this.resetBoardForm();

                this.$store.dispatch('getBoards');
            });
        },
        deleteBoard(board) {
            this.$http.delete('http://127.0.0.1:8000/api/boards/' + board.id).then((response) => {
                this.$store.dispatch('getBoards');
            });
        },

        resetBoardForm() {
            this.form.board.title = "";
            this.form.board.sort = 100;
        },
        resetTaskForm() {
            this.form.task.title = null;
            this.form.task.sort = 100;
            this.form.task.board_id = null;
        },

        addTask() {
            this.$http.post('http://127.0.0.1:8000/api/tasks', this.form.task).then((response) => {
                this.resetTaskForm();

                this.$store.dispatch('getBoards');
            });
        },
        updateTask() {
            this.$http.post('http://127.0.0.1:8000/api/tasks/' + this.form.task.id, this.form.task).then((response) => {
                this.resetTaskForm();

                this.$store.dispatch('getBoards');
            });
        },
        deleteTask(task) {
            this.$http.delete('http://127.0.0.1:8000/api/tasks/' + task.id).then((response) => {
                this.$store.dispatch('getBoards');
            });
        },
        updateTaskBoard(taskId, boardId)
        {
            let update = {
              board_id: boardId,
            };

            this.$http.post('http://127.0.0.1:8000/api/tasks/' + taskId, update).then((response) => {
                this.resetTaskForm();

                this.$store.dispatch('getBoards');
            });
        },

        openAddTaskForm(board_id) {
            this.form.task.board_id = board_id;
            this.$bvModal.show('add-task')
        },
        openEditTaskForm(task) {
            this.form.task.board_id = task.board_id;
            this.form.task.title = task.title;
            this.form.task.sort = task.sort;
            this.form.task.id = task.id;

            this.$bvModal.show('edit-task')
        },
        openEditBoardForm(board) {
            this.form.board.title = board.title;
            this.form.board.sort = board.sort;
            this.form.board.id = board.id;

            this.$bvModal.show('edit-board')
        },

        orderList() {
            this.list = this.list.sort((one, two) => {
                return one.order - two.order;
            });
        },
        onMove({relatedContext, draggedContext}) {
            const relatedElement = relatedContext.element;
            const draggedElement = draggedContext.element;
            return (
                (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
            );
        },
        onAdd(event) {
            console.log(event);
            let elementId = event.item.attributes.task.value;
            let boardId = event.to.attributes.board.value;

            this.updateTaskBoard(elementId, boardId);
        },
        onSort(event) {
            console.log(event);
        }
    },
    mounted() {
        this.$store.dispatch('checkLoggedIn');

        this.$store.dispatch('getBoards');

        this.resetBoardForm();

        this.resetTaskForm();
    }
}
</script>

<style scoped>
.board {
    max-height: 300px;
}

.flip-list-move {
    transition: transform 0.5s;
}

.no-move {
    transition: transform 0s;
}

.ghost {
    opacity: 0.5;
    background: #c8ebfb;
}

.list-group {
    min-height: 20px;
}

.list-group-item {
    cursor: move;
}

.list-group-item i {
    cursor: pointer;
}
</style>
