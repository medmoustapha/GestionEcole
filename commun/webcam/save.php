<?php
if(isset($GLOBALS["HTTP_RAW_POST_DATA"])){
	$jpg = $GLOBALS["HTTP_RAW_POST_DATA"];
	$img = $_GET["img"];
	$filename = "../../cache/photos/".$_GET['id_personne']."_temp.jpg";
	file_put_contents($filename, $jpg);
} 
else
{
	echo "Encoded JPEG information not received.";
}
?>