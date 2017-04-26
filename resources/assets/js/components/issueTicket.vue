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
                            <td><a @click="openTicket">Open</a> </td>
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
                    <tbody v-for="allActive in allActives">
                    <tr>
                        <td>{{ allActive.client.first_name}}</td>
                        <td v-if="allActive.progress">{{ allActive.progress.description}}</td>
                        <td v-if="!allActive.progress">--</td>
                        <td><a @click="openTicket(allActive.id)">Open</a> </td>
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
                                <label>At Doctor/Nurse</label>
                                <label class="pull-right">Status: {{status}}</label>
                            </slot>
                        </div>
                        <div class="modal-body" style="text-align: center" v-show="modalLoading">
                            <img :src="baseUrl+'/images/loading.gif'">
                        </div>

                        <div class="modal-body" v-show="!modalLoading">
                            <slot name="body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <img src="https://placehold.it/140x100" style="width: 100%; height: auto">
                                        </div>
                                        <div class="row" v-if="currentTicket.client">
                                            <h5>
                                                <p><label>Client Name:</label> {{currentTicket.client.first_name}},  {{currentTicket.client.other_names}}</p>
                                                <p><label>Client Type:</label> {{currentTicket.client.type}}</p>
                                                <p><label>Year of Birth:</label> {{currentTicket.client.yob}}</p>
                                                <input type="hidden" v-model="currentTicket.id" id="hiddenTicketId">
                                            </h5>
                                        </div>

                                    </div>
                                    <div class="col-md-8">
                                        <div class="row" style="max-height: 400px;overflow-y: scroll">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a data-toggle="tab" href="#progress">Ticket</a></li>
                                                <li><a data-toggle="tab" href="#special"> Special medical condition(s)</a></li>
                                                <li><a data-toggle="tab" href="#history"> History</a></li>
                                            </ul>

                                            <div class="row tab-content" >
                                                <div id="progress" class="tab-pane fade in active">
                                                    <h6>Progress </h6>
                                                    <div class="accordion">
                                                        <!--first accordion-->
                                                        <div class="accordion-section">
                                                            <a class="accordion-section-title" href="#accordion-1">
                                                                Assigned a Ticket
                                                                <b style="color: white;background-color: green;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Done
                                                                </b>
                                                            </a>
                                                            <div id="accordion-1" class="accordion-section-content" v-if="currentTicket.assigned_by">
                                                                <div class="well well-small dark">
                                                                    <h6>This Ticket was created at: {{currentTicket.created_at}}</h6>
                                                                    <h6>assigned by <i>{{currentTicket.assigned_by.last_name}}, {{currentTicket.assigned_by.first_name}}</i>  to  <i>{{currentTicket.assigned_to.last_name}}, {{currentTicket.assigned_to.first_name}}</i></h6>
                                                                </div>
                                                            </div><!--end .accordion-section-content-->
                                                        </div><!--end .accordion-section-->

                                                        <div class="accordion-section">
                                                            <a class="accordion-section-title" href="#accordion-2">
                                                                at Doctor/Nurse
                                                                <b style="color: white;background-color: #f6fcab;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Current...
                                                                </b>
                                                                <b style="color: white;background-color: green;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Done
                                                                </b>
                                                                <b style="color: white;background-color: #f2534e;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Pending...
                                                                </b>
                                                            </a>
                                                            <div id="accordion-2" class="accordion-section-content">
                                                                <div class="col-md-8">
                                                                    <label>
                                                                        Input Symptoms and general Observations, if any.<br>
                                                                        <b style="font-size: 8px">
                                                                            [Input a single symptom, then hit enter before inputting another]
                                                                        </b>
                                                                    </label>
                                                                    <div class="row" style="width: 100%">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" data-role="tagsinput" id="sympt" v-model="currentTicket.tags">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <button class="btn btn-sm btn-primary" @click="saveSymptoms">{{atDoctorButton}}</button>
                                                                        </div>
                                                                        <hr>
                                                                        <div class="row" v-show="recommendAction">
                                                                            <div class="row">
                                                                                <a style="font-size: 10px" @click="recommendLab">Recommend Lab Test(s)</a>
                                                                                <a style="font-size: 10px" @click="prescribeMedication">Prescribe Medication</a>
                                                                            </div>
                                                                            <div v-show="successtoLab" class="alert alert-info">
                                                                                <h6>Success! Request sent. Send Client to Lab for Tests and Await response from Lab Technician</h6>
                                                                            </div>
                                                                            <div class="row" v-show="chooseLab" style="font-size: 12px">
                                                                                <label>
                                                                                    Select Lab Technician
                                                                                </label>
                                                                                <select class="form-control" v-model="selectedLabTech">
                                                                                    <option v-for="labTechnician in labTechnicians" :value="labTechnician.id">{{labTechnician.first_name}}</option>
                                                                                </select>
                                                                                <label>
                                                                                    Lab Tests:
                                                                                    <b style="font-size: 10px">
                                                                                        Input a single test, then hit enter before inputting another.
                                                                                    </b>
                                                                                </label>
                                                                                <div class="form-group">
                                                                                    <input style="min-height: 150px;width: 100%" type="text" class="form-control" data-role="tagsinput" id="tests">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <button class="btn btn-primary" @click="saveLab">{{sendtoLab}}</button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row" v-show="chooseMed" style="font-size: 12px">
                                                                                Under Heavy Construction
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <h6><b>Summary</b></h6>
                                                                    <div class="row" style="font-size: 9px">
                                                                        <label style="font-size: 11px">Symptoms recorded:</label><br>
                                                                        <i v-for="symptom in currentTicket.symptoms">
                                                                            {{symptom.description}},
                                                                        </i>
                                                                    </div>
                                                                </div>

                                                            </div><!--end .accordion-section-content-->
                                                        </div><!--end .accordion-section-->

                                                        <div class="accordion-section">
                                                            <a class="accordion-section-title" href="#accordion-3">
                                                                Seen a Lab Technician
                                                                <b style="color: white;background-color: #f2534e;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Pending...
                                                                </b>
                                                            </a>
                                                            <div id="accordion-3" class="accordion-section-content">
                                                                <div class="row" v-if="currentTicket.tests">
                                                                    <div v-for="test in currentTicket.tests">
                                                                        <b>{{test.description}}:</b> <i>{{test.result}}</i>
                                                                    </div>
                                                                </div>
                                                            </div><!--end .accordion-section-content-->
                                                        </div>
                                                        <div class="accordion-section">
                                                            <a class="accordion-section-title" href="#accordion-4">Seen Chemist<b style="color: white;background-color: #f2534e;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">Pending...</b></a>
                                                            <div id="accordion-4" class="accordion-section-content">
                                                                <label>
                                                                    Prescription
                                                                    <b style="font-size: 10px">
                                                                        Input a single prescription, then press enter before inputting another
                                                                    </b>
                                                                </label>

                                                                    <div class="form-group">
                                                                        <input style="min-height: 150px;width: 100%" type="text" class="form-control" data-role="tagsinput" id="med">
                                                                    </div>
                                                                    <div class="form-group pull-right">
                                                                        <button class="btn btn-primary" @click="saveP">{{savePrescription}}</button>
                                                                        <button class="btn btn-primary" @click="submitP()">{{toChemist}}</button>
                                                                    </div>
                                                            </div><!--end .accordion-section-content-->
                                                        </div><!--end .accordion-section-->
                                                    </div><!--end .accordion-->
                                                </div>

                                                <div id="history" class="tab-pane fade">
                                                    <h1>History</h1>
                                                </div>
                                                <div id="special" class="tab-pane fade">
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
            this.allActiveMethod()
        },
        data: function () {
            return{
                ticketModal: false,
                allActives: [],
                currentTicket: [],
                atDoctorButton: 'Save Symptoms',
                toChemist: 'Submit to Chemist',
                savePrescription: 'Save',
                sendtoLab: 'Send Client to Lab',
                afterSymptoms: false,
                symptoms: '',
                recommendAction: true,
                chooseLab: false,
                chooseMed: false,
                labTechnicians: [],
                selectedLabTech: '',
                successtoLab: false,
                modalLoading: true,
                baseUrl: base_url,
                status: 'No Operation'

            }
        },
        methods:{
            //get all Active Lab Technicians
            labtechs: function () {
              var inheritance = this;
              axios.get(base_url+'/tickets/my-tickets/query/labtechs')
                  .then(function (response) {
                      inheritance.labTechnicians = response.data;
                  }.bind(this));
            },
            //open a specific ticket, for docs/nurses
            openTicket: function (ticketid) {
                var inheritance = this;
                inheritance.modalLoading = true;
                axios.get(base_url+'/tickets/my-tickets/'+ticketid)
                    .then(function (response) {
                        inheritance.currentTicket = response.data;
                        inheritance.labtechs();
                        inheritance.modalLoading = false;
                    }.bind(this));
                inheritance.ticketModal = true;
            },
            //close the above opened ticket.
            closeTicket: function () {
                var inheritance=this;
                inheritance.ticketModal=false;
                inheritance.currentTicket = [];
            },
            //list all active tickets, thatbelong to thelogged in user
            allActiveMethod: function () {
                var inheritance = this;
                console.log('ai');
                axios.get(base_url+'/tickets/my-tickets/all-active')
                    .then(function (response) {
                        console.log(response.data);
                        inheritance.allActives = response.data;
                    }.bind(this));
            },
            //for doctor. save client's symptoms
            saveSymptoms: function () {
                var inheritance=this;
                inheritance.atDoctorButton = 'Saving...';
                inheritance.status = 'Saving Symptoms...';
                var symptom = $('#sympt').val();
                var hticket_id = $('#hiddenTicketId').val();
                console.log(hticket_id);
                inheritance.symptoms = symptom;
                axios.get(base_url+'/tickets/my-tickets/save/symptoms?ticket_id='+hticket_id+'&status=pending&symptoms='+inheritance.symptoms)
                    .then(function (response) {
                        console.log(response);
                        inheritance.recommendAction = true;
                        inheritance.atDoctorButton = 'Save Symptoms';
                        inheritance.status = 'Symptoms Successfully Saved'
                    }.bind(this));
            },
            recommendLab: function () {
                var inheritance=this;
                inheritance.chooseLab=true;
                inheritance.chooseMed=false;
            },
            prescribeMedication: function () {
              var inheritance = this;
              inheritance.chooseLab=false;
              inheritance.chooseMed=true;
            },
            saveP: function () {
                var inheritance = this;
                inheritance.savePrescription = "Saving...";
                var prescription = $('#med').val();
                console.log(base_url+'/tickets/my-tickets/query/startchemist?med='+prescription+'&ticket_id='+inheritance.currentTicket.id);
                axios.get(base_url+'/tickets/my-tickets/query/startchemist?med='+prescription+'&ticket_id='+inheritance.currentTicket.id)
                    .then(function () {
                        inheritance.savePrescription = "Save";
                    }.bind(this))
            },
            submitP: function () {
                console.log('huh');
            },
            //start a lab ticket.
            saveLab: function () {
              var inheritance = this;
              inheritance.sendtoLab = 'Sending request...';
              var tests = $('#tests').val();
              console.log(base_url+'/tickets/my-tickets/query/startlab?tests='+tests+'&technician='+inheritance.selectedLabTech+'&ticket_id='+inheritance.currentTicket.id);
              axios.get(base_url+'/tickets/my-tickets/query/startlab?tests='+tests+'&technician='+inheritance.selectedLabTech+'&ticket_id='+inheritance.currentTicket.id)
                  .then(function () {
                      inheritance.successtoLab = true;
                      inheritance.sendtoLab = 'Sent';
                  }.bind(this))
            }

        }
    }
</script>
