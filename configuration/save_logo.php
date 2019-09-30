<?php
  $content_dir = 'cache/';
  if (file_exists("cache/logo_ecole.png"))
  {
    unlink("cache/logo_ecole.png");
  }
  if (file_exists("cache/logo_ecole.jpg"))
  {
    unlink("cache/logo_ecole.jpg");
  }
  $tmp_file = $_FILES['fichier']['tmp_name'];

  if(is_uploaded_file($tmp_file))
  {
    $name_file = $_FILES['fichier']['name'];
	$extension=substr($name_file,strlen($name_file)-3, 3);
	$name_file="logo_ecole.".$extension;
    if(move_uploaded_file($tmp_file, $content_dir . $name_file))
    {
      echo '<script language="javascript" type="text/javascript">parent.principal.formUploadCallback(\''.$name_file.'\');</script>';
    }
    else
    {
      echo '<script language="javascript" type="text/javascript">parent.principal.formUploadCallback(\'move\');</script>';
    }
  }
  else
  {
    echo '<script language="javascript" type="text/javascript">parent.principal.formUploadCallback(\'upload\');</script>';
  }
?>