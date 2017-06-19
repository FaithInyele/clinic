<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div>
        <div class="row">
            <h4>My Tickets</h4>
        </div>
        <div class="row">
            <hr>
        </div>
        <table class="table table-striped table-bordered dt-responsive" id="vueTable"
               cellspacing="0" width="100%" style="font-size: 10px">
            <thead>
            <tr>
                <th>Client Names</th>
                <th>Assigned Doctor</th>
                <th>Last Activity</th>
                <th>Created At</th>
                <th>Last Updated At</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="ticket in tickets">
                <td>{{ticket.client.first_name}}, {{ticket.client.other_names}}</td>
                <td>{{ticket.doctor.first_name}}, {{ticket.client.other_names}}</td>
                <td>{{ticket.id}}</td>
                <td>{{ticket.created_at}}</td>
                <td>{{ticket.updated_at}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        mounted: function() {
            console.log('Component mounted.');
            this.activeTickets();
        },
        data: function () {
            return{
                tickets: [],
            }
        },
        methods:{
            activeTickets: function () {
                var inheritance = this;
                axios.get(base_url+'/tickets/my-tickets/all/r_patient')
                    .then(function (response) {
                        inheritance.tickets = response.data;
                        setTimeout(function() { $("#vueTable").DataTable(); }, 500);
                    })
            }
        }
    }
</script>
