<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="row">
        <div class="col-lg-8">
            <div class="row" style="margin-bottom: 10px;margin-left: 0px">
                <label class="pull-left" style="font-size: 20px">
                    My Tickets
                </label>
                <i class="fa fa-question-circle pull-right" style="color: darkblue;font-size: 20px;cursor: pointer" @click="helpOn()"></i>
            </div>
            <div class="row">
                <div class="row alert alert-info" v-show="help">
                    <button type="button" class="close" @click="helpOff()">&times;</button>
                    <header>Help information</header>
                    <p>
                    <ol>
                        <li>This Page displays all the Clients currently assigned to you</li>
                        <li>You can attend to a client by clicking on the open button besides their names; from where you can find details about all the requested Prescriptions and proceed accordingly</li>
                    </ol>
                    </p>
                </div>
            </div>
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>My List</strong> <br>
                It is Highly recommended you follow the list as is.<br>
            </div>
            <div class="row" v-for="client in allClients">
                <div class="row" style="background-color: #f8f8f8;border: 2px solid #53CDF6">
                    <div class="row">
                        <label> Client Name:</label>
                        {{ client.c_fname}}, {{ client.c_othernames}}
                        <i class="pull-right">
                            Ticket created on:{{ client.created_at}}
                        </i>
                    </div>
                    <hr style="margin: 5px">
                    <div class="row">
                        Details: Prescriptions requested are ; {{client.tests}}
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
                                <label>At Chemist</label>
                                <label :class="{'alert-success': statusSuccess,'alert-danger': statusError, 'pull-right': classLoad, 'alert-info': statusWarn}" style="text-align: right">Status: {{status}}</label>
                            </slot>
                        </div>
                        <div class="modal-body" style="text-align: center" v-show="modalLoading">
                            <img :src="baseUrl+'/images/loading.gif'">
                        </div>

                        <div class="modal-body" v-if="currentClient.client">
                            <slot name="body">
                                <div class="row" id="printable" style="text-align: center">
                                    <h1 style="text-align: center">iHospital</h1>
                                    <h4 style="text-align: center">Payment Receipt</h4>
                                    <div class="row" style="text-align: left">
                                        <div class="row" style="text-align: right">
                                            Ticket Number: #{{currentClient.ticket.id}}<br>
                                            Ticket created on: {{currentClient.ticket.created_at}}<br>
                                            Doctor in-charge: {{currentClient.doctor.first_name}}, {{currentClient.doctor.last_name}}<br>
                                        </div>
                                        <div class="row">
                                            Client Name: {{currentClient.client.first_name}}, {{currentClient.client.other_names}}<br>
                                        </div>

                                    </div>
                                    <table class="table table-striped table-bordered dt-responsive"
                                           cellspacing="0" width="100%" style="font-size: 10px">
                                        <thead>
                                        <tr>
                                            <td>Description</td>
                                            <td>Amount</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-if="currentClient.medicine" v-for="test in currentClient.medicine">
                                            <!--<td>{{medicine.id}}</td>
                                            <td>{{medicine.id}}</td>-->
                                        </tr>
                                        <tr>
                                            <td><b>Total</b></td>
                                            <td><b>{{currentClient.total}}</b></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="row" style="text-align: left">
                                        Amount Paid: {{currentClient.total}}<br>
                                        Payment Method: {{payment_method}}
                                    </div>
                                </div>
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
                                            </ul>

                                            <div class="row tab-content" >
                                                <div id="progress" class="tab-pane fade in active" style="min-height: 80%">
                                                    <div v-for="medicine in currentClient.medicine">
                                                        <hr>
                                                        <div :class="{row : ro, successful: medicine.status == 'issued' || medicine.status == 'external'}" style="padding-top: 10px;padding-bottom: 10px">
                                                            <div>
                                                                <div class="col-md-5">{{medicine.details.resource_name}}</div>
                                                                <div class="col-md-5">
                                                                    <b>Unit Price:</b>   {{medicine.details.unit_price}}
<!--
                                                                    <input type="text" class="form-control sm" placeholder="Alternative, if any" v-model="medicine.alternatative">
-->
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="dropdown">
                                                                        <button :class="{btn: classLoad, 'btn-sm': classLoad, 'btn-primary': classLoad, 'dropdown-toggle':classLoad, completed: currentClient.progress.level > 4 && currentClient.progress.level <= 6 }" type="button" data-toggle="dropdown">Confirm
                                                                            <span class="caret"></span></button>
                                                                        <ul class="dropdown-menu">
                                                                            <li><a @click="confirm(medicine, 'affirm')">Affirm {{medicine.medicine}}</a></li>
                                                                            <!--<li><a @click="confirm(medicine, 'alternative')">Affirm Alternative</a></li>-->
                                                                            <li><a @click="confirm(medicine, 'external')">Affirm External Purchase</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr style="margin: 10px; border: 2px solid #149ED2">
                                                    <div class="row">
                                                        <label>Total To Pay:</label><i class="pull-right">{{currentClient.total}}</i>
                                                        <h6>Client should make 100% payment before proceeding...</h6>
                                                        <div class="form-group">
                                                            <input type="number" class="form-control input-sm" placeholder="Amount Paid" v-model="amountPaid">
                                                        </div>
                                                        <div class="form-group">
                                                            <select class="input-sm" v-model="payment_method">
                                                                <option value="" selected disabled>-Select Payment Method-</option>
                                                                <option value="Cash">Cash</option>
                                                                <option value="Cheque">Cheque</option>
                                                                <option value="Mobile Money">Mobile Money</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <button v-if="currentClient.progress" :class="{btn: classLoad, 'btn-success': classLoad, completed: currentClient.progress.level > 4 && currentClient.progress.level <= 6}" @click="closeMedication">Pay</button>
                                                        <button v-if="printButton" class="btn btn-warning" onclick="printdata('printable')">
                                                            Print Receipt
                                                        </button>
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
                                <button :class="{'btn btn-danger':classLoad, 'pull-right':classLoad}" @click="closeTicket">
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
                ro: true,
                statusError: false,
                statusSuccess: false,
                classLoad: true,
                statusWarn: false,
                amountPaid: '',
                payment_method:'',
                printButton:false,
                help: false
            }
        },
        methods:{
            helpOn: function () {
                var inheritance = this;
                inheritance.help = true;
            },
            helpOff: function () {
                var inheritance = this;
                inheritance.help = false;
            },
            clientsAtChemist: function () {
                var inheritance =this;
                axios.get(base_url+'/progress/atchemist')
                    .then(function (response) {
                        inheritance.allClients = response.data;
                    }.bind(this))
            },
            currentTicket: function (ticket_id) {
                var inheritance=this;
                axios(base_url+'/atchemist/view/'+ticket_id)
                    .then(function (response) {
                        console.log(response.data);
                        inheritance.currentClient = response.data;
                        inheritance.modalLoading = false;
                        inheritance.ticketModal=true;
                    }.bind(this));

            },
            closeTicket: function () {
                var inheritance =this;
                inheritance.clientsAtChemist();
                inheritance.currentClient = [];
                inheritance.ticketModal=false;
                inheritance.printButton =false;
            },
            //reverse status
            reverseStatus: function () {
                var inheritance = this;
                inheritance.statusSuccess =false;
                inheritance.statusWarn = false;
                inheritance.statusError = false;
            },
            confirm: function (medicine, other) {
                var inheritance = this;
                inheritance.reverseStatus();
                inheritance.status = 'Updating Prescription...';
                var ticket_id = inheritance.currentClient.id;
                var wah= false;
                if (other == 'affirm'){
                    wah = true;
                    medicine.alternatative = null;
                    medicine.status = 'issued';
                }else if (other == 'alternative'){
                    if (medicine.alternatative == null){
                        inheritance.status = 'Alternative Medication is Empty!';
                        inheritance.statusError = true;
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
                            inheritance.statusSuccess = true;
                        }.bind(this))
                        .catch(function (error) {
                            inheritance.status = 'There was an Error while Processing your Request';
                            inheritance.statusError = true;
                        });
                }

            },
            alternative: function (medicine) {
                console.log(medicine.medicine);
            },
            closeMedication: function () {
                var inheritance = this;
                inheritance.reverseStatus();
                inheritance.status = "Paying up Prescription...";
                //console.log(base_url+'/atchemist/close?ticket_id='+inheritance.currentClient.id+'&prescription_id='+inheritance.currentClient.prescription.id);
                if (inheritance.amountPaid != inheritance.currentClient.total){
                    inheritance.status = 'Amount to be Paid should be exact as Total owed';
                    inheritance.statusError = true;
                }else {
                    console.log(base_url+'/atchemist/close?ticket_id='+inheritance.currentClient.ticket.id+
                        '&prescription_id='+inheritance.currentClient.id+
                        '&amount='+inheritance.currentClient.total+
                        '&payment_method='+inheritance.payment_method);
                    axios.get(base_url+'/atchemist/close?ticket_id='+inheritance.currentClient.ticket.id+
                        '&prescription_id='+inheritance.currentClient.id+
                        '&amount='+inheritance.currentClient.total+
                        '&payment_method='+inheritance.payment_method)
                        .then(function (response) {
                            inheritance.status = 'Prescription Paid Successfully';
                            inheritance.statusSuccess = true;
                            inheritance.printButton = true;
                        })
                        .catch(function (error) {
                            inheritance.status = 'There was an Error while Processing your Request';
                            inheritance.statusError = true;
                        });
                }

            }

        }
    }
</script>
