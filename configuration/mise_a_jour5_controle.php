<?php
  $fichier='cache/update/'.$_POST['fichier'].'/install.txt';

  $fp = @fopen( $fichier, 'r' );
  if ($fp) 
  {
    $type=fgets($fp);
    $decoupe=explode("|",$type);
	if ($decoupe[0]!="update")
	{
	  echo "erreur2";
	}
	else
	{
	  if ($decoupe[1]!=substr($gestclasse_config_plus['version_de_l_application'],0,strlen($decoupe[1])))
	  {
	    echo "erreur3";
	  }
	  else
	  {
	    echo $_POST['fichier'];
	  }
	}  
    fclose($fp);
  }
  else
  {
    echo "erreur";
  }
?>