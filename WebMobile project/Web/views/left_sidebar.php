<?php if(isset($_SESSION["logged_account"])){ ?>
	<a href="index.php?controller=forum&action=add_topic" style="text-decoration:none" id="forum_add_button" style="width:100%" hidden>
		<button class="btn btn-lg btn-primary btn-block btn-signin" id="submit_button_new_topic" type="button">
		<span style="color:white;" class="glyphicon glyphicon-plus" aria-hidden="true"></span> New topic
	</button>
		<br/><br/>
	</a>
<?php } ?>	
	
	
	<div id="forum_search_form" hidden>
	<input type="text" id="pat" name="pattern" class="form-control" />
	<button class="btn btn-lg btn-primary btn-block btn-signin" id="submit_button_search" type="button">
		<span style="color:white;" class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
	</button>
	<br/><br/>
	</div>
	
	
	
	<?php require_once("models/forum.php");
		$hot_topics=forum::hot_topic(); ?>
		<div id="hot_topic" class="well">
		<h4>POPULAR TOPICS</h4><br/>
	    <?php foreach($hot_topics as $ht) {
			echo "<a href=index.php?controller=forum&action=about_topic&id=".$ht->id.">".$ht->topic_name."</a>"; echo "<br/><br/>";
		} ?>
		</div>

