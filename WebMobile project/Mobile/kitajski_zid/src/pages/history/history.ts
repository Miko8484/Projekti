import {Component} from '@angular/core';
import {NavController, NavParams} from 'ionic-angular';
import {MyHistoryApi} from '../../providers/my-history-api';
import {LoginApi} from '../../providers/login-api';
import {LoginPage} from '../login/login';
import {History} from '../../providers/history';
import { SocialSharing } from '@ionic-native/social-sharing';
/*
 Generated class for the Reddits page.

 See http://ionicframework.com/docs/v2/components/#navigation for more info on
 Ionic pages and navigation.
 */
@Component({
    selector: 'page-history',
    templateUrl: 'history.html',
    providers: [MyHistoryApi,SocialSharing]
})

export class HistoryPage {
    text: String = "";
    public base64Image: string;

    history_arr: History[] = [];

    constructor(public navCtrl: NavController,
                public navParams: NavParams,
                private historyProvider: MyHistoryApi,
                public user: LoginApi,
				private socialSharing: SocialSharing) {
    }

    username = this.user.getUsername();
    id = this.user.getID();

    enemy = "goblins";//fix tu more biti username od p2
    ionViewDidLoad() {
        this.base64Image =  "http://90.157.177.163/user_image/" + this.user.getImage();
        this.getHistory(this.username);
    }

	
	facebookShare(){
		var url = "http://90.157.177.163//?controller=history&action=player_history&id="+this.id;
		var message = " I just achieved a great victory!" + url;
		//(message, subject, file, url)
		//shareViaFacebookWithPasteMessageHint(message, image, url, pasteMessageHint)
		this.socialSharing.shareViaFacebookWithPasteMessageHint(" I just achieved a great victory!" + url, null, url ,message).then((success) => {
  			}, (err) => {
			alert(err);
		});
	}
	
	basicShare(){
        var url = "http://90.157.177.163//?controller=history&action=player_history&id="+this.id;
			    this.socialSharing.share(" I just achieved a great victory!",
	url, null,
	null); 
	}

    getHistory(username) {
        this.historyProvider.getHistory(username).subscribe(response => {
            if(response==undefined){
                this.text="result is undefined";
            }else {
                this.history_arr = response;
                console.log(response)
            }
        });
    }

    logout(){
    this.user.setUsername('');
    this.user.setID('');
    this.user.setImage('');
    this.navCtrl.push(LoginPage);
    }


}
