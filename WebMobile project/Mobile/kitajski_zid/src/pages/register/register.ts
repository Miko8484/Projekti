import { Component } from '@angular/core';
import { NavController, NavParams, AlertController } from 'ionic-angular';
import { Camera , CameraOptions } from '@ionic-native/camera';
import { Transfer, FileUploadOptions, TransferObject } from '@ionic-native/transfer';
import { File } from '@ionic-native/file';

import {LoginApi} from '../../providers/login-api';
import {Md5} from 'ts-md5/dist/md5';

import { Http } from '@angular/http';
import 'rxjs/add/operator/map';

import {LoginPage} from '../login/login';


@Component({
  selector: 'page-register',
  templateUrl: 'register.html',
  providers: [Transfer,File]
})
export class RegisterPage {
  public base64Image: string;
  username: string;
  http: any;
  baseUrl: String;
  items: any;
  registerCredentials = { username: '', firstname: '', lastname: '', email: '', password: '', password2: '' };
  imageName='';
  password: Int32Array;
  fileTransfer: TransferObject = this.transfer.create();
  identification:string;
  found:boolean;


  constructor(public navCtrl: NavController, public navParams: NavParams, private alertCtrl: AlertController, private camera: Camera, 
              public user: LoginApi,http: Http,private transfer: Transfer, private file: File) 
              {
                  this.http = http;
                  this.baseUrl = "http://90.157.177.163"; 
              }

  ionViewDidLoad() {

  }

  createAccount(){
        if((this.registerCredentials.password=='' && this.registerCredentials.password2=='') ||
           (this.registerCredentials.password!='' && this.registerCredentials.password2=='') || 
           (this.registerCredentials.password=='' && this.registerCredentials.password2!='') ||
           (this.registerCredentials.password!=this.registerCredentials.password2))
           {
               let alert = this.alertCtrl.create({
                    title: 'Error',
                    subTitle: "Passwords need to match",
                    buttons: ['OK']
                });
                alert.present(prompt);
           }
        else if(this.imageName=='')
        {
            let alert = this.alertCtrl.create({
                title: 'Error',
                subTitle: "Image is required",
                buttons: ['OK']
            });
            alert.present(prompt);
        }
        else
        {
            let header= new Headers();
            header.append('Content-Type','application/json');

            var body: { email: string; username: string; firstname: string; lastname: string, password:string|Int32Array} = 
            {email: this.registerCredentials.email, 
             username: this.registerCredentials.username, 
             firstname: this.registerCredentials.firstname, 
             lastname: this.registerCredentials.lastname, 
             password: Md5.hashStr(this.registerCredentials.password)};

             this.loadUserData(this.registerCredentials.username).subscribe(response => {
                    if(response.username==this.registerCredentials.username)
                    {
                        let alert = this.alertCtrl.create({
                            title: 'Error',
                            subTitle: "User with this username already exsits",
                            buttons: ['OK']
                        });
                        alert.present(prompt);
                    }
                    else
                    {
                        this.http.post('http://90.157.177.163/api.php/account', JSON.stringify(body), {header:header})
                        .map(res => res)
                        .subscribe(data=> {
                        let alert = this.alertCtrl.create({
                                title: 'Success',
                                subTitle: "Registration completed",
                                buttons: ['OK']
                            });
                            alert.present(prompt);
                        console.log(data);
                        });

                        if(this.imageName!='')
                        {
                            let options: FileUploadOptions = {
                            fileKey: 'file',
                            fileName: this.imageName.substr(this.imageName.lastIndexOf('/') + 1),
                            mimeType: "image/jpeg"
                            }
                        
                            this.loadUserData(this.registerCredentials.username).subscribe(response => {
                                
                                this.fileTransfer.upload(this.imageName, 'http://90.157.177.163/upload_image.php?id='+response.id, options)
                                .then((data) => {
                                            
                                }, (err) => {
                                        console.log(err);
                                        let alert = this.alertCtrl.create({
                                            title: 'Error',
                                            subTitle: "There was an error with image upload",
                                            buttons: ['OK']
                                        });
                                        alert.present(prompt);
                                })
                            });   
                        }
                        this.navCtrl.push(LoginPage);
                    }
            }); 
        }        
  }

makePicture(){
    const options: CameraOptions = {
        quality : 75,
        destinationType : this.camera.DestinationType.FILE_URI,
        sourceType : this.camera.PictureSourceType.CAMERA,
        encodingType: this.camera.EncodingType.JPEG,
        saveToPhotoAlbum: false,
        targetHeight:300,
        targetWidth:300
    }

    this.camera.getPicture(options).then((imageData) => {
        this.imageName=imageData;
        this.base64Image =  this.imageName;
        }, (err) => {
            console.log(err);
    });	
}

loadUserData(username)
{
    return this.http.get(this.baseUrl + '/api.php/account/' + username).map(res => res.json()); 
}

}
