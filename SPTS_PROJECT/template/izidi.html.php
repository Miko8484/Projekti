<?php ob_start()?>

<?php 
	$id=$_GET['id'];
	
?>

<h1 style="display:inline-block;margin-right:10px;">Izidi </h1> 

<h2 style="display:inline-block;"><?php echo naslovturnira($id);?></h2>
	
	<div id="ele4" class="b">
		<p>
			<?php echo izidi($id); ?>
		</p>
		
    </div>




<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>
            window.jQuery || document.write('<script src="print/demo/js/vendor/jquery-1.9.1.min.js"><\/script>')
        </script>

        <script src="print/jQuery.print.js"></script>

       <script type='text/javascript'>
            //<![CDATA[
            $(function() {
                $("#ele4").find('button').on('click', function() {
                    //Print ele4 with custom options
                    $("#ele4").print({
                        //Use Global styles
                        globalStyles : true,

                        //Add link with attrbute media=print
                        mediaPrint : false,
                    });
                });

                // Fork https://github.com/sathvikp/jQuery.print for the full list of options
            });
            //]]>

        </script>

<?php

$title = "Izidi";
$content=ob_get_clean();

require "layout.html.php";
?>



 
