import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import 'rxjs/add/operator/map';
import {Md5} from 'ts-md5/dist/md5';

@Injectable()
export class LoginApi {
  http: any;
  baseUrl: String;
  username: string;
  userID: string;
  userImage:string;
  items:any;

  constructor(http: Http) {
    this.http = http;
    this.baseUrl = "http://90.157.177.163";
  }

  getPosts(username,password){
      this.username = username;
    return this.http.get(this.baseUrl + '/api.php/account/login_autho/' + username + '/' + Md5.hashStr(password)).map(res => res.json());
  }

  setUsername(usernamePassed){
    this.username=usernamePassed;
  }

  getUsername()
  {
      return this.username;
  }

  setID(id)
  {
      this.userID = id;
  }

  getID()
  {
    return this.userID;
  }

  setImage(imagePath)
  {
    this.userImage=imagePath;
  }

  getImage(){
    return this.userImage;
  }

}
