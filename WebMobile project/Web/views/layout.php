<!DOCTYPE html>
<html>
<head>
    <!-- v glavi dodamo zunanje vire za bootstrap in jquery podporo, ter nekaj oblike, za pravilno delovanje navigation bara (vzorec je iz w3schools strani) -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>


    <link rel="stylesheet" type="text/css" href="game_style.css?version=10"/>
    <link rel="stylesheet" type="text/css" href="slog.css?version=23"/>

    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.js"></script>

    <script>
        //prikaz modalnega okna ob izbrisu forum topic
        jQuery(document).ready(function ($) {
            $(".btn btn-default").click(function () {
                window.location = $(this).data("href");
            });

            $('.btn btn-default').css('cursor', 'pointer');

            $('#confirm-delete').on('show.bs.modal', function (e) {
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            });
        });

        //vrstice tabele so povezave na podrobno temo
        $('tr').click(function () {
            var href = $(this).find("a").attr("href");
            if (href) {
                window.location = href;
            }
        });

        window.addEventListener("DOMContentLoaded", function () {
            var form = document.getElementById("forum_search_form");
			
			document.getElementById("submit_button_new_topic").style.cssText = "cursor:pointer";
			document.getElementById("submit_button_search").style.cssText = "cursor:pointer";
            document.getElementById("submit_button_search").addEventListener("click", function () {
                form.submit();
            });
        });


    </script>
</head>
<body>
<div class="container-fluid">
    		<nav class="navbar navbar-default navbar-fixed-top">
   				<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header" style="padding-left:5%">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#" >
							CHINESE WALL
						</a>
						
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >   
							<ul class="nav navbar-nav navbar-left" style="padding-left:3%">
								<li><a href="index.php"><span class="glyphicon glyphicon-home"></span> HOME </a></li>
								<li><a href="?controller=game&action=game_page"><span class="glyphicon glyphicon-knight"></span> GAME</a></li>
								<li><a href="?controller=account&action=leaderboards" id="leaderboards_button"><span class="glyphicon glyphicon-king"></span> LEADERBOARDS</a></li>
								<li><a href="?controller=history&action=player_history<?php if(isset($_SESSION["logged_account"])) echo "&id=".$_SESSION["logged_account"]->id;?>" id="leaderboards_button"><span class="glyphicon glyphicon-king"></span> HISTORY</a></li>
								<li><a href="?controller=forum&action=all_topics" id="forum_button"><span class="glyphicon glyphicon-comment"></span> FORUM</a></li>
							</ul>					
							<ul class="nav navbar-nav navbar-right" style="padding-right:5%">
								 <?php if (isset($_SESSION["logged_account"])): ?>
									<li><a id="left_info" href='?controller=account&action=edit'><span class="glyphicon glyphicon-user"></span> Profile</a></li>
									<li><a id="right_info" href='?controller=account&action=logout'><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
								<?php else: ?>
								<li><a href="#" id="left_info" data-toggle="modal" data-target="#mySignUpModal"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
								<li><a href="#" id="right_info" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
								<?php endif; ?>
							</ul>
					</div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->
			</nav>  	
			<div class="container-fluid main-container">
					
					<div class="col-md-2 content">
						<div class="panel panel-default">
							
							<div class="panel-body">
								<?php require_once('left_sidebar.php'); ?>
							</div>
						</div>
					</div>
					
					<div class="col-md-8 content">
						<div class="panel panel-default">
							<div class="panel-heading" id="main_content_header" style="font-size:20px">
							</div>
							<div class="panel-body">
								<?php run_action($controller, $action) ?>
							</div>
						</div>
					</div>
					<?php if (isset($_SESSION["logged_account"])): ?>
					<div class="col-md-2 content">
						<div class="panel panel-default">
							<div class="panel-heading">
								Game account
							</div>
							<div class="panel-body">
								<?php run_action("account", "game_account_card") ?>
							</div>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<div class="container">
				  <div class="row">
				  <hr>
					<div class="col-lg-12">
					  <div class="col-md-8">
						<p class="muted pull-right">© 2017 Mitja Celec & Nino Serec. All rights reserved</p>
					  </div>
					</div>
				  </div>
				</div>
</div>

			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="height: 0px;width: 0px;">
						<div class="modal-body" >
							 <div class="card card-container">
							 <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -40px; margin-right: -35px;"><font color="red" size="6"><span aria-hidden="true">×</span></font></button>
					            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
					            <p id="profile-name" class="profile-name-card"></p>
					            <form class="form-signin" action="?controller=account&action=login" method="post">
					                <span id="reauth-email" class="reauth-email"></span>
					                <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Username" required autofocus>
					                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
					                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Log in</button>
					            </form>
					        </div><
						</div>

					</div>
				</div>
			</div>

				<div class="modal fade" id="mySignUpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content" style="height: 0px;width: 0px;">
							<div class="modal-body" >
								 <div class="card card-container">
								 <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -40px; margin-right: -35px;"><font color="red" size="6"><span aria-hidden="true">×</span></font></button>
						            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
						            <form class="form-signin" action="?controller=account&action=register" method="post" enctype="multipart/form-data">
						                <span id="reauth-email" class="reauth-email"></span>
						                <input type="text" name="username" id="UserName" class="form-control" placeholder="Username" required="required" autofocus="autofocus">
						                <input type="text" name="firstname" id="FirstName" class="form-control" placeholder="First Name" required="required">
						                <input type="text" name="lastname" id="LastName" class="form-control" placeholder="Last Name" required>
										<input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
						                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
						                <input type="password" name="password2" id="confirmPassword" class="form-control" placeholder="Confirm Password" required>
										<label for="files">User image:</label> 
										<input id="files" type="file" name="Filename" /> <br/>
										<input class="btn btn-lg btn-primary btn-block btn-signin" name="signin" type="submit" value="Sign in" alt="Sign in" title="Sign in" />
						            </form>
						        </div>
							</div>
						</div>
					</div>
				</div>
				
			<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel">Delete topic</h4>
						</div>
						<div class="modal-body">
							<p>
							<p class="debug-url"></p>
							<p>Are you sure you want to delete this topic?</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							<a class="btn btn-danger btn-ok">Confirm</a>
						</div>
					</div>
				</div>
			</div>

<body>
</html>