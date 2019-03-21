import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import 'rxjs/add/operator/map';

@Injectable()
export class CountryApi {
  http: any;
  baseUrl: String;

  constructor(http: Http) {
    this.http = http;
    this.baseUrl = "http://maps.googleapis.com/maps/api/geocode/json?";
  }

  getCountry(latitude,longitude){
    //console.log (this.baseUrl + 'latlng=' + latitude + ',' + longitude + '&sensor=false');
    return this.http.get(this.baseUrl + 'latlng=' + latitude + ',' + longitude + '&sensor=false').map(res => res.json());
  }


}
