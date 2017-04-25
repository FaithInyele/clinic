<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <table class="table table-striped table-bordered dt-responsive" id="vueTable"
           cellspacing="0" width="100%" style="font-size: 10px">
        <thead>
        <tr>
            <th>First Name</th>
            <th>Other Name(s)</th>
            <th>D.O.B</th>
            <th>Type</th>
            <th>Gender</th>
            <th>Id No.</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="client in clients">
            <td>{{ client.first_name}}</td>
            <td>{{ client.other_names}}</td>
            <td>{{ client.yob}}</td>
            <td>{{ client.type}}</td>
            <td>{{ client.gender}}</td>
            <td>{{ client.id_number}}</td>
            <td>
                <a class="btn btn-danger">Open</a>
                <a class="btn btn-success">Edit</a>
                <a class="btn btn-success">Delete</a>
            </td>
        </tr>

        </tbody>
    </table>
</template>

<script>
    export default {
        mounted: function() {
            console.log('Component mounted.');
            this.allClients();
        },
        data: function () {
            return{
                clients: [],
            }
        },
        methods:{
            allClients: function () {
                var inheritance = this;
                axios.get(base_url+'/clients/all')
                    .then(function (response) {
                        inheritance.clients = response.data;
                    }.bind(this));
                setTimeout(function() { $("#vueTable").DataTable(); }, 500);
            }

        }
    }
</script>
