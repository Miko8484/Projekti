<template>
    <b-container fluid>
    <b-row>
        <b-col sm="2">
          
        </b-col>
        <b-col sm="8">
            <b-card>
              <b-alert v-if="editMessage" variant="success" show>{{editMessage}}</b-alert>

              <div class="d-flex bd-highlight">
                <div class="p-2 flex-grow-1 bd-highlight"><h1>{{title}}</h1></div>
                <div class="mt-2 p-2 bd-highlight"><i class="material-icons md-18">mode_comment</i> {{postCommentsCount}} &emsp;</div>
                <div class="mt-2 p-2 bd-highlight"><i class="material-icons md-18">visibility</i> {{postViewsCount}} &emsp;</div>
                <div class="mt-2 p-2 bd-highlight"><i class="material-icons md-18">thumb_up</i> {{postLikesCount}} &emsp;</div>
                <div class="mt-2 p-2 bd-highlight"><i class="material-icons md-18">thumb_down</i> {{postDislikesCount}} &emsp;</div>
              </div>

              <small>Posted by {{author}} {{moment(postDate).fromNow()}} in </small>
              <b-badge style="font-size:0.8rem" pill variant="primary">{{postTheme}}</b-badge>
              <br/><br/>

              <div v-if="!editMode">
                <p v-html="content">{{content}}</p>
                <div v-if="author!=getUsername">
                  <b-button v-bind:style="likeButton" style="border-radius:13px" variant="primary" v-on:click="likeAction('like')">
                      <i  class="material-icons md-18">thumb_up</i> Like
                  </b-button>
                  <b-button v-bind:style="dislikeButton" style="border-radius:13px" variant="primary" v-on:click="likeAction('dislike')">
                      <i class="material-icons md-18">thumb_down</i> Dislike
                  </b-button>
                </div>

                <div v-if="author==getUsername">
                  <b-button style="border-radius:13px" variant="primary" v-on:click="editMode=!editMode">
                      <i  class="material-icons md-18">edit</i> Edit
                  </b-button>
                  <b-button style="border-radius:13px" variant="primary" v-on:click="deletePost()">
                      <i class="material-icons md-18">delete</i> Delete
                  </b-button>
                </div>
              </div>

              <div v-if="editMode">
                <b-form-group
                    id="fieldset2"
                    label-for="postTheme"
                >
                    <b-form-select required v-model.trim="theme" :options="options" class="mb-3" />
                </b-form-group> 
                    
                <vue-editor class="mb-3" placeholder="Text" v-model="content" :editorToolbar="customToolbar"></vue-editor>

                <b-button style="border-radius:13px" class="col-12" variant="primary" v-on:click="editPost()">
                    <i  class="material-icons md-18">edit</i> Edit
                </b-button>
              </div>
              <hr/>

              <forum-comments @commentAdded="postCommentsCount+=1;"></forum-comments>

            </b-card>
            <br/>
        </b-col>
    </b-row>
  </b-container>
</template>

<script>
import axios from 'axios'
import moment from 'moment'
import { mapGetters } from 'vuex'
import { VueEditor } from "vue2-editor";
import ForumComments from './ForumComments.vue'


export default {
    components : {
        VueEditor,
        ForumComments
    },
    methods: {
      likeAction($action){
        var vm=this;
        axios({ method: 'POST', 
                url: '/postLikeAction', 
                headers: {'Authorization': 'Bearer ' + this.getToken}, 
                data: { postHash: vm.$route.params.hash,
                        shortTitle: vm.$route.params.title,
                        action: $action,
                        username: vm.getUsername } 
                }).then(function (response) {
                    vm.showLike=response.data[0];
                    vm.postLikesCount=response.data[1];
                    vm.showDislike=response.data[2];
                    vm.postDislikesCount=response.data[3]
                }).catch(function (error) {});
      },
      deletePost(){
        var vm=this;
        axios.delete('/postDelete/'+this.$route.params.hash+'/'+this.$route.params.title, {
        params: {
        },
        headers: {
            'Authorization': 'Bearer ' + this.getToken
        }
        }).then(function (response) {
          vm.$router.push({ name: 'forum' });
        }).catch(function (error) {});
      },
      editPost(){
        let vm=this;
        let formData = new FormData();
        formData.append('theme',vm.theme)
        formData.append('content', vm.content)
        formData.append('_method', 'PUT');
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.getToken
        axios.post('/postEdit/'+this.$route.params.hash+'/'+this.$route.params.title, formData,{
          headers: {
              'Content-Type': 'x-www-form-urlencoded'
          }
        }).then(response => {
          vm.postTheme=vm.theme,
          vm.editMode=false,
          vm.editMessage=response.data
        })
          .catch(error => {})
      }
    },
    data() { 
      return {
        title:'',
        content:'',
        author:'',
        postDate:'',
        postTheme:'',
        postViewsCount:'',
        postCommentsCount:'',
        postLikesCount:'',
        postDislikesCount:'',
        showLike:'',
        showDislike:'',
        moment:moment,
        editMode:false,
        theme: null,
        editMessage:'',
        likeButton: {
          color: ''
        },
        dislikeButton: {
          color: ''
        },
        customToolbar: [
            ["bold", "italic", "underline"],
            [{'align': ''}, {'align': 'center'}, {'align': 'right'}, {'align': 'justify'}],
            [{ list: "ordered" }, { list: "bullet" }],
            ['blockquote'],
            [{ 'script': 'sub'}, { 'script': 'super' }],
            [{ 'color': [] }, { 'background': [] }],
            ['link', "image"],
            ['clean'],
        ],
        selected: null,
        options: [
            { value: null, text: 'Please select a category that post belongs to' },
            { value: 'Bets', text: 'Bets' },
            { value: 'General', text: 'General' },
            { value: 'News', text: 'News' },
            { value: 'Sports', text: 'Sports' },
        ]
      }
    },
    mounted() {
      let vm = this;
      axios.get('/post/'+this.$route.params.hash+'/'+this.$route.params.title, {
        params: {
        },
        headers: {
          'Authorization': 'Bearer ' + this.getToken
        }
      }).then(function (response) {
        vm.title = response.data.title,
        vm.content = response.data.content,
        vm.author = response.data.user.username,
        vm.postDate = response.data.created_at,
        vm.postTheme = response.data.forum_theme.theme,
        vm.postViewsCount = response.data.views,
        vm.postCommentsCount = response.data.commentsNumber,
        vm.postLikesCount = response.data.likes,
        vm.postDislikesCount = response.data.dislikes
        vm.theme = response.data.forum_theme.theme
      }).catch(function (error) {});

      axios.get('/postLikes', {
        params: {
          postHash: vm.$route.params.hash,
          shortTitle: vm.$route.params.title,
          username: vm.getUsername
        },
        headers: {
          'Authorization': 'Bearer ' + this.getToken
        }
      }).then(function (response) {
        vm.showLike=response.data['like'],
        vm.showDislike=response.data['dislike']
      }).catch(function (error) {});
    },
    computed: {
      ...mapGetters([
            'getToken','getUsername'
        ]),
    },
    watch: {
      showLike: function () {
        if(this.showLike)
        {
          this.likeButton.color="red";
          this.dislikeButton.color="white";
        }
        else
          this.likeButton.color="white";
      },
      showDislike: function () {
        if(this.showDislike)
        {
          this.dislikeButton.color="red";
          this.likeButton.color="white";
        }
        else
          this.dislikeButton.color="white";
      },
      editMessage: function () {
        var vm=this;
        setTimeout(function(){ vm.editMessage=""; }, 5000);
      },
    },
}
</script>

