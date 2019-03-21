<template>
<!--<small>{{moment(match.startDate).format('HH:mm')}}</small>-->
<b-form-radio-group>
<b-row > 
    <b-col sm="3" class="text-right scoreRow">
        <span class="">{{match['team1'].name}}</span>
    </b-col>
    <b-col class="text-center scoreRow">
        <b>{{match.team1goals}}</b>
    </b-col>
    <b-col class="scoreRow">
        <b-form-radio @change="scoreChanged(match,'HOME_TEAM')" :disabled="match.past == true" v-model="pickedWinner" value="HOME_TEAM" ></b-form-radio>
    </b-col>
    <b-col class="scoreRow" sm="2">
        <b-input @change="scoreChanged(match,match.pickedWinner)" :disabled="match.past == true" :formatter="format" v-model="match.otherScore" size="sm" class="text-center scoreInput" type="text"/>
    </b-col>
    <b-col class="scoreRow">
        <b-form-radio @change="scoreChanged(match,'AWAY_TEAM')" :disabled="match.past == true" v-model="pickedWinner" value="AWAY_TEAM" ></b-form-radio>
    </b-col>
    <b-col class="text-center scoreRow">
        <b>{{match.team2goals}}</b>
    </b-col>
    <b-col sm="3" class="text-left scoreRow">   
        <span class="">{{match['team2'].name}}</span>
    </b-col>
</b-row>     
</b-form-radio-group>     
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'
import moment from 'moment'
import moment_timezone from 'moment-timezone'
export default {
    props: ['match'],
    data() { 
      return {
          matches:[],
          moment:moment,
          changed:[],
          showBets:false,
          placedBetMessageSuccess:'',
          placedBetMessageWarning:'',
          userTimezone:'',
          pickedWinner:''
      }
    },
    methods:{
        scoreChanged(match,pickedWinner){
            if(!match.pickedWinner)
                match.pickedWinner=pickedWinner;
            else if(match.pickedWinner!=pickedWinner)
                match.pickedWinner=pickedWinner;

            this.$emit('betChange', match);
        },
        format(value, event) {
            return value.toString().replace(/\D/g,'');
        },
    },
    computed: {
      ...mapGetters([
            'getToken','getUsername'
        ]),
    },
    mounted() {
        if(this.match.userTeam1Bet==1)
            this.pickedWinner='HOME_TEAM';
        else if(this.match.userTeam2Bet==1)
            this.pickedWinner='AWAY_TEAM';
    },
}
</script>
