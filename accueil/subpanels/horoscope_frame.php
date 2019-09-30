<?php
  session_start();
  include "../../langues/fr-FR/config.php";
  if ($_SESSION['language_application']!="fr-FR")
  {
	if (file_exists("../../langues/".$_SESSION['language_application']."/config.php"))
	{
	  include "../../langues/".$_SESSION['language_application']."/config.php";
	}
  }

  require_once("../../commun/magpierss/rss_fetch.inc");
  $signe=$_GET['signe'];
	   
  $rss = fetch_rss("http://www.asiaflash.com/horoscope/rss_horojour_".$signe.".xml");
  if (is_array($rss->items))
  {
   // on ne recupere que les elements les + recents
   $items = array_slice($rss->items,0);
   foreach ($items as $item)
   {
     $description=$item['description'];
	 $i=1;
	 foreach ($liste_choix['horoscope'] AS $cle => $value)
	 {
	   $value=utf8_decode($value);
	   if ($i<=9) { $j="0".$i; } else { $j=$i; }
  	   $description=str_replace('<br/><center><br/><img src="http://www.asiaflash.com/anh/bleu_'.$j.'.gif" alt="'.$value.'" title="'.$value.'"/></center>','',$description);
	   $description=str_replace('Horoscope '.$value.' - ','',$description);
	   $i++;
	 }
	 echo '<font style="font-size:12px;font-family:Arial">'.substr(utf8_encode($description),10,strlen($description)).'</font>';
   }
  } 
?>
