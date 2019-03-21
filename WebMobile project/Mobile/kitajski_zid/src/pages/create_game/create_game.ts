import {Component} from '@angular/core';
import {NavController, NavParams} from 'ionic-angular';
import {MyGameApi} from '../../providers/my-game-api';
import {LoginApi} from '../../providers/login-api';
import {LoginPage} from '../login/login';
/*
 Generated class for the Reddits page.

 See http://ionicframework.com/docs/v2/components/#navigation for more info on
 Ionic pages and navigation.
 */
@Component({
    selector: 'page-create_game',
    templateUrl: 'create_game.html',
    providers: [MyGameApi]
})

export class Create_gamePage {


    constructor(public navCtrl: NavController,
                public navParams: NavParams,
                private gameProvider: MyGameApi,
                public user: LoginApi) {
    }

    username = this.user.getUsername();
    text = "";
    public base64Image: string;

    ionViewDidLoad() {
        this.base64Image =  "http://90.157.177.163/user_image/" + this.user.getImage();
    }

    logout(){
    this.user.setUsername('');
    this.user.setID('');
    this.user.setImage('');
    
    this.navCtrl.push(LoginPage);
  }


}
