<?php
  
	class contact
	{
		public static function send()
		{
			if(isset($_POST['confirm_button']))
			{
				$email=strip_tags($_POST['email']);
				$content=strip_tags($_POST['content']);
				
				 $to = "mitja.celec12@gmail.com";
				 $subject = "Hi!";
				 $body = $content."\n\n".$email;
				 $headers = "From: sender@example.com\r\n" . "X-Mailer: php";
				 if (mail($to, $subject, $body,$headers)) {
				   echo("<p>Email successfully sent!</p>");
				  } else {
				   echo("<p>Email delivery failed…</p>");
				  }

			}
		}
	}
  
?>