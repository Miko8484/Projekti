<template>
    <b-card no-body>
        <b-tabs card class="cardTab">
            <b-tab title="Group stage" class="scroll" active>
                <table responsive aria-colcount="8" v-for="standing in standings['groups']" :key="standing.id"  class="table b-table table-hover border">
                    <thead class="groupStandingsTableHead">
                        <tr>
                            <th v-b-tooltip.hover title="Position" aria-colindex="1" class="text-center groupTableHeader">#</th>
                            <th aria-colindex="2" class="text-left groupTableHeader">{{formatGroupName(standing[0].group)}}</th>
                            <th v-b-tooltip.hover title="Matches played" aria-colindex="3" class="text-center groupTableHeader">MP</th>
                            <th v-b-tooltip.hover title="Wins" aria-colindex="4" class="text-center groupTableHeader">W</th>
                            <th v-b-tooltip.hover title="Losses" aria-colindex="5" class="text-center groupTableHeader">L</th>
                            <th v-b-tooltip.hover title="Overtime losses" aria-colindex="6" class="text-center groupTableHeader">OT</th>
                            <th v-b-tooltip.hover title="Goals" aria-colindex="7" class="text-center groupTableHeader">G</th>
                            <th v-b-tooltip.hover title="Points" aria-colindex="8" class="text-center groupTableHeader">Pts</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        <tr class="" v-for="group in standing" :key="group.id">
                            <td aria-colindex="1" class="standings text-center">{{group.place}}</td>
                            <td aria-colindex="2" class="standings">{{group.team.name}}</td>
                            <td aria-colindex="3" class="standings text-center">{{group.gamesPlayed}}</td>
                            <td aria-colindex="4" class="standings text-center">{{group.won}}</td>
                            <td aria-colindex="5" class="standings text-center">{{group.lost}}</td>
                            <td aria-colindex="6" class="standings text-center">{{group.overtime}}</td>
                            <td aria-colindex="7" class="standings text-center">{{group.scoredGoals}}:{{group.concedeGoals}}</td>
                            <td aria-colindex="8" class="standings text-center">{{group.points}}</td>
                        </tr>
                    </tbody>
                </table>
            </b-tab>
            <b-tab title="Playoff" class="scroll">
                <div class='bracket'>
                    <div class='round' v-for="standing in standings['playoffs']" :key="standing.id">
                        <div class='match' v-for="match in standing" :key="match.id">
                            <div class='match__content'>
                                <b-list-group>
                                    <b-list-group-item class="bracketListItem d-flex justify-content-between">
                                        {{match[0].team1.name}}
                                        <span>{{match.team1wins}}</span>
                                    </b-list-group-item>
                                    <b-list-group-item class="bracketListItem d-flex justify-content-between">
                                        {{match[0].team2.name}}
                                        <span>{{match.team2wins}}</span>
                                    </b-list-group-item>
                                </b-list-group>
                            </div>
                        </div>
                    </div>
                </div>
            </b-tab>
        </b-tabs>
    </b-card>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'
export default {
    data() { 
      return {
          standings:[],
          singleStandings:true
      }
    },
    methods:{
        formatGroupName(groupName)
        {
            return groupName.replace(/_/g," ");
        }
    },
    mounted() {
        let vm=this;

        axios({ method: 'GET', 
                url: '/standings/'+this.$route.params.league, 
                headers: {'Authorization': 'Bearer ' + this.getToken}, 
                params: {},
            }).then(function (response) {
                vm.standings=response.data;
            }).catch(function (error) {});
    },
    computed: {
      ...mapGetters([
            'getToken','getUsername'
        ]),
    },
}
</script>
