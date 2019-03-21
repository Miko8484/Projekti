    import Vue from 'vue'
    import VueRouter from 'vue-router'
    import BootstrapVue from 'bootstrap-vue'
    import {store} from './store/store'
    import {ServerTable, ClientTable, Event} from 'vue-tables-2';
    import axios from 'axios';

    Vue.use(BootstrapVue);
    Vue.use(VueRouter);
    Vue.use(ServerTable, {}, false, 'bootstrap4', 'default');

    window.Vue = require('vue');



/*
    axios.interceptors.response.use(
        response => response,
        error => {
            // Reject promise if usual error
            if (error.response.status !== 500) {
                return Promise.reject(error);
            }

            /* 
                * When response code is 401, try to refresh the token.
                * Eject the interceptor so it doesn't loop in case
                * token refresh causes the 401 response
                */
            //axios.interceptors.response.eject(interceptor);
       /*     console.log("aaaa");
            return axios.post('/refreshToken', {
                'refresh_token': store.getters.getUsername
            }).then(response => {
                console.log(response);
                /*saveToken();
                error.response.config.headers['Authorization'] = 'Bearer ' + response.data.access_token;
                return axios(error.response.config);*/
        /*    }).catch(error => {
                //console.log(error);
                /*destroyToken();
                this.router.push('/login');
                return Promise.reject(error);*/
        /*    });
        }
    );*/





    import Landing from './components/LandingPage.vue'
    import Home from './components/HomePage.vue'
    import Logout from './components/Logout.vue'
    import Leaderboards from './components/Leaderboards.vue'
    import Navbar from './components/Navbar.vue'
    import Forum from './components/Forum.vue'
    import AccountProfile from './components/AccountProfile.vue'
    import UserProfile from './components/UserProfile.vue'
    import CreatePost from './components/CreatePost.vue'
    import ViewPost from './components/ViewPost.vue'
    import Bets from './components/Bets.vue'
    import Betting from './components/Betting.vue'

    const router = new VueRouter({
        mode: 'history',
        routes: [
            {
                path: '/',
                name: 'home',
                components: {
                    con: Landing
                  },
                meta: {
                    requiresVisitor: true,
                }
            },
            {
                path: '/main',
                name: 'main',
                components: {
                    nav: Navbar,
                    con: Home
                  },
                meta: {
                    requiresAuth: true,
                }
            },
            {
                path: '/leaderboards',
                name: 'leaderboards',
                components: {
                    nav: Navbar,
                    con: Leaderboards
                  },
                meta: {
                    requiresAuth: true,
                }
            },
            {
                path: '/forum',
                name: 'forum',
                components: {
                    nav: Navbar,
                    con: Forum
                  },
                meta: {
                    requiresAuth: true,
                }
            },
            {
                path: '/logout',
                name: 'logout',
                components: {
                    con: Logout
                  },
                meta: {
                    requiresAuth: true,
                }
            },
            {
                path: '/account',
                name: 'accountProfile',
                components: {
                    nav: Navbar,
                    con: AccountProfile
                  },
                meta: {
                    requiresAuth: true,
                }
            },
            {
                path: '/user/:username',
                name: 'userProfile',
                components: {
                    nav: Navbar,
                    con: UserProfile
                  },
                meta: {
                    requiresAuth: true,
                }
            },
            {
                path: '/createpost',
                name: 'createpost',
                components:{
                    nav: Navbar,
                    con: CreatePost
                },
                meta: {
                    requiresAuth:true,
                }
            },
            {
                path: '/post/:hash/:title',
                name: 'post',
                components:{
                    nav: Navbar,
                    con: ViewPost
                },
                meta: {
                    requiresAuth:true,
                }
            },
            {
                path: '/commentAdd',
                name: 'commentAdd',
                components:{
                    nav: Navbar,
                    con: ViewPost
                },
                meta: {
                    requiresAuth:true,
                }
            },
            {
                path: '/bets',
                name: 'bets',
                components:{
                    nav: Navbar,
                    con: Bets
                },
                meta: {
                    requiresAuth:true,
                }
            },
            {
                path: '/betting/:league',
                name: 'betting',
                components:{
                    nav: Navbar,
                    con: Betting
                },
                meta: {
                    requiresAuth:true,
                }
            },
            /*{
                path :'*',
                name: 'notfound',
                component: Home,
                meta: {
                    requiresVisitor: true,
                }
            }*/
        ],
    });

    router.beforeEach((to, from, next) => {
        if (to.matched.some(record => record.meta.requiresAuth)) {
          if (!store.getters.loggedIn) {
            next({
              name: 'home',
            })
          } else {
            next()
          }
        } else if (to.matched.some(record => record.meta.requiresVisitor)) {
          if (store.getters.loggedIn) {
            next({
              name: 'main',
            })
          } else {
            next()
          }
        } else {
          next()
        }
      })


    const app = new Vue({
        el: '#app',
        components: { Home, Landing, Logout, Navbar, Leaderboards, Forum },
        router,
        store,
    });


