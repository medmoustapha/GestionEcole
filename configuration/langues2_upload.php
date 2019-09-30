<?php
  $content_dir = 'cache/update/';
  $tmp_file = $_FILES['fichier']['tmp_name'];

  if(is_uploaded_file($tmp_file))
  {
    $name_file = str_replace(" ","_",$_FILES['fichier']['name']);
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