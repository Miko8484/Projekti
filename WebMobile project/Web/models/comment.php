<?php
//model nam služi kot objekt, s pomočjo katerega definiramo obliko podatkov, za potrebe obdelave in prikaza
//hkrati pa nam služi kot razred, preko katerega s statičnimi metodami lahko beremo sezname objektov ter posamične objekte iz trajne hrambe (baza) in jih tja tudi dodajamo
class comment
{
    //struktura podatkov kot razredne spremenjivke
    //$id; $oglas_id; $user_id; $content; $date_created;
    public $id;
    public $oglas_id;
    public $user_id;
    public $content;
    public $date_created;
    public $user_mail;
    //konstrutkor, ki nam ustvari novi primerek razreda
    public function __construct( $id,$oglas_id, $user_id, $content, $date_created,$user_mail)
    {
        $this->id=$id;
        $this->oglas_id = $oglas_id; $this->user_id = $user_id; $this->content = $content; $this->date_created = $date_created;$this->user_mail=$user_mail;
    }
    //funkcija, ki vrne polje vseh objektov, ki so trajno shranjeni v bazi
    public static function vsi()
    {
        //prazno polje
        $list = [];
        //dobimo mysqli_connect objekt
        $db = Db::getInstance();
        //izvedemo query
        $result = mysqli_query($db, 'SELECT * FROM comment');
        //v zanki ustvarjamo posamične objekte
        while ($row = mysqli_fetch_assoc($result)) {
            //dodajamo jih na konec polja
            $list[] = new comment($row['id'],$row['oglas_id'], $row['user_id'], $row['content'], $row['date_created'],$row["user_mail"]);
        }
        //vrnemo polje
        return $list;
    }
    public static function vsi_v_oglasu($oglas_id)
    {
        //prazno polje
        $list = [];
        //dobimo mysqli_connect objekt
        $db = Db::getInstance();
        //izvedemo query
        $result = mysqli_query($db, "SELECT * FROM comment where oglas_id='$oglas_id' order by date_created desc") or die (mysqli_error($db));
        //v zanki ustvarjamo posamične objekte
        while ($row = mysqli_fetch_assoc($result)) {
            //dodajamo jih na konec polja
            $list[] = new comment($row['id'],$row['oglas_id'], $row['user_id'], $row['content'], $row['date_created'],$row["user_mail"]);
        }
        //vrnemo polje
        return $list;
    }
    //poiščemo spefični comment glede na id
    public static function najdi($id)
    {
        //preverimo, da je id v številski obliki
        $id = intval($id);
        //izvedemo poizvedbo
        $db = Db::getInstance();
        $result = mysqli_query($db, "SELECT * FROM comment where id=$id");
        $row = mysqli_fetch_assoc($result);
        //ker pričakujemo samo en rezultat, vrnemo en objekt razreda comment
        return new comment( $row['id'],$row['oglas_id'], $row['user_id'], $row['content'], $row['date_created'],$row["user_mail"]);
    }
    //funkcija za shranjevanje novega commenta v bazo
    public static function add_comment($oglas_id, $user_id, $content, $date_created, $user_mail)
    {
        $db = Db::getInstance();
        //klasični način z lepljenjem
        // mysqli_query($db,"Insert into comment (Naslov,Vsebina,DatumObjave) Values (\"$naslov\",\"$vsebina\",now())");
        //primer mysqli poizvedbe s prepared statements
        //namesto lepljenja poizvedbe, damo na mesto, kjer želimo parametre, ki jih posreduje uporabnik placeholderje - znak ?
        //ustvarimo objekt prepared statement, kateremu je potrebno namesto vprašajev, določiti vrednosti, s katerimi se poizvedba izvede
        if ($stmt = mysqli_prepare($db, " insert into comment ( oglas_id, user_id, content, date_created , user_mail)
                                          Values (              ? ,           ?     ,    ? ,        ?, ?)")
        ) {
            //dodamo parametre v takšnem vrsnem redu, kot smo zapisali vprašaje
            //drugi argument je niz, ki mora imeti dolžino enako številu vprašajev (placeholderjev)
            //vsak znak niza predstavlja tip vrednosti, ki bo zamenjala vprašaje
            //uporabimo s za string, i za integer ,d za double in b za blob
            //v našem primeru, bomo v poizvedbo dodajanja poslali dva niza, zato je vrednost argumenta "ss"
            //nato naštejemo vrednosti
            mysqli_stmt_bind_param($stmt, "iisss", $oglas_id, $user_id, $content, $date_created, $user_mail);

            //izvedemo poizvedbo
            mysqli_stmt_execute($stmt);
            echo mysqli_error($db);

            echo "created";
            //če smo izvedli poizvedbo, kjer pričakujemo rezultate, potem lahko te dobimo na sledeč način:
            //$result=mysqli_stmt_get_result($stmt);
            //v praksi, lahko sedaj s bind_param parametre zamenjamo in spet pokličemo execute
            //tako lahko zelo efekitvno izvajamo npr. vstavljanje večje količine podatkov
            //na koncu vedno zapremo prepared statemenet
            mysqli_stmt_close($stmt);
        } else {
            echo "error";
            echo mysqli_error($db);
        }
        //funkcija nam vrne id zadnjega zapisa, ki smo ga vstavili, v kolikor imamo v tabeli AUTO INCREMENT primarni ključ
        $id = mysqli_insert_id($db);
        //vrnemo objekt, ki predstavlja trenunto dodani comment

        return comment::najdi($id);
    }
    public static function edit($id, $oglas_id, $user_id, $content, $date_created)
    {// tu bi mogo preveri nekak id še ka neje od nekoga drugoga
        $db = Db::getInstance();
        $result = mysqli_query($db, " UPDATE comment SET oglas_id='$oglas_id' user_id='$user_id' content='$content' date_created='$date_created' WHERE id='$id'") or die (mysqli_error($db));
    }

    public static function delete_comment($id){

        $db = Db::getInstance();
        $result = mysqli_query($db, "DELETE FROM comment where id=$id") or die(mysqli_error($db));

    }

}
?>
