<template>
    <div class="row">
        <ul class="stats_box">
            <li>
                <div class="sparkline bar_week"></div>
                <div class="stat_text">
                    <strong style="font-size: 70px"><i class="fa fa-user"></i> {{counts.total_tickets}}</strong>Total tickets attended to
                </div>
            </li>
            <li>
                <div class="sparkline line_day"></div>
                <div class="stat_text">
                    <strong style="font-size: 70px"><i class="fa fa-calendar"></i> {{counts.created_today}}</strong>My Today's total Tickets
                </div>
            </li>
            <li>
                <div class="sparkline pie_week"></div>
                <div class="stat_text">
                    <strong style=" font-size: 70px"><i class="fa fa-calendar"></i>{{counts.active_tickets}}</strong>My Active Tickets
                </div>
            </li>
            <li>
                <div class="sparkline stacked_month"></div>
                <div class="stat_text">
                    <strong style="font-size: 70px"><i class="fa fa-calendar"></i>{{counts.active_inpatient}}-{{counts.active_outpatient}}</strong>My (Inpatient - Outpatient)
                </div>
            </li>
        </ul>
        <div class="row">
            <hr>
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
                counts: []
            }
        },
        methods:{
            getCounts: function () {
                var inheritance= this;
                axios.get(base_url+'/reports/doctor')
                    .then(function (response) {
                        console.log(response.data);
                        inheritance.counts = response.data;
                    })
            }
        }
    }
</script>
