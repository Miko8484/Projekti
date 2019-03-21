import {Component} from '@angular/core';
import {NavController, NavParams} from 'ionic-angular';
import {MyGameApi} from '../../providers/my-game-api';
import {Board} from '../../providers/board';
import {LoginApi} from '../../providers/login-api';
import {LoginPage} from '../login/login';
/*
 Generated class for the Reddits page.

 See http://ionicframework.com/docs/v2/components/#navigation for more info on
 Ionic pages and navigation.
 */
@Component({
    selector: 'page-game',
    templateUrl: 'game.html',
    providers: [MyGameApi],
    styles: [`
    .card {
      height: 70px;
      width: 100px;
    }
    .cell{
    float: left;
    width: 40px;
    height: 40px;
    font-size: 18px;
    text-align:center;
    border: 1px solid #383838;
    }
    .owner0{
    background-color: #5e802e;
}
.owner1{
    background-color: #7f262a;
}
.owner2{
    background-color: #5b94bb;
}
.center_table{

    margin: 0 auto !important;
    float: none !important;
}
  `],
})

export class GamePage {
    item: Board = {
        board: null
    };
    a: Number;
    b: Number[];
    c: Number;
    text: String = "";
    public base64Image: string;


    constructor(public navCtrl: NavController,
                public navParams: NavParams,
                private gameProvider: MyGameApi,
                public user: LoginApi) {
    }

    username = this.user.getUsername();
    enemy = "goblins";//fix tu more biti username od p2
    game_id = 110;

    ionViewDidLoad() {
        this.base64Image =  "http://90.157.177.163/user_image/" + this.user.getImage();
        this.getGame(this.username);
    }

    getGame(username) {
        this.gameProvider.getGame(username).subscribe(response => {
            if(response.board==undefined){
                this.text="you are not ingame";
            }else {
                this.item = response;
                console.log(response)
            }
            });
    }

    send(event, x1, y1) {
        var coor: { x: Number; y: Number; } = {x: x1, y: y1};
        console.log("sending " + JSON.stringify(coor));
        this.gameProvider.send(coor, this.username).subscribe(response => {
            this.a = response[0];
            this.b = response[1];
            this.c = response[2];
            console.log(this.b);
            if (this.a==1) {
                //var element = angular.element('#'+this.b[0] + "," + this.b[1]');
                var element = document.getElementById(this.b[0] + "," + this.b[1]);
                console.log(element);
                element.classList.remove('owner0');
                element.classList.add('owner2');
                event.target.classList.remove('owner0'); // To Remove
                event.target.classList.add('owner1'); // To ADD
            }
        });
    }

    clicked(event) {
        event.target.classList.remove('class1'); // To Remove
        event.target.classList.contains('class2'); // To check
        event.target.classList.toggle('class4'); // To toggle
    }


    logout(){
    this.user.setUsername('');
    this.user.setID('');
    this.user.setImage('');
    
    this.navCtrl.push(LoginPage);
  }


}
