<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="row">
        <div class="col-lg-8">
            <button class="btn btn-success" @click="addPreference">Add New Nurse Station Resource</button>
            <h5>Nurse Station Resource(s)</h5>
            <div class="row" v-for="preference in preferences">
                <div class="row" style="background-color: #f8f8f8;border: 2px solid #53CDF6">
                    <div class="row">
                        <label> Resource Name:</label>
                        {{preference.resource_name}}
                        <i class="pull-right">
                            last edited on:{{preference.updated_at}}
                        </i>
                    </div>
                    <hr style="margin: 5px">
                    <div class="row">
                        {{preference.resource_description}}
                    </div>
                    <hr style="margin: 5px">
                    <div class="row">
                        <a class="pull-right btn btn-sm btn-success btn-custom" @click="openFeeModal(preference)">Edit</a>
                        <a class="pull-right btn btn-sm btn-success btn-custom" style="margin-right: 10px">Open</a>
                    </div>
                </div>
                <br>
            </div>
        </div>

        <transition name="modal" v-if="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container" style="width: 50% !important">

                        <div class="modal-header">
                            <slot name="header">
                                <label>Add New Preference</label>
                            </slot>
                        </div>

                        <div class="modal-body">
                            <slot name="body">
                                <div class="form-group">
                                    <h6>Preference Name:</h6>
                                    <input type="text" class="form-control" v-model="preference_name">
                                </div>
                                <div class="form-group">
                                    <h6>Preference Description:</h6>
                                    <input type="text" class="form-control" v-model="preference_description">
                                </div>
                            </slot>
                        </div>

                        <div class="modal-footer">
                            <slot name="footer">
                                <button class="btn btn-success" @click="savePreference">
                                    {{saveButton}}
                                </button>
                                <button class="btn btn-warning" @click="closeAddPreference">
                                    Cancel
                                </button>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <transition name="modal" v-if="serviceFee">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container" style="width: 50% !important">

                        <div class="modal-header">
                            <slot name="header">
                                <label>Edit Preference</label>
                            </slot>
                        </div>

                        <div class="modal-body">
                            <slot name="body">
                                <div class="form-group">
                                    <h6>Preference Name:</h6>
                                    <input type="text" class="form-control" v-model="editPreference.name">
                                </div>
                                <div class="form-group">
                                    <h6>Preference Description:</h6>
                                    <input type="text" class="form-control" v-model="editPreference.description">
                                </div>
                                <div class="form-group">
                                    <h6>Service Fee:</h6>
                                    <input type="text" class="form-control" v-model="editPreference.amount">
                                </div>
                            </slot>
                        </div>

                        <div class="modal-footer">
                            <slot name="footer">
                                <button class="btn btn-success" @click="saveEdit">
                                    {{editButton}}
                                </button>
                                <button class="btn btn-warning" @click="closeEdit">
                                    Cancel
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
            this.allPreferences();
        },
        data: function () {
            return{
                modal: false,
                preference_name: '',
                preference_description: '',
                preferences: [],
                saveButton: 'save',
                serviceFee: false,
                editPreference: [],
                editButton: 'Edit'
            }
        },
        methods:{
            allPreferences: function () {
                var inheritance = this;
                axios.get(base_url+'/resources/nurse-station/all')
                    .then(function (response) {
                        inheritance.preferences = response.data;
                    }.bind(this))
            },
            saveEdit: function () {
                var inheritance =this;
                inheritance.editButton = 'Updating';
                axios.post(base_url+'/preferences/edit', inheritance.editPreference)
                    .then(function (response) {
                        //console.log(response.data);
                        inheritance.allPreferences();
                        inheritance.editButton = 'Edit';
                    }.bind(this))

            },
            closeEdit: function () {
                var inheritance =this;
                inheritance.serviceFee = false;
            },
            addPreference: function () {
                var inheritance =this;
                inheritance.modal = true;
            },
            openFeeModal: function (preference) {
                var inheritance =this;
                inheritance.editPreference = preference;
            },
            closeAddPreference: function () {
                var inheritance =this;
                inheritance.modal = false;
                inheritance.preference_name = '';
                inheritance.preference_description = '';
            },
            savePreference: function (preference) {
                var inheritance = this;
                //console.log(preference.name);
                inheritance.saveButton = 'Saving...';
                console.log(base_url+'/preferences/add');
                axios.post(base_url+'/resources/nurse-station/new', {resource_name: inheritance.preference_name, resource_description:inheritance.preference_description})
                    .then(function (response) {
                        inheritance.preferences.push(response.data);
                        inheritance.saveButton = 'Save';
                        inheritance.allPreferences();
                    }.bind(this))
            }

        }
    }
</script>
