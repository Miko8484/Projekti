<template>
    <b-container fluid>

        <b-alert :show="hideCountDownSuccess"
            variant="success"
            @dismissed="hideCountDownSuccess=0"
            @dismiss-count-down="countDownChanged">
                {{commentAddedMessage}}
        </b-alert>
        <b-alert :show="hideCountDownWarning"
            variant="warning"
            @dismissed="hideCountDownWarning=0"
            @dismiss-count-down="countDownChanged">
                {{commentAddedMessage}}
        </b-alert>

        <quill-editor ref="myTextEditor"
                    v-model="commentContent"
                    :options="editorOption">
        </quill-editor><br/>
        <b-button class="col-12" style="border-radius:13px" variant="primary" v-on:click="addComment()">
            Add your comment
        </b-button>
        <small style="opacity:0.8">
          This site is protected by reCAPTCHA and the Google
          <a href="https://policies.google.com/privacy">Privacy Policy</a> and
          <a href="https://policies.google.com/terms">Terms of Service</a> apply.
        </small>
        <br/><br/>
        <b-list-group v-if="comments && comments.length">
            <b-list-group-item v-for="comment in comments" :key="comment.id" class="flex-column align-items-start">
                <SingleComment @commentAdded="newReply()" @replyAdded="refreshComments()" @commentDeleted="refreshComments()" :comment="comment"/>
            </b-list-group-item>
        </b-list-group>
    </b-container>
</template>

<script>
import axios from 'axios'
import { quillEditor } from 'vue-quill-editor'
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
import { mapGetters } from 'vuex'
import moment from 'moment'
import Avatar from 'vue-avatar'
import SingleComment from './SingleComment.vue'

export default {
    components : {
        quillEditor,
        Avatar,
        SingleComment
    },
    data() { 
      return {
        commentContent:'',
        comments:[],
        moment:moment,
        editorOption: {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image'],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'header': [2, 3, 4, 5, 6, false] }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'font': [] }],
                    [{ 'align': [] }],      
                ],
            },
            placeholder:'Enter your comment'
        },
        likeButton: {
          color: ''
        },
        hideAfterSeconds:8,
        hideCountDownSuccess:0,
        hideCountDownWarning:0,
        commentAddedMessage:''
      }
    },
    methods:{
        addComment(){
            var vm=this;
            grecaptcha.ready(function() {
                grecaptcha.execute('6Ld7M5AUAAAAAEl3J6SLIsF2PSUgNi5PDyleRU6q', {action: 'login'}).then(function(token) {
                    axios({ method: 'POST', 
                            url: '/commentAdd', 
                            headers: {'Authorization': 'Bearer ' + vm.getToken}, 
                            data: { username: vm.getUsername,
                                    comment: vm.commentContent,
                                    postHash: vm.$route.params.hash,
                                    shortTitle: vm.$route.params.title,
                                    token: token } 
                        }).then(function (response) {
                            vm.$emit('commentAdded');
                            vm.commentAddedMessage="Comment added successfully."
                            vm.hideCountDownSuccess = vm.hideAfterSeconds;
                            vm.refreshComments()
                        }).catch(function (error) {
                            vm.commentAddedMessage=error.response.data;
                            vm.hideCountDownWarning = vm.hideAfterSeconds;
                        });
                    
                });
            });
        },
        refreshComments()
        {
            let vm=this;
            axios.get('/commentGet/'+this.$route.params.hash+'/'+this.$route.params.title, {
                params: {
                    user: vm.getUsername
                },
                headers: {
                'Authorization': 'Bearer ' + this.getToken
                }
            }).then(function (response) {
                vm.comments=response.data
            }).catch(function (error) {});
        },
        newReply()
        {
            this.$emit('commentAdded');
        },
        countDownChanged (hideCountDown) {
            this.hideCountDown = hideCountDown
        },
    },
    computed: {
      ...mapGetters([
            'getToken','getUsername'
        ]),
    },
    mounted(){
        this.refreshComments();
    }
}
</script>

