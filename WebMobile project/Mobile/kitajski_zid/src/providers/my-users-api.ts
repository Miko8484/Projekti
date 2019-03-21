import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import 'rxjs/add/operator/map';


@Injectable()
export class MyUsersApi {
  http: any;
  baseUrl: String;

  constructor(http: Http) {
    this.http = http;
    this.baseUrl = "http://90.157.177.163";
    //console.log('Hello MyReddit Provider');
  }

  getPosts(){
    //console.log (this.baseUrl + '/api.php/account' );
    return this.http.get(this.baseUrl + '/api.php/account').map(res => res.json()); 
  }

}
