import { Component } from '@angular/core';
import { NavController, NavParams } from 'ionic-angular';
import {MyNewsApi} from '../../providers/my-news-api';
import {LoginApi} from '../../providers/login-api';
import {LoginPage} from '../login/login';
import { ProfilePage } from '../profile/profile';

@Component({
  selector: 'page-news',
  templateUrl: 'news.html',
  providers: [MyNewsApi]  
})
export class NewsPage {
  public base64Image: string;
  bds : any;
  category:any;

  constructor(public navCtrl: NavController, public navParams: NavParams,public user: LoginApi, public myParsingData: MyNewsApi) {
    this.getAds();
  }

  ionViewDidLoad() {
    this.base64Image =  "http://90.157.177.163/user_image/" + this.user.getImage();
    //console.log('ionViewDidLoad MyParsePage');
  }

  getAds(){
    this.bds = [];
    this.bds = this.myParsingData.loadParsingData();
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
