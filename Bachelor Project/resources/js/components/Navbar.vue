<template>
    <b-navbar toggleable="md" type="light" variant="info" style="font-size:24px">

    <b-navbar-toggle target="nav_collapse"></b-navbar-toggle>

    <b-navbar-brand href="#">FunBet</b-navbar-brand>

    <b-collapse is-nav id="nav_collapse">

        <b-navbar-nav class="ml-auto">
            <b-nav-item>
                <router-link :to="{ name: 'main' }">
                    <i class="material-icons md-36">home</i>
                    Home
                </router-link>
            </b-nav-item>

            <b-nav-item>
                <router-link :to="{ name: 'bets' }">
                    <i class="material-icons md-36">casino</i>
                    Bets
                </router-link>
            </b-nav-item>
            
            <b-nav-item>
                <router-link :to="{ name: 'leaderboards' }">
                    <i class="material-icons md-36">list</i> 
                    Leaderboard
                </router-link>
            </b-nav-item>
            
            <b-nav-item>
                <router-link :to="{ name: 'forum' }"> 
                    <i class="material-icons md-36">forum</i>
                    Forum
                </router-link>
            </b-nav-item>
            
            <b-nav-item>
                <router-link :to="{ name: 'support' }">
                    <i class="material-icons md-36">contact_support</i> 
                    Support
                </router-link>
            </b-nav-item>
        </b-navbar-nav>

        <!-- Right aligned nav items -->
        <b-navbar-nav class="ml-auto">
            <b-dropdown id="ddown-right" right :text="getUsername" variant="primary" class="m-2">
            <b-dropdown-item href="#">
                <router-link :to="{ name: 'accountProfile' }">
                    <i class="material-icons md-28">person</i>My Profile
                </router-link>
            </b-dropdown-item>
            <b-dropdown-item href="#">
                <router-link :to="{ name: 'logout' }"><img src="/images/sign-out.svg" width="28px" height="28px" class="material-icons md-18"  alt="signin"> Logout</router-link>
            </b-dropdown-item>
            
        </b-dropdown>     
        </b-navbar-nav>

    </b-collapse>
    </b-navbar>
</template>

<script>
import {mapGetters} from 'vuex'

export default {
    computed: mapGetters([
        'getUsername'
    ])
}

</script>

