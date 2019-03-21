<script type="text/javascript">

    var folder = "views/game/pictures";

    $.ajax({
        url : folder,
        success: function (data) {
            $(data).find("a").attr("href", function (i, val) {

                if( val.match(/\.(jpe?g|png|gif)$/) ) {
                    var property = document.getElementById("desna_table");
                    $(property).append( "<label class='nav_img'><input id='"+"input"+val+"' type='radio' name='tower'><img id='"+val+"' src='"+ folder + val +"'></label>" );
                    var asd = document.getElementById(val);
                    $(asd).click(function(){
                        getimg(val);
                    });
                }
            });
        }
    });

    var red = document.getElementById('red');
    $(red).click(function(){
        getcolor('red');
    });
    var blue = document.getElementById('blue');
    $(blue).click(function(){
        getcolor('blue');
    });
    var yellow = document.getElementById('yellow');
    $(yellow).click(function(){
        getcolor('yellow');
    });
    var pink = document.getElementById('pink');
    $(pink).click(function(){
        getcolor('pink');
    });
    var neutral = document.getElementById('neutral');
    $(neutral).click(function(){
        getcolor('neutral');
    });
    var save_board = document.getElementById('save_board');
    $(save_board).click(function(){
        save_board_post();
    });
    var img="field.png";
    var color="red";

    function getimg(a){
        img = a;
    }
    function getcolor(a){
        color = a;
    }

    function play(x,y){
        property = document.getElementById("slika_"+x+","+y);
        $(property).attr('src', '/includes/towers/'+color+'/'+img);


        property = document.getElementById("cell_"+x+","+y);
        $(property).removeClass();
        $(property).addClass( "cell " );
        $(property).addClass( color );

        board[x][y][1]= img.replace(/\.[^/.]+$/, "")

        switch(color) {
            case "red":
                player=1;
                break;
            case "blue":
                player=2;
                break;
            case "yellow":
                player=3;
                break;
            case "pink":
                player=4;
                break;
            case "neutral":
                player="neutral";
                break;
        }
        board[x][y][0]=player;
    }

</script>
