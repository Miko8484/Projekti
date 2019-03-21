
<?php

header('Access-Control-Allow-Origin: *');

$id=$_GET['id'];

$ime=sha1(date('Y-m-d H:i:s'));
$target = "user_image/";
$target = $target . basename( $_FILES['file']['name']);
$file_type = $_FILES['file']['type'];
$allowed = array("image/jpeg", "image/png");
$filename = $_FILES["file"]["name"];
$file_basename = substr($filename, 0, strripos($filename, '.'));
$file_ext = substr($filename, strripos($filename, '.')); 
$newfilename = $file_basename . $ime . $file_ext;

if(in_array($file_type, $allowed)) 
{
	if(move_uploaded_file($_FILES['file']['tmp_name'], "user_image/" . $newfilename))
	{
		echo "Upload and move success".$id;
	}
}

require_once('models/account.php');
require_once('connection.php');
ionic_account::updateImage($id,$newfilename);

?>