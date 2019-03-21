<script>
$("#forum_add_button").show();
document.getElementById("main_content_header").innerHTML="Forum";
function delete_comment(id)
{
	var user = $("#theOrigin").val();
	
	url="api.php/fcomment/"+id;
	$.ajax({
    type: "DELETE",
    url: url,
	dataType: "json",
	contentType: "application/json; charset=utf-8",
    success: function (data) 
		{	
			if(data=="True")
			{
				document.getElementById("div_comments").innerHTML = "";	
				url="api.php/fcomment/"+<?php echo $topic->id ?>;
				$.ajax({
				type: "GET",
				url: url,
				dataType: "json",
				contentType: "application/json; charset=utf-8",
				success: function (data) 
					{		 
						$.each(data,function(i,v){
								ele = document.createElement("div");
								ele.className = "comment";
								ele.id = "comment1";
								document.getElementById('div_comments').appendChild(ele);
								
								var node = document.querySelector("#comment1"),
								ele = document.createElement("h5");
								ele.innerHTML = "RE: <?php echo $topic->topic_name; ?>" ;
								ele.id = "comment_header";
								node.parentNode.insertBefore(ele, node.nextSibling);
								
								var id=data[i].user;
								var ele = document.createElement("span");
								ele.innerHTML = "by " + data[i].user + "<span id=\"span2\" style=\"font-size:1em;color:black;\"> >> " + data[i].post_date + " </span> ";
								ele.id = "span1";
								var node = document.querySelector("#comment_header");
								node.parentNode.insertBefore(ele, node.nextSibling);
								
								ele = document.createElement("p");
								ele.innerHTML = data[i].comment;
								ele.id = "topic_comment";
								var node = document.querySelector("#span1");
								node.parentNode.insertBefore(ele, node.nextSibling);
								
								ele = document.createElement("hr");
								ele.id = "hr_separator";
								var node = document.querySelector("#topic_comment");
								node.parentNode.insertBefore(ele, node.nextSibling);
								
								if(data[i].user == user)
								{
									ele = document.createElement("a");
									ele.id="delete_comment";
									ele.className = data[i].id;
									ele.name = "delete_comment";
									ele.setAttribute("onclick","delete_comment("+data[i].id+");return false;");
									ele.style.cssText = "cursor:pointer;";
									var node = document.querySelector("#span1");
									node.parentNode.insertBefore(ele, node.nextSibling);
									
									ele2 = document.createElement("span");
									ele2.className="glyphicon glyphicon-remove-sign";
									ele2.setAttribute("aria-hidden", "true");
									ele.appendChild(ele2);
								}
						 }); 
					}
				});
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			alert(JSON.stringify(data));
			alert("Status: " + textStatus); alert("Error: " + errorThrown); 
		} 
	});
}
</script>

<div id="topic_content">

<h2 id="topic_header"><?php echo $topic->topic_name; ?></h2>
<span id="posted_by">by <?php echo $topic->user;?></span>
<span id="posted_by_follow"> >> <?php echo $topic->post_date?></span> 

<?php if(isset($_SESSION["logged_account"])){ if($topic->user==$_SESSION["logged_account"]->username) { ?>

<a href="#" style="text-decoration:none" class="edit_button" data-href="index.php?controller=forum&action=delete_topic&id=<?php echo $topic->id ?>" data-toggle="modal" data-target="#confirm-delete">
	  <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
</a>

<?php }} ?>

<br/><br/>

<p id="topic_text">
	<script>
		url="api.php/forum/topic_content/"+<?php echo $topic->id ?>;
		$.ajax({
		type: "GET",
		url: url,
		dataType: "json",
		contentType: "application/json; charset=utf-8",
		success: function (data3) 
			{		
				document.getElementById('topic_text').innerHTML = data3.content;
			}
		});
	</script>
	<?php //echo $topic->content;?>
</p>

<hr id="main_hr"/>
<div id="div_comments"></div>

<script>
$(document).ready(function() {
	var user = $("#theOrigin").val();
	url="api.php/fcomment/"+<?php echo $topic->id ?>;
	$.ajax({
    type: "GET",
    url: url,
	dataType: "json",
	contentType: "application/json; charset=utf-8",
    success: function (data) 
		{		
			$.each(data,function(i,v){
					ele = document.createElement("div");
					ele.className = "comment";
					ele.id = "comment1";
					document.getElementById('div_comments').appendChild(ele);
					
					var node = document.querySelector("#comment1"),
				    ele = document.createElement("h5");
					ele.innerHTML = "RE: <?php echo $topic->topic_name; ?>" ;
					ele.id = "comment_header";
					node.parentNode.insertBefore(ele, node.nextSibling);
					
					var id=data[i].user;
					var ele = document.createElement("span");
					ele.innerHTML = "by " + data[i].user + "<span id=\"span2\" style=\"font-size:1em;color:black;\"> >> " + data[i].post_date + " </span> ";
					ele.id = "span1";
					var node = document.querySelector("#comment_header");
					node.parentNode.insertBefore(ele, node.nextSibling);
					
					ele = document.createElement("p");
					ele.innerHTML = data[i].comment;
					ele.id = "topic_comment";
					var node = document.querySelector("#span1");
					node.parentNode.insertBefore(ele, node.nextSibling);
					
					ele = document.createElement("hr");
					ele.id = "hr_separator";
					var node = document.querySelector("#topic_comment");
					node.parentNode.insertBefore(ele, node.nextSibling);
					
					if(data[i].user == user)
					{
						ele = document.createElement("a");
						ele.id="delete_comment";
						ele.className = data[i].id;
						ele.name = "delete_comment";
						ele.setAttribute("onclick","delete_comment("+data[i].id+");return false;");
						ele.style.cssText = "cursor:pointer;";
						var node = document.querySelector("#span1");
						node.parentNode.insertBefore(ele, node.nextSibling);
						
						ele2 = document.createElement("span");
						ele2.className="glyphicon glyphicon-remove-sign";
						ele2.setAttribute("aria-hidden", "true");
						ele.appendChild(ele2);
					}
					
			 }); 
		}
	});
	
	
	
	$("#dodaj").click(function(){
		var user = $("#theOrigin").val();
		url="api.php/fcomment/"+<?php echo $topic->id ?>;
		data={};
		data.id=<?php echo $topic->id ?>;
		var text = $("#summernote").val();
		cleanText = text.replace(/<\/?[^>]+(>|$)/g, "");
		data.content=cleanText;
		
		$.ajax({
		type: "POST",
		url: url,
		contentType: "application/json; charset=utf-8",
		data: JSON.stringify(data),
		success: function (data) {
			document.getElementById("div_comments").innerHTML = "";	
			$("#summernote").summernote('code', '');			
			url="api.php/fcomment/"+<?php echo $topic->id ?>;
			$.ajax({
			type: "GET",
			url: url,
			dataType: "json",
			contentType: "application/json; charset=utf-8",
			success: function (data) 
				{		 
					$.each(data,function(i,v){
							ele = document.createElement("div");
							ele.className = "comment";
							ele.id = "comment1";
							document.getElementById('div_comments').appendChild(ele);
							
							var node = document.querySelector("#comment1"),
							ele = document.createElement("h5");
							ele.innerHTML = "RE: <?php echo $topic->topic_name; ?>" ;
							ele.id = "comment_header";
							node.parentNode.insertBefore(ele, node.nextSibling);
							
							var id=data[i].user;
							var ele = document.createElement("span");
							ele.innerHTML = "by " + data[i].user + "<span id=\"span2\" style=\"font-size:1em;color:black;\"> >> " + data[i].post_date + " </span> ";
							ele.id = "span1";
							var node = document.querySelector("#comment_header");
							node.parentNode.insertBefore(ele, node.nextSibling);
							
							ele = document.createElement("p");
							ele.innerHTML = data[i].comment;
							ele.id = "topic_comment";
							var node = document.querySelector("#span1");
							node.parentNode.insertBefore(ele, node.nextSibling);
							
							ele = document.createElement("hr");
							ele.id = "hr_separator";
							var node = document.querySelector("#topic_comment");
							node.parentNode.insertBefore(ele, node.nextSibling);
							
							if(data[i].user == user)
							{
								ele = document.createElement("a");
								ele.id="delete_comment";
								ele.className = data[i].id;
								ele.name = "delete_comment";
								ele.setAttribute("onclick","delete_comment("+data[i].id+");return false;");
								ele.style.cssText = "cursor:pointer;";
								var node = document.querySelector("#span1");
								node.parentNode.insertBefore(ele, node.nextSibling);
								
								ele2 = document.createElement("span");
								ele2.className="glyphicon glyphicon-remove-sign";
								ele2.setAttribute("aria-hidden", "true");
								ele.appendChild(ele2);
							}
					 }); 
				}
			});
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			alert(JSON.stringify(data));
			alert("Status: " + textStatus); alert("Error: " + errorThrown); 
		} 
		});
	});	
});



</script>


<!--action="?controller=forum&action=about_topic&id=php echo $topic->id; "-->
<?php if(isset($_SESSION["logged_account"])){ ?>
<!--<form method='post' name="form"  class="form-horizontal">-->

<textarea id="summernote" name="content"></textarea>
<script>
$(document).ready(function() {
	$('#summernote').summernote({
	height: 200,
	  toolbar: [
		['style', ['bold', 'italic', 'underline', 'clear']],
		['font', ['superscript', 'subscript']],
		['color', ['color']],
		['para', ['ul', 'ol', 'paragraph']],
	  ]
	});
});
</script>
<button id="dodaj" type="dodaj" class="btn btn-default">Add comment</button>
<!--
<input type="submit" name="confirm_button" id="submit" class="btn btn-info" value="Post comment">

</form>-->
<?php } else { ?>
<br/>
<p><big><strong> <a href='?controller=strani&action=register' style="color:#09f">Register</a> or <a href='?controller=strani&action=login' style="color:#09f">log in</a> to comment.</strong></big></p>

<?php } ?>

</div>

<?php if(isset($_SESSION['logged_account'])) { ?>
<input hidden id="theOrigin"  name="theOrigin" value="<?=$_SESSION['logged_account']->username;?>"></input>
<?php } ?>