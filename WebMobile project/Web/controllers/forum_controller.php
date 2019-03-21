<?php
class forum_controller {
	  
    public function all_topics()
	{
		$topics=forum::all();
		require_once('views/forum/index.php');
	}
	public function add_topic()
	{
		forum::add();
		require_once('views/forum/add_topic.php');
	}
	public function about_topic()
	{
		require_once("models/fcomment.php");
		$id=$_GET['id'];
		
		//fcomment::add($id);
		
		$topic=forum::about($id);
		
		$comments=fcomment::all($id);
		
		require_once('views/forum/about_topic.php');
		forum::increment_view($id);
	}
	
	public function edit_topic()
	{
		$id=$_GET['id'];
		$topic=forum::about($id);
		forum::edit($id);
		
		require_once('views/forum/about_topic.php');
		
	}
	
	public function delete_topic()
	{
		$id=$_GET['id'];
		forum::delete_topic($id);
	}
	
	public function prikaziAPI($request,$input) {
	  if(isset($request[2]) && $request[1]=="topic_content")
	  {
		  $tc = forum::getContent(intval($request[2]));
		  require_once('views/forum/JSON_topic_content.php');
	  }
	  else
	  {
		  if (!isset($request[1]))
		  {
			$topics=forum::all();
			require_once('views/forum/JSON_list_topics.php');
		  }
		  else
		  {
			$pattern=$request[1];
			$topics=forum::search_topic($pattern);
			require_once('views/forum/JSON_list_topics.php');
		  }
	  }

    }

}
?>