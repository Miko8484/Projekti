import { Injectable } from '@angular/core';
import { Http, Response} from '@angular/http';
import { Observable } from 'rxjs/Rx';
import { Board } from './board';
import 'rxjs/add/operator/map';

@Injectable()
export class MyGameApi {
  http: any;
  baseUrl: String;

  constructor(http: Http) {
    this.http = http;
    this.baseUrl = "http://90.157.177.163";
    //this.baseUrl = "http://localhost";
  }


  getGame(username): Observable<Board>{
    let u = this.http.get(this.baseUrl + '/api.php/game/' + username).map(mapOneS);
    return u;
  }

  send(arr,id){
    let u=this.http.post(this.baseUrl + '/api.php/game/' + id, {arr}).map((res => res.json()));
    return u;
  }


}

function mapOneS(response:Response): Board{
  let u = <Board>({
    board: response.json().board,

  });
  return u;
}
