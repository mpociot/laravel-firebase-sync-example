<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <h1>Tasks</h1>
                    <div class="form-group">
                        <input class="form-control" type="text" v-model="task" placeholder="Task" />
                    </div>
                    <div class="form-group">
                        <a @click="createTodo()" class="btn btn-primary">Save</a>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Done</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="todo in todos">
                            <tr v-if="todo">
                                <td>
                                    <input @change="updateTodo(todo)" class="form-control" type="text" v-model="todo.task" />
                                </td>
                                <td>
                                    <input @change="updateTodo(todo)" type="checkbox" :true-value="1" :false-value="0" name="is_done" v-model="todo.is_done" />
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

            </div>

        </div>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.25/vue.min.js"></script>
        <script src="https://www.gstatic.com/firebasejs/live/3.0/firebase.js"></script>

        <script>
            // Initialize Firebase
            var config = {
                apiKey: "{{ config('services.firebase.api_key') }}",
                authDomain: "{{ config('services.firebase.auth_domain') }}",
                databaseURL: "{{ config('services.firebase.database_url') }}",
                storageBucket: "{{ config('services.firebase.storage_bucket') }}",
            };
            firebase.initializeApp(config);

            new Vue({
                el: 'body',

                data: {
                    task: '',
                    todos: []
                },

                ready: function() {
                    var self = this;
                    // Initialize firebase realtime database.
                    firebase.database().ref('todos/').on('value', function(snapshot) {
                        self.$set('todos', snapshot.val());
                    });
                },

                methods: {

                    /**
                     * Create a new todo and synchronize it with Firebase
                     */
                    createTodo: function() {
                        var self = this;
                        $.post('/todo', {
                            _token: '{!! csrf_token() !!}',
                            task: self.task,
                            is_done: false
                        });
                        this.task = '';
                    },


                    /**
                     * Update an existing todo and synchronize it with Firebase
                     */
                    updateTodo: function(todo) {
                        $.post('/todo/'+todo.id, {
                            _method: 'PUT',
                            _token: '{!! csrf_token() !!}',
                            task: todo.task,
                            is_done: todo.is_done
                        });
                    }
                }
            });
        </script>

    </body>
</html>
