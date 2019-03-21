<script>
document.getElementById("main_content_header").innerHTML="Chinese wall - strategy game";
</script>


<!--<div class="jumbotron">
    <h1>Kitajski zid</h1>
    <p>Strateška namizna igra</p>
</div>-->
<div>
    <div class=" col-md-5">
<img src="/includes/game_example_2.png" class="col-md-12 img-responsive" >
<img src="/includes/game_example.png" class="col-md-12 img-responsive" >
    </div>
    <div class="col-md-5">
    <?php
    $root = $_SERVER['DOCUMENT_ROOT'];

    $fh = fopen($root."/game_description.txt", 'r');
    $pageText = fread($fh, 25000);
    echo nl2br($pageText);

    ?>
    </div>
</div>
