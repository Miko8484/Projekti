import { Component } from '@angular/core';
import { NavController, NavParams } from 'ionic-angular';
import {MyUsersApi} from '../../providers/my-users-api';
import {LoginPage} from '../login/login';
import { ProfilePage } from '../profile/profile';

import {LoginApi} from '../../providers/login-api';

@Component({
selector: 'page-users',
templateUrl: 'users.html',
providers: [MyUsersApi]
})

export class UsersPage {
items: any;
public base64Image: string;

constructor(public navCtrl: NavController, public navParams: NavParams, private usersProvider: MyUsersApi, public user: LoginApi) {}

ionViewDidLoad() {
  this.base64Image =  "http://90.157.177.163/user_image/" + this.user.getImage();
  this.getPosts();
}

getPosts(){
  this.usersProvider.getPosts().subscribe(response => {
    this.items = response;
  }); 
}

logout(){
    this.user.setUsername('');
    this.user.setID('');
    this.user.setImage('');
    
    this.navCtrl.push(LoginPage);
  }
profile()
  {
    this.navCtrl.push(ProfilePage);
  }

}
