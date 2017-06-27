<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div>
        <div class="form-group">
            <button class="btn btn-sm btn-primary" @click="openAddModal">Add New Lab Resource</button>
        </div>
        <table class="table table-striped table-bordered dt-responsive" id="vueTable"
               cellspacing="0" width="100%" style="font-size: 10px">
            <thead>
            <tr>
                <th>Test Name</th>
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
                    <a class="btn btn-success" @click="openEditModal(resource)">
                        <i class="fa fa-edit">Edit</i>
                    </a>
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
                                <label>Add New Lab Resource</label>
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
                                <label>Edit Lab Resource</label>
                            </slot>
                        </div>

                        <div class="modal-body" style="max-height: 350px; overflow-y: scroll" v-if="editResource">
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
                                <button :class="{btn: classLoad, 'btn-success':classLoad}" @click="editResources()">
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
                editButton: 'Update'
            }
        },
        methods:{
            openAddModal: function () {
                var inheritance= this;
                inheritance.resourceAddModal = true;
                inheritance.afterSaveButton = false;
            },
            openEditModal: function (resource) {
                var inheritance= this;
                inheritance.editResource = resource;
                inheritance.resourceEditModal = true;
                //inheritance.afterSaveButton = false;
            },
            closeAddModal: function () {
                var inheritance = this;
                inheritance.newResource = [];
                inheritance.resourceAddModal = false;
            },
            closeEditModal: function () {
                var inheritance = this;
                inheritance.editResource = [];
                inheritance.resourceEditModal = false;
            },
            allResources: function () {
                var inheritance = this;
                axios.get(base_url+'/resources/lab/all')
                    .then(function (response) {
                        inheritance.resources = response.data;
                    }.bind(this));
                setTimeout(function() { $("#vueTable").DataTable(); }, 500);
            },
            saveResource: function () {
              var inheritance = this;
              inheritance.saveButton = 'Saving...';
              console.log(inheritance.newResource);
              axios.post(base_url+'/resources/lab/new', {resource_name: inheritance.newResource.resource_name,
                                                         description: inheritance.newResource.description,
                                                         unit_price: inheritance.newResource.unit_price })
                  .then(function (response) {
                      console.log(response.data);
                        inheritance.saveButton = 'Saved';
                        inheritance.afterSaveButton = true;
                        inheritance.allResources();
                  }.bind(this))
            },
            editResources: function () {
                var inheritance = this;
                inheritance.editButton = 'Editing...';
                //console.log(inheritance.newResource);
                axios.post(base_url+'/resources/lab/edit', {resource_name: inheritance.editResource.resource_name,
                    description: inheritance.editResource.description,
                    unit_price: inheritance.editResource.unit_price,
                    resource_id: inheritance.editResource.id})
                    .then(function (response) {
                        console.log(response.data);
                        //inheritance.saveButton = 'Saved';
                        inheritance.allResources();
                        inheritance.closeEditModal();
                    }.bind(this))
            }
        }
    }
</script>
