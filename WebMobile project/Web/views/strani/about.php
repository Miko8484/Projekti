

<h3> Copyright Information </h3>
<p>&copy; <a href="http://www.facebook.com/nino.serec">Nino</a></p>
<p>&copy; <a href="https://www.facebook.com/mitja.celec.1">Mitja</a></p>
<hr>

<h3> Game Information </h3>
<p>


    <?php
    $root = $_SERVER['DOCUMENT_ROOT']."/vaja/Projekt";

    $fh = fopen($root."/game_description.txt", 'r');
    $pageText = fread($fh, 25000);
    echo nl2br($pageText);

    ?>


</p>