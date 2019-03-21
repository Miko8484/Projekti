import { Component } from '@angular/core';
import { NavController, NavParams } from 'ionic-angular';
import {MyRedditApi} from '../../providers/my-reddit-api';
import {LoginPage} from '../login/login';
import { ProfilePage } from '../profile/profile';

import {LoginApi} from '../../providers/login-api';

@Component({
selector: 'page-reddit',
templateUrl: 'reddit.html',
providers: [MyRedditApi]
})

export class RedditPage {
items: any;
category:any;
limit:any;
public base64Image: string;


constructor(public navCtrl: NavController, public navParams: NavParams, private redditProvider: MyRedditApi,public user: LoginApi) {}

ionViewDidLoad() {
  this.base64Image =  "http://90.157.177.163/user_image/" + this.user.getImage();
  this.getDefaults();
  this.getPosts(this.category, this.limit);
}

getDefaults(){
  this.category = 'artificial';
  this.limit = 30;
}

getPosts(category, limit){
  this.redditProvider.getPosts(category, limit).subscribe(response => {
    this.items = response.data.children;
    //console.log(response.data.children);
  }); 
}

changeCategory(){
  this.limit = 30;
  this.getPosts(this.category, this.limit);
}

getThumbnailImage(picture_name)
{
    if(picture_name==="self" || picture_name==="default")
      return "https://www.megx.net/net.megx.esa/img/no_photo.png";
    else
      return picture_name;
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
