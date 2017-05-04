<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div>
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
                    <a class="btn btn-success" @click="openModal()">Open</a>
                    <a class="btn btn-success" @click="editClient(client)">Edit</a>
                </td>
            </tr>

            </tbody>
        </table>

        <transition name="modal">
            <div class="modal-mask" v-show="clientModal">
                <div class="modal-wrapper">
                    <div class="modal-container">

                        <div class="modal-header">
                            <slot name="header">
                                default header
                            </slot>
                        </div>

                        <div class="modal-body">
                            <slot name="body">
                                Under Heavy Construction. <i class="fa-gear"></i>
                            </slot>
                        </div>

                        <div class="modal-footer">
                            <slot name="footer">
                                default footer
                                <button class="modal-default-button" @click="closeModal()">
                                    OK
                                </button>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
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
                clientModal: false
            }
        },
        methods:{
            openModal: function () {
                var inheritance= this;
                inheritance.clientsModal = true;
            },
            closeModal: function () {
                var inheritance = this;
                inheritance.clientsModal = false;
            },
            allClients: function () {
                var inheritance = this;
                axios.get(base_url+'/clients/all')
                    .then(function (response) {
                        inheritance.clients = response.data;
                    }.bind(this));
                setTimeout(function() { $("#vueTable").DataTable(); }, 500);
            },
            viewClient: function (client) {
                
            },
            editClient: function (client) {
                
            }

        }
    }
</script>
