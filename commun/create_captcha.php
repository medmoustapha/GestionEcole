<?php
  $image = @imagecreatefrompng("../images/fond_captcha.png");
  $bg = imagecolorallocate($image, 255, 255, 255);
  $textcolor = imagecolorallocate($image, 0, 0, 0);
  $code2=$_GET['code'];
  $code="";
  for ($i=1;$i<=strlen($code2);$i++)
  {
    $code=$code.substr($code2,$i-1,1)." ";
  }
  $code=substr($code,0,strlen($code)-1);
  $text_width = imagefontwidth(5)*strlen($code);
  $x = 70 - (ceil($text_width/2)); 
  imagestring($image, 5, $x, 5, $code, $textcolor);
  header('Content-type: image/png');
  imagepng($image);
  imagedestroy($image);
?>
