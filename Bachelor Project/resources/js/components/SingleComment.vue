<template>
<div>
    <b-media no-body>
        <b-media-aside vertical-align="center">
        <avatar v-if="comment.user.avatar!='noimage.jpg'"
            :src="'/storage/profile_images/'+comment.user.avatar"
            :size="100">
        </avatar>
        <avatar v-if="comment.user.avatar=='noimage.jpg'"
            :username="comment.user.username"
            :size="100">
        </avatar>
        </b-media-aside>
        <b-media-body class="ml-3">
            <small>Posted by {{comment.user.username}} {{moment(comment.created_at).fromNow()}}</small>
            <br/><br/>

            <p v-html="comment.comment" class="mb-1"></p>

            <div v-if="comment.user.username!=getUsername">
                <b-button :style="[comment.liked ? {'color':'red'} : {'color':'white'}]"  style="border-radius:13px" variant="primary" v-on:click="likeAction(comment.id,$event)">
                    <i class="material-icons md-18">thumb_up</i> {{comment.likes}}
                </b-button>
                <b-button style="border-radius:13px" variant="primary" v-on:click="showAddReply=!showAddReply">
                    <i class="material-icons md-18">reply</i> Reply
                </b-button>
            </div>
            <div v-if="comment.user.username==getUsername && comment.comment!='[comment deleted]'">
                <b-button style="border-radius:13px" variant="primary" v-on:click="deleteComment(comment.id)">
                    <i class="material-icons md-18">delete</i> Delete
                </b-button>
            </div>
        </b-media-body>
    </b-media>
    
    <div v-show="showAddReply">
        <br/>
        <quill-editor ref="myTextEditor"
                    v-model="replyContent"
                    :options="editorOption">
        </quill-editor><br/>
        <b-button class="col-12" style="border-radius:13px" variant="primary" v-on:click="addReply()">
            Reply to comment
        </b-button>
    </div>
</div>
</template>

<script>
import moment from 'moment'
import Avatar from 'vue-avatar'
import axios from 'axios'
import { mapGetters } from 'vuex'
import { quillEditor } from 'vue-quill-editor'

export default {
    props: ['comment'],
    components : {
        Avatar,
        quillEditor,
    },
    data() { 
      return {
        moment:moment,
        showAddReply:false,
        replyContent:'',
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
            placeholder:'Reply to comment'
        },
      }
    },
    methods:{
        likeAction(){
            var vm=this;
            axios({ method: 'POST', 
                    url: '/commentLikeAction', 
                    headers: {'Authorization': 'Bearer ' + this.getToken}, 
                    data: { commentId: vm.comment.id,
                            username: vm.getUsername } 
                }).then(function (response) {
                    if(response.data[0]==true)
                    {
                        vm.comment.liked=true;
                        vm.comment.likes+=1;
                    }
                    else
                    {
                         vm.comment.liked=false;
                        vm.comment.likes-=1;
                    }
                }).catch(function (error) {});
        },
        addReply(){
            var vm=this;
            axios({ method: 'POST', 
                    url: '/commentReply', 
                    headers: {'Authorization': 'Bearer ' + this.getToken}, 
                    data: { commentId: vm.comment.id,
                            username: vm.getUsername,
                            content: vm.replyContent } 
                }).then(function (response) {
                    vm.showAddReply=false;
                    vm.$emit('replyAdded');
                    vm.$emit('commentAdded');
                }).catch(function (error) {});   
        },
        deleteComment(id){
            var vm=this;
            axios.delete('/commentDelete/'+id, {
            params: {
            },
            headers: {
                'Authorization': 'Bearer ' + this.getToken
            }
            }).then(function (response) {
                vm.$emit('commentDeleted');
            }).catch(function (error) {});  
        }
    },
    computed: {
      ...mapGetters([
            'getToken','getUsername'
        ]),
    },
}
</script>
