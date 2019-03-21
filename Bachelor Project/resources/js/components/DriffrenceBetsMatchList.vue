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
                <b-list-group-item :disabled="match.past==true && match.userBet==null" :variant="getVariant(match.userBet)" class="scoreListItem text-center" v-for="match in date" :key="match.id">
                    <basketball-match @betChange="scoreChanged(match)" :match="match" />
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
import BasketballMatch from './BasketballMatch.vue'
export default {
    components : {
        BasketballMatch
    },
    data() { 
      return {
          matches:[],
          moment:moment,
          changed:[],
          showBets:false,
          placedBetMessageSuccess:'',
          placedBetMessageWarning:'',
      }
    },
    methods:{
        scoreChanged(match){
            var n = this.changed.includes(match);
            if(!n)
                this.changed.push(match);
        },
        placeBets(){
            let vm=this;
            axios({ method: 'POST', 
                    url: '/bets', 
                    headers: {'Authorization': 'Bearer ' + this.getToken}, 
                    data: { 
                       username: this.getUsername,
                       editMatches: vm.changed,
                       sport: 'basketball'
                    } 
                }).then(function (response) {
                    if(response.data=='You placed your bets!')
                        vm.placedBetMessageSuccess=response.data;
                    else
                        vm.placedBetMessageWarning=response.data;
                }).catch(function (error) {});
            
        },
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
                        month: this.$parent.wantedMonth,
                        sport: 'basketball'
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
        axios({ method: 'GET', 
                url: '/matches/'+this.$route.params.league, 
                headers: {'Authorization': 'Bearer ' + this.getToken}, 
                params: {
                    username: vm.getUsername,
                    sport: 'basketball'
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
