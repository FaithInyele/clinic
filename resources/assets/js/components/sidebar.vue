<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="row">
        <div class="row">
            <div v-if="newMessages" v-for="ticket in newMessages">
                Client Name: 
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

                            </slot>
                        </div>

                        <div class="modal-footer">
                            <slot name="footer">
                                <button class="btn btn-warning" @click="closeModal">
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
            this.consults();
        },
        data: function () {
            return{
                modal: false,
                newMessages: []
            }
        },
        methods:{
            closeModal: function () {
                var inheritance = this;
                inheritance.modal = false
            },
            openModal: function () {
                var inheritance =this;
                inheritance.modal = true;
            },
            consults: function () {
                var inheritance = this;
                axios.get(base_url+'/chat/unread')
                    .then(function (response) {
                        inheritance.newMessages = response.data;
                        console.log(response.data);
                    }.bind(this))
            }
        }
    }
</script>
