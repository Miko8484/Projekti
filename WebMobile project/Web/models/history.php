<?php
require_once ("game.php");
//model nam služi kot objekt, s pomočjo katerega definiramo obliko podatkov, za potrebe obdelave in prikaza
//hkrati pa nam služi kot razred, preko katerega s statičnimi metodami lahko beremo sezname objektov ter posamične objekte iz trajne hrambe (baza) in jih tja tudi dodajamo


class history
{
    public $id;
    public $winner_id;
    public $loser_id;
    public $date;
    public $elo_gain;

    /*
    $id,
$winner_id,
$loser_id,
$date,
$elo_gain,
    */

    //konstrutkor, ki nam ustvari novi primerek razreda
    public function __construct($id, $winner_id, $loser_id, $date, $elo_gain)
    {
        $this->id = $id;
        $this->winner_id = $winner_id;
        $this->loser_id = $loser_id;
        $this->date = $date;
        $this->elo_gain = $elo_gain;
    }

    //funkcija, ki vrne polje vseh objektov, ki so trajno shranjeni v bazi
    public static function all($user_id)
    {
        $list = [];
        $db = database::get_GTdb();
        $result = mysqli_query($db, "SELECT * FROM history where winner_id='$user_id' or loser_id='$user_id' ORDER BY date DESC") or die(mysqli_error($db));
        //v zanki ustvarjamo posamične objekte
        while ($row = mysqli_fetch_assoc($result)) {
            //dodajamo jih na konec polja
            $list[] = new history($row['id'], $row['winner_id'], $row['loser_id'], $row['date'], $row['elo_gain']);
        }
        //vrnemo polje
        return $list;
    }

    public static function add_history($id, $winner_id, $loser_id, $date, $elo_gain)
    {
        $db = database::get_GTdb();
        if ($stmt = mysqli_prepare($db, " insert into history (     id,winner_id,loser_id,date,elo_gain)
                                          Values (              ? ,           ?     ,    ? ,        ?, ?)")
        ) {

            mysqli_stmt_bind_param($stmt, "iiisi", $id, $winner_id, $loser_id, $date, $elo_gain);

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            echo "error";
            echo mysqli_error($db);
        }
    }

}