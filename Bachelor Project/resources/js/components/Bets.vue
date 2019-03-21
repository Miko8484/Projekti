<template>
    <b-container fluid>
        <b-row>
        <b-col sm="8" offset-md="2">
            <b-card>
                <h4 class="card-title ml-2">Football</h4>
                <router-link :to="{ name: 'betting', params: { league: 'LaLiga' } }"> 
                    <b-img class="mr-2 mb-2 imgScale" src="/images/laliga.png" alt="LaLiga" />
                </router-link>
                <router-link :to="{ name: 'betting', params: { league: 'Ligue1' } }"> 
                    <b-img class="mr-2 mb-2 imgScale" src="/images/Ligue1.png" alt="Ligue1" />
                </router-link>
                <router-link :to="{ name: 'betting', params: { league: 'PremierLeague' } }"> 
                    <b-img class="mr-2 mb-2 imgScale" src="/images/Premier_League_Logo.jpg" alt="PremierLeague" />
                </router-link>
                <router-link :to="{ name: 'betting', params: { league: 'Bundesliga' } }"> 
                    <b-img class="mr-2 mb-2 imgScale" src="/images/Bundesliga_logo.svg.png" alt="Bundesliga" />
                </router-link>
                <router-link :to="{ name: 'betting', params: { league: 'SeriaA' } }"> 
                    <b-img class="mr-2 mb-2 imgScale" src="/images/SerieA_logo.png" alt="SeriaA" />
                </router-link>
                <router-link :to="{ name: 'betting', params: { league: 'ChampionsLeague' } }"> 
                    <b-img class="mr-2 mb-2 imgScale" src="/images/champions_league.png" alt="Champions league" />
                </router-link>
                <br/><br/>

                <h4 class="card-title ml-2">Basketball</h4>
                <router-link :to="{ name: 'betting', params: { league: 'NBA' } }"> 
                    <b-img class="mr-2 mb-2 imgScale" src="/images/Nba-logo.jpg" alt="NBA" />
                </router-link>
                <br/><br/>

                <h4 class="card-title ml-2">Ice hockey</h4>
                <router-link :to="{ name: 'betting', params: { league: 'NHL' } }"> 
                    <b-img class="mr-2 mb-2 imgScale" src="/images/NHL-Logo.svg.png" alt="NBA" />
                </router-link>
                <br/><br/>
            </b-card>
        </b-col>
        </b-row>
    </b-container>
</template>

<script>
import axios from 'axios'
export default {
    
}
</script>
