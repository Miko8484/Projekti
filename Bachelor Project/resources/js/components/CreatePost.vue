<template>
    <b-container fluid>
    <b-row>
        <b-col sm="2">
          
        </b-col>
        <b-col sm="8">
            <b-alert v-if="errorMessage!=''" variant="danger" show>
                {{errorMessage}}
            </b-alert>
             <b-card header="Create new post" headerClass="biggerCardHeader">
                <form  action="#" @submit.prevent="createPost">
                    <b-form-group
                        id="fieldset1"
                        label-for="postTitle"
                    >
                        <b-form-input class="mb-3" size="lg" required placeholder="Title" type="text" name="postTitle" v-model.trim="title"></b-form-input>
                    </b-form-group> 

                    <b-form-group
                        id="fieldset2"
                        label-for="postTheme"
                    >
                        <b-form-select required v-model.trim="theme" :options="options" class="mb-3" />
                    </b-form-group> 
                       
                    <vue-editor class="mb-3" placeholder="Text" v-model="content" :editorToolbar="customToolbar"></vue-editor>

                    <b-button type="submit" class="col-12" size="lg" variant="primary">Create post</b-button>
                       
                </form>
            </b-card>
        </b-col>
    </b-row>
  </b-container>
</template>

<script>
import { VueEditor } from "vue2-editor";
import axios from 'axios';
import {mapGetters} from 'vuex';

export default {
    components: {
        VueEditor
    },
    computed:{
        ...mapGetters([
            'getUsername',
            'getToken'
        ]),
    },
    data() { 
      return {
        title:'',
        theme:null,
        content: '',
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
        ],
        errorMessage:'',
        errorMessageShow:false
      }
    },
    methods:{
        createPost(){
            let vm = this
            axios({ method: 'POST', 
                    url: '/forum', 
                    headers: {'Authorization': 'Bearer ' + this.getToken}, 
                    data: { title: vm.title,
                            content: vm.content,
                            theme: vm.theme } 
                }).then(function (response) {
                    vm.$router.push('/post/'+response.data.secret+'/'+response.data.shortTitle);
                }).catch(function (error) {
                    vm.errorMessageShow=true;
                    if(error.response.data.errors.title!='')
                        vm.errorMessage=error.response.data.errors.title[0];
                    if(error.response.data.errors.content!='')
                        vm.errorMessage+=error.response.data.errors.content[0];
                });
        }
    },
}
</script>
