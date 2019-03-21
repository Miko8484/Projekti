import { Component } from '@angular/core';
import { NavController, NavParams, AlertController } from 'ionic-angular';
import { Geolocation } from '@ionic-native/geolocation';
import { CountryApi } from '../../providers/country-api';
import { ProfilePage } from '../profile/profile';
import {LoginApi} from '../../providers/login-api';
import {LoginPage} from '../login/login';

@Component({
  selector: 'page-about',
  templateUrl: 'about.html',
  providers: [CountryApi]
})
export class AboutPage {
  public base64Image: string;
  items: any;
  country_api: string;

  constructor(public navCtrl: NavController, public navParams: NavParams,public user: LoginApi, public geolocation: Geolocation, public country: CountryApi, private alertCtrl: AlertController) {}

  ionViewDidLoad() {
    this.base64Image =  "http://90.157.177.163/user_image/" + this.user.getImage();
    this.loadPosition();
  }

  ionViewDidEnter()
  {
    this.base64Image =  "http://90.157.177.163/user_image/" + this.user.getImage();
  }

  loadPosition(){
		this.geolocation.getCurrentPosition().then((position) => {
      this.country.getCountry(position.coords.latitude,position.coords.longitude).subscribe(response => {
          this.items = response;

          this.country_api=this.items.results[7].formatted_address;

          if(this.country_api=="Slovenija" || this.country_api=="Slovenia")
            this.change_to_slo();
          else
            this.change_to_usa();
      }); 
		});

	}

  change_to_usa(){
    document.getElementById("text").innerHTML = 
    "Chinese wall is a strategy board game. It is played by two players.<br/>"+
    "In-game players place pieces called wall on board. You win by securing more territory than your opponent.<br/><br/>"+
    
    "The board has a (X,Y) sized grid and a point in grid is called field. Size of the board depends on the game mode that is decided before the start of the game.<br/>"+
    "Each player may only play one piece per turn. Player one is called white player, while player 2 is called black.<br/><br/>"+
    
    "White has white wall pieces, while black has black wall pieces. The game starts by white player placing a piece anywhere on the board, followed by the black player.<br/><br/>"+
    
    "The next pieces can only be placed in 1x1 proximity of another piece of the same color.<br/>"+
    "Both players continue playing pieces on empty fields until one played can no longer place a piece anywhere on the board. Then that player loses.<br/>"+
    "The gameplay is somewhat similar to Chinese board game GO, with the exception of that the pieces are not taken when surrounded.<br/>"+
    "The rules seem simple, but the game is very complex as the amount of possible of moves is very large.<br/>"+
    "It poses a challenge for AI development.";
  }

  change_to_slo(){
    document.getElementById("text").innerHTML =
    "Kitajski zid je strategijska namizna igra, ki jo lahko igrata dva igralca.<br/>"+
    "V igri igralca postavljata figure na desko. Figuram se pravi wall. Zmagaš pa tako, da si prisvojiš čim več teritorija.<br/><br/>"+

    "Deska ima (X,Y) velikost mreže. Točki v mreži pravimo field. Velikost mreže je odvisna od tipa igre, ki jo igralca zbereta pred začetkom igre.<br/>"+
    "Vsak igralec lahko postavi eno figuro na potezo. Prvi igralec je bele barve, drugi pa črne. <br/><br/>"+

    "Bel igralec lahko postavlja bele figure, črni pa črne figure. Igra se začne ko bel igralec postavi svojo figuro na katero koli točko na deski, nato pa mu sledi še črni.<br/><br/>"+

    "Naslednje figure se lahko igrajo samo na točke, kjer se v 1x1 bližini nahajo druge figure iste barve.<br/>"+
    "Oba igralca nadaljujeta z postavljanjem figur na deski, dokler en igralec več nima nobene možne poteze, potem ta igralec izgubi.<br/><br/>"+

    "Igra je podobna kitajski igri GO, le da se tu, figure ne morajo prevzemati. <br/>"+
    "Pravila se zdijo enostavna, ampak za igro se nahaja kompleksna umetna inteligenca, saj je število možnih potez zelo veliko.<br/>";
  }

  profile()
  {
    this.navCtrl.push(ProfilePage);
  }

  logout(){
    this.user.setUsername('');
    this.user.setID('');
    this.user.setImage('');

    this.navCtrl.push(LoginPage);
  }

}
