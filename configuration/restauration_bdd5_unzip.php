<?php
  $file_name = $_POST['fichier'];

  require_once('commun/pclzip/pclzip.lib.php');
  chdir('cache/restore/');
  $archive = new PclZip( $file_name );

  $zip_dir='./'.substr($file_name,0,strlen($file_name)-4);
  
  if( $archive->extract( PCLZIP_OPT_PATH, $zip_dir ) == 0 ){
     echo "erreur";
  }
  else
  {
    if (file_exists($zip_dir."/export_bdd.sql"))
    {
      echo substr($file_name,0,strlen($file_name)-4);
    }
    else
    {
      echo "erreur2";
    }
  }
?>
