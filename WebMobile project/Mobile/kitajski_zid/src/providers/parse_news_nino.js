var express = require('express');
var router = express.Router();
var htmlparser = require("htmlparser2");
var request = require('request');
var fs = require("fs");
var async = require("async");

var main_arr = [];
//var country = "";

router.post('/parse', function (req, res) {
    request("http://aidreams.co.uk/forum/index.php?topic=12179.0#.WQoYgYh96Uk", function (error, response, body) {
        if (error) {
            console.log('error:', error);
        } else {
            parse_forum(body);
        }
    });
});

function writeToFile() {
    fs.writeFile('./data.json', JSON.stringify(main_arr), 'utf-8');
}

function parse_forum(html) {

    var Pcontent = 0;
    var Puser = 0;
    var Ptime = 0;
    var content ="";
    var user ="";
    var time ="";
    //console.log(html);

    console.log('start:');
    var parser = new htmlparser.Parser({
            onopentag: function (name, attribs) {
                if (name == "div" && attribs.class == "poster") {
                    Puser = 1;
                }
                else if (name == "a" && Puser==1) {
                    Puser = 2;
                }
                else if (name == "div" && attribs.class == "keyinfo") {
                    Ptime=1;
                }
                else if (name == "div" && attribs.class == "smalltext" && Ptime==1) {
                    Ptime=2;
                }

                else if (name == "div" && attribs.class == "inner") {
                    Pcontent = 1;
                }
            },
            ontext: function (text) {
                if (Puser==2) {
                    user +=text;
                    Puser=0;
                }
                else if (Ptime==2) {
                    time += text;
                    Ptime=0;
                }
                else if (Pcontent==1) {
                    content += text;
                }
            },
            onclosetag: function (tagname) {
                if (Pcontent==1 && tagname == "div") {
                    Puser=0;
                    Pcontent=0;
                    main_arr.push({user: user,content: content,time: time});
                    content="";
                    user="";
                }
            }
        }, {decodeEntities: true}
    );
    parser.write(html);
    writeToFile();
    console.log("parser end");
    parser.end();
}

module.exports = router;