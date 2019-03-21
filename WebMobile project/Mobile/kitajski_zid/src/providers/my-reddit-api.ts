import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import 'rxjs/add/operator/map';

@Injectable()
export class MyRedditApi {
  http: any;
  baseUrl: String;

  constructor(http: Http) {
    this.http = http;
    this.baseUrl = "https://www.reddit.com/r";
    //console.log('Hello MyReddit Provider');
  }

  getPosts(category, limit){
    //console.log (this.baseUrl + '/' + category + '/.json?limit='+limit);
    return this.http.get(this.baseUrl + '/' + category + '/.json?limit='+limit)
    .map(res => res.json()); 
  }

}
