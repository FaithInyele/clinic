<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="row">
        <div class="col-lg-8">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>My List</strong> <br>
                It is Highly recommended you follow the list as is.<br>
            </div>
            <ul class="accordion-tabs-minimal">
                <li class="tab-header-and-content">
                    <a href="#" class="tab-link is-active">Out Patient(s)</a>
                    <div class="tab-content">
                        <div class="row">
                            <label>My Out-Patient Clients</label>
                            <input type="text" placeholder="Search Client..." class="input-sm pull-right">
                        </div>
                        <hr>
                        <div class="row" v-for="allActive in allActives" style="margin-left: 0px">
                            <div class="row" style="background-color: #f8f8f8;border: 2px solid #EA4A5A;border-radius: 10px;margin-left: 0px">
                                <div class="row">
                                    <label> Client Name:</label>
                                    {{ allActive.client.first_name}}, {{allActive.client.other_names}}
                                    <i class="pull-right">
                                        last updated on: {{allActive.progress.updated_at}}
                                    </i>
                                </div>
                                <hr style="margin: 5px">
                                <div class="row">
                                    Current Status:{{ allActive.progress.description}}
                                </div>
                                <hr style="margin: 5px">
                                <div class="row">
                                    <a class="pull-right btn btn-sm btn-success btn-custom" @click="admitClient(allActive.id)" style="margin-right: 10px">{{admitButton}}</a>
                                    <a class="pull-right btn btn-sm btn-success btn-custom" @click="openTicket(allActive.id)" style="margin-right: 10px">Open</a>
                                    <a class="pull-right btn btn-sm btn-success btn-custom" @click="endTicket(allActive.id)" style="margin-right: 10px">Close Ticket</a>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </li>
                <li class="tab-header-and-content">
                    <a href="#" class="tab-link">In Patient(s)</a>
                    <div class="tab-content">
                        <div class="row">
                            <label>My In-Patient Clients</label>
                            <input type="text" placeholder="Search Client..." class="input-sm pull-right">
                        </div>
                        <hr>
                        <inpatient></inpatient>
                    </div>
                </li>

            </ul>

        </div>
        <div class="col-lg-4">
            <h5>My Statistics</h5>
            <sidebar></sidebar>
        </div>

        <transition name="modal">
            <div class="modal-mask" v-show="ticketModal">
                <div class="modal-wrapper">
                    <div class="modal-container">

                        <div class="modal-header">
                            <slot name="header">
                                <label>At Doctor/Nurse</label>
                                <label :class="{'alert-success': statusSuccess,'alert-danger': statusError, 'pull-right': classLoad, 'alert-info': statusWarn}" style="text-align: right">Status: {{status}}</label>
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
                                            <img src="https://placehold.it/140x100" style="width: 100%; height: auto;border-radius: 10px">
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
                                    <div class="col-md-8" style="max-height: 400px;overflow-y: scroll">
                                        <ul class="accordion-tabs-minimal">
                                            <li class="tab-header-and-content">
                                                <a href="#" class="tab-link is-active">Ticket</a>
                                                <div class="tab-content">
                                                    <h6>Progress </h6>
                                                    <div class="accordion">
                                                        <!--first accordion-->
                                                        <div class="accordion-section">
                                                            <a class="accordion-section-title"  href="#accordion-1" style="color: #EA4A5A;text-shadow: none">
                                                                Assigned a Ticket
                                                                <b class="pull-right" v-if="currentTicket.progress" v-show="currentTicket.progress.level >= 0" style="color: white;background-color: green;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Done
                                                                </b>
                                                            </a>
                                                            <div id="accordion-1" class="accordion-section-content" v-if="currentTicket.assigned_by">
                                                                <h6><i class="fa fa-check"></i> This Ticket was created on: {{currentTicket.created_at}},</h6>
                                                                <h6><i class="fa fa-check"></i>and assigned by <i>{{currentTicket.assigned_by.last_name}}, {{currentTicket.assigned_by.first_name}}</i>  to  <i>{{currentTicket.assigned_to.last_name}}, {{currentTicket.assigned_to.first_name}}</i></h6>
                                                            </div><!--end .accordion-section-content-->
                                                        </div><!--end .accordion-section-->

                                                        <div class="accordion-section" style="max-height: 250px; overflow-y: scroll">
                                                            <a class="accordion-section-title" href="#accordion-2" style="color: #EA4A5A;text-shadow: none">
                                                                at Doctor/Nurse
                                                                <b class="pull-right" v-if="currentTicket.progress" v-show="currentTicket.progress.level <= 1" style="color: white;background-color: #f6fcab;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Current...
                                                                </b>
                                                                <b class="pull-right" v-if="currentTicket.progress" v-show="currentTicket.progress.level > 1" style="color: white;background-color: green;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Done
                                                                </b>
                                                            </a>
                                                            <div id="accordion-2" class="accordion-section-content">
                                                                <div :class="{'col-md-8': classLoad}">
                                                                    <div>
                                                                        Input Symptoms and general Observations, if any.<br>
                                                                        <b style="font-size: 8px">
                                                                            [Input a single symptom, then hit enter before inputting another]
                                                                        </b>
                                                                    </div>

                                                                    <div class="form-group" v-if="currentTicket.progress" :class="{completed: currentTicket.progress.level >= 2}">
                                                                        <input-tag placeholder="Add Symptoms" class="input-sm"  :on-change="saveSymptoms" :tags="currentTicket.tags"></input-tag>
                                                                    </div>
                                                                    <hr style="margin: 0px">
                                                                    <div v-show="recommendAction">
                                                                        <div class="">
                                                                            <a style="font-size: 10px" @click="recommendLab">Recommend Lab Test(s)</a> |
                                                                            <a style="font-size: 10px " @click="prescribeMedication">Prescribe Medication</a>  |
                                                                        </div>
                                                                        <div class="row" v-show="chooseLab" style="font-size: 12px">
                                                                            <div>
                                                                                Select Lab Technician
                                                                            </div>
                                                                            <h5 v-if="currentTicket.lab_technician">
                                                                                {{currentTicket.lab_technician.first_name}}
                                                                            </h5>

                                                                            <select class="form-control input-sm" v-if="!currentTicket.lab_technician" v-model="selectedLabTech">
                                                                                <option selected disabled value="" >-Select a Lab Technician to Assign-</option>
                                                                                <option v-for="labTechnician in labTechnicians" :value="labTechnician.id">{{labTechnician.first_name}}</option>
                                                                            </select>
                                                                            <div>
                                                                                <b style="font-size: 10px">
                                                                                    <div class="form-group completed" v-if="currentTicket.progress">
                                                                                        <input-tag id="test_tags" placeholder="Tests Added will be displayed here"  :on-change="saveLab" :tags="test_tags"></input-tag>
                                                                                    </div>                                                                                    </b>
                                                                            </div>
                                                                            Search Required Lab Tests:<br>
                                                                            <input type="text" class="form-control input-sm" v-model="searchTest">
                                                                            <div class="alert alert-warning" v-show="noResults">
                                                                                No results found.
                                                                            </div>
                                                                            <div class="row" v-for="result in results">
                                                                                <div class="row">
                                                                                    <div class="col-sm-4">
                                                                                        <label>Name</label><br>
                                                                                        {{result.resource_name}}
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <label>Unit Price</label><br>
                                                                                        {{result.unit_price}}
                                                                                    </div>
                                                                                    <div class="col-sm-2" style="padding-top: 10px">
                                                                                        <button v-if="result && currentTicket.progress" v-show="result.status==false" :class="{btn:classLoad, 'btn-sm':classLoad, 'btn-success':classLoad, completed: currentTicket.progress.level >= 2}" @click="addTest(result)">Add</button>
                                                                                        <button v-if="result && currentTicket.progress" v-show="result.status==true" :class="{btn:classLoad, 'btn-sm':classLoad,  'btn-danger':classLoad, completed: currentTicket.progress.level >= 2}" @click="removeTest(result)">Remove</button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <hr style="margin: 5px !important;">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group" v-if="currentTicket.progress" :class="{completed: currentTicket.progress.level >= 2 || test_tags == ''}">
                                                                                <button class="btn btn-sm btn-primary pull-right" @click="sendLab">{{sendtoLab}}</button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row" v-show="chooseMed" style="font-size: 12px">
                                                                            <label>
                                                                                Prescription
                                                                                <b style="font-size: 10px">
                                                                                    Input a single prescription, then press enter before inputting another
                                                                                </b>
                                                                            </label>

                                                                            <div v-if="currentTicket.progress" :class="{completed: classLoad}">
                                                                                <input-tag :on-change="saveP" :tags="prescription_tags"></input-tag>
                                                                            </div>

                                                                            <input type="text" class="form-control input-sm" v-model="searchPrescription">

                                                                            <div class="alert alert-warning" v-show="noPrescriptionResults">
                                                                                No results found.
                                                                            </div>
                                                                            <div class="row" v-for="result in prescriptionResults">
                                                                                <div class="row">
                                                                                    <div class="col-sm-4">
                                                                                        <label>Name</label><br>
                                                                                        {{result.resource_name}}
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <label>Unit Price</label><br>
                                                                                        {{result.unit_price}}
                                                                                    </div>
                                                                                    <div class="col-sm-2" style="padding-top: 10px">
                                                                                        <button v-if="result && currentTicket.progress" v-show="result.status==false" :class="{btn:classLoad, 'btn-sm':classLoad, 'btn-success':classLoad, completed: currentTicket.progress.level >= 2}" @click="addPrescription(result)">Add</button>
                                                                                        <button v-if="result && currentTicket.progress" v-show="result.status==true" :class="{btn:classLoad, 'btn-sm':classLoad,  'btn-danger':classLoad, completed: currentTicket.progress.level >= 2}" @click="removePrescription(result)">Remove</button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <hr style="margin: 5px !important;">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group pull-right">
                                                                                <button v-if="currentTicket.progress" :class="{btn: classLoad, 'btn-primary':classLoad, 'btn-sm': classLoad, completed:currentTicket.progress.level >= 2}" @click="submitP()">{{toChemist}}</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-4">
                                                                    <h6><b><u>Previous Instance Summary:</u></b></h6>
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
                                                            <a class="accordion-section-title" href="#accordion-3" style="color: #EA4A5A;text-shadow: none">
                                                                Seen a Lab Technician
                                                                <b class="pull-right" v-if="currentTicket.progress" v-show="currentTicket.progress.level < 2" style="color: white;background-color: #f2534e;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Pending...
                                                                </b>
                                                                <b class="pull-right" v-if="currentTicket.progress" v-show="currentTicket.progress.level == 2" style="color: white;background-color: #f6fcab;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Current...
                                                                </b>
                                                                <b class="pull-right" v-if="currentTicket.progress" v-show="currentTicket.progress.level > 2" style="color: white;background-color: green;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Done
                                                                </b>
                                                            </a>
                                                            <div id="accordion-3" class="accordion-section-content">
                                                                <div class="row" v-if="currentTicket.tests">
                                                                    <div v-for="test in currentTicket.tests">
                                                                        <div class="row">
                                                                            <div class="col-md-2" style="text-align: right">
                                                                                <label>{{test.details.resource_name}}:</label>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <i v-if="test.result">{{test.result}}</i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!--end .accordion-section-content-->
                                                        </div>
                                                        <div class="accordion-section">
                                                            <a class="accordion-section-title" href="#accordion-4" style="color: #EA4A5A;text-shadow: none">
                                                                Seen Chemist
                                                                <b class="pull-right" v-if="currentTicket.progress" v-show="currentTicket.progress.level < 3" style="color: white;background-color: #f2534e;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Pending...
                                                                </b>
                                                                <b class="pull-right" v-if="currentTicket.progress" v-show="currentTicket.progress.level == 3" style="color: white;background-color: #f6fcab;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Current...
                                                                </b>
                                                                <b class="pull-right" v-if="currentTicket.progress" v-show="currentTicket.progress.level > 3" style="color: white;background-color: green;border-radius: 5px;margin-left: 10px;padding-left: 3px;padding-right: 3px">
                                                                    Done
                                                                </b>
                                                            </a>
                                                            <div id="accordion-4" :class="{'accordion-section-content':classLoad, completed:currentTicket.progress.level != 3}" v-if="currentTicket.progress" >
                                                                <label>
                                                                    Prescription
                                                                    <b style="font-size: 10px">
                                                                        Input a single prescription, then press enter before inputting another
                                                                    </b>
                                                                </label>

                                                                <div v-if="currentTicket.progress" :class="{completed: classLoad}">
                                                                    <input-tag :on-change="saveP" :tags="prescription_tags"></input-tag>
                                                                </div>

                                                                <input type="text" class="form-control input-sm" v-model="searchPrescription">

                                                                <div class="alert alert-warning" v-show="noPrescriptionResults">
                                                                    No results found.
                                                                </div>
                                                                <div class="row" v-for="result in prescriptionResults">
                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <label>Name</label><br>
                                                                            {{result.resource_name}}
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label>Unit Price</label><br>
                                                                            {{result.unit_price}}
                                                                        </div>
                                                                        <div class="col-sm-2" style="padding-top: 10px">
                                                                            <button v-if="result" v-show="result.status==false" class="btn btn-sm btn-success" @click="addPrescription(result)">Add</button>
                                                                            <button v-if="result" v-show="result.status==true" class="btn btn-sm btn-danger" @click="removePrescription(result)">Remove</button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <hr style="margin: 5px !important;">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group pull-right">
                                                                    <button v-if="currentTicket.progress" :class="{btn: classLoad, 'btn-primary':classLoad, 'btn-sm': classLoad, completed:currentTicket.progress.level >= 4}" @click="submitP()">{{toChemist}}</button>
                                                                </div>
                                                            </div><!--end .accordion-section-content-->
                                                        </div><!--end .accordion-section-->
                                                    </div><!--end .accordion-->                                                </div>
                                            </li>
                                            <li class="tab-header-and-content">
                                                <a href="#" class="tab-link">Pre-examination</a>
                                                <div class="tab-content">
                                                    <h5>Pre-Examination Results</h5>
                                                    <div class="row" v-if="currentTicket.pre_examination">
                                                        <div v-for="examination in currentTicket.pre_examination">
                                                            <div class="row" style="background-color: #f8f8f8;border: 2px solid #53CDF6;margin-top: 10px">
                                                                <div class="row">
                                                                    <label> Examination:</label>
                                                                    {{examination.details.resource_name}}
                                                                    <i class="pull-right">
                                                                        Performed on:{{examination.updated_at}}
                                                                    </i>
                                                                </div>
                                                                <hr style="margin: 5px">
                                                                <div class="row">
                                                                    Result: {{examination.result}}
                                                                </div>
                                                                <hr style="margin: 5px">
                                                            </div>
                                                            <label></label>
                                                            <i class="pull-right"></i>
                                                        </div>
                                                    </div>                                                </div>
                                            </li>
                                            <li class="tab-header-and-content">
                                                <a href="#" class="tab-link">Special Medical Conditions</a>
                                                <div class="tab-content" v-if="currentTicket.special_case">
                                                    <h6>Special Medical Conditions<i class="pull-right">Last Updated on:{{currentTicket.special_case.updated_at}}</i> </h6>
                                                    <div class="row pullquote-left">
                                                        <div class="row">
                                                            {{currentTicket.special_case.description}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="tab-header-and-content">
                                                <a href="#" class="tab-link">History</a>
                                                <div class="tab-content">
                                                    <h6>History</h6>
                                                </div>
                                            </li>
                                            <li class="tab-header-and-content">
                                                <a href="#" class="tab-link">Consultation</a>
                                                <div class="tab-content">
                                                    <label>Consult with another Doctor, concerning the Client</label>
                                                    <h6>Select Doctor</h6>
                                                    <select class="input-sm" v-model="chat_doctor">
                                                        <option value="">-Select Doctor-</option>
                                                        <option v-if="allDocs" v-for="doc in allDocs" :value="doc.id">{{doc.last_name}} , {{doc.first_name}}</option>
                                                    </select>
                                                    <button class="btn btn-sm" @click="startChat">Proceed</button>
                                                    <div class="row" style="margin: 0px;padding-top: 10px">
                                                        <div class="row chat-window" id="chat_window_1" style="margin: 0px">
                                                            <div class="col-xs-12 col-md-12">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-body msg_container_base">
                                                                        <div v-if="currentChat" v-for="chat in currentChat.messages" :class="{row: classLoad, 'msg_container':classLoad, 'base_sent':chat.me == 'yes', 'base_receive':chat.me == 'no'}">
                                                                            <div class="col-md-2 col-xs-2 avatar" v-if="chat.me == 'no'">
                                                                                <img src="http://www.bitrebels.com/wp-content/uploads/2011/02/Original-Facebook-Geek-Profile-Avatar-1.jpg" class=" img-responsive ">
                                                                            </div>
                                                                            <div class="col-md-10 col-xs-10">
                                                                                <div :class="{'messages':classLoad, 'msg_sent':chat.me == 'yes', 'msg_receive':chat.me == 'no'}">
                                                                                    <p>{{chat.message}}</p>
                                                                                    <time datetime="2009-11-13T20:00">{{chat.user.first_name}} • {{chat.created_at}}</time>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-2 col-xs-2 avatar" v-if="chat.me == 'yes'">
                                                                                <img src="http://www.bitrebels.com/wp-content/uploads/2011/02/Original-Facebook-Geek-Profile-Avatar-1.jpg" class=" img-responsive ">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel-footer">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control input-sm chat_input" v-model="chatMessage" placeholder="Write your message here..." />
                                                                            <span class="input-group-btn">
                                                                                    <button class="btn btn-primary btn-sm" @click="sendMessage">Send</button>
                                                                                </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="btn-group dropup">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                                <span class="glyphicon glyphicon-cog"></span>
                                                                <span class="sr-only">Toggle Dropdown</span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a href="#" id="new_chat"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>
                                                                <li><a href="#"><span class="glyphicon glyphicon-list"></span> Ver outras</a></li>
                                                                <li><a href="#"><span class="glyphicon glyphicon-remove"></span> Fechar Tudo</a></li>
                                                                <li class="divider"></li>
                                                                <li><a href="#"><span class="glyphicon glyphicon-eye-close"></span> Invisivel</a></li>
                                                            </ul>
                                                        </div>

                                                    </div>                                                </div>
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                            </slot>
                        </div>

                        <div class="modal-footer">
                            <slot name="footer">

                                <button class="btn btn-sm btn-warning" @click="closeTicket">
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
    import InputTag from 'vue-input-tag';

    export default {
        props:'openTicket',
        mounted: function() {
            console.log('Component mounted.');
            this.allActiveMethod();
        },
        components:{InputTag},
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
                tagsArray: [],
                recommendAction: true,
                chooseLab: false,
                chooseMed: false,
                chooseAdmit: false,
                labTechnicians: [],
                selectedLabTech: '',
                successtoLab: false,
                modalLoading: true,
                baseUrl: base_url,
                status: 'No Operation',
                test_tags: [],
                test_tagsId: [],
                prescription_tags: [],
                prescription_tagsId: [],
                statusError: false,
                statusSuccess: false,
                classLoad: true,
                statusWarn: false,
                searchTest: '',
                results: [],
                prescriptionResults: [],
                noResults:false,
                searchPrescription: '',
                noPrescriptionResults: false,
                allDocs: [],
                chat_doctor: '',
                currentChat: [],
                chatMessage: '',
                admitButton: 'Admit Client'
            }
        },
        watch: {
            searchTest: function () {
                this.results = [];
                this.findTests();
            },
            searchPrescription: function () {
                this.prescriptionResults = [];
                this.findPrescription();
            }
        },
        methods:{
            addTest: function (result) {
                var inheritance = this;
                inheritance.test_tags.push(result.resource_name);
                inheritance.test_tagsId.push(result.id);
                inheritance.saveLab();
                inheritance.findTests();
            },
            activeDocs: function () {
                var inheritance=this;
                axios.get(base_url+'/tickets/my-tickets/all/doctors')
                    .then(function (response) {
                        inheritance.allDocs = response.data;
                    }.bind(this));
            },
            removeTest: function (result) {
                var inheritance = this;
                let index = inheritance.test_tags.indexOf(result.resource_name);
                this.test_tags.splice(index, 1);
                let index2 = inheritance.test_tagsId.indexOf(result.id);
                this.test_tagsId.splice(index, 1);
                inheritance.saveLab();
                inheritance.findTests();
            },
            addPrescription: function (result) {
                var inheritance = this;
                inheritance.prescription_tags.push(result.resource_name);
                inheritance.prescription_tagsId.push(result.id);
                inheritance.saveP();
                inheritance.findPrescription();
            },
            removePrescription: function (result) {
                var inheritance = this;
                let index = inheritance.prescription_tags.indexOf(result.resource_name);
                this.prescription_tags.splice(index, 1);
                let index2 = inheritance.prescription_tagsId.indexOf(result.id);
                this.prescription_tagsId.splice(index, 1);
                inheritance.saveP();
                inheritance.findPrescription();
            },
            //search thru lab tests
            findTests: _.debounce(function () {
                var inheritance= this;
                var labdatas_id = inheritance.currentTicket.lab_datas !=null ? inheritance.currentTicket.lab_datas.id : 'null';
                console.log(base_url+'/search/test?q='+inheritance.searchTest+'&labdatas_id='+labdatas_id);
                if (inheritance.searchTest.length >=1){
                    axios.get(base_url+'/search/test?q='+inheritance.searchTest+'&labdatas_id='+labdatas_id)
                        .then(function (response) {
                            console.log(response.data);
                            if (response.data.length == 0){
                                inheritance.noResults = true;
                            }else {
                                inheritance.noResults = false;
                            }
                            inheritance.results = response.data;
                        }.bind(this))
                }
            }, 500),
            findPrescription: _.debounce(function () {
                var inheritance = this;
                console.log('huh');
                var prescription_id = inheritance.currentTicket.prescription !=null ? inheritance.currentTicket.prescription.id : 'null';
                console.log(base_url+'/search/prescription?q='+inheritance.searchPrescription+'&prescription_id='+prescription_id);
                if (inheritance.searchPrescription.length >=1){
                    axios.get(base_url+'/search/prescription?q='+inheritance.searchPrescription+'&prescription_id='+prescription_id)
                        .then(function (response) {
                            console.log(response);
                            if (response.data.length == 0){
                                inheritance.noPrescriptionResults = true;
                            }else {
                                inheritance.noPrescriptionResults = false;
                            }
                            inheritance.prescriptionResults = response.data;
                        }.bind(this))
                }
            }, 500),
            //get all Active Lab Technicians
            labtechs: function () {
                var inheritance = this;
                axios.get(base_url+'/tickets/my-tickets/query/labtechs')
                    .then(function (response) {
                        inheritance.labTechnicians = response.data;
                    }.bind(this))
                    .catch(function (error) {
                        inheritance.status = 'There was an Error while Processing your Request';
                        inheritance.statusError = true;
                    });
            },
            //open a specific ticket, for docs/nurses
            openTicket: function (ticketid) {
                var inheritance = this;
                inheritance.modalLoading = true;
                axios.get(base_url+'/tickets/my-tickets/'+ticketid)
                    .then(function (response) {
                        inheritance.currentTicket = response.data;
                        inheritance.activeDocs();
                        inheritance.labtechs();
                        inheritance.modalLoading = false;
                        if(response.data.progress.description == 'Client at Lab'){
                            inheritance.status = "Currently awaiting response from Lab";
                            inheritance.statusWarn = true;
                        }
                        inheritance.updateTestTags();
                        inheritance.updatePrescriptionTags();
                        setTimeout(function() { $('select').tagsinput('refresh'); }, 500);
                    }.bind(this))
                    .catch(function (error) {
                        inheritance.status = 'There was an Error while Processing your Request';
                        inheritance.statusError = true;
                    });
                inheritance.ticketModal = true;
            },
            updateTestTags: function () {
                var inheritance=this;
                if (inheritance.currentTicket.ticket_tags != null){
                    inheritance.test_tags = inheritance.currentTicket.ticket_tags;
                    inheritance.test_tagsId = inheritance.currentTicket.test_tagsId;
                }
            },
            updatePrescriptionTags: function () {
                var inheritance = this;
                if (inheritance.currentTicket.medicine_tags != null){
                    inheritance.prescription_tags = inheritance.currentTicket.medicine_tags;
                    inheritance.prescription_tagsId = inheritance.currentTicket.medicine_tagsId;
                }
            },
            //close the above opened ticket.
            closeTicket: function () {
                var inheritance=this;
                if (confirm('Are you sure you want to close Current Ticket?')){
                    inheritance.ticketModal=false;
                    inheritance.status = 'No Operation';
                    inheritance.currentTicket = [];
                    inheritance.allActiveMethod();
                }
            },
            //list all active tickets, that belong to the logged in user
            allActiveMethod: function () {
                var inheritance = this;
                axios.get(base_url+'/tickets/my-tickets/all-active')
                    .then(function (response) {
                        inheritance.allActives = response.data;
                    }.bind(this))
                    .catch(function (error) {
                        inheritance.status = 'There was an Error while Processing your Request';
                        inheritance.statusError = true;
                    });
            },
            //revert all status during api call
            revertStatus: function () {
                var inheritance = this;
                inheritance.statusSuccess = false;
                inheritance.statusError = false;
                inheritance.statusWarn = false;
            },
            //for doctor. save client's symptoms
            saveSymptoms: function () {
                var inheritance=this;
                console.log(inheritance.tagsArray);
                inheritance.revertStatus();
                inheritance.atDoctorButton = 'Saving...';
                inheritance.status = 'Saving Symptoms...';
                // var symptom = $('#sympt').val();
                //console.log(symptom);
                var hticket_id = $('#hiddenTicketId').val();
                console.log(hticket_id);
                //inheritance.symptoms = symptom;
                axios.get(base_url+'/tickets/my-tickets/save/symptoms?ticket_id='+hticket_id+'&status=pending&symptoms='+inheritance.currentTicket.tags)
                    .then(function (response) {
                        console.log(response);
                        inheritance.recommendAction = true;
                        inheritance.atDoctorButton = 'Save Symptoms';
                        inheritance.status = 'Symptoms Successfully Saved';
                        inheritance.statusSuccess = true;
                    }.bind(this))
                    .catch(function (error) {
                        inheritance.status = 'There was an Error while Processing your Request';
                        inheritance.statusError = true;
                    });
            },
            recommendLab: function () {
                var inheritance=this;
                inheritance.chooseLab=true;
                inheritance.chooseMed=false;
                inheritance.chooseAdmit = false;
            },
            admitClient: function (ticket_id) {
              var inheritance = this;
              console.log(ticket_id);
              inheritance.admitButton = 'Admitting...';
              console.log(base_url+'/admit/'+ticket_id);
              axios.get(base_url+'/inpatient/admit/'+ticket_id)
                  .then(function (response) {
                        inheritance.admitButton = 'Admitted';
                        inheritance.allActiveMethod();
                  })
                  .catch(function (error) {

                  });
            },
            prescribeMedication: function () {
                var inheritance = this;
                inheritance.chooseLab=false;
                inheritance.chooseMed=true;
                inheritance.chooseAdmit = false;
            },
            showAdmit: function () {
                var inheritance = this;
                inheritance.chooseLab=false;
                inheritance.chooseMed=false;
                inheritance.chooseAdmit = true;
            },
            //save the prescriptions given
            saveP: function () {
                var inheritance = this;
                inheritance.revertStatus();
                inheritance.status = 'Saving Prescription(s)';
                inheritance.savePrescription = "Saving...";
                var prescription_id = inheritance.currentTicket.prescription != null ? inheritance.currentTicket.prescription.id : 'none';
                console.log(base_url+'/tickets/my-tickets/query/startchemist?med='+inheritance.prescription_tagsId+'&ticket_id='+inheritance.currentTicket.id+'&prescription_id='+prescription_id);
                axios.get(base_url+'/tickets/my-tickets/query/startchemist?med='+inheritance.prescription_tagsId+'&ticket_id='+inheritance.currentTicket.id+'&prescription_id='+prescription_id)
                    .then(function (response) {
                        inheritance.status = 'Prescription(s) Successfully Saved';
                        inheritance.statusSuccess = true;
                        inheritance.statusError = false;
                        inheritance.savePrescription = "Save";
                        inheritance.currentTicket.prescription = response.data;
                        //inheritance.openTicket(inheritance.currentTicket.id);
                    }.bind(this))
                    .catch(function (error) {
                        inheritance.status = 'There was an Error while Processing your Request';
                        inheritance.statusError = true;
                    });
            },
            //save all prescriptions and close chapter
            submitP: function () {
                var inheritance = this;
                inheritance.revertStatus();
                inheritance.status = 'Submitting Prescription(s)';
                inheritance.toChemist = 'Submitting';
                console.log(base_url+'/atchemist/submit/'+inheritance.currentTicket.prescription.id+'?ticket_id='+inheritance.currentTicket.id);
                axios.get(base_url+'/atchemist/submit/'+inheritance.currentTicket.prescription.id+'?ticket_id='+inheritance.currentTicket.id)
                    .then(function (response) {
                        inheritance.status = 'Prescription(s) Successfully Submitted';
                        inheritance.statusSuccess =true;
                        //inheritance.openTicket(inheritance.currentTicket.id);
                        inheritance.toChemist = 'Submit to Chemist';
                    }.bind(this))
                    .catch(function (error) {
                        inheritance.status = 'There was an Error while Processing your Request';
                        inheritance.statusError = true;
                    });
            },
            //start a lab ticket.
            saveLab: function () {
                var inheritance = this;
                inheritance.revertStatus();
                var ticket_id = inheritance.currentTicket.id;
                var labdatas_id = inheritance.currentTicket.lab_datas !=null ? inheritance.currentTicket.lab_datas.id : null;
                //inheritance.selectedLabTech = inheritance.selectedLabTech != '' ? inheritance.selectedLabTech:inheritance.currentTicket.lab_technician.id;
                //inheritance.sendtoLab = 'Saving Lab Test(s)...';
                inheritance.status = 'Saving Tests...';
                var tests = $('#tests').val();
                console.log(base_url+'/tickets/my-tickets/query/startlab?tests='+inheritance.test_tagsId+'&technician='+inheritance.selectedLabTech+'&ticket_id='+inheritance.currentTicket.id+'&labdatas_id='+labdatas_id);
                axios.get(base_url+'/tickets/my-tickets/query/startlab?tests='+inheritance.test_tagsId+'&technician='+inheritance.selectedLabTech+'&ticket_id='+inheritance.currentTicket.id+'&labdatas_id='+labdatas_id)
                    .then(function (response) {
                        inheritance.currentTicket.lab_datas = response.data;
                        inheritance.status = 'Tests Successfully Saved';
                        inheritance.statusSuccess = true;
                        //inheritance.sendtoLab = 'Sent';
                        //inheritance.openTicket(ticket_id);
                    }.bind(this))
                    .catch(function (error) {
                        inheritance.status = 'There was an Error while Processing your Request';
                        inheritance.statusError = true;
                    });
            },
            //send client to lab
            sendLab: function () {
                var inheritance = this;
                inheritance.revertStatus();
                inheritance.status = 'Sending Client data to Lab...';
                inheritance.sendtoLab = 'Sending...';
                //console.log(base_url+'/tickets/my-tickets/query/sendlab?labdatas_id='+inheritance.currentTicket.lab_datas.id+'&ticket_id='+inheritance.currentTicket.id);
                //inheritance.saveLab();
                axios.get(base_url+'/tickets/my-tickets/query/sendlab?labdatas_id='+inheritance.currentTicket.lab_datas.id+'&ticket_id='+inheritance.currentTicket.id)
                    .then(function (response) {
                        inheritance.currentTicket.progress = response.data;
                        inheritance.sendtoLab = 'Send Client to Lab';
                        inheritance.status = 'Data Successfully Send to Lab';
                        inheritance.statusSuccess = true;
                    }.bind(this))
                    .catch(function (error) {
                        inheritance.status = 'There was an Error while Processing your Request';
                        inheritance.statusError = true;
                    });
            },
            //close a ticket completely
            endTicket: function (ticket_id) {
                var inheritance = this;
                if (confirm('Are you sure to close Ticket?')){
                    axios.get(base_url+'/tickets/my-tickets/close/'+ticket_id)
                        .then(function (response) {
                            inheritance.allActiveMethod();
                        }.bind(this))

                }
            },
            startChat: function () {
                var inheritance = this;
                axios.get(base_url+'/chat/start?ticket_id='+inheritance.currentTicket.id+'&consultant_id='+inheritance.chat_doctor)
                    .then(function (response) {
                        console.log(response.data.messages[0]);
                        inheritance.currentChat = response.data;
                        console.log('huh');
                    }.bind(this));
                //console.log(inheritance.chat_doctor);
            },
            sendMessage: function () {
                var inheritance = this;
                //console.log(inheritance.chatMessage);
                axios.post(base_url+'/chat/newmessage', {consultant_id: inheritance.currentChat.id,message: inheritance.chatMessage, message_to:inheritance.currentChat.originator.id})
                    .then(function (response) {
                        inheritance.currentChat.messages.push(response.data);
                    }.bind(this))
            }
        }
    }
</script>
