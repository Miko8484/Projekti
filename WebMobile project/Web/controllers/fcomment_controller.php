<?php
class fcomment_controller {
	  
    public function all_comments()
	{
		$topics=forum::all();
		require_once('views/forum/index.php');
	}
	public function add_comment()
	{
		$id=$_GET['id'];
		fcomment::add($id,"p");
		require_once('views/forum/about_topic.php');
	}
	public function edit_comment()
	{
		$id=$_GET['id'];
		$topic=forum::about($id);
		require_once('views/forum/about_topic.php');
		forum::increment_view($id);
	}
	public function delete_comment()
	{
		$id=$_GET['id'];
		$topic=forum::about($id);
		require_once('views/forum/about_topic.php');
		forum::increment_view($id);
	}

    public function prikaziAPI($request,$input) {
        if(isset($request[2]))
        {
            if (!isset($request[1]))
                return call('strani', 'napaka');
            else
            {
                if($request[1]=="number")
                {
                    $numbers=fcomment::number_of_comments(intval($request[2]));
                    require_once('views/forum/JSON_number_comments.php');
                }
                else if($request[1]=="user_rights")
                {
                    if(isset($request[3]))
                    {
                        $rights=fcomment::check_rights(intval($request[2]),$request[3]);
                        require_once('views/forum/JSON_user_rights_comments.php');
                    }
                    else
                        return call('strani', 'napaka');
                }
                else
                {
                    return call('strani', 'napaka');
                }
            }
        }
        else
        {
            $comments=fcomment::all(intval($request[1]));

            if (!isset($request[1]))
                return call('strani', 'napaka');
            else
                require_once('views/forum/JSON_all_comments.php');
        }
    }
	
	public function dodajAPI($request,$input) {
	 fcomment::add(intval($request[1]),$input->content);

      //require_once('views/forum/dodajAPI.php');
      
    }
	
	public function odstraniAPI($request,$input) {
		if(isset($request[1])){
			if(isset($_SESSION["logged_account"]))
			{
				$success=fcomment::delete_comment(intval($request[1]));
				require_once('views/forum/JSON_delete_comment.php');
			}
		}
		else
			return call('strani', 'napaka');
	}

}
?>