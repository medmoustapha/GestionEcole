<?php
  $fichier='cache/update/'.$_POST['fichier'].'/install.txt';

  $fp = @fopen( $fichier, 'r' );
  if ($fp) 
  {
    $type=fgets($fp);
    $decoupe=explode("|",$type);
	if ($decoupe[0]!="langue")
	{
	  echo "erreur2";
	}
	else
	{
      echo $_POST['fichier'];
	}  
    fclose($fp);
  }
  else
  {
    echo "erreur";
  }
?>