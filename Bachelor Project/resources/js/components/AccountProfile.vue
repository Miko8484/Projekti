<template>
<div class="container">
    <div class="row my-2">
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
                    <b-tab title="Edit profile">
                        <form  action="#" @submit.prevent="editUser">
                            <b-form-group
                                id="fieldset1"
                                label="Username"
                                label-for="usernameReg"
                                :invalid-feedback="invalidFeedbackUsername"
                                :state="usernameState"
                            >
                                <b-form-input required placeholder="Enter new username" v-on:input="usernameChange" :state="usernameState" type="text" name="usernameReg" id="usernameReg"  v-model.trim="username"></b-form-input>
                            </b-form-group>

                            <b-form-group
                                id="fieldset2"
                                label="Email"
                                label-for="emailReg"
                                :invalid-feedback="invalidFeedbackEmail"
                                :state="emailState"
                            >
                                <b-form-input required placeholder="Enter new email" v-on:input="emailChange" :state="emailState" type="email" name="emailReg" id="emailReg"  v-model.trim="email"></b-form-input>
                            </b-form-group>

                            <b-form-group
                                    id="fieldset3"
                                    label="Password"
                                    label-for="passwordReg"
                                    :invalid-feedback="invalidFeedbackPassword"
                                    :state="passwordState"
                            >
                                    <b-input-group>
                                        <b-form-input placeholder="Enter new password" v-on:input="passwordChange" :state="passwordState" :type="passwordType" name="passwordReg" id="passwordReg"  v-model.trim="password"></b-form-input>

                                        <b-input-group-append>
                                            <b-btn @click="switchVisibility" variant="outline-secondary">
                                                <i class="material-icons md-18" v-if="!passwordVisible">visibility_off</i>
                                                <i class="material-icons md-18" v-if="passwordVisible">visibility</i>
                                            </b-btn>
                                        </b-input-group-append>
                                    </b-input-group>
                            </b-form-group>
                            

                            <b-form-group
                                    id="fieldset4"
                                    class="pre-formatted"
                                    label="Avatar"
                                    label-for="avatarReg"
                                    :invalid-feedback="invalidFeedbackAvatar"
                                    :state="avatarState"
                            >
                                <b-form-file :state="avatarState" v-on:input="fileChange" v-model.trim="file" placeholder="Choose a file..."></b-form-file>

                            </b-form-group>

                            <b-button type="submit" class="col-12" variant="primary">Edit</b-button>
                        </form>
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
        password:'',
        file:'',
        usernameError:'',
        emailError:'',
        passwordError:'',
        avatarError:'',
        passwordType:'password',
        passwordVisible:false,
        forumPosts:[],
        scores:[],
        moment:moment,
      }
    },
    mounted() {
      let vm = this;
      axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.getToken
      axios.get('/profile/me', {
        params: {
        }
      }).then(function (response) {
          vm.username=response.data['user'].username,
          vm.image=response.data['user'].avatar,
          vm.email=response.data['user'].email,
          vm.forumPosts=response.data['forumPosts'],
          vm.scores=response.data['scores']
          console.log(response.data);
      }).catch(function (error) {}); 
    },
    computed: {
        ...mapGetters([
            'getUsername',
            'getToken'
        ]),
        usernameState () {
            if(this.usernameError!='')
                return false
            else
                return null
        },
        invalidFeedbackUsername () {
            if (this.username.length > 0)
                return this.usernameError
        },

        passwordState () {
            if(this.passwordError!='')
                return false
            else
                return null
        },
        invalidFeedbackPassword () {
            if (this.password.length > 0)
                return this.passwordError
        },

        emailState () {
            if(this.emailError!='')
                return false
            else
                return null
        },
        invalidFeedbackEmail () {
            if (this.email.length > 0)
                return this.emailError
        },

        avatarState () {
            if(this.avatarError!='')
                return false
            else
                return null
        },
        invalidFeedbackAvatar () {
            return this.avatarError
        },

        validFeedback () {
        }
    },
    methods:{
        usernameChange(){
            this.usernameError='';
        },
        emailChange(){
            this.emailError='';
        },
        passwordChange(){
            this.passwordError='';
        },
        fileChange(){
            this.avatarError='';
        },
        editUser() {
            let vm=this;
            let formData = new FormData();
            formData.append('oldUsername',vm.getUsername)
            formData.append('avatar', vm.file)
            formData.append('username', vm.username)
            formData.append('email', vm.email)
            formData.append('password', vm.password)
            formData.append('_method', 'PUT');
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.getToken
            axios.post('/profile/'+this.getUsername, formData,{
              headers: {
                  'Content-Type': 'x-www-form-urlencoded'
              }
            }).then(response => {
                localStorage.setItem('username', this.username)
                this.$store.commit('setUsername',this.username);
                vm.password=''
                vm.image=response.data;
              })
              .catch(error => {
                if(reject.response.data.errors.username)
                    this.usernameError=reject.response.data.errors.username[0]

                if(reject.response.data.errors.email)
                    this.emailError = reject.response.data.errors.email[0]
                
                if(reject.response.data.errors.password)
                    this.passwordError = reject.response.data.errors.password[0]
                
                if(reject.response.data.errors.avatar || this.file!=null){
                    this.avatarError = reject.response.data.errors.avatar[0]
                    if(reject.response.data.errors.avatar[1]){
                        this.avatarError += '\n'+reject.response.data.errors.avatar[1]
                    }
                    if(reject.response.data.errors.avatar[2]){
                        this.avatarError += '\n'+reject.response.data.errors.avatar[2]
                    }
                }
              })
        },
        switchVisibility(){
            this.passwordType = this.passwordType === 'password' ? 'text' : 'password'
            this.passwordVisible = this.passwordVisible === false ? true : false
        }
    }
}
</script>
