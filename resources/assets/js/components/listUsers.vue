<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
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
</template>

<script>
    export default {
        mounted: function() {
            console.log('Component mounted.');
            this.allUsers();
        },
        data: function () {
            return{
                users: [],
                activateBtn: 'Activate',
                deActivateBtn: 'deActivate'
            }
        },
        methods:{
            allUsers: function () {
                var inheritance = this;
                axios.get(base_url+'/users/all')
                    .then(function (response) {
                        inheritance.users = response.data;
                    }.bind(this));
                setTimeout(function() { $("#vueTable").DataTable(); }, 500);
                //$("#dataTable").dataTable();
            },
            //activate or deactivate a given user
            activate: function (user) {
                var inheritance =this;
                if (user.active == 1){
                    inheritance.deActivateBtn = 'deActivating...'
                }else {
                    inheritance.activateBtn = 'activating...'
                }
                axios.post(base_url+'/users/activate' , user)
                    .then(function (response) {
                        inheritance.allUsers();
                        console.log(response.data);
                    });
            }

        }
    }
</script>
