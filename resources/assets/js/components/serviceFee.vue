<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="row">
        <div class="col-lg-8">
            <button class="btn btn-success" @click="addPreference">Add New Preference</button>
            <h5>Preferences</h5>
            <div class="row" v-for="preference in preferences">
                <div class="row" style="background-color: #f8f8f8;border: 2px solid #53CDF6">
                    <div class="row">
                        <label> Preference Name:</label>
                        {{preference.name}}
                        <i class="pull-right">
                            last edited on:{{preference.updated_at}}
                        </i>
                    </div>
                    <hr style="margin: 5px">
                    <div class="row">
                        {{preference.description}}
                    </div>
                    <hr style="margin: 5px">
                    <div class="row">
                        <a href="" class="pull-right btn btn-sm btn-success">Edit</a>
                        <a href="" class="pull-right btn btn-sm btn-success" style="margin-right: 10px">Open</a>
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
                                <!--<div class="form-group">
                                    <h6>Preference Name:</h6>
                                    <input type="text" class="form-control" v-model="preference.status">
                                </div>-->
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
                saveButton: 'save'
            }
        },
        methods:{
            allPreferences: function () {
                var inheritance = this;
                axios.get(base_url+'/preferences/all')
                    .then(function (response) {
                        inheritance.preferences = response.data;
                    }.bind(this))
            },
            addPreference: function () {
                var inheritance =this;
                inheritance.modal = true;
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
                axios.post(base_url+'/preferences/add', {name: inheritance.preference_name, description:inheritance.preference_description})
                    .then(function (response) {
                        console.log(response.data);
                        inheritance.preferences.push(response.data);
                        inheritance.saveButton = 'Save';
                        inheritance.allPreferences();
                    }.bind(this))
            }

        }
    }
</script>
