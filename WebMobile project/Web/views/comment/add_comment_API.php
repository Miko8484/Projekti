<?php
//pogled za uspešno dodajanje preko RESTful apija.
//pogled klicatelja dodajanja samo preusmeri na naslov apija z idjem na novo dodanega oglasa
header('Location: view_comment.php?id'.$comment->id);

?>