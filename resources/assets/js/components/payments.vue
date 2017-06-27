<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="row">
        <div class="col-lg-8">
            <h3>Pending Payments</h3>
            <hr>
            <div class="row" v-for="payment in payments">
                <div class="row" style="background-color: #f8f8f8;border: 2px solid #53CDF6;border-radius: 10px;margin-left: 0px">
                    <div class="row">
                        <label> Payment Location:</label>
                        {{payment.progress.description}}
                        <i class="pull-right">
                            last edited on:{{payment.updated_at}}
                        </i>
                    </div>
                    <hr style="margin: 5px">
                    <div class="row">
                        Total amount to be Paid:     <b>{{payment.total}}</b>
                    </div>
                    <hr style="margin: 5px">
                    <div class="row">
                        <a class="pull-right btn btn-sm btn-warning  btn-custom" @click="pay(payment)" style="margin-right: 10px">Pay</a>
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
                                <label>Make Payment: {{currentPayment.progress.description}}</label>
                            </slot>
                        </div>

                        <div class="modal-body">
                            <div class="row" id="printable" style="text-align: center">
                                <h1 style="text-align: center">iHospital</h1>
                                <h4 style="text-align: center">Payment Receipt</h4>
                                <div class="row" style="text-align: left">
                                    <div class="row" style="text-align: right">
                                        Ticket Number: #{{currentPayment.ticket.id}}<br>
                                        Ticket created on: {{currentPayment.ticket.created_at}}<br>
                                        Doctor in-charge: {{currentPayment.doctor.first_name}}, {{currentPayment.doctor.last_name}}<br>
                                    </div>
                                    <div class="row">
                                        Client Name: {{currentPayment.client.first_name}}, {{currentPayment.client.other_names}}<br>
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
                                    <tr v-for="test in currentPayment.tests">
                                        <td>{{test.details.resource_name}}</td>
                                        <td>{{test.amount}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Total</b></td>
                                        <td><b>{{currentPayment.total}}</b></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="row" style="text-align: left">
                                    Amount Paid: {{currentPayment.total}}<br>
                                    Payment Method: {{currentPayment.payment_method}}
                                </div>
                            </div>
                            <slot name="body">
                                <table class="table table-striped table-bordered dt-responsive"
                                       cellspacing="0" width="100%" style="font-size: 10px">
                                    <thead>
                                    <tr>
                                        <td>Description</td>
                                        <td>Amount</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="test in currentPayment.tests">
                                            <td>{{test.details.resource_name}}</td>
                                            <td>{{test.amount}}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Total</b></td>
                                            <td><b>{{currentPayment.total}}</b></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="row">
                                    <h6>Client should make 100% payment before proceeding...</h6>
                                    <select class="input-sm" v-model="currentPayment.payment_method">
                                        <option value="" selected disabled>-Select Payment Method-</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Mobile Money">Mobile Money</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </slot>
                        </div>

                        <div class="modal-footer">
                            <slot name="footer">
                                <button v-if="payButton" class="btn btn-success" @click="savePayment(currentPayment)">
                                    {{saveButton}}
                                </button>
                                <button v-if="printButton" class="btn btn-warning" onclick="printdata('printable')">
                                    Print Receipt
                                </button>
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
            this.allPayments();
        },
        data: function () {
            return{
                modal: false,
                payments: [],
                currentPayment: [],
                saveButton: 'Pay',
                serviceFee: false,
                editPreference: [],
                editButton: 'Edit',
                payButton: true,
                printButton: false
            }
        },
        methods:{
            allPayments: function () {
                var inheritance = this;
                axios.get(base_url+'/tickets/payments/pending')
                    .then(function (response) {
                        inheritance.payments = response.data;
                    }.bind(this))
            },
            pay: function (payment) {
                var  inheritance = this;
                inheritance.modal = true;
                inheritance.currentPayment = payment;
            },
            closeModal: function () {
                var inheritance =this;
                inheritance.allPayments();
                inheritance.modal = false;
            },
            savePayment: function (payment) {
                var inheritance = this;
                inheritance.saveButton = 'Paying...';
                if (payment.payment_method == ''){

                }else{
                    axios.post(base_url+'/tickets/payments/pay-lab', payment)
                        .then(function (response) {
                            //console.log(response.data);
                            inheritance.saveButton = 'Pay';
                            inheritance.allPayments();
                            inheritance.payButton = false;
                            inheritance.printButton = true;
                            //inheritance.modal = false;
                            //console.log(response.data);
                        })
                }
            },
        }
    }
</script>
