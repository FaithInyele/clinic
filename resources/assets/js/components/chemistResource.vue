<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div>
        <div class="form-group">
            <button class="btn btn-sm btn-primary" @click="openAddModal">Add New Lab Resource</button>
        </div>
        <table class="table table-striped table-bordered dt-responsive" id="vueTable"
               cellspacing="0" width="100%" style="font-size: 10px">
            <thead>
            <tr>
                <th>Medicine Name</th>
                <th>Description</th>
                <th>Unit Price</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="resource in resources">
                <td>{{ resource.resource_name}}</td>
                <td>{{ resource.description}}</td>
                <td>{{ resource.unit_price}}</td>
                <td>
                    <a class="btn btn-success" @click="viewClient(client)">Open</a>
                    <a class="btn btn-success" @click="editClient(client)">Edit</a>
                </td>
            </tr>

            </tbody>
        </table>

        <!--<transition name="modal">
            <div class="modal-mask" v-show="clientModal">
                <div class="modal-wrapper">
                    <div class="modal-container" v-if="currentClient.id" style="width: 50% !important">

                        <div class="modal-header">
                            <slot name="header">
                                <label>
                                    {{currentClient.first_name}}, {{currentClient.other_names}}
                                </label>
                                <h6 class="pull-right">
                                    last updated on: {{currentClient.updated_at}}
                                </h6>
                            </slot>
                        </div>

                        <div class="modal-body" style="max-height: 350px; overflow-y: scroll">
                            <slot name="body">
                                <hr>
                                <label>Personal Information</label>
                                <div class="form-group">
                                    <h6>First Name:</h6>
                                    <input type="text" v-model="currentClient.first_name" class="form-control">
                                </div>

                                <div class="form-group">
                                    <h6>Other Name(s):</h6>
                                    <input type="text" v-model="currentClient.other_names" class="form-control">
                                </div>

                                <div class="form-group">
                                    <h6>Date of Birth:</h6>
                                    <input type="text" v-model="currentClient.yob" class="form-control">
                                </div>
                                <hr>
                                <label>Contact Information</label>

                                <div class="form-group">
                                    <h6>Phone Number:</h6>
                                    <input type="text" v-model="currentClient.phone" class="form-control">
                                </div>

                                <div class="form-group">
                                    <h6>Email Address:</h6>
                                    <input type="text" v-model="currentClient.email" class="form-control">
                                </div>

                                <div class="form-group">
                                    <h6>Physical Address:</h6>
                                    <input type="text" v-model="currentClient.address" class="form-control">
                                </div>
                                <hr>
                                <label>Next-of-Keen Information</label>

                                <div class="form-group">
                                    <h6>Keen Type:</h6>
                                    <input type="text" v-model="currentClient.keen_type" class="form-control">
                                </div>

                                <div class="form-group">
                                    <h6>Keen Name:</h6>
                                    <input type="text" v-model="currentClient.keen_name" class="form-control">
                                </div>

                                <div class="form-group">
                                    <h6>Keen Contacts:</h6>
                                    <input type="text" v-model="currentClient.keen_contact" class="form-control">
                                </div>

                            </slot>
                        </div>

                        <div class="modal-footer">
                            <slot name="footer">
                                <button class="btn btn-success" @click="editClient(currentClient)">
                                    {{updateButton}}
                                </button>
                                <button class="btn btn-danger" @click="closeModal()">
                                    Cancel
                                </button>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </transition>-->

        <transition name="modal">
            <div class="modal-mask" v-show="resourceAddModal">
                <div class="modal-wrapper">
                    <div class="modal-container" style="width: 50% !important">

                        <div class="modal-header">
                            <slot name="header">
                                <label>Add New Medicine</label>
                            </slot>
                        </div>

                        <div class="modal-body" style="max-height: 350px; overflow-y: scroll">
                            <slot name="body">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label>Resource Name</label>
                                    </div>
                                    <div class="col-md-8" style="margin-bottom: 10px">
                                        <input type="text" v-model="newResource.resource_name" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label>Resource Description</label>
                                    </div>
                                    <div class="col-md-8" style="margin-bottom: 10px">
                                        <textarea v-model="newResource.description" class="form-control">

                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label>Resource Unit Price</label>
                                    </div>
                                    <div class="col-md-8" style="margin-bottom: 10px">
                                        <input type="number" v-model="newResource.unit_price" class="form-control">
                                    </div>
                                </div>
                            </slot>
                        </div>

                        <div class="modal-footer">
                            <slot name="footer">
                                <button :class="{btn: classLoad, 'btn-success':classLoad, completed: afterSaveButton}" @click="saveResource()">
                                    {{saveButton}}
                                </button>
                                <button class="btn btn-danger" @click="closeAddModal()">
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
            this.allResources();
        },
        data: function () {
            return{
                resources: [],
                resourceAddModal: false,
                newResource: [],
                saveButton: 'Save',
                afterSaveButton: false,
                classLoad: true
            }
        },
        methods:{
            openAddModal: function () {
                var inheritance= this;
                inheritance.resourceAddModal = true;
                inheritance.afterSaveButton = false;
            },
            closeAddModal: function () {
                var inheritance = this;
                inheritance.resourceAddModal = false;
            },
            allResources: function () {
                var inheritance = this;
                axios.get(base_url+'/resources/chemist/all')
                    .then(function (response) {
                        inheritance.resources = response.data;
                    }.bind(this));
                setTimeout(function() { $("#vueTable").DataTable(); }, 500);
            },
            saveResource: function () {
                var inheritance = this;
                inheritance.saveButton = 'Saving...';
                console.log(inheritance.newResource);
                axios.post(base_url+'/resources/chemist/new', {resource_name: inheritance.newResource.resource_name,
                    description: inheritance.newResource.description,
                    unit_price: inheritance.newResource.unit_price })
                    .then(function (response) {
                        console.log(response.data);
                        inheritance.saveButton = 'Saved';
                        inheritance.afterSaveButton = true;
                        inheritance.allResources();
                    }.bind(this))
            }
        }
    }
</script>
