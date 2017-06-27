<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div>
        <div class="row" style="margin-bottom: 10px;margin-left: 0px">
            <label class="pull-left" style="font-size: 20px">
                Chemist Resources
            </label>
            <i class="fa fa-question-circle pull-right" style="color: darkblue;font-size: 20px;cursor: pointer" @click="helpOn()"></i>
        </div>
        <div class="row">
            <div class="row alert alert-info" v-show="help">
                <button type="button" class="close" @click="helpOff()">&times;</button>
                <header>Help information</header>
                <p>
                <ol>
                    <li>Chemist Resources are the medications that are available the facility</li>
                    <li>This Page displays all the Chemist Resources</li>
                    <li>The resources can be Edited by Clicking on the edit button.</li>
                </ol>
                </p>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-sm btn-primary" @click="openAddModal">Add New Chemist Resource</button>
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
                    <a class="btn btn-success" @click="openEditModal(resource)">Edit</a>
                </td>
            </tr>

            </tbody>
        </table>

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
        <transition name="modal">
            <div class="modal-mask" v-show="resourceEditModal">
                <div class="modal-wrapper">
                    <div class="modal-container" style="width: 50% !important">

                        <div class="modal-header">
                            <slot name="header">
                                <label>Edit Medicine</label>
                            </slot>
                        </div>

                        <div class="modal-body" style="max-height: 350px; overflow-y: scroll">
                            <slot name="body">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label>Resource Name</label>
                                    </div>
                                    <div class="col-md-8" style="margin-bottom: 10px">
                                        <input type="text" v-model="editResource.resource_name" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label>Resource Description</label>
                                    </div>
                                    <div class="col-md-8" style="margin-bottom: 10px">
                                        <textarea v-model="editResource.description" class="form-control">

                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label>Resource Unit Price</label>
                                    </div>
                                    <div class="col-md-8" style="margin-bottom: 10px">
                                        <input type="number" v-model="editResource.unit_price" class="form-control">
                                    </div>
                                </div>
                            </slot>
                        </div>

                        <div class="modal-footer">
                            <slot name="footer">
                                <button :class="{btn: classLoad, 'btn-success':classLoad, completed: afterSaveButton}" @click="editResources()">
                                    {{editButton}}
                                </button>
                                <button class="btn btn-danger" @click="closeEditModal()">
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
                resourceEditModal: false,
                newResource: [],
                saveButton: 'Save',
                afterSaveButton: false,
                classLoad: true,
                editResource: [],
                help: false,
                editButton: 'Update'
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
            openAddModal: function () {
                var inheritance= this;
                inheritance.resourceAddModal = true;
                inheritance.afterSaveButton = false;
            },
            closeAddModal: function () {
                var inheritance = this;
                inheritance.resourceAddModal = false;
            },
            openEditModal: function (resource) {
                var inheritance= this;
                inheritance.editResource = resource;
                inheritance.resourceEditModal = true;
                //inheritance.afterSaveButton = false;
            },
            closeEditModal: function () {
                var inheritance = this;
                inheritance.resourceEditModal = false;
            },
            allResources: function () {
                var inheritance = this;
                axios.get(base_url+'/resources/chemist/all')
                    .then(function (response) {
                        inheritance.resources = response.data;
                    }.bind(this));
                setTimeout(function() { $("#vueTable").DataTable(); }, 500);
            },
            editResources: function () {
                var inheritance = this;
                inheritance.saveButton = 'Updating...';
                console.log(inheritance.newResource);
                axios.post(base_url+'/resources/chemist/edit', {resource_name: inheritance.editResource.resource_name,
                    description: inheritance.editResource.description,
                    unit_price: inheritance.editResource.unit_price,
                    resource_id: inheritance.editResource.id})
                    .then(function (response) {
                        console.log(response.data);
                        inheritance.allResources();
                        inheritance.resourceEditModal =false;
                    }.bind(this))
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
                        inheritance.saveButton = 'Save Another';
                        //inheritance.afterSaveButton = true;
                        inheritance.newResource = [];
                        inheritance.allResources();
                    }.bind(this))
            }
        }
    }
</script>
