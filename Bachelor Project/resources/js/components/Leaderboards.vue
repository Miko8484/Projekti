<template>
  <b-container fluid>
    <b-row>
        <b-col sm="2">
          <leaderboardsfilter @filterChanged="filterChanged"></leaderboardsfilter>
        </b-col>
        <b-col sm="8">
          <b-alert v-if="userPlacement" show variant="success">Your are placed <b>{{loggedUserPlace}}</b> with <b>{{loggedUserPoints}}</b> points.</b-alert>
          <b-alert v-if="!userPlacement" show variant="success">You are not participating in any of the selected leagues.</b-alert>
          <v-server-table ref="table" url="/leaderboards" @loaded="onLoaded" :columns="columns" :options="options"></v-server-table>
        </b-col>
    </b-row>
  </b-container>
  
</template>

<script>
import axios from 'axios'
import leaderboardsfilter from './LeaderboardsFilter.vue'
import { mapGetters } from 'vuex'

export default {
  components : {
    leaderboardsfilter,
  },
  methods: {
    onLoaded()
    {
      var table = document.getElementsByTagName('tr');
      for(var i=0;i<table.length;i++)
      {
        if(table[i].innerText.charAt(0) == '1')
          table[i].classList.add("firstPlace");
        else
          table[i].classList.remove("firstPlace");

        if(table[i].innerText.charAt(0) == '2')
          table[i].classList.add("secondPlace");
        else
          table[i].classList.remove("secondPlace");

        if(table[i].innerText.charAt(0) == '3')
          table[i].classList.add("thirdPlace");
        else
          table[i].classList.remove("thirdPlace");
      }

      var sortPoints = document.createElement("i");
      sortPoints.classList.add("material-icons");
      sortPoints.classList.add("md-18");
      sortPoints.id="sortPoints";
      sortPoints.innerHTML="swap_vert";

      var sortUsername = document.createElement("i");
      sortUsername.classList.add("material-icons");
      sortUsername.classList.add("md-18");
      //sortUsername.id="sortUsername";
      sortUsername.innerHTML="swap_vert";

      var x = document.getElementsByClassName("glyphicon-sort");
      var u = document.getElementById("sortUsername");
      if(u==null){
        x[0].appendChild(sortUsername);
        x[0].id="sortUsername";
      }
      var p = document.getElementById("sortPoints");
      if(p==null)
      {
        x[1].appendChild(sortPoints);
        x[1].id="sortPoints";
      }

      if(x.length>0)
        x[0].innerHTML="<i class='material-icons md-18'>swap_vert</i>";
      
      x = document.getElementsByClassName("glyphicon-chevron-up");
      if(x.length>0)
      {
        if(x[0].id=="sortUsername")
        {
          var element = document.getElementById("sortUsername");
          element.innerHTML="<i class='material-icons md-18'>arrow_drop_up</i>";
        }
        else
        {
          var element = document.getElementById("sortPoints");
          element.innerHTML="<i class='material-icons md-18'>arrow_drop_up</i>";
        }
      }

      x = document.getElementsByClassName("glyphicon-chevron-down");
      if(x.length>0)
      {
        if(x[0].id=="sortUsername")
        {
          var element = document.getElementById("sortUsername");
          element.innerHTML="<i class='material-icons md-18'>arrow_drop_down</i>";;
        }
        else
        {
          var element = document.getElementById("sortPoints");
          element.innerHTML="<i class='material-icons md-18'>arrow_drop_down</i>";
        }
      }
      
    },
    filterChanged(football,basketball,iceHockey) {
      this.leagues = football.toString() + ',' + basketball.toString() + ',' + iceHockey.toString()
      console.log(this.leagues)
      this.$refs.table.refresh()
    },
  },
  computed: {
    ...mapGetters([
        'getToken'
    ]),
  },
  data() { 
    let vm =this;
    return { 
      columns: ['place','username', 'points'],
      tableData: [],
      leagues: 'All',
      loggedUserPlace: '',
      loggedUserUsername: this.$store.state.username,
      loggedUserPoints:'',
      userPlacement:true,
      options: {
        requestAdapter(data) {
          return {
            sort: data.orderBy ? data.orderBy : 'points',
            direction: data.ascending ? 'desc' : 'asc',
            page: data.page,
            limit: data.limit,
            query: data.query
          }
        },
        requestFunction: function (data) {
          axios.get('/leaderboards/'+this.$store.state.username+'/'+vm.leagues, {
            params: {
              leagues: this.leagues
            },
            headers: {
              'Authorization': 'Bearer ' + vm.getToken
            }
          }).then(function (response) {
            if(response.data!='Null')
            {
              vm.loggedUserPoints=response.data[0].points
              vm.loggedUserPlace=response.data[0].place
              vm.userPlacement=true
            }
            else
              vm.userPlacement=false
          }).catch(function (error) {});  

          return axios.get(this.url, {
              params:{ 
                filter:data.query,
                sort:data.sort,
                page:data.page,
                direction:data.direction,
                limit:data.limit,
                leagues: vm.leagues
              },
              headers: {
                'Authorization': 'Bearer ' + vm.getToken
              }
          }).catch(function (e) {
              this.dispatch('error', e);
          }.bind(this));
        },
        headings: {
            username: 'Username',
            points: 'Points',
            place: 'Place'
          },
          filterByColumn:true,
        perPage:3,
        perPageValues:[3],
        sortable: ['points','username'],
        filterable:['username'],
        responseAdapter({data}) {
          return {
            data: data.data,
            count: data.total
          }
        },
      }
    }
  }
}
</script>


