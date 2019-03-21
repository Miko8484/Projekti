<template>
    <b-card no-body>
        <b-tabs v-if="!singleStandings" card class="cardTab scroll">
            <b-tab title="Group stage" active>
                <table responsive aria-colcount="8" v-for="standing in standings['groups']" :key="standing.id"  class="table b-table table-hover border">
                    <thead class="groupStandingsTableHead">
                        <tr>
                            <th v-b-tooltip.hover title="Position" aria-colindex="1" class="text-center groupTableHeader">#</th>
                            <th aria-colindex="2" class="text-left groupTableHeader">{{formatGroupName(standing[0].group)}}</th>
                            <th v-b-tooltip.hover title="Matches played" aria-colindex="3" class="text-center groupTableHeader">MP</th>
                            <th v-b-tooltip.hover title="Wins" aria-colindex="4" class="text-center groupTableHeader">W</th>
                            <th v-b-tooltip.hover title="Draws" aria-colindex="5" class="text-center groupTableHeader">D</th>
                            <th v-b-tooltip.hover title="Losses" aria-colindex="6" class="text-center groupTableHeader">L</th>
                            <th v-b-tooltip.hover title="Goals" aria-colindex="7" class="text-center groupTableHeader">G</th>
                            <th v-b-tooltip.hover title="Points" aria-colindex="8" class="text-center groupTableHeader">Pts</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        <tr class="" v-for="group in standing" :key="group.id">
                            <td aria-colindex="1" class="standings text-center">{{group.place}}</td>
                            <td aria-colindex="2" class="standings">{{group.team.shortName}}</td>
                            <td aria-colindex="3" class="standings text-center">{{group.gamesPlayed}}</td>
                            <td aria-colindex="4" class="standings text-center">{{group.won}}</td>
                            <td aria-colindex="5" class="standings text-center">{{group.draw}}</td>
                            <td aria-colindex="6" class="standings text-center">{{group.lost}}</td>
                            <td aria-colindex="7" class="standings text-center">{{group.scoredGoals}}:{{group.concedeGoals}}</td>
                            <td aria-colindex="8" class="standings text-center">{{group.points}}</td>
                        </tr>
                    </tbody>
                </table>
            </b-tab>
            <b-tab title="Playoff">
                <div class='bracket'>
                    <div class='round' v-for="standing in standings['playoff']" :key="standing.id">
                        <div class='match' v-for="match in standing" :key="match.id">
                            <div class='match__content'>
                                <b-list-group>
                                    <b-list-group-item class="bracketListItem d-flex justify-content-between">
                                        {{match.match1.team1.shortName}}
                                        <span>{{match.match1.team1goals}}&emsp;{{match.match2.team1goals}}&emsp;</span>
                                    </b-list-group-item>
                                    <b-list-group-item class="bracketListItem d-flex justify-content-between">
                                        {{match.match1.team2.shortName}}
                                        <span>{{match.match1.team2goals}}&emsp;{{match.match2.team2goals}}&emsp;</span>
                                    </b-list-group-item>
                                </b-list-group>
                            </div>
                        </div>
                    </div>
                </div>
            </b-tab>
        </b-tabs>
        <table v-if="singleStandings" responsive aria-colcount="8"  class="table b-table table-hover border">
            <thead class="">
                <tr>
                    <th aria-colindex="1" class="text-center">#</th>
                    <th aria-colindex="2" class="">Team</th>
                    <th aria-colindex="3" class="text-center">MP</th>
                    <th aria-colindex="4" class="text-center">W</th>
                    <th aria-colindex="5" class="text-center">D</th>
                    <th aria-colindex="6" class="text-center">L</th>
                    <th aria-colindex="7" class="text-center">G</th>
                    <th aria-colindex="8" class="text-center">Pts</th>
                </tr>
            </thead>
            <tbody class="">
                <tr class="" v-for="standing in standings" :key="standing.id">
                    <td v-b-tooltip.hover title="Position" aria-colindex="1" class="standings text-center">{{standing.place}}</td>
                    <td aria-colindex="2" class="standings">{{standing.team.shortName}}</td>
                    <td v-b-tooltip.hover title="Matches played" aria-colindex="3" class="standings text-center">{{standing.gamesPlayed}}</td>
                    <td v-b-tooltip.hover title="Wins" aria-colindex="4" class="standings text-center">{{standing.won}}</td>
                    <td v-b-tooltip.hover title="Draws" aria-colindex="5" class="standings text-center">{{standing.draw}}</td>
                    <td v-b-tooltip.hover title="Losses" aria-colindex="6" class="standings text-center">{{standing.lost}}</td>
                    <td v-b-tooltip.hover title="Goals" aria-colindex="7" class="standings text-center">{{standing.scoredGoals}}:{{standing.concedeGoals}}</td>
                    <td v-b-tooltip.hover title="Points" aria-colindex="8" class="standings text-center">{{standing.points}}</td>
                </tr>
            </tbody>
        </table>
    </b-card>
    <!---->
    <!---->

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
        console.log(this.$route.params.league);
        if(this.$route.params.league=="ChampionsLeague")
            this.singleStandings=false;

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
