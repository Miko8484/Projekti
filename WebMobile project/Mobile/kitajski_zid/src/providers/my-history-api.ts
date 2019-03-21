import {Injectable} from '@angular/core';
import {Http, Response} from '@angular/http';
import {Observable} from 'rxjs/Rx';
import {History} from './history';
import 'rxjs/add/operator/map';

@Injectable()
export class MyHistoryApi {
    http: any;
    baseUrl: String;

    constructor(http: Http) {
        this.http = http;
        this.baseUrl = "http://90.157.177.163";
    }


    getHistory(player_name): Observable<History[]> {
        let u = this.http.get(this.baseUrl + '/api.php/history/' + player_name).map(mapS);
        return u;
    }


}
function mapS(response: Response): History[] {
    return response.json().map(toS);
}

function toS(r: any): History {
    let u = <History>({
        id: r.id,
        winner_id: r.winner_id,
        loser_id: r.loser_id,
        date: r.date,
        elo_gain: r.elo_gain
    });

    return u;
}