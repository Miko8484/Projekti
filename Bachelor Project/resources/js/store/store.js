import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)
axios.defaults.baseURL = '/api';

export const store = new Vuex.Store({
    state:{
        token: localStorage.getItem('access_token') || null,
        username : localStorage.getItem('username')
    },
    getters: {
        loggedIn(state) {
          return state.token !== null
        },
        getUsername(state){
          return state.username
        },
        getToken(state){
          return state.token
        }
    },
    mutations: {
        retrieveToken(state, token) {
          state.token = token
        },
        destroyToken(state) {
          state.token = null
          state.username = null
        },
        setUsername(state,username){
          state.username=username
        }
    },
    actions: {
        register(context, data) {

          let formData = new FormData();
          //formData.append('avatar', data.avatar)
          formData.append('avatar', data.avatar ? data.avatar : '');
          formData.append('username', data.username)
          formData.append('email', data.email)
          formData.append('password', data.password)
          formData.append('token', data.token)

          return new Promise((resolve, reject) => {
            axios.post('/register', formData,{
              headers: {
                  'Content-Type': 'multipart/form-data'
              }
            })
              .then(response => {
                resolve(response)
              })
              .catch(error => {
                reject(error)
              })
          })
        },
        destroyToken(context) {
          axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token
    
          if (context.getters.loggedIn) {
            return new Promise((resolve, reject) => {
              axios.post('/logout')
                .then(response => {
                  localStorage.removeItem('access_token')
                  localStorage.removeItem('username')
                  context.commit('destroyToken')
                  resolve(response)
                })
                .catch(error => {
                  localStorage.removeItem('access_token')
                  localStorage.removeItem('username')
                  context.commit('destroyToken')
                  reject(error)
                })
            })
          }
        },
        retrieveToken(context, credentials) {
    
          return new Promise((resolve, reject) => {
            axios.post('/login', {
              username: credentials.username,
              password: credentials.password,
              token: credentials.token
            })
              .then(response => {
                const token = response.data.access_token

                localStorage.setItem('access_token', token)
                localStorage.setItem('username', credentials.username)
                localStorage.setItem('expire',response.data.expires_in)
                localStorage.setItem('refresh_token',response.data.refresh_token)
                context.commit('retrieveToken', token)
                context.commit('setUsername',credentials.username)
                resolve(response)
                
              })
              .catch(error => {
                console.log(error)
                reject(error)
              })
            })
        }
    }
})