<template>
    <div>      
        <header class="masthead">
            <div class="container-fluid h-100">
                <div class="row h-100">
                    <div class="col-lg-6 my-auto">
                        <div class="header-content mx-auto">
                            <h1 class="mb-5">
                                Compete with your friends and people around the world in betting. Join FunBet today and start betting for free!
                            </h1>
                        </div>
                    </div>
                    <div class="col-lg-6 my-auto">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-9 col-md-7 col-lg-8 mx-auto">
                                    <div class="card card-signin my-5">
                                        <div class="col-sm-12 col-12 card-header text-center">
                                            <ul class="nav nav-pills card-header-pills">
                                                <li class="col-sm-6 col-6 nav-item">
                                                <a v-on:click="showLogin = true; showRegister=false; showError=false" 
                                                    v-bind:class="{ active: showLogin }"
                                                    class="nav-link" id="loginFormLink" href="#">Login</a>
                                                </li>
                                                <li class="col-sm-6 col-6 nav-item">
                                                <a v-on:click="showRegister = true; showLogin=false; showError=false"
                                                    v-bind:class="{ active: showRegister }"
                                                    class="nav-link" id="registerFormLink" href="#">Register</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <b-alert class="pre-formatted" v-if="showError" show variant="danger">{{errorMessage}}</b-alert>
                                            <b-alert  v-if="showSuccess" variant="success" show>{{successMessage}}</b-alert>
                                            <loginform @messageChanged="errorMessage = $event;showError=true; showSuccess=false;" v-if="showLogin"></loginform>
                                            <registerform @registerSuccess="successMessage=$event; showRegister=false; showLogin=true; showSuccess=true;" v-if="showRegister"></registerform>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section class="features" id="features">
    <div class="container">
      <div class="section-heading text-center">
        <h2>Unlimited Features, Unlimited Fun</h2>
        <p class="text-muted">Check out what you can do with this app theme!</p>
        <hr>
      </div>
      <div class="row">
        
        <div class="col-lg-12 my-auto">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-4">
                <div class="feature-item">
                  <i class="icon-screen-smartphone text-primary"></i>
                  <h3>Device Mockups</h3>
                  <p class="text-muted">Place bets on multiple sports and leagues that we support, with new being added !</p>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="feature-item">
                  <i class="icon-camera text-primary"></i>
                  <h3>Leaderboards</h3>
                  <p class="text-muted">Compete with people around the world and see how you place against them!</p>
                </div>
              </div>
            
              <div class="col-lg-4">
                <div class="feature-item">
                  <i class="icon-present text-primary"></i>
                  <h3>Discussions</h3>
                  <p class="text-muted">Join or create discussions on forum, find out what other players think!</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
    </div>
</template>

<script>
    import loginform from './LoginForm.vue'
    import registerform from './RegisterForm.vue'
    export default {
        components : {
            loginform,
            registerform,
        },
        data() {
            return {
                showLogin: true,
                showRegister: false,
                showError: false,
                showSuccess: false,
                errorMessage:'',
                successMessage:''
            }
        }
    }
</script>