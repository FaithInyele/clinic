<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading"><b>New Clients (Issued Ticket Stage)</b></div>

                <div class="panel-body myTicket">
                    <table class="table table-striped table-bordered dt-responsive" id="dataTable"
                           cellspacing="0" width="100%" style="font-size: 10px">
                        <thead>
                        <tr>
                            <th>Client</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Makamu</td>
                            <td>Issues Ticket</td>
                            <td><a @click="">Open</a> </td>
                        </tr>
                        <tr>
                            <td>Makamu</td>
                            <td>Issues Ticket</td>
                            <td><a href="">Open</a> </td>
                        </tr>
                        <tr>
                            <td>Makamu</td>
                            <td>Issues Ticket</td>
                            <td><a href="">Open</a> </td>
                        </tr>
                        <tr>
                            <td>Makamu</td>
                            <td>Issues Ticket</td>
                            <td><a href="">Open</a> </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><b>Lab Clients (Issued Lab Ticket)</b></div>

                <div class="panel-body myTicket">
                    You are logged in!
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><b>Chemist Clients(Issued Chemist Ticket)</b></div>

                <div class="panel-body myTicket">
                    You are logged in!
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>My List</strong> <br>
                It is Highly recommended you follow the list as is.<br>
            </div>

            <div class="row">
                <table class="table table-striped table-bordered dt-responsive" id=""
                       cellspacing="0" width="100%" style="font-size: 10px">
                    <thead>
                    <tr>
                        <th>Client</th>
                        <th>Progress</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody v-for="client in allClients">
                    <tr>
                        <td>{{ client.c_fname}}</td>
                        <td>{{ client.created_at}}</td>
                        <!-- <td v-if="allActive.progress">{{ allActive.progress.description}}</td>
                         <td v-if="!allActive.progress">&#45;&#45;</td>-->
                        <td><a @click="currentTicket(client.ticket_id)">Open</a> </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <transition name="modal">
            <div class="modal-mask" v-show="ticketModal">
                <div class="modal-wrapper">
                    <div class="modal-container">

                        <div class="modal-header">
                            <slot name="header">
                                <label>At Chemist</label>
                                <label class="pull-right">Status: {{status}}</label>
                            </slot>
                        </div>
                        <div class="modal-body" style="text-align: center" v-show="modalLoading">
                            <img :src="baseUrl+'/images/loading.gif'">
                        </div>

                        <div class="modal-body" v-if="currentClient.client">
                            <slot name="body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <img src="https://placehold.it/140x100" style="width: 100%; height: auto">
                                        </div>
                                        <div class="row" v-if="currentClient.client">
                                            <h5>
                                                <p><label>Client Name:</label> {{currentClient.client.first_name}},  {{currentClient.client.other_names}}</p>
                                                <p><label>Client Type:</label> {{currentClient.client.type}}</p>
                                                <p><label>Year of Birth:</label> {{currentClient.client.yob}}</p>
                                                <input type="hidden" v-model="currentClient.id" id="hiddenTicketId">
                                            </h5>
                                        </div>

                                    </div>
                                    <div class="col-md-8">
                                        <div class="row" style="max-height: 400px;overflow-y: scroll">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a data-toggle="tab" href="#progress">Ticket</a></li>
                                                <li><a data-toggle="tab" href="#history"> History</a></li>
                                            </ul>

                                            <div class="row tab-content" >
                                                <div id="progress" class="tab-pane fade in active" style="min-height: 80%">
                                                    <div v-for="medicine in currentClient.medicine">
                                                        <hr>
                                                        <div :class="{row : ro, successful: medicine.status == 'issued' || medicine.status == 'external'}" style="padding-top: 10px;padding-bottom: 10px">
                                                            <div>
                                                                <div class="col-md-5">{{medicine.medicine}}</div>
                                                                <div class="col-md-5">
                                                                    <input type="text" class="form-control sm" placeholder="Alternative, if any" v-model="medicine.alternatative">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Confirm
                                                                            <span class="caret"></span></button>
                                                                        <ul class="dropdown-menu">
                                                                            <li><a @click="confirm(medicine, 'affirm')">Affirm {{medicine.medicine}}</a></li>
                                                                            <li><a @click="confirm(medicine, 'alternative')">Affirm Alternative</a></li>
                                                                            <li><a @click="confirm(medicine, 'external')">Affirm External Purchase</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="history" class="tab-pane fade">
                                                    <h1>History</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </slot>
                        </div>

                        <div class="modal-footer">
                            <slot name="footer">
                                <button class="modal-default-button" @click="closeTicket">
                                    Close
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
            this.clientsAtChemist();
        },
        data: function () {
            return{
                ticketModal: false,
                allClients: [],
                currentClient: [],
                modalLoading: true,
                status: 'No Operation',
                baseUrl: base_url,
                buttonInstance: ['button'],
                ro: true
            }
        },
        methods:{
            clientsAtChemist: function () {
                var inheritance =this;
                axios.get(base_url+'/progress/atchemist')
                    .then(function (response) {
                        inheritance.allClients = response.data;
                    }.bind(this))
            },
            currentTicket: function (ticket_id) {
                var inheritance=this;
                inheritance.ticketModal=true;
                axios(base_url+'/atchemist/view/'+ticket_id)
                    .then(function (response) {
                        inheritance.currentClient = response.data;
                    }.bind(this));
                inheritance.modalLoading = false;

            },
            closeTicket: function () {
                var inheritance =this;
                inheritance.currentClient = [];
                inheritance.ticketModal=false;
            },
            confirm: function (medicine, other) {
                var inheritance = this;
                inheritance.status = 'Updating Prescription...';
                var ticket_id = inheritance.currentClient.id;
                var wah= false;
                if (other == 'affirm'){
                    wah = true;
                    medicine.alternatative = null;
                    medicine.status = 'issued';
                }else if (other == 'alternative'){
                    if (medicine.alternatative == null){

                    }else{
                        medicine.status = 'issued';
                        wah = true;
                    }
                }else if(other == 'external'){
                    wah = true;
                    medicine.alternatative = null;
                    medicine.status = 'external';
                }
                if (wah == true){
                    axios.post(base_url+'/atchemist/update', medicine)
                        .then(function (response) {
                            inheritance.currentTicket(ticket_id);
                            inheritance.status = 'Prescription Successfully Updated';
                        }.bind(this))
                }

            },
            alternative: function (medicine) {
                console.log(medicine.medicine);
            }

        }
    }
</script>
