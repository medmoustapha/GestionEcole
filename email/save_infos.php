<?php
  $id_destinataire=$_POST['id_destinataire'];
  $date=date("Y-m-d H:i:s");
  $titre=$_POST['titre'];
  $messagerie=$_POST['messagerie_e'];
  $id_dossier_exp=$_POST['id_dossier_exp'];
  $id_dossier_dest=$_POST['id_dossier_dest'];
  
  $message=$Langue['MSG_ENVOYER']." <a href=".$gestclasse_config_plus['gestclasse_url'].">".$gestclasse_config_plus['gestclasse_url']."</a> ".$Langue['MSG_ENVOYER2']."<br>".$gestclasse_config_plus['signature_messagerie'];
  $sujet=$Langue['MSG_SUJET'];
  $headers = 'From: '. $gestclasse_config_plus['email_defaut'] . "\r\n" .
			 'Content-Type: text/html; charset="iso-8859-1"' ."\r\n" .
			 'Reply-To: ' . $gestclasse_config_plus['email_defaut'] . "\r\n" .
			 'X-Mailer: PHP/' . phpversion();
  $destinataire_liste="";
  $etat_liste="";
  $dossier_dest="";
  
  for ($i=0;$i<count($id_destinataire);$i++)
  {
	$id_dest=substr($id_destinataire[$i],1,strlen($id_destinataire[$i]));
	$type_dest=substr($id_destinataire[$i],0,1);
	$destinataire_liste=$destinataire_liste.$type_dest.$id_dest.";";
	$etat_liste=$etat_liste."N;";
	$dossier_dest=$dossier_dest.$id_dossier_dest.";";
	// Vérification qu'il faut ou non envoyer un email pour prévenir du message
	if ($type_dest=="P" || $type_dest=="D")
	{
	  $req2=mysql_query("SELECT * FROM `profs` WHERE id='".$id_dest."'");
	  if (mysql_result($req2,0,'recevoir_email')=="1")
	  {
		if (mysql_result($req2,0,'email')!="")
		{
  		  $to=mysql_result($req2,0,'email');
          mail($to, $sujet, $message, $headers);
		}
	  }
	}  
	else
	{
	  $req2=mysql_query("SELECT * FROM `eleves` WHERE id='".$id_dest."'");
	  if (mysql_result($req2,0,'recevoir_email_pere')=="1")
	  {
	    if (mysql_result($req2,0,'email_pere')!="")
		{
  	      $to=mysql_result($req2,0,'email_pere');
          mail($to, $sujet, $message, $headers);
		}
	  }
	  
	  if (mysql_result($req2,0,'recevoir_email_mere')=="1")
	  {
	    if (mysql_result($req2,0,'email_mere')!="")
		{
	      $to=mysql_result($req2,0,'email_mere');
          mail($to, $sujet, $message, $headers);
		}
	  }
	}  
  }
  $id=Construit_Id("email",50);
  $req=mysql_query("INSERT INTO `email` (id,id_expediteur,type_expediteur,id_destinataire,etat,id_dossier_exp,id_dossier_dest,titre,messagerie,date,pj,pj_nom) VALUES ('$id','".$_SESSION['id_util']."','".$_SESSION['type_util']."','".substr($destinataire_liste,0,strlen($destinataire_liste)-1)."','".substr($etat_liste,0,strlen($etat_liste)-1)."','$id_dossier_exp','".substr($dossier_dest,0,strlen($dossier_dest)-1)."','$titre','$messagerie','$date','".$_POST['pj_id']."','".$_POST['pj_name']."')");
  echo $id;
?>