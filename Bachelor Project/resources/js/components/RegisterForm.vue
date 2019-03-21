<template>
  <div class="login-form">
    <form action="#" @submit.prevent="register">
        <b-form-group
            id="fieldset1"
            label="Username"
            label-for="usernameReg"
            :invalid-feedback="invalidFeedbackUsername"
            :state="usernameState"
        >
            <b-form-input required type="text" name="usernameReg" id="usernameReg" v-on:input="usernameChange" :state="usernameState" v-model.trim="usernameReg"></b-form-input>
        </b-form-group>

        <b-form-group
            id="fieldset2"
            label="Email"
            label-for="emailReg"
            :invalid-feedback="invalidFeedbackEmail"
            :state="emailState"
        >
            <b-form-input required type="email" name="emailReg" id="emailReg" v-on:input="emailChange" :state="emailState" v-model.trim="emailReg"></b-form-input>
        </b-form-group>

        <b-form-group
                id="fieldset3"
                label="Password"
                label-for="passwordReg"
                :invalid-feedback="invalidFeedbackPassword"
                :state="passwordState"
        >
                <b-input-group>
                    <b-form-input required :type="passwordType" name="passwordReg" id="passwordReg" v-on:input="passwordChange" :state="passwordState" v-model.trim="passwordReg"></b-form-input>
            
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

        <b-button type="submit" class="col-12" variant="primary">Register</b-button>
        <small style="opacity:0.8">
          This site is protected by reCAPTCHA and the Google
          <a href="https://policies.google.com/privacy">Privacy Policy</a> and
          <a href="https://policies.google.com/terms">Terms of Service</a> apply.
        </small>

    </form>
  </div>
</template>

<script>
export default {
computed: {
    usernameState () {
        if(this.usernameError!='')
            return false
        else
            return null
    },
    invalidFeedbackUsername () {
        if (this.usernameError.length > 0) 
            return this.usernameError
    },

    passwordState () {
        if(this.passwordError!='')
            return false
        else
            return null
    },
    invalidFeedbackPassword () {
        if (this.passwordReg.length > 0)
            return this.passwordError
    },

    emailState () {
        if(this.emailError!='')
            return false
        else
            return null
    },
    invalidFeedbackEmail () {
        if (this.emailReg.length > 0)
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
  data() {
    return {
      usernameReg: '',
      emailReg: '',
      passwordReg: '',
      file: null,
      usernameError:'',
      emailError:'',
      passwordError:'',
      avatarError:'',
      passwordType:'password',
      passwordVisible:false
    }
  },
  methods: {
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
        handleFileUpload(){
            this.file = this.$refs.file.files[0];
        },
        register() {
            let vm = this;
            grecaptcha.ready(function() {
            grecaptcha.execute('6Ld7M5AUAAAAAEl3J6SLIsF2PSUgNi5PDyleRU6q', {action: 'login'}).then(function(token) {
                    vm.$store.dispatch('register', {
                        username: vm.usernameReg,
                        email: vm.emailReg,
                        password: vm.passwordReg,
                        avatar: vm.file,
                        token: token
                    }).then(response => {
                        vm.$emit('registerSuccess', "Registration sucessfull. Before you can log in, you need to activate your account with link that we sent to your email.");
                        }, reject=> {
                            if(reject.response.data.errors.username)
                                vm.usernameError=reject.response.data.errors.username[0]

                            if(reject.response.data.errors.email)
                                vm.emailError = reject.response.data.errors.email[0]
                            
                            if(reject.response.data.errors.password)
                                vm.passwordError = reject.response.data.errors.password[0]
                            
                            if(reject.response.data.errors.avatar || vm.file!=null){
                                vm.avatarError = reject.response.data.errors.avatar[0]
                                if(reject.response.data.errors.avatar[1]){
                                    vm.avatarError += '\n'+reject.response.data.errors.avatar[1]
                                }
                                if(reject.response.data.errors.avatar[2]){
                                    vm.avatarError += '\n'+reject.response.data.errors.avatar[2]
                                }
                            }
                        })
                });
            });
        },
        switchVisibility(){
            this.passwordType = this.passwordType === 'password' ? 'text' : 'password'
            this.passwordVisible = this.passwordVisible === false ? true : false
        }
  }
}
</script>