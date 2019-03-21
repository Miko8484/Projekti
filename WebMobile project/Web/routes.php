<?php

//usmerjevalnik


//funkcija, ki kliče kontrolerje in hkrati vključuje njihovo kodo
function load_before($controller){
//naložimo datoteko z razredom kontrolerja
    require_once('controllers/' . $controller . '_controller.php');
    //naložimo model, ki ga kontroler potrebuje
    //modele bi lahko nalagali tudi v konstruktorju kontrolerja, namesto tukaj
    require_once('models/' . $controller . '.php');
}

function call($controller, $action)
{

    //pripravimo ime razreda kontrolerja
    $o = $controller . "_controller";
    //ustvarimo objekt razreda kontrolerja
    $controller = new $o;
    //pokličemo akcijo
    $controller->{$action}();

}

//vsi dovoljeni kontrolerji, v našem primeru 2
//tukaj lahko dodamo tudi avtentikacijo (preverjamo, če je uporabnik v seji in ali ima pravice izvesti določeno akcijo)
$controllers = array('strani' => ['login_first', 'home', 'napaka', 'register', 'login', 'contact','forum_edit'],
    'account' => ['register', 'login', 'logout', 'edit', 'edit_page', 'dodaj', 'all_accounts', 'user_login', 'add_account', 'add_account_page', 'admin_settings', 'parse_news', 'leaderboards','view_account', 'game_account_card'],
    'comment' => ['all_comments_oglas', 'add_comment', 'add_comment_page', 'view_comment', 'delete_comment'],
    'forum' => ['all_topics', 'add_topic', 'edit_topic', 'about_topic', 'delete_topic', 'search_topic'],
    'game' => ['custom_board', 'game_page', 'create_new_game', 'end_game', 'game_page'],
    'fcomment' => ['add_comment'],
    'history' => ['player_history']
);

if(isset($controller)) {
    if (array_key_exists($controller, $controllers)) {
        if (in_array($action, $controllers[$controller])) {
            load_before($controller);
        }
    }
}

function run_action($controller,$action)
{
    global $controllers;

    if(isset($controller)) {
        if (array_key_exists($controller, $controllers)) {
            if (in_array($action, $controllers[$controller])) {
                load_before($controller);
            }
        }
    }

    if (array_key_exists($controller, $controllers)) {
        if (in_array($action, $controllers[$controller])) {
            call($controller, $action);
        } else {
            echo "napaka1";
            call('strani', 'napaka');
        }
    } else {
        echo "napaka2";
        call('strani', 'napaka');
    }
}

?>