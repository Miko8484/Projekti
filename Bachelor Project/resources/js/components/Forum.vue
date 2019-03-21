<template>
  <b-container fluid>
    <b-row>
        <b-col sm="2">
          <forumfilter @filterChanged="filterChanged"></forumfilter>
        </b-col>
        <b-col sm="8">
          <b-alert v-if="deleteMessage" variant="success" show>{{deleteMessage}}</b-alert>
          <b-container fluid>
            <b-row>
              <b-col style="padding-left:0" sm="4">
                <b-form-input class="mb-3" placeholder="Search post" type="text" name="findPost" id="findPost"></b-form-input>  
              </b-col>
              <b-col sm="8" class="text-center">
                <span class="spanIcon noselect float-right mt-2" v-on:click="recentFilter">
                  <i id="mostRecent" class="material-icons md-18">swap_vert</i>Most recent
                </span>
                <span class="spanIcon noselect float-right mt-2 mr-2" v-on:click="viewedFilter">
                  <i id="mostViewed" class="material-icons md-18">swap_vert</i>Most viewed
                </span>
              </b-col>
            </b-row>
          </b-container>
          <b-list-group v-if="posts && posts.length">
            <b-list-group-item v-for="post in posts" :key="post.id" href="#" class="flex-column align-items-start">
              <router-link :to="`/post/${post.postHash}/${post.shortTitle}`">
                <b-media no-body>
                  <b-media-aside vertical-align="center">
                    <avatar v-if="post.user.avatar!='noimage.jpg'"
                      :src="'/storage/profile_images/'+post.user.avatar"
                      :size="100">
                    </avatar>
                    <avatar v-if="post.user.avatar=='noimage.jpg'"
                      :username="post.user.username"
                      :size="100">
                    </avatar>
                  </b-media-aside>
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
        </b-col>
    </b-row>
    <br/><br/>
  </b-container>
</template>

<script>
import axios from 'axios'
import forumfilter from './forumfilter.vue'
import moment from 'moment'
import Avatar from 'vue-avatar'
import { mapGetters } from 'vuex'

export default {
    components : {
        forumfilter,
        Avatar
    },
    methods: {
      refreshPosts(){
        var textInput = document.getElementById('findPost');
        let vm=this;
        axios.get('/forum', {
          params: {
            findPost: textInput.value,
            filterBy: this.filterBy,
            filterDirection: this.filterDirection,
            theme: vm.theme
          },
          headers: {
            'Authorization': 'Bearer ' + this.getToken
          }
        }).then(function (response) {
          vm.posts=response.data
        }).catch(function (error) {});
      },
      recentFilter: function(){
          var x = document.getElementById("mostViewed")
            x.innerHTML="swap_vert";
          var x = document.getElementById("mostRecent");
          if(x.innerHTML=="swap_vert" || x.innerHTML=="arrow_drop_down"){
            x.innerHTML="arrow_drop_up";
            this.filterBy="created_at";
            this.filterDirection="desc";
          }
          else if(x.innerHTML=="arrow_drop_up"){
            x.innerHTML="arrow_drop_down";
            this.filterBy="created_at";
            this.filterDirection="asc";
          }

          this.refreshPosts();
      },
      viewedFilter: function(){
          var x = document.getElementById("mostRecent")
            x.innerHTML="swap_vert";
          var x = document.getElementById("mostViewed");
          if(x.innerHTML=="swap_vert" || x.innerHTML=="arrow_drop_down"){
            x.innerHTML="arrow_drop_up";
            this.filterBy="views";
            this.filterDirection="desc";
          }
          else if(x.innerHTML=="arrow_drop_up"){
            x.innerHTML="arrow_drop_down";
            this.filterBy="views";
            this.filterDirection="asc";
          }

          this.refreshPosts();
      },
      filterChanged(filterTheme) {
        var textInput = document.getElementById('findPost');
        let vm = this;
        console.log(filterTheme)
        axios.get('/forum', {
          params: {
            findPost: textInput.value,
            filterBy: vm.filterBy,
            filterDirection: vm.filterDirection,
            theme: filterTheme
          },
          headers: {
            'Authorization': 'Bearer ' + this.getToken
          }
        }).then(function (response) {
          vm.posts=response.data
        }).catch(function (error) {}); 
      }
    },
    data() { 
      return {
        moment:moment,
        posts:[],
        filterBy:'created_at',
        filterDirection:'desc',
        theme:'all',
        deleteMessage:'',
        url:'/forum'
      }
    },
    mounted() {
      let vm = this;
      this.deleteMessage = this.$route.params.userId;
      /*axios.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest'; 
      axios.defaults.headers.common["X-CSRF-TOKEN"] = window.Laravel.csrfToken;*/

      axios.get('/forum', {
        params: {
          filterBy: vm.filterBy,
          filterDirection: vm.filterDirection,
          theme: vm.theme
        },
        withCredentials: true,
        headers: {
          'Authorization': 'Bearer ' + this.getToken,
          //'X-Requested-With': 'XMLHttpRequest',
          //'X-CSRF-TOKEN':window.Laravel.csrfToken
        }
      }).then(function (response) {
        vm.posts=response.data
      }).catch(function (error) {}); 

      var textInput = document.getElementById('findPost');

      var timeout = null;
      textInput.onkeyup = function (e) {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            axios.get('/forum', {
              params: {
                findPost: textInput.value,
                filterBy: vm.filterBy,
                filterDirection: vm.filterDirection
              },
              headers: {
                'Authorization': 'Bearer ' + vm.getToken
              }
            }).then(function (response) {
              vm.posts=response.data
            }).catch(function (error) {}); 
        }, 1000);
      };
    },
    computed: {
      ...mapGetters([
            'getToken'
        ]),
      contentLimit(postContent){
        if ( postContent.length > 100 ) {
          return postContent.substring(0,100) + '...'
        } else {
          return postContent
        }
      }
    },
    watch: {
      deleteMessage: function () {
        var vm=this;
        setTimeout(function(){ vm.deleteMessage=""; }, 5000);
      },
    }
}
</script>
