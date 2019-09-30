<?php
  $content_dir = 'cache/photos/';
  $tmp_file = $_FILES['fichier']['tmp_name'];
  $id_p=$_POST['id_personne'].'_temp';

  if(is_uploaded_file($tmp_file))
  {
    $name_file = $id_p.".jpg";
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