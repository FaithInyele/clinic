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

                        <!--<div class="modal-header">
                            <slot name="header">
                                default header
                            </slot>
                        </div>-->

                        <div class="modal-body">
                            <slot name="body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <img src="https://placehold.it/140x100" style="width: 100%; height: auto">
                                        </div>
                                        <div class="row" v-if="currentTicket.client">
                                            <h5>
                                                <p><b>Client Name:</b> {{currentTicket.client.first_name}},  {{currentTicket.client.other_names}}</p>
                                                <p><b>Client Type:</b> {{currentTicket.client.type}}</p>
                                                <p><b>Year of Birth:</b> {{currentTicket.client.yob}}</p>
                                                <p>{{currentTicket.updated_at}}</p>
                                                <input type="hidden" v-model="currentTicket.id" id="hiddenTicketId">
                                            </h5>
                                        </div>

                                    </div>
                                    <div class="col-md-8">
                                        <div class="row" style="max-height: 450px;overflow-y: scroll">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a data-toggle="tab" href="#progress">Ticket</a></li>
                                                <li><a data-toggle="tab" href="#history"> History</a></li>
                                            </ul>

                                            <div class="row tab-content" >
                                                <div id="progress" class="tab-pane fade in active">
                                                    <h1>Progress</h1>
                                                    <div class="accordion">
                                                        <div class="accordion-section">
                                                            <a class="accordion-section-title" href="#accordion-1">Assigned a Ticket <b style="color: white;background-color: green;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">Done</b></a>
                                                            <div id="accordion-1" class="accordion-section-content">
                                                                <div class="well well-small dark">
                                                                    <strong>Details</strong> <br>
                                                                    <ul class="list-unstyled">
                                                                        <li>Ticket created at: <span class="inlinesparkline pull-right">{{currentTicket.created_at}}</span></li>
                                                                    </ul>
                                                                </div>
                                                            </div><!--end .accordion-section-content-->
                                                        </div><!--end .accordion-section-->

                                                        <div class="accordion-section">
                                                            <a class="accordion-section-title" href="#accordion-2">Seen Doctor/Nurse <b style="color: white;background-color: #fcf01a;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">Current...</b></a>
                                                            <div id="accordion-2" class="accordion-section-content">
                                                                <label>
                                                                    Symptoms
                                                                    <b style="font-size: 10px">
                                                                        Input a single symptom, then hit enter before inputting another
                                                                    </b>
                                                                </label>
                                                                <div class="row" style="width: 100%">
                                                                    <div class="form-group">
                                                                        <input style="min-height: 150px;width: 100%" type="text" class="form-control" data-role="tagsinput" id="sympt">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <button class="btn btn-primary" @click="saveSymptoms">{{atDoctorButton}}</button>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="row" v-show="recommendAction">
                                                                        <div class="row">
                                                                            <a style="font-size: 10px" @click="recommendLab">Recommend Lab Test(s)</a>
                                                                            <a style="font-size: 10px" @click="prescribeMedication">Prescribe Medication</a>
                                                                        </div>
                                                                        <div v-show="successtoLab" class="alert alert-info">
                                                                            <h6>Success! Request sent. Send Cliend to Lab for Tests and Await response from Lab Technician</h6>
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
                                                            </div><!--end .accordion-section-content-->
                                                        </div><!--end .accordion-section-->

                                                        <div class="accordion-section">
                                                            <a class="accordion-section-title" href="#accordion-3">Seen a Lab Technician <b style="color: white;background-color: #f2534e;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">Pending...</b></a>
                                                            <div id="accordion-3" class="accordion-section-content">
                                                                <p>Mauris interdum fringilla augue vitae tincidunt. Curabitur vitae tortor id eros euismod ultrices. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent nulla mi, rutrum ut feugiat at, vestibulum ut neque? Cras tincidunt enim vel aliquet facilisis. Duis congue ullamcorper vehicula. Proin nunc lacus, semper sit amet elit sit amet, aliquet pulvinar erat. Nunc pretium quis sapien eu rhoncus. Suspendisse ornare gravida mi, et placerat tellus tempor vitae.</p>
                                                            </div><!--end .accordion-section-content-->
                                                        </div>
                                                        <div class="accordion-section">
                                                            <a class="accordion-section-title" href="#accordion-4">Seen Chemist<b style="color: white;background-color: #f2534e;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">Pending...</b></a>
                                                            <div id="accordion-4" class="accordion-section-content">
                                                                <p>Mauris interdum fringilla augue vitae tincidunt. Curabitur vitae tortor id eros euismod ultrices. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent nulla mi, rutrum ut feugiat at, vestibulum ut neque? Cras tincidunt enim vel aliquet facilisis. Duis congue ullamcorper vehicula. Proin nunc lacus, semper sit amet elit sit amet, aliquet pulvinar erat. Nunc pretium quis sapien eu rhoncus. Suspendisse ornare gravida mi, et placerat tellus tempor vitae.</p>
                                                            </div><!--end .accordion-section-content-->
                                                        </div><!--end .accordion-section-->
                                                    </div><!--end .accordion-->
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
            this.allActiveMethod()
        },
        data: function () {
            return{
                ticketModal: false,
                allActives: [],
                currentTicket: [],
                atDoctorButton: 'Save',
                sendtoLab: 'Send Client to Lab',
                afterSymptoms: false,
                symptoms: '',
                recommendAction: true,
                chooseLab: false,
                chooseMed: false,
                labTechnicians: [],
                selectedLabTech: '',
                successtoLab: false
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
                console.log(ticketid);
                axios.get(base_url+'/tickets/my-tickets/'+ticketid)
                    .then(function (response) {
                        inheritance.currentTicket = response.data;
                        this.labtechs();
                        //console.log(response.data.client.first_name);
                    }.bind(this));
                inheritance.ticketModal = true;
            },
            //close the above opened ticket.
            closeTicket: function () {
                var inheritance=this;
                inheritance.ticketModal=false;
            },
            //list all active tickets, thatbelong to thelogged in user
            allActiveMethod: function () {
                var inheritance = this;
                axios.get(base_url+'/tickets/my-tickets/all-active')
                    .then(function (response) {
                        inheritance.allActives = response.data;
                    }.bind(this));
            },
            //for doctor. save client's symptoms
            saveSymptoms: function () {
                var inheritance=this;
                inheritance.atDoctorButton = 'Saving...';
                var symptom = $('#sympt').val();
                var hticket_id = $('#hiddenTicketId').val();
                console.log(hticket_id);
                inheritance.symptoms = symptom;
                axios.get(base_url+'/tickets/my-tickets/save/symptoms?ticket_id='+hticket_id+'&status=pending&symptoms='+inheritance.symptoms)
                    .then(function (response) {
                        console.log(response);
                        inheritance.recommendAction = true;
                        inheritance.atDoctorButton = 'Save';
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
            //start a lab ticket.
            saveLab: function () {
              var inheritance = this;
              inheritance.sendtoLab = 'Sending request...';
              var tests = $('#tests').val();
              console.log(base_url+'/tickets/my-tickets/query/startlab?tests='+tests+'&technician='+inheritance.selectedLabTech+'&ticket_id='+inheritance.currentTicket.id);
              axios.get(base_url+'/tickets/my-tickets/query/startlab?tests='+tests+'&technician='+inheritance.selectedLabTech+'&ticket_id='+inheritance.currentTicket.id)
                  .then(function () {
                      inheritance.successtoLab = true
                      inheritance.sendtoLab = 'Sent';
                  }.bind(this))
            }

        }
    }
</script>
