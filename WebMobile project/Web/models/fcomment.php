<?php

class fcomment {
	
    public $id;
    public $comment;
	public $post_date;
	public $forum;
	public $user;


    public function __construct($id, $comment, $date, $forum, $username) {
      $this->id      = $id;
      $this->comment  = $comment;
	  $this->post_date = $date;
	  $this->forum = $forum;
      $this->user  = $username;
    }
	
	public static function all($id) {

		$list = [];

		$db = Db::getInstance();

		$result = mysqli_query($db,"SELECT id,vsebina,DATE_FORMAT(datum_objave, '%H:%i %d.%m.%Y') as datum_objave,tk_uporabnik,tk_forum FROM comment WHERE tk_forum='$id'");

		while($row = mysqli_fetch_assoc($result)){

			$list[] = new fcomment($row['id'], $row['vsebina'], $row['datum_objave'],$row['tk_forum'],$row['tk_uporabnik']);
		}

        return $list;
    }
	
	public static function add($id,$content) 
	{
			$db = Db::getInstance();
			
			if($content != ""){
				$comment=strip_tags($content);
				$date=date('Y-m-d H:i:s');
				$user_id=$_SESSION['logged_account']->username;
				$forum_id=$id;
				
				$sql="INSERT INTO comment (vsebina,datum_objave,tk_uporabnik,tk_forum) VALUES ('$comment','$date','$user_id','$forum_id')";
				mysqli_query($db,$sql);
			}
	  
    }
	
	public static function about($id) {
		
		$db = Db::getInstance();
		
		$result = mysqli_query($db,"SELECT * FROM forum WHERE id='$id'");
		$row = mysqli_fetch_assoc($result);
		$topic = new forum($row['id'], $row['ime_teme'], $row['vsebina'],$row['datum_objave'],$row['views'],$row['tk_uporabnik']);
		return $topic;
	}
	
	public static function increment_view($id)
	{
		$db = Db::getInstance();
		$sql="UPDATE forum SET views = views + 1 WHERE id='$id'";
		mysqli_query($db,$sql);
	}
	
	public static function id_to_username($id)
	{
		$db = Db::getInstance();
		
		$result = mysqli_query($db,"SELECT * FROM forum WHERE id='$id'");
		$row = mysqli_fetch_assoc($result);
		
	}
	
	public static function number_of_comments($id)
	{
		$db = Db::getInstance();
		
		$result = mysqli_query($db,"SELECT COUNT(*) as number FROM comment WHERE tk_forum='$id'");
		$row = mysqli_fetch_assoc($result);
		
		return $row;
	}
	
	public static function check_rights($id,$username)
	{
		$db = Db::getInstance();
		
		$result = mysqli_query($db,"SELECT COUNT(*) as rights FROM comment WHERE tk_uporabnik='$username' AND tk_forum='$id'");
		$row = mysqli_fetch_assoc($result);
		return $row;
	}
	
	public static function delete_comment($id)
	{
		$db = Db::getInstance();
		$sql="DELETE FROM comment WHERE id='$id'";
		mysqli_query($db,$sql);
		return "True";
		
	}
	
	
}

?>