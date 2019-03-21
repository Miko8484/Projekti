import { Component } from '@angular/core';
import { NavController, NavParams, AlertController } from 'ionic-angular';
import {LoginApi} from '../../providers/login-api';
import { TabsPage } from '../tabs/tabs';
import { RegisterPage } from '../register/register';


@Component({
selector: 'page-login',
templateUrl: 'login.html'
})

export class LoginPage {
items: any;
loginCredentials = { username: '', password: '' };
username='';
tarBarElement:Element;

constructor(public navCtrl: NavController, public navParams: NavParams, private loginProvider: LoginApi, private alertCtrl: AlertController) {}

ionViewDidLoad(){
  let elements = document.querySelectorAll(".tabbar");

    if (elements != null) {
        Object.keys(elements).map((key) => {
            elements[key].style.display = 'none';
        });
    }
}

login() {
   this.getPosts(this.loginCredentials.username, this.loginCredentials.password);
}

createAccount(){
  this.navCtrl.push(RegisterPage);
}

getPosts(username,password){
  this.loginProvider.getPosts(username,password).subscribe(response => {
    
    if(response.id=="False")
    {
        this.showError("Wrong username or passwrod, try again");
    }
    else
    {
        this.items = response;
        this.loginProvider.setID(response.id.id);
        this.loginProvider.setImage(response.id.image);

        this.username=this.loginCredentials.username;
        this.navCtrl.push(TabsPage);
    }
  }); 
}

showError(text) {
 
    let alert = this.alertCtrl.create({
      title: 'Wrong information',
      subTitle: text,
      buttons: ['OK']
    });
    alert.present(prompt);
  }




}
