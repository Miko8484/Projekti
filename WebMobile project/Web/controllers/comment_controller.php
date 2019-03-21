<?php
require_once("strani_controller.php");
require_once("oglasi_controller.php");
require_once("account_controller.php");

class comment_controller
{

    public static function all_comments_oglas()
    {
        if (isset($_GET["oglas_id"])) {
            $oglas_id = $_GET["oglas_id"];
        } else {
            $oglas_id = $_POST["oglas_id"];
        }

        $comments = comment::vsi_v_oglasu($oglas_id);
        require_once('views/comment/all_comments_oglas.php');
    }

    public static function add_comment()
    {
        if (isset($_SESSION['logged_account'])) {
            $user_id = $_SESSION["logged_account"]->id;
            $user_mail = " ";
        } else {
            $user_id = -1;
            $user_mail = $_POST["user_mail"];
        }
        $date_created = date("Y-m-d H:i:s");


        comment::add_comment($_POST["oglas_id"], $user_id, $_POST["content"], $date_created, $user_mail);
        comment_controller::all_comments_oglas();
    }

    public static function add_comment_page()
    {
        require_once('views/comment/add_comment.php');
    }

    public static function view_comment()
    {
        $comment = comment::najdi($_GET["id"]);
        require_once('views/comment/view_comment.php');
    }

    public static function delete_comment(){
        comment::delete_comment($_GET["id"]);
        oglasi_controller::index();
    }


    public function dodajAPI($request,$input) {

        //v $input je objekt, ki predstavlja oglas, ki je bil prejeto preko post zahteve v api

        $date_created = date("Y-m-d H:i:s");
        comment::add_comment($input->oglas_id, -1, $input->content, $date_created, $input->user_mail);
        //naložimo pogled, ki apiju vrne glavo za preusmeritev na novo dodani oglas
        require_once('views/comment/add_comment_API.php');

    }
}

?>