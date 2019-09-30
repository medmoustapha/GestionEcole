<?php
  $req = mysql_query("SELECT * FROM `email` WHERE id = '" . $_GET['id'] . "'");
  $recharge=false;
  $destinataire=explode(";",mysql_result($req,0,'id_destinataire'));
  $etat=explode(";",mysql_result($req,0,'etat'));
  if (in_array($_SESSION['type_util'].$_SESSION['id_util'],$destinataire))
  {
    $key=array_search($_SESSION['type_util'].$_SESSION['id_util'],$destinataire);
		$etat[$key]="L";
		$result=implode(";",$etat);
    $req2 = mysql_query("UPDATE `email` SET etat='$result' WHERE id = '" . $_GET['id'] . "'");
		$recharge=true;
  }

  $tpl = new template("email");
  $tpl->set_file("gform","detailview.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  $decaler=explode(':',$gestclasse_config_plus['decalage_horaire']);
	if (substr($decaler[0],0,1)=="-") { $decaler[1]=-$decaler[1]; }

	$msg='<select id="nav_liste_email" name="nav_liste_email" class="text ui-widget-content ui-corner-all">';
	$msg2='<select id="nav_liste_email2" name="nav_liste_email2" class="text ui-widget-content ui-corner-all">';
  switch ($_SESSION['id_dossier_mail'])
  {
    case "R" : 
			$req_liste=mysql_query("SELECT * FROM `email` WHERE (id_destinataire LIKE '".$_SESSION['type_util'].$_SESSION['id_util']."' OR id_destinataire LIKE '".$_SESSION['type_util'].$_SESSION['id_util'].";%' OR id_destinataire LIKE '%;".$_SESSION['type_util'].$_SESSION['id_util']."' OR id_destinataire LIKE '%;".$_SESSION['type_util'].$_SESSION['id_util'].";%') ORDER BY date DESC");
			for ($i=1;$i<=mysql_num_rows($req_liste);$i++)
			{
				$destinataire=explode(';',mysql_result($req_liste,$i-1,'id_destinataire'));
				$key=array_search($_SESSION['type_util'].$_SESSION['id_util'],$destinataire);
				$etat=explode(";",mysql_result($req_liste,$i-1,'etat'));
				if ($etat[$key]!="S") 
				{
				  $msg=$msg.'<option value="'.mysql_result($req_liste,$i-1,'id').'"';
				  $msg2=$msg2.'<option value="'.mysql_result($req_liste,$i-1,'id').'"';
					if (mysql_result($req_liste,$i-1,'id')==$_GET['id']) { $msg=$msg.' SELECTED';$msg2=$msg2.' SELECTED'; } else { $msg=$msg.'';$msg2=$msg2.''; }
					if (strlen(mysql_result($req_liste,$i-1,'titre'))>20) { $titre=substr(mysql_result($req_liste,$i-1,'titre'),0,20).'...'; } else { $titre=mysql_result($req_liste,$i-1,'titre'); }
					$msg=$msg.'>'.$Langue['LBL_LE']." ".Date_Convertir(mysql_result($req_liste,$i-1,'date'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_A'].' '.date("H:i:s",mktime(substr(mysql_result($req_liste,$i-1,'date'),11,2)-$decaler[0],substr(mysql_result($req_liste,$i-1,'date'),14,2)-$decaler[1],substr(mysql_result($req_liste,$i-1,'date'),17,2),01,01,2010)).' - '.$titre.'</option>';
					$msg2=$msg2.'>'.$Langue['LBL_LE']." ".Date_Convertir(mysql_result($req_liste,$i-1,'date'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_A'].' '.date("H:i:s",mktime(substr(mysql_result($req_liste,$i-1,'date'),11,2)-$decaler[0],substr(mysql_result($req_liste,$i-1,'date'),14,2)-$decaler[1],substr(mysql_result($req_liste,$i-1,'date'),17,2),01,01,2010)).' - '.$titre.'</option>';
				}
			}
			break;
    case "E" : 
			$req_liste=mysql_query("SELECT * FROM `email` WHERE id_expediteur='".$_SESSION['id_util']."' AND type_expediteur='".$_SESSION['type_util']."' AND id_dossier_exp='E' ORDER BY date DESC");
			for ($i=1;$i<=mysql_num_rows($req_liste);$i++)
			{
				$msg=$msg.'<option value="'.mysql_result($req_liste,$i-1,'id').'"';
				$msg2=$msg2.'<option value="'.mysql_result($req_liste,$i-1,'id').'"';
				if (mysql_result($req_liste,$i-1,'id')==$_GET['id']) { $msg=$msg.' SELECTED';$msg2=$msg2.' SELECTED'; } else { $msg=$msg.'';$msg2=$msg2.''; }
				if (strlen(mysql_result($req_liste,$i-1,'titre'))>20) { $titre=substr(mysql_result($req_liste,$i-1,'titre'),0,20).'...'; } else { $titre=mysql_result($req_liste,$i-1,'titre'); }
				$msg=$msg.'>'.$Langue['LBL_LE']." ".Date_Convertir(mysql_result($req_liste,$i-1,'date'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_A'].' '.date("H:i:s",mktime(substr(mysql_result($req_liste,$i-1,'date'),11,2)-$decaler[0],substr(mysql_result($req_liste,$i-1,'date'),14,2)-$decaler[1],substr(mysql_result($req_liste,$i-1,'date'),17,2),01,01,2010)).' - '.$titre.'</option>';
				$msg2=$msg2.'>'.$Langue['LBL_LE']." ".Date_Convertir(mysql_result($req_liste,$i-1,'date'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_A'].' '.date("H:i:s",mktime(substr(mysql_result($req_liste,$i-1,'date'),11,2)-$decaler[0],substr(mysql_result($req_liste,$i-1,'date'),14,2)-$decaler[1],substr(mysql_result($req_liste,$i-1,'date'),17,2),01,01,2010)).' - '.$titre.'</option>';
			}
			break;
  }
	$msg=$msg.'</select>';
	$msg2=$msg2.'</select>';
	$tpl->set_var("LISTE_EMAIL",$msg);
	$tpl->set_var("LISTE_EMAIL2",$msg2);
	
  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  $tpl->set_var("DATE",$Langue['LBL_LE']." ".Date_Convertir(mysql_result($req,0,'date'),'Y-m-d',$Format_Date_PHP).' '.$Langue['LBL_A'].' '.date("H:i:s",mktime(substr(mysql_result($req,0,'date'),11,2)-$decaler[0],substr(mysql_result($req,0,'date'),14,2)-$decaler[1],substr(mysql_result($req,0,'date'),17,2),01,01,2010)));
	$type_e=mysql_result($req,0,'type_expediteur');
	$id_e=mysql_result($req,0,'id_expediteur');
	if ($type_e=="E") { $req2=mysql_query("SELECT * FROM `eleves` WHERE id='$id_e'"); } else { $req2=mysql_query("SELECT * FROM `profs` WHERE id='$id_e'"); }
  $tpl->set_var("EXPEDITEUR",mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom'));

	$destinataire=explode(";",mysql_result($req,0,'id_destinataire'));
	$msg="";
	for ($i=0;$i<count($destinataire);$i++)
	{
		$type_e=substr($destinataire[$i],0,1);
		$id_e=substr($destinataire[$i],1,strlen($destinataire[$i]));
	  if ($type_e=="E") { $req2=mysql_query("SELECT * FROM `eleves` WHERE id='$id_e'"); } else { $req2=mysql_query("SELECT * FROM `profs` WHERE id='$id_e'"); }
	  if ($type_e==$_SESSION['type_util'] && $id_e==$_SESSION['id_util'])
	  {
	    $msg=$msg.'<u>'.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').'</u>, ';
	  }
	  else
	  {
	    $msg=$msg.mysql_result($req2,0,'nom').' '.mysql_result($req2,0,'prenom').", ";
	  }
	}
	$tpl->set_var("DESTINATAIRE",substr($msg,0,strlen($msg)-2));
	$tpl->set_var("TITRE",mysql_result($req,0,'titre'));
	$tpl->set_var("MESSAGERIE",mysql_result($req,0,'messagerie'));
	$tpl->set_var("ID",mysql_result($req,0,'id'));
	
	$id_pj=explode(', ',mysql_result($req,0,'pj'));
	$nom_pj=explode(', ',mysql_result($req,0,'pj_nom'));
	$msg="";
	for ($j=0;$j<count($id_pj);$j++)
	{
	  $msg .='<a href="cache/email/'.$id_pj[$j].'" target="_blank">'.$nom_pj[$j].'</a><br>';
	}
	$tpl->set_var("LISTE_PJ",$msg);

	$tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Imprimer").button();
  $("#Imprimer2").button();
	$("#Imprimer").click(function()
  {
    data = "id_message=<?php echo $_GET['id']; ?>";
    window.open("index2.php?module=email&action=imprimer_lancer&"+data,"Impression");
    $("#dialog-form").dialog( "close" );
  });
	$("#Imprimer2").click(function()
  {
    $("#Imprimer").click();
  });

  $("#Repondre").button();
  $("#Repondre2").button();
  $("#Supprimer").button();
  $("#Supprimer2").button();
  $("#Supprimer").click(function()
  {
		Emails_Supprimer_Email('<?php echo $_GET['id']; ?>');
  });
  $("#Supprimer2").click(function()
  {
		Emails_Supprimer_Email('<?php echo $_GET['id']; ?>');
  });

  $("#Annuler").button();
  $("#Annuler2").button();
  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });

  $("#Repondre").click(function()
  {
    Charge_Dialog("index2.php?module=email&action=editview&id=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_ENVOYER_MESSAGE']; ?>");
  });
  $("#Repondre2").click(function()
  {
    Charge_Dialog("index2.php?module=email&action=editview&id=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_ENVOYER_MESSAGE']; ?>");
  });

  $("#nav_liste_email").change(function()
  {
		Charge_Dialog("index2.php?module=email&action=detailview&id="+$("#nav_liste_email").val(),"<?php echo $Langue['LBL_LIRE_MESSAGE']; ?>");
  });
  
  $("#nav_liste_email2").change(function()
  {
		Charge_Dialog("index2.php?module=email&action=detailview&id="+$("#nav_liste_email2").val(),"<?php echo $Langue['LBL_LIRE_MESSAGE']; ?>");
  });

<?php if ($recharge==true) { ?>
  $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
<?php } ?>
});

function Emails_Supprimer_Email(id)
{
  $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_SUPPRIMER_MESSAGE']; ?></div>');

  $( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog(
	{
		title: "<?php echo $Langue['LBL_SUPPRIMER_MESSAGE']; ?>",
		resizable: false,
		draggable: false,
		height:200,
		width:450,
		modal: true,
		buttons:[
		{
			text: "<?php echo $Langue['BTN_SUPPRIMER']; ?>",
			click: function()
			{
				Message_Chargement(4,1);
				var request = $.ajax({type: "POST", url: "index2.php", data: "module=email&action=save_suppression&id="+id});
				request.done(function(msg)
				{
					$( "#dialog-confirm" ).dialog( "close" );
					$("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
					$("#dialog-form").dialog( "close" );
					Message_Chargement(1,0);
				});
			}
		},
	  {
			text: "<?php echo $Langue['BTN_ANNULER2']; ?>",
			click: function()
      {
				$( this ).dialog( "close" );
			}
	  }]
	});
}
</script>
