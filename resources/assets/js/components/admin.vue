<template>
    <div class="row">
        <ul class="stats_box">
            <li>
                <div class="sparkline bar_week"></div>
                <div class="stat_text">
                    <strong style="font-size: 80px"><i class="fa fa-user"></i> {{counts.users}}</strong>Users <a :href="baseUrl+'/users/view'" style="color: darkred">  View</a>
                </div>
            </li>
            <li>
                <div class="sparkline line_day"></div>
                <div class="stat_text">
                    <strong style="font-size: 80px"><i class="fa fa-calendar"></i> {{counts.all_tickets}}</strong>Total Tickets <a :href="baseUrl+'/tickets'" style="color: darkred">View</a>
                </div>
            </li>
            <li>
                <div class="sparkline pie_week"></div>
                <div class="stat_text">
                    <strong style="font-size: 80px"><i class="fa fa-calendar"></i>{{counts.active_tickets}}</strong>Active Tickets <a :href="baseUrl+'/tickets'" style="color: darkred">View</a>
                </div>
            </li>
            <li>
                <div class="sparkline stacked_month"></div>
                <div class="stat_text">
                    <strong style="font-size: 50px"><i class="fa fa-money" style="font-size: 15px"></i>{{counts.total_payments}}</strong>Total Payments<a :href="baseUrl+'/payments'" style="color: darkred">  View</a>
                </div>
            </li>
        </ul>
        <div class="row">
            <label>Trends</label>
            <hr>
        </div>
        <div class="row">
            <div class="col-md-8">
                <ul class="accordion-tabs-minimal">
                    <li class="tab-header-and-content">
                        <a href="#" class="tab-link is-active">Tickets</a>
                        <div class="tab-content">
                            <div class="row raisedbox">
                                <div id="ticket-trend" style="min-width: 310px;max-width: 800px;height: 400px;margin: 0 auto"></div>
                            </div>
                        </div>
                    </li>
                    <li class="tab-header-and-content">
                        <a href="#" class="tab-link">Payment</a>
                        <div class="tab-content">
                            <div class="row raisedbox">
                                <div id="payment-trend" style="min-width: 310px;max-width: 800px;height: 400px;margin: 0 auto"></div>
                            </div>
                        </div>
                    </li>
                    <li class="tab-header-and-content">
                        <a href="#" class="tab-link">Lab-Tests</a>
                        <div class="tab-content">
                            <table class="table table-striped table-bordered dt-responsive" id="vueTable"
                                   cellspacing="0" width="100%" style="font-size: 10px">
                                <thead>
                                <tr>
                                    <th>Test Id</th>
                                    <th>Test name</th>
                                    <th>test Counts</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="test in counts.tests_trend">
                                    <td>{{ test.lab_resource_id}}</td>
                                    <td>{{ test.resource_name}}</td>
                                    <td>{{ test.total}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </li>
                    <li class="tab-header-and-content">
                        <a href="#" class="tab-link">Medication</a>
                        <div class="tab-content">
                            <table class="table table-striped table-bordered dt-responsive" id="vueTable2"
                                   cellspacing="0" width="100%" style="font-size: 10px">
                                <thead>
                                <tr>
                                    <th>Test Id</th>
                                    <th>Medication name</th>
                                    <th>Issued Counts</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="test in counts.med_trend">
                                    <td>{{ test.chemist_resource_id}}</td>
                                    <td>{{ test.resource_name}}</td>
                                    <td>{{ test.total}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Today's Statistics</h5>
                <hr>
                <div class="row"><label>Tickets Created:</label>   <b class="pull-right">{{counts.tickets_created}}</b><hr></div>
                <div class="row"><label>Open Tickets</label>   <b class="pull-right">{{counts.active_tickets}}</b><hr></div>
                <div class="row"><label>Admissions Today</label>   <b class="pull-right">{{counts.admissions_today}}</b><hr></div>
                <div class="row"><label>Discharges Today</label>   <b class="pull-right">{{counts.discharges_today}}</b><hr></div>
                <div class="row"><label>Lab Tests</label>    <b class="pull-right">{{counts.labdata}}({{counts.tests}})</b><hr></div>
                <div class="row"><label>Prescriptions Given</label>   <b class="pull-right">{{counts.prescriptions}}({{counts.medicine}})</b><hr></div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {
            console.log('Component mounted.');
            this.getCounts();
        },
        data: function () {
            return {
                counts: [],
                baseUrl: base_url
            }
        },
        methods:{
            getCounts: function () {
                var inheritance= this;
                axios.get(base_url+'/reports/admin')
                    .then(function (response) {
                        console.log(response.data);
                        inheritance.counts = response.data;
                        setTimeout(function() { $("#vueTable").DataTable(); }, 500);
                        setTimeout(function() { $("#vueTable2").DataTable(); }, 500);

                    })
            }
        }
    }
</script>
