<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="row">
        <div class="col-lg-8">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>My List</strong> <br>
                It is Highly recommended you follow the list as is.<br>
            </div>
            <div class="row" v-for="client in clients">
                <div class="row" style="background-color: #f8f8f8;border: 2px solid #53CDF6;border-radius: 10px;margin-left: 0px">
                    <div class="row">
                        <label> Client Name:</label>
                        {{ client.c_fname}}, {{ client.c_othernames}}
                        <i class="pull-right">
                            Ticket created on:{{ client.created_at}}
                        </i>
                    </div>
                    <hr style="margin: 5px">
                    <div class="row">
                        <label>Details</label> Tests requested are; {{client.tests}}
                    </div>
                    <hr style="margin: 5px">
                    <div class="row">
                        <a class="pull-right btn btn-sm btn-success btn-custom" @click="currentTicket(client.id)" style="margin-right: 10px">Open</a>
                    </div>
                </div>
                <br>
            </div>
        </div>
        <div class="col-lg-4">

        </div>

        <transition name="modal">
            <div class="modal-mask" v-show="ticketModal">
                <div class="modal-wrapper">
                    <div class="modal-container">

                        <div class="modal-header">
                            <slot name="header">
                                <label>At Lab Technician</label>
                                <label :class="{'alert-success': statusSuccess,'alert-danger': statusError, 'pull-right': classLoad, 'alert-info': statusWarn}" style="text-align: right">Status: {{status}}</label>
                            </slot>
                        </div>
                        <div class="modal-body" style="text-align: center" v-show="modalLoading">
                            <img :src="baseUrl+'/images/loading.gif'">
                        </div>

                        <div class="modal-body" v-show="!modalLoading">
                            <slot name="body" v-if="currentClient.client">
                                <div class="row">

                                <div class="col-md-4">
                                    <div class="row">
                                        <img src="https://placehold.it/140x100" style="width: 100%; height: auto;border-radius: 10px">
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

                                        <div class="row tab-content" v-if="currentClient">
                                            <div id="progress" :class="{'tab-pane':classLoad, 'fade in': classLoad, 'active':classLoad, completed:currentClient.progress.level >= 3 && currentClient.progress.level <= 6}" style="min-height: 80%">
                                                <h6>Requested Tests: {{currentClient.progress.level}}</h6>
                                                <div class="form-group" v-for="test in currentClient.tests">
                                                    <div class="row pullquote-left">
                                                        <div class="row" style="padding-left: 10px;padding-right: 10px;margin-bottom: 10px">
                                                            <div>{{ test.details.resource_name }} <i style="font-size: 10px" class="pull-right">requested at: {{ test.created_at }}</i></div>
                                                            <label style="font-size: 9px">Enter results here:</label>
                                                            <textarea class="form-control input-sm" v-model="test.result">
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="pull-right">
                                                    <button class="btn btn-primary" @click="saveResults(currentClient.tests)">{{ saveButton }}</button>
                                                    <button class="btn btn-primary" @click="submitResults(currentClient.tests)">{{ resultsButton }}</button>
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
                                <button class="btn btn-danger" @click="closeTicket">
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
            this.clientsAtLab();
        },
        data: function () {
            return{
                ticketModal: false,
                modalLoading: true,
                clients: [],
                currentClient: [],
                saveButton: 'Save',
                resultsButton: 'Send Results',
                baseUrl: base_url,
                status: 'No Operation',
                statusError: false,
                statusSuccess: false,
                classLoad: true,
                statusWarn: false
            }
        },
        methods:{
            //close modal
            closeTicket: function () {
                var inheritance = this;
                inheritance.clientsAtLab();
                inheritance.currentClient = [];
                inheritance.ticketModal = false;
            },
            //open modal
            openTicket: function () {
                var inheritance =this;
                inheritance.ticketModal=true;
            },
            //revert all status
            revertStatus: function () {
                var inheritance =this;
                inheritance.statusError = false;
                inheritance.statusSuccess = false;
                inheritance.statusWarn = false;
            },
            //get all clients for lab
            clientsAtLab: function () {
                var inheritance =this;
                axios.get(base_url+'/progress/atlab')
                    .then(function (response) {
                        inheritance.clients = response.data;
                    }.bind(this))
                    .catch(function (error) {
                        inheritance.status = 'There was an Error while Processing your Request';
                        inheritance.statusError = true;
                    });
            },
            //close lab test and submit the results
            submitResults: function (data) {
                var inheritance = this;
                inheritance.revertStatus();
                inheritance.resultsButton = 'Sending Results';
                inheritance.status = 'Sending Results to Doctor';
                inheritance.saveResults(data);
                console.log(base_url+'/atlab/lab/update/'+inheritance.currentClient.id+'?ticket_id='+inheritance.currentClient.ticket.id);
                axios.get(base_url+'/atlab/lab/update/'+inheritance.currentClient.id+'?ticket_id='+inheritance.currentClient.ticket.id)
                    .then(function (response) {
                        console.log(response.data);
                        inheritance.resultsButton = 'Send Results';
                        inheritance.status = 'Results Successfully Submitted';
                        inheritance.statusSuccess = true;
                        inheritance.currentClient.progress.level = 3;
                        inheritance.closeTicket();
                    }.bind(this))
                    .catch(function (error) {
                        inheritance.status = 'There was an Error while Processing your Request';
                        inheritance.statusError = true;
                    });
            },
            //open a given ticket
            currentTicket: function (ticket_id) {
                var inheritance = this;
                inheritance.revertStatus();
                inheritance.modalLoading = true;
                console.log(base_url+'/atlab/view/'+ticket_id);
                axios.get(base_url+'/atlab/view/'+ticket_id)
                    .then(function (response) {
                        console.log(response.data);
                        inheritance.currentClient = response.data;
                        inheritance.modalLoading = false;
                        inheritance.ticketModal = true;
                    }.bind(this))
                    .catch(function (error) {
                        inheritance.status = 'There was an Error while Processing your Request';
                        inheritance.statusError = true;
                    });
            },
            //save results
            saveResults: function (data) {
                var inheritance = this;
                inheritance.revertStatus();
                inheritance.status = 'Saving Results...';
                inheritance.saveButton = 'Saving...';
                axios.post(base_url+'/atlab/test/update', data)
                    .then(function (response) {
                        inheritance.status = 'Result(s) successfully Saved';
                        inheritance.statusSuccess = true;
                        inheritance.saveButton = 'Save';
                    }.bind(this))
                    .catch(function (error) {
                        inheritance.status = 'There was an Error while Processing your Request';
                        inheritance.statusError = true;
                    });
            }
        }
    }
</script>
