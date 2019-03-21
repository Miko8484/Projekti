<template>
    <b-card no-body class="text-center" style="max-width: 20rem;">
        <b-card-body>
            <p class="card-text">
                <router-link :to="{ name: 'createpost' }">
                    <b-button size="lg" style="border-radius:13px" variant="primary">
                        <i class="material-icons md-25">add</i> Create post
                    </b-button>
                </router-link>
            </p>
        </b-card-body>
        <b-list-group flush>
            <b-list-group-item @click="activate('all')" :class="{ active : active_el == 'all' }" href="#some-link">All discussion</b-list-group-item>
            <b-list-group-item @click="activate('general')" :class="{ active : active_el == 'general' }" href="#">General</b-list-group-item>
            <b-list-group-item @click="activate('sports')" :class="{ active : active_el == 'sports' }" href="#">Sports</b-list-group-item>
            <b-list-group-item @click="activate('bets')" :class="{ active : active_el == 'bets' }" href="#">Bets</b-list-group-item>
            <b-list-group-item @click="activate('news')" :class="{ active : active_el == 'news' }" href="#">News</b-list-group-item>
        </b-list-group>
    </b-card>
</template>

<script>
export default {
    data() {
        return{
            active_el:1
        }
    },
    methods:{
        activate:function(el){
            this.active_el = el;
            this.$emit('filterChanged', el);
        }
    }
}
</script>
