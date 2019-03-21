<template>
<div>
    <div v-if="!showBets">
         <div class="sk-circle">
            <div class="sk-circle1 sk-child"></div>
            <div class="sk-circle2 sk-child"></div>
            <div class="sk-circle3 sk-child"></div>
            <div class="sk-circle4 sk-child"></div>
            <div class="sk-circle5 sk-child"></div>
            <div class="sk-circle6 sk-child"></div>
            <div class="sk-circle7 sk-child"></div>
            <div class="sk-circle8 sk-child"></div>
            <div class="sk-circle9 sk-child"></div>
            <div class="sk-circle10 sk-child"></div>
            <div class="sk-circle11 sk-child"></div>
            <div class="sk-circle12 sk-child"></div>
        </div>
    </div>
    <b-card v-if="showBets" no-body class="cardNoBorder">
        <b-card no-body class="mb-2" v-for="date in matches" :key="date.id" >
            <div class="card-header scoreCardHeader"><div>{{convertDateTime(date[0].startDate)}}</div></div>
            <b-list-group flush>
                <b-list-group-item :variant="getVariant(match.userBet)" class="scoreListItem text-center" v-for="match in date" :key="match.id">
                    <!--<small>{{moment(match.startDate).format('HH:mm')}}</small>-->
                    <b-row> 
                        <b-col sm="3" class="text-right scoreRow">
                            <span v-if="shortname" class="">{{match['team1'].shortName}}&emsp;</span>
                            <span v-if="longname" class="">{{match['team1'].name}}&emsp;</span>
                        </b-col>
                        <b-col class="text-center" sm="2">
                            <b-input @change="scoreChanged(match)" :disabled="match.past == true" :formatter="format" v-model="match.userTeam1Bet" size="sm" class="text-center scoreInput" type="text"/>
                        </b-col>
                        <b-col class="scoreRow">
                            <b>{{match.team1goals}}</b>
                        </b-col>
                        <b-col class="scoreRow">
                            -
                        </b-col>
                        <b-col class="scoreRow">
                            <b>{{match.team2goals}}</b>
                        </b-col>
                        <b-col class="text-center" sm="2">
                            <b-input @change="scoreChanged(match)" :disabled="match.past == true" :formatter="format" v-model="match.userTeam2Bet" size="sm" class="text-center scoreInput" type="text"/>
                        </b-col>
                        <b-col sm="3" class="text-left scoreRow">   
                            <span v-if="shortname" class="">&emsp;{{match['team2'].shortName}}</span>
                            <span v-if="longname" class="">{{match['team2'].name}}&emsp;</span>
                        </b-col>
                    </b-row>
                </b-list-group-item>
            </b-list-group>      
        </b-card>
        <b-button class="col-12 mt-2 mb-2" style="border-radius:13px" variant="primary" v-on:click="placeBets()">
            Place your bets
        </b-button>
    </b-card>
</div>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'
import moment from 'moment'
import moment_timezone from 'moment-timezone'
export default {
    data() { 
      return {
          matches:[],
          moment:moment,
          changed:[],
          showBets:false,
          placedBetMessageSuccess:'',
          placedBetMessageWarning:'',
          userTimezone:'',
          shortname:false,
          longname:false,
          sport:''
      }
    },
    methods:{
        getVariant(userBet){
            if(userBet=='SUCCESS')
                return 'success'
            else if(userBet=='PARTIAL')
                return 'warning'
            else if(userBet=='FAILED')
                return 'danger'
            else
                return 'none'
        },
        scoreChanged(match){
            var n = this.changed.includes(match);
            if(!n)
                this.changed.push(match);
        },
        placeBets(){
            let vm=this;
            axios({ method: 'POST', 
                    url: '/bets', 
                    headers: {'Authorization': 'Bearer ' + this.getToken,
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }, 
                    data: { 
                       username: this.getUsername,
                       editMatches: vm.changed,
                       sport: 'football'
                    } 
                }).then(function (response) {
                    if(response.data=='You placed your bets!')
                        vm.placedBetMessageSuccess=response.data;
                    else
                        vm.placedBetMessageWarning=response.data;
                }).catch(function (error) {});
            
        },
        format(value, event) {
            return value.toString().replace(/\D/g,'');
        },
        refreshMatchList()
        {
            this.showBets=false;
            let vm=this;
            axios({ method: 'GET', 
                    url: '/matches/'+this.$route.params.league, 
                    headers: {'Authorization': 'Bearer ' + this.getToken}, 
                    params: {
                        username: vm.getUsername,
                        seasonStart: this.$parent.wantedSeasonStart,
                        seasonEnd: this.$parent.wantedSeasonEnd,
                        matchday: this.$parent.wantedMatchday,
                        month: this.$parent.wantedMonth,
                        sport: vm.sport
                    },
                }).then(function (response) {
                    vm.matches=response.data;
                    vm.showBets=true;
                }).catch(function (error) {});
        },
        convertDateTime(date)
        {
            var utcDateTime = moment.utc(date);
            var userLocal = utcDateTime.tz(moment.tz.guess(true)).format("dddd, MMMM Do YYYY, HH:mm");
            return userLocal;
        }
    },
    mounted() {
        let vm=this;

        if(this.$route.params.league=='NHL')
        {
            vm.longname=true;
            vm.sport='icehockey';
        }
        else
        {
            vm.shortname=true;
            vm.sport='football';
        }

        axios({ method: 'GET', 
                url: '/matches/'+this.$route.params.league, 
                headers: {'Authorization': 'Bearer ' + this.getToken}, 
                params: {
                    username: vm.getUsername,
                    sport: vm.sport
                },
            }).then(function (response) {
                vm.matches=response.data;
                vm.showBets=true;
            }).catch(function (error) {});
    },
    computed: {
      ...mapGetters([
            'getToken','getUsername'
        ]),
    },
    watch: {
        placedBetMessageSuccess: function () {
            this.$emit('placedBetsSuccess', this.placedBetMessageSuccess);
        },
        placedBetMessageWarning: function () {
            this.$emit('placedBetsWarning', this.placedBetMessageWarning);
        }
    },
}
</script>
