<template>
    <b-container fluid>
        <b-row>
            <b-col lg="2">
                <b-form-select v-model="selectedSeason" class="mb-2">
                    <option @click="seasonChange(season)" v-for="season in seasons" :key="season.id">
                        {{"Season: "+moment(season.start).format("YYYY")+'/'+moment(season.end).format("YYYY")}}
                    </option>
                </b-form-select>

                <b-form-select v-if="footballMatches" v-model="selectedMatchday">
                    <option @click="matchdayChange(index)" v-for="index in seasonMatchdays.currentMatchday" :key="index">
                        {{"Matchday: "+index }}
                    </option>
                </b-form-select>

                <b-form-select @change="monthChange($event)" v-if="basketballMatches || icehockeyMatches" v-model="wantedMonth">
                    <option value="" disabled hidden>Select month</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                </b-form-select>

            </b-col>
            <b-col lg="6">
                <b-alert :show="hideCountDownSuccess"
                    variant="success"
                    @dismissed="hideCountDownSuccess=0"
                    @dismiss-count-down="countDownChanged">
                        {{placedBetsMessage}}
                </b-alert>
                <b-alert :show="hideCountDownWarning"
                    variant="warning"
                    @dismissed="hideCountDownWarning=0"
                    @dismiss-count-down="countDownChanged">
                        {{placedBetsMessage}}
                </b-alert>
                <scored-bets-match-list v-if="footballMatches||icehockeyMatches" ref="childScoredMatches"
                            @placedBetsSuccess="placedBetsMessage = $event;hideCountDownSuccess = hideAfterSeconds"
                            @placedBetsWarning="placedBetsMessage = $event;hideCountDownWarning = hideAfterSeconds"
                ></scored-bets-match-list>

                <driffrence-bets-match-list v-if="basketballMatches" ref="childDiffrenceMatches"
                            @placedBetsSuccess="placedBetsMessage = $event;hideCountDownSuccess = hideAfterSeconds"
                            @placedBetsWarning="placedBetsMessage = $event;hideCountDownWarning = hideAfterSeconds"
                ></driffrence-bets-match-list>
            </b-col>
            <b-col lg="4">
                <football-standings v-if="footballMatches"></football-standings>
                <basketball-standings v-if="basketballMatches"></basketball-standings>
                <icehockey-standings v-if="icehockeyMatches"></icehockey-standings>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'
import moment from 'moment'
import ScoredBetsMatchList from './ScoredBetsMatchList.vue'
import DriffrenceBetsMatchList from './DriffrenceBetsMatchList.vue'
import FootballStandings from './FootballStandings.vue'
import BasketballStandings from './BasketballStandings.vue'
import IcehockeyStandings from './IcehockeyStandings.vue'
export default {
    components : {
        ScoredBetsMatchList, DriffrenceBetsMatchList, FootballStandings, BasketballStandings, IcehockeyStandings
    },
    data() { 
      return {
          moment:moment,
          seasons:[],
          seasonMatchdays: [],
          selectedMatchday:'',
          selectedSeason:'',
          wantedMatchday:'',
          wantedSeasonStart:'',
          wantedSeasonEnd:'',
          placedBetsMessage:'',
          hideAfterSeconds:8,
          hideCountDownSuccess:0,
          hideCountDownWarning:0,
          footballMatches:false,
          basketballMatches:false,
          icehockeyMatches:false,
          wantedMonth:''
      }
    },
    methods:{
        seasonChange(season)
        {
            this.wantedSeasonStart=season.start;
            this.wantedSeasonEnd=season.end;
            this.$refs.childScoredMatches.refreshMatchList();
        },
        matchdayChange(index)
        {
            this.wantedMatchday=index;
            this.$refs.childScoredMatches.refreshMatchList();
        },
        countDownChanged (hideCountDown) {
            this.hideCountDown = hideCountDown
        },
        monthChange(event){
            this.wantedMonth=event;
            if(this.basketballMatches)
                this.$refs.childDiffrenceMatches.refreshMatchList();
            else
                this.$refs.childScoredMatches.refreshMatchList();
        }
    },
    /*beforeRouteEnter(to, from, next) {
        next(vm => {
            axios({ method: 'GET', 
                    url: '/test', 
                    params: {
                        username: vm.getUsername
                    },
                }).then(function (response) {
                    console.log(response);
                    vm.test= response.data;
                    next(vm => vm.setData(err, test))
                }).catch(function (error) {
                    console.log(err);
                });
        });

    },*/
    mounted() {
        if(this.$route.params.league=="NBA")
            this.basketballMatches=true;
        else if(this.$route.params.league=="NHL")
            this.icehockeyMatches=true;
        else
            this.footballMatches=true;

        let vm=this;
        axios({ method: 'GET', 
                url: '/seasons/'+this.$route.params.league, 
                headers: {'Authorization': 'Bearer ' + this.getToken}, 
                params: {},
            }).then(function (response) {
                vm.seasons=response.data;
                vm.selectedSeason='Season: '+moment(response.data[0].start).format("YYYY")+'/'+moment(response.data[0].end).format("YYYY");
                vm.selectedMatchday='Matchday: '+response.data[0].currentMatchday;
                vm.seasonMatchdays=response.data[0];
            }).catch(function (error) {});
    },
    computed: {
      ...mapGetters([
            'getToken','getUsername'
        ]),
    },
}
</script>
