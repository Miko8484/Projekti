<?php ob_start()?>

<h1>Tekme</h1>

  <form method='post' name="form" action="#">
         <div id="content_holder">
            <div id="ele4" class="b">
                <p>
                    <?php echo tekme(); ?>
                </p>
            </div>
        </div>
        <div class="footer">
			<input type="submit" name="gumb0" value="Zaključi" id="submit" /> 
		</div>

  </form> 
        

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


$title = "Tekme";
$content=ob_get_clean();

require "layout.html.php";


?>

 


