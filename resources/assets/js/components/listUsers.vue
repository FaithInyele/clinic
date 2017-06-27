<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div>
        <div class="row" style="margin-bottom: 10px;margin-left: 0px">
            <label class="pull-left" style="font-size: 20px">
                Registered Users
            </label>
            <i class="fa fa-question-circle pull-right" style="color: darkblue;font-size: 20px;cursor: pointer"
               @click="helpOn()"></i>
        </div>
        <div class="row">
            <div class="row alert alert-info" v-show="help">
                <button type="button" class="close" @click="helpOff()">&times;</button>
                <header>Help information</header>
                <p>
                <ol>
                    <li>This Page displays all the Usres registered in the System</li>
                    <li>Users can be Deactivated and this loose access to the system by clicking on the Deactivate
                        button besides their names
                    </li>
                </ol>
                </p>
            </div>
        </div>
        <table class="table table-striped table-bordered dt-responsive" id="vueTable"
               cellspacing="0" width="100%" style="font-size: 10px">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users">
                <td>{{ user.first_name}}</td>
                <td>{{ user.last_name}}</td>
                <td>{{ user.role}}</td>
                <td>
                    <a @click="activate(user)" v-if="user.active==1" class="btn btn-danger">DeActivate</a>
                    <a @click="activate(user)" v-if="user.active==0" class="btn btn-success">Activate</a>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        mounted: function () {
            console.log('Component mounted.');
            this.allUsers();
        },
        data: function () {
            return {
                users: [],
                activateBtn: 'Activate',
                deActivateBtn: 'deActivate',
                help: false
            }
        },
        methods: {
            helpOn: function () {
                var inheritance = this;
                inheritance.help = true;
            },
            helpOff: function () {
                var inheritance = this;
                inheritance.help = false;
            },
            allUsers: function () {
                var inheritance = this;
                axios.get(base_url + '/users/all')
                    .then(function (response) {
                        inheritance.users = response.data;
                    }.bind(this));
                setTimeout(function () {
                    $("#vueTable").DataTable();
                }, 500);
                //$("#dataTable").dataTable();
            },
            //activate or deactivate a given user
            activate: function (user) {
                var inheritance = this;
                if (user.active == 1) {
                    inheritance.deActivateBtn = 'deActivating...'
                } else {
                    inheritance.activateBtn = 'activating...'
                }
                axios.post(base_url + '/users/activate', user)
                    .then(function (response) {
                        inheritance.allUsers();
                        console.log(response.data);
                    });
            }

        }
    }
</script>
