import { Component } from '@angular/core';
import { NavController, NavParams, AlertController } from 'ionic-angular';
import { Camera , CameraOptions } from '@ionic-native/camera';
import { Transfer, FileUploadOptions, TransferObject } from '@ionic-native/transfer';
import { File } from '@ionic-native/file';

import {LoginApi} from '../../providers/login-api';
import {Md5} from 'ts-md5/dist/md5';

import { Http } from '@angular/http';
import 'rxjs/add/operator/map';


@Component({
  selector: 'page-profile',
  templateUrl: 'profile.html',
  providers: [Transfer,File]
})
export class ProfilePage {
  public base64Image: string;
  username: string;
  http: any;
  baseUrl: String;
  items: any;
  editCredentials = { username: '', firstname: '', lastname: '', email: '', password: '', password2: '' };
  imageName='';
  password: Int32Array;
  fileTransfer: TransferObject = this.transfer.create();


  constructor(public navCtrl: NavController, public navParams: NavParams, private alertCtrl: AlertController, private camera: Camera, 
              public user: LoginApi,http: Http,private transfer: Transfer, private file: File) 
              {
                  this.http = http;
                  this.baseUrl = "http://90.157.177.163"; 
              }

  ionViewDidLoad() {
     this.loadUserData().subscribe(response => {
        this.items = response;
        this.editCredentials.username=response.username;
        this.editCredentials.firstname=response.firstname;
        this.editCredentials.lastname=response.lastname;
        this.editCredentials.email=response.email;
        this.base64Image = "http://90.157.177.163/user_image/" + response.image;
    }); 
  }

  loadUserData()
  {
    this.username=this.user.getUsername();
    return this.http.get(this.baseUrl + '/api.php/account/' + this.username).map(res => res.json()); 
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

  editProfile()
  {
    let header= new Headers();
    header.append('Content-Type','application/json');

    
    if(this.editCredentials.password=='' && this.editCredentials.password2==''){
      var body1: { email: string; username: string; firstname: string; lastname: string,id: string } = 
      {email: this.editCredentials.email, username: this.editCredentials.username, firstname: this.editCredentials.firstname, 
        lastname: this.editCredentials.lastname, id:this.user.getID()};

        this.http.put('http://90.157.177.163/api.php/account', JSON.stringify(body1), {header:header})
            .map(res => res)
            .subscribe(data=> {
              console.log(data);
            });
    }
    else
    {
        if((this.editCredentials.password!='' && this.editCredentials.password2=='') || 
           (this.editCredentials.password=='' && this.editCredentials.password2!='') ||
           (this.editCredentials.password!=this.editCredentials.password2))
        {
          let alert = this.alertCtrl.create({
            title: 'Error',
            subTitle: "Passwords need to match",
            buttons: ['OK']
          });
          alert.present(prompt);
        }
        else
        {
          this.user.setUsername(this.editCredentials.username)
          var body2: { email: string; username: string; firstname: string; lastname: string,id: string, password:string|Int32Array} = 
          {email: this.editCredentials.email, username: this.editCredentials.username, firstname: this.editCredentials.firstname, 
            lastname: this.editCredentials.lastname, id:this.user.getID(), password: Md5.hashStr(this.editCredentials.password)};

            this.http.put('http://90.157.177.163/api.php/account', JSON.stringify(body2), {header:header})
            .map(res => res)
            .subscribe(data=> {
              let alert = this.alertCtrl.create({
                    title: 'Success',
                    subTitle: "Data changed",
                    buttons: ['OK']
                  });
                  alert.present(prompt);
              console.log(data);
            });
        }
    }

    if(this.imageName!='')
    {
        let options: FileUploadOptions = {
          fileKey: 'file',
          fileName: this.imageName.substr(this.imageName.lastIndexOf('/') + 1),
          mimeType: "image/jpeg"
        }

        this.fileTransfer.upload(this.imageName, 'http://90.157.177.163/upload_image.php?id='+this.user.getID(), options)
        .then((data) => {
                    this.loadUserData().subscribe(response => {
                        this.user.setImage(response.image);
                    }); 
        }, (err) => {
                console.log(err);
                  let alert = this.alertCtrl.create({
                    title: 'Error',
                    subTitle: "There was an error with image upload",
                    buttons: ['OK']
                  });
                  alert.present(prompt);
        })
    }
  }
}
