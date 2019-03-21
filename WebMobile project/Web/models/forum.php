<?php

class forum {
	
    public $id;
    public $topic_name;
    public $content;
	public $post_date;
	public $views;
	public $user;


    public function __construct($id, $name, $content, $date, $views, $username) {
      $this->id      = $id;
      $this->topic_name  = $name;
      $this->content = $content;
	  $this->post_date = $date;
	  $this->views = $views;
      $this->user  = $username;
    }
	
	public static function all() {

		$list = [];

		$db = Db::getInstance();

		$result = mysqli_query($db,'SELECT * FROM forum ORDER BY datum_objave DESC');

		while($row = mysqli_fetch_assoc($result)){

			$list[] = new forum($row['id'], $row['ime_teme'], $row['vsebina'],$row['datum_objave'],$row['views'],$row['tk_uporabnik']);
		}

        return $list;
    }
	
	public static function add() {

		if(isset($_POST['confirm_button']))
		{
			$db = Db::getInstance();
			
			$header=strip_tags($_POST['topic_name']);
			$content=strip_tags($_POST['content']);
			$date=date('Y-m-d H:i:s');
			$user=$_SESSION['logged_account']->username;
			
			$sql="INSERT INTO forum (ime_teme,vsebina,datum_objave,views,tk_uporabnik) VALUES ('$header','$content','$date','0','$user')";
			mysqli_query($db,$sql);
			//require_once('views/forum/index.php');
			//header('Location: index.php?controller=forum&action=all_topics');
		}
	  
    }
	
	public static function about($id) {
		
		$db = Db::getInstance();
		
		$result = mysqli_query($db,"SELECT id,ime_teme,vsebina,DATE_FORMAT(datum_objave, '%H:%i %d.%m.%Y') as datum_objave,views,tk_uporabnik FROM forum WHERE id='$id'");
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
		
		$result = mysqli_query($db,"SELECT * FROM account WHERE id='$id'");
		$row = mysqli_fetch_assoc($result);
		return $row['username'];
	}
	
	public static function count_comment($id)
	{
		$db = Db::getInstance();
		$result = mysqli_query($db,"SELECT COUNT(id) as count FROM comment WHERE tk_forum='$id'");
		$row = mysqli_fetch_assoc($result);
		return $row['count'];
	}
	
	public static function edit($id)
	{
		$db = Db::getInstance();
		if(isset($_POST['confirm_button']))
		{
			$header=$_POST['topic_name'];
			$content=$_POST['content'];
			
			$sql=("UPDATE forum SET ime_teme='$header',vsebina='$content' WHERE id='$id'");
			mysqli_query($db,$sql);
			if(mysqli_error($db))
			{
				echo mysqli_error($db);
				exit();
			}
			
			//header('Location: index.php?controller=forum&action=about_topic&id='.$id.'');
		}
	}
	
	public static function delete_topic($id)
	{
		$db = Db::getInstance();
		
		$query = "DELETE FROM comment WHERE tk_forum='$id'"; 
		$result = mysqli_query($db,$query);
		
		$query = "DELETE FROM forum WHERE id='$id'"; 
		$result = mysqli_query($db,$query);
		
		require_once('views/forum/index.php');
	}
	
	public static function search_topic($pattern)
	{
		$list = [];
		$db = Db::getInstance();
		$query = "SELECT * FROM forum WHERE (ime_teme LIKE '%".$pattern."%') ORDER BY datum_objave DESC"; 
		$result = mysqli_query($db,$query);
		while ($row = mysqli_fetch_array($result))
		{
			$list[] = new forum($row['id'], $row['ime_teme'], $row['vsebina'],$row['datum_objave'],$row['views'],$row['tk_uporabnik']);
		}
		return $list;
	}
	
	public static function hot_topic()
	{
		$list = [];
		$db = Db::getInstance();
		$query = "SELECT * FROM forum ORDER BY views DESC LIMIT 3"; 
		$result = mysqli_query($db,$query);
		while ($row = mysqli_fetch_array($result))
		{
			$list[] = new forum($row['id'], $row['ime_teme'], $row['vsebina'],$row['datum_objave'],$row['views'],$row['tk_uporabnik']);
		}
		return $list;
	}
	
	public static function getContent($id)
	{
		$db = Db::getInstance();
		$result = mysqli_query($db,"SELECT vsebina as content FROM forum WHERE id='$id'");
		$row = mysqli_fetch_assoc($result);
		return $row;
	}
}

?>