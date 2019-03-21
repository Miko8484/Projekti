<template>
<div class="container">
    <b-row v-if="error!=''" align-h="center">
        <b-alert class="col-lg-8 order-lg-2" show variant="warning">{{error}}</b-alert>
    </b-row>
    
    <div v-if="error==''" class="row my-2">
        <div class="col-lg-8 order-lg-2">
            <b-card no-body>
                <b-tabs card>
                    <b-tab title="Profile" active>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Username:</h6>
                                <p>
                                    {{username}}
                                </p>
                                <h6>Email:</h6>
                                <p>
                                    {{email}}
                                </p>
                            </div>
                            <div class="col-md-12">

                            </div>
                        </div>
                    </b-tab>
                    <b-tab title="Points">
                        <b-card border-variant="light" v-for="score in scores" :key="score.sport_id">
                            <h4 class="card-title">{{score.sport.sportName.charAt(0).toUpperCase()+ score.sport.sportName.slice(1)}}</h4>
                            <h6 class="card-subtitle mb-2 text-muted">Placed {{score.place}}. in this sport</h6>
                            
                            <table aria-busy="false" aria-colcount="3" aria-rowcount="4" class="table b-table border table-striped">
                                <thead class="">
                                    <tr>
                                        <th aria-colindex="1" class="">League</th>
                                        <th aria-colindex="2" class="">Points</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <tr class="" v-for="league in score.leagues" :key="league.leagueName">
                                        <td aria-colindex="1" class="">{{league.league}}</td>
                                        <td aria-colindex="2" class="">{{league.points}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </b-card>
                    </b-tab>
                    <b-tab title="Forum posts">
                        <b-list-group v-if="forumPosts && forumPosts.length">
                            <b-list-group-item v-for="post in forumPosts" :key="post.id" href="#" class="flex-column align-items-start">
                            <router-link :to="`/post/${post.postHash}/${post.shortTitle}`">
                                <b-media no-body>
                                <b-media-body class="ml-3">
                                    <div class="d-flex justify-content-between">
                                    <h3 class="mb-1">{{post.title}} <span class="badge badge-pill badge-primary">{{post.forum_theme.theme}}</span></h3>
                                    <small>
                                        <i class="material-icons md-18">mode_comment</i> {{post.commentsNumber}} &emsp;
                                        <i class="material-icons md-18">visibility</i> {{post.views}}
                                    </small>
                                    </div>
                                    
                                    <p v-if="post.content.length<=100" v-html="post.content" class="mb-1"></p>
                                    
                                    <p v-if="post.content.length>100" v-html="post.content.substring(0,200)+'...'" class="mb-1"></p>

                                    <small>Posted by {{post.user.username}} {{moment(post.created_at).fromNow()}}</small>
                                </b-media-body>
                                </b-media>
                            </router-link>
                            </b-list-group-item>
                        </b-list-group>
                    </b-tab>
                </b-tabs>
            </b-card>
        </div>
        <div class="col-lg-4 order-lg-1 text-center">
            <img v-if="image" :src="'/storage/profile_images/'+image" class="mx-auto img-fluid img-circle d-block" alt="avatar">
        </div>
    </div>
</div>
</template>

<script>
import axios from 'axios'
import {mapGetters} from 'vuex'
import moment from 'moment'

export default {
    data() { 
      return {
        username:'',
        image:'',
        email:'',
        forumPosts:[],
        scores:[],
        moment:moment,
        error:'',
      }
    },
    mounted() {
      let vm = this;
      axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.getToken
      axios.get('/user/'+this.$route.params.username, {
        params: {
        }
      }).then(function (response) {
          vm.username=response.data['user'].username,
          vm.image=response.data['user'].avatar,
          vm.email=response.data['user'].email,
          vm.forumPosts=response.data['forumPosts'],
          vm.scores=response.data['scores']
      }).catch(function (error) {
          vm.error=error.response.data;
      }); 
    },
    computed: {
        ...mapGetters([
            'getToken'
        ]),
    }
}
</script>
