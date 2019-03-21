<template>
    <div class="login-form">
        <form enctype="multipart/form-data" action="#" @submit.prevent="login">

        <b-form-group
            id="fieldset1"
            label="Username"
            label-for="usernameLog"
            :state="usernameState"
        >
            <b-form-input type="text" name="usernameLog" id="usernameLog" v-on:input="usernameChange" :state="usernameState" v-model.trim="usernameLog"></b-form-input>
        </b-form-group>

        <b-form-group
                id="fieldset2"
                label="Password"
                label-for="passwordLog"
                :state="passwordState"
            >
                <b-form-input type="password" name="passwordLog" id="passwordLog" v-on:input="passwordChange" :state="passwordState" v-model.trim="passwordLog"></b-form-input>
        </b-form-group>

        <b-button type="submit" class="col-12" variant="primary">Login</b-button>
        <small style="opacity:0.8">
          This site is protected by reCAPTCHA and the Google
          <a href="https://policies.google.com/privacy">Privacy Policy</a> and
          <a href="https://policies.google.com/terms">Terms of Service</a> apply.
        </small>

        </form>

        <b-button class="col-12 mt-2 mb-2" style="border-radius:13px" variant="primary" v-on:click="fblogin()">
            Facebook
        </b-button>
    </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'login',
   data() {
    return {
      usernameLog: '',
      passwordLog: '',
      usernameError:'',
      passwordError:'',
      usernameState:null,
      passwordState:null,
      captcha:'',
    }
  },
  methods: {
    fblogin(){
      //axios.defaults.headers.common["Access-Control-Allow-Origin"] = 'maskole'
      axios({ method: 'GET', 
              url: '/redirect',
                }).then(function (response) {
                  window.location=response.data;
                    //console.log(response);
                }).catch(function (error) {console.log(error)});
    },
    usernameChange(){
        this.usernameState=null;
    },
    passwordChange(){
        this.passwordState=null;
    },
    login() {
      if(this.usernameLog.length==0 || this.passwordLog.length==0)
      {
        if(this.usernameLog.length==0 && this.passwordLog.length==0)
        {
          this.usernameState=false;
          this.passwordState=false;
          this.$emit('messageChanged', "Please enter your username and password.");
        }
        else if(this.usernameLog.length==0)
        {
          this.usernameState=false;
          this.$emit('messageChanged', "Please enter your username.");
        }
        else if(this.passwordLog.length==0)
        {
          this.passwordState=false;
          this.$emit('messageChanged', "Please enter your password.");
        }
      }
      else
      {
        let vm=this;
        grecaptcha.ready(function() {
            grecaptcha.execute('6Ld7M5AUAAAAAEl3J6SLIsF2PSUgNi5PDyleRU6q', {action: 'login'}).then(function(token) {
                vm.$store.dispatch('retrieveToken', {
                  username: vm.usernameLog,
                  password: vm.passwordLog,
                  token: token
                }).then(response => {
                    vm.$router.push({ name: 'main' })
                  }, reject=> {
                      if(vm.usernameLog.length==0)
                        vm.usernameError='Please enter username.'
                      if(vm.passwordLog.length==0)
                        vm.passwordError='Please enter password.'
                      else
                      {
                        vm.$emit('messageChanged', reject.response.data)
                      }
                })
            });
        });
        //grecaptcha.reset();
        
      }
    }
  }
}
</script>
