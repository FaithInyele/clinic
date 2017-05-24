<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="row">
        <div class="row">
            <h3>Search Client to Start ticket</h3>
            <hr>
            <h6>Search can take Student ID No, National ID No. or Name(s)</h6>
            <div class="form-group">
                <input type="text" class="form-control" v-model="searchTerm">
            </div>
            <div class="form-group">
                {{ buttonText }}
            </div>
            <div class="alert alert-warning" v-show="noResults">
                No results found. You can try a different Criteria
            </div>
        </div>
        <div class="row pullquote-left" v-for="result in results" style="padding-left: 20px">
            <a :href="baseUrl+'/tickets/start?client_id='+result.id+'&client_fname='+result.first_name ">
            <p><b>First Name:</b>{{ result.first_name}}   <b style="margin-left: 10px">Other Names:</b>{{ result.other_names}}</p>
            <p><b>Client Type:</b> {{result.type}}</p>
            <p><b>Year of Birth:</b> {{ result.yob}}</p>
            </a>
        </div>

    </div>
</template>

<script>
    export default {
        mounted: function() {
            console.log('Component mounted.')
        },
        data: function(){
            return{
                searchTerm: '',
                buttonText: '',
                noResults: false,
                results: [],
                baseUrl: base_url
            }
        },
        watch: {
            searchTerm: function () {
                this.results = [];
                this.searchClient();
            }
        },
        methods:{
            searchClient: _.debounce(function () {
                var relation=this;
                this.buttonText = 'Searching...'
                console.log(base_url+'/tickets/search?term='+this.searchTerm);
                if (this.searchTerm.length >= 1){
                    axios.get(base_url+'/tickets/search?term='+this.searchTerm)
                        .then(function (response) {
                            console.log(response.data);
                            if (response.data.length == 0){
                                relation.noResults = true;
                            }else {
                                relation.noResults = false;
                            }
                            relation.results = (response.data);
                            relation.buttonText = ''
                        }.bind(this)).catch(function (error) {
                        console.log(error);
                    });
                }
            }, 500)
        }
    }
</script>
