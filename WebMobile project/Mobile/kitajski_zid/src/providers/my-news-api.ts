import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import 'rxjs/add/operator/map';
import * as htmlparser from 'htmlparser2';


@Injectable()
export class MyNewsApi {

    cont = [];
    htmldata = "";
    url: any;

    constructor(public http: Http) {
        //console.log('Hello MyParsingData Provider');
    }

    loadParsingData()
    {
        this.cont = [];
        this.url = 'http://www.computerworld.com/news/';
        //console.log(this.url);
        this.http.get(this.url)
            .map(res => res.text())
            .subscribe(
            data => this.htmldata = data,
            err => this.logError(err),
            () => this.parsingData(this.htmldata)
        );

        return this.cont;//JSON.stringify(this.cont);
    }

    logError(err) {
        console.error('There was an error: ' + err);
    }	

    parsingData(html) 
    {
        var startDiv = 0;
        var startFigure=0;
        var startH3=0;
        var startH4=0;
        var startA=0;
        var secondDiv=0;
        var bd = new Bd();
        var parser = new htmlparser.Parser({
                onopentag: (name, attribs)=> {
                    /*if(name==="a" && startFigure===1 && startDiv===1)
                        console.log(attribs.href);*/
                    if(secondDiv===1 && name==="h3")
                        startH3=1;
                    if(startDiv===1 && name==="h4")
                        startH4=1;
                },
                onattribute: (name, value) =>{
                    if (name === "class" && value === "river-well article"){
                        bd=new Bd();
                        startDiv=1;
                        secondDiv=0;startH3=0;
                    }
                    if(name==="class" && value==="well-img" && startDiv===1)
                    {
                        startFigure=1;
                    }
                    if(startDiv===1 && startFigure===1 && name==="data-original")
                    {
                        if(value!=""){
                            bd.image = value;
                            //console.log("IMG: " + value);
                        }
                    }
                    if(name==="class" && value==="post-cont" && startDiv===1)
                    {
                        secondDiv=1;
                        startFigure=0;
                    }
                    if(startH3===1 && name==="href")
                    {
                        if(value!=""){
                            bd.link=value;
                            //console.log("Link: " + value);
                        }
                        //startH3=0;
                        startA=1;
                    }

                    if(name==="class" && value==="load-btn")
                    {
                        startDiv=0;startFigure=0;
                        secondDiv=0;startH3=0;
                    }
                },
                ontext: (text)=>{
                    if(startH3===1 && startA===1)
                    {
                        bd.header=text;
                        //console.log("Naslov: "+text);
                        startH3=0;startA=0;
                    }
                    if(startH4===1)
                    {
                        bd.content=text;
                        //console.log("Vsebina: "+text);
                        startH4=0;
                        this.cont.push(bd);
                    }
                },
                onclosetag: (tagname)=>{
                    if(tagname="div")
                    {
                        startFigure=0;
                    }
                }
            }, {decodeEntities: true}
        );
       
        parser.write(html);
        parser.end();

        return this.cont;
    }

}

class Bd {
    header: String;
    content: String;
    image: String;
    link: String;
}