<?php
  $targ_w = 190;
  $targ_h = 250;
  $jpeg_quality = 90;
  $id_personne=$_POST['id_personne'];
    $dimensions=getimagesize('cache/photos/'.$id_personne.'_temp.jpg');
	$src = 'cache/photos/'.$id_personne.'_temp.jpg';
	$extension="jpg";
    if ($dimensions[0]<$dimensions[1])
    {
	  if ($dimensions[1]>450) { $largeur=450*$dimensions[0]/$dimensions[1]; $hauteur=450; } else { $largeur=$dimensions[0]; $hauteur=$dimensions[1]; }
    }
    else
    {
	  if ($dimensions[0]>600) { $hauteur=600*$dimensions[1]/$dimensions[0]; $largeur=600; } else { $largeur=$dimensions[0]; $hauteur=$dimensions[1]; }
    }
  
  $thumb = imagecreatetruecolor($largeur, $hauteur);
  $source = imagecreatefromjpeg($src);
  imagecopyresized($thumb, $source, 0, 0, 0, 0, $largeur, $hauteur, $dimensions[0], $dimensions[1]);
  imagejpeg($thumb,'cache/photos/'.$id_personne.'_temp2.jpg',100);
  
  $img_r = imagecreatefromjpeg('cache/photos/'.$id_personne.'_temp2.jpg');
  $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

  imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']);
  
  imagejpeg($dst_r,'cache/photos/'.$id_personne.'.jpg',$jpeg_quality);
  
  unlink('cache/photos/'.$id_personne.'_temp.'.$extension);
  unlink('cache/photos/'.$id_personne.'_temp2.jpg');
  
  echo '<script language="javascript" type="text/javascript">parent.principal.formUploadCallback(\'ok\');</script>';
?>