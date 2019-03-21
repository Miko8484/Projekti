<?php

class history_controller
{

    public function player_history()
    {
        if (isset($_GET["id"])) {

            $id = $_GET["id"];
            $history_arr = history::all($id);
            require_once('views/account/player_history.php');
        }else{
            require_once('views/strani/login_first.php');
        }
    }


    public function prikaziAPI($request, $input){
        //če uporabnik v klicu apija ni podal idja commenta -> api.php/commenti/x, vrnemo napako
        //opcijsko, bi tukaj lahko vrnili tudi seznam vseh commentov
        //kar bi pomenilo, da klic metode GET na api.php/commenti vrne vse commente
        if (!(isset($request[1]))){
            return -1;
        }
        if (is_numeric($request[1])){
            $account_id=($request[1]);
        }else{
            $account = game_account::find_by_username($request[1]);
            $account_id =$account->id;
        }
        if($account_id == null or $account_id==0){
            echo -1;
            return 0;
        }

        $history_arr = history::all($account_id);

        echo json_encode($history_arr);
        return 1;
    }

}

?>