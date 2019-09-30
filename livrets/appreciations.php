<?php
  /*****************************************/
  /* Affichage et saisie des appréciations */
  /*****************************************/
if ($gestclasse_config_plus['appreciation_livrets']=="1" && $gestclasse_config_plus['signature_livrets']=="1")
{
  echo '<br /><div class="ui-widget ui-widget-header ui-corner-all ui-div-separation-form">'.$Langue['LBL_APPRECIATION_COMMENTAIRES'].'</div>';

  foreach ($liste_choix['livret_decoupage_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
  {
    // On vérifie que l'enseignant a mis une appréciation et l'a signée
    echo '<div class="ui-widget ui-widget-content ui-corner-all ui-div-separation-form2">';
	echo '<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form">'.$value.'</div>';
	$parametre='livrets|'.$cle.'|'.$annee_scolaire_ls.'|P|'.$id_eleve;
	$req_signature=mysql_query("SELECT * FROM `signatures` WHERE parametre='$parametre'");
	if (mysql_num_rows($req_signature)=="")
	// Pas de signature
	{
	  if ($id_prof==$_SESSION['id_util'])
	  {
		  echo '<table cellspacing=0 cellpadding=0 border=0 style="width:100%"><tr><td width=50% valign=top class="textgauche">';
	      echo '<form id="form_'.$cle.'" name="form_'.$cle.'" action=index2.php method=POST>';
		  echo '<div id="msg_ok'.$cle.'"></div>';
		  echo '<input type="hidden" id="module" name="module" value="livrets">';
		  echo '<input type="hidden" id="action" name="action" value="save_appreciation">';
		  echo '<input type="hidden" id="trimestre" name="trimestre" value="'.$cle.'">';
		  echo '<input type="hidden" id="id_util" name="id_util" value="'.$id_prof.'">';
		  echo '<input type="hidden" id="id_eleve" name="id_eleve" value="'.$id_eleve.'">';
		  echo '<input type="hidden" id="type_util" name="type_util" value="P">';
		  echo '<input type="hidden" id="annee_scolaire_ls" name="annee_scolaire_ls" value="'.$annee_scolaire_ls.'">';
		  echo '<table cellspacing=0 cellpadding=0 class="tableau_editview2" style="padding-top:0px;margin-top:0px"><tr>';
		  echo '<td class="droite" valign=top nowrap>'.$Langue['LBL_APPRECIATION_COMMENTAIRES'].' :&nbsp;</td>';
		  $req_appreciation=mysql_query("SELECT * FROM `livrets_appreciation` WHERE id_eleve='".$id_eleve."' AND trimestre='$cle' AND annee='".$annee_scolaire_ls."' AND id_util='".$id_prof."' AND type_util='P'");
		  if (mysql_num_rows($req_appreciation)=="") { $id="";$commentaire=""; } else { $commentaire=str_replace("<br>","\r\n",mysql_result($req_appreciation,0,'appreciation'));$id=mysql_result($req_appreciation,0,'id'); }
		  echo '<td class="gauche" valign=top><input type="hidden" id="id" name="id" value="'.$id.'"><textarea name="appreciation" id="appreciation'.$cle.'" rows=12 cols=55 class="text ui-widget-content ui-corner-all">'.$commentaire.'</textarea><br /><div style="text-align:center"><input type="Submit" id="Valider_Appreciation'.$cle.'" name="Valider_Appreciation" value="'.$Langue['BTN_ENREGISTRER'].'"></div></form></td></tr></table>';
		  echo '</td><td width=50% valign=top class="droite"><div class="textdroite">';
		  $texte=$Langue['LBL_APPRECIATION_SIGNATURE'];
		  $complement=$cle;
		  $adresse_plus='Charge_Dialog("index2.php?module=livrets&action=detailview_ls&id_eleve='.$id_eleve.'&annee_scolaire_ls='.$annee_scolaire_ls.'");';
		  if (mysql_num_rows($req_appreciation)!="")
		  {
		      include "commun/signature_electronique.php";
		  }
		  echo '</div></td></tr>';
		  echo '</table>';
		  echo '<script language="Javascript">';
		  echo '$(document).ready(function()';
		  echo '{';
		  echo '$("#Valider_Appreciation'.$cle.'").button();';
?>
		  $("#form_<?php echo $cle; ?>").submit(function(event)
		  {
			var bValid = true;
			var results = $(this).serialize();
			if (!checkValue($("#appreciation<?php echo $cle; ?>"))) { bValid=false; }
			event.preventDefault();
			if ( bValid )
			{
			  Message_Chargement(2,1);
			  var $form = $( this );
			  url = $form.attr( 'action' );
			  data = $form.serialize();
			  var request = $.ajax({type: "POST", url: url, data: data});
			  request.done(function(msg)
			  {
			    decoupe=msg.split("-");
			    Charge_Dialog("index2.php?module=livrets&action=detailview_ls&id_eleve="+decoupe[0]+"&annee_scolaire_ls="+decoupe[1]);
			  });
			}
			else
			{
  		      $("#msg_ok<?php echo $cle; ?>").fadeIn( 1000 );
  			  $("#msg_ok<?php echo $cle; ?>").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div>');
    		  setTimeout(function()
			  {
				$("#msg_ok<?php echo $cle; ?>").effect("blind",1000);
			  }, 3000 );
			}
		  });
<?php
		  echo '});';
		  echo '</script>';
	  }
	}
	else
    // Appréciation et signature par l'enseignant
	{
	  // On regarde si c'est un élève qui est connecté car, dans ce cas, on ne montre les appréciations que si l'enseignant et le directeur ont signé
	  if ($_SESSION['type_util']=="E")
	  {
	    $parametre='livrets|'.$cle.'|'.$annee_scolaire_ls.'|D|'.$id_eleve;
		$req_signature=mysql_query("SELECT * FROM `signatures` WHERE parametre='$parametre'");
		if (mysql_num_rows($req_signature)=="") { $montre=false; } else { $montre=true; }
	  }
	  else
	  {
	    $montre=true;
	  }	
	  
	  if ($montre==true)
	  {
		  $parametre='livrets|'.$cle.'|'.$annee_scolaire_ls.'|P|'.$id_eleve;
		  echo '<div class="ui-widget ui-widget-content ui-corner-all ui-div-separation-form"><p class="explic"><strong>'.$Langue['LBL_APPRECIATION_ENSEIGNANT'].'</strong></p>'; 
		  $req_appreciation=mysql_query("SELECT * FROM `livrets_appreciation` WHERE id_eleve='".$id_eleve."' AND trimestre='$cle' AND annee='".$annee_scolaire_ls."' AND id_util='".$id_prof."' AND type_util='P'");
		  if (mysql_num_rows($req_appreciation)!="")
		  {
			echo '<p class=explic>'.mysql_result($req_appreciation,0,'appreciation').'</p>';
		  }
 	      include "commun/signature_electronique.php";
		  echo '</div>';
	  }
      // On vérifie alors que le directeur a mis une appréciation et l'a signée
	    $parametre='livrets|'.$cle.'|'.$annee_scolaire_ls.'|D|'.$id_eleve;
		$req_signature=mysql_query("SELECT * FROM `signatures` WHERE parametre='$parametre'");
		if (mysql_num_rows($req_signature)=="")
		// Pas de signature
		{
		  if ($_SESSION['type_util']=="D")
		  {
			  echo '<div class="ui-widget ui-widget-content ui-corner-all ui-div-separation-form"><p class="explic"><strong>'.$Langue['LBL_APPRECIATION_DIRECTEUR'].'</strong></p>'; 
			  echo '<table cellspacing=0 cellpadding=0 border=0 style="width:100%"><tr><td width=50% valign=top class="textgauche">';
			  echo '<form id="form_'.$cle.'" name="form_'.$cle.'" action=index2.php method=POST>';
			  echo '<div id="msg_ok'.$cle.'"></div>';
			  echo '<input type="hidden" id="module" name="module" value="livrets">';
			  echo '<input type="hidden" id="action" name="action" value="save_appreciation">';
			  echo '<input type="hidden" id="trimestre" name="trimestre" value="'.$cle.'">';
			  echo '<input type="hidden" id="id_util" name="id_util" value="'.$id_prof.'">';
			  echo '<input type="hidden" id="id_eleve" name="id_eleve" value="'.$id_eleve.'">';
			  echo '<input type="hidden" id="type_util" name="type_util" value="D">';
			  echo '<input type="hidden" id="annee_scolaire_ls" name="annee_scolaire_ls" value="'.$annee_scolaire_ls.'">';
			  echo '<table cellspacing=0 cellpadding=0 class="tableau_editview2" style="padding-top:0px;margin-top:0px"><tr>';
			  echo '<td class="droite" valign=top nowrap>'.$Langue['LBL_APPRECIATION_COMMENTAIRES'].' :&nbsp;</td>';
			  $req_appreciation=mysql_query("SELECT * FROM `livrets_appreciation` WHERE id_eleve='".$id_eleve."' AND trimestre='$cle' AND annee='".$annee_scolaire_ls."' AND id_util='".$id_prof."' AND type_util='D'");
			  if (mysql_num_rows($req_appreciation)=="") { $id="";$commentaire=""; } else { $commentaire=str_replace("<br>","\r\n",mysql_result($req_appreciation,0,'appreciation'));$id=mysql_result($req_appreciation,0,'id'); }
			  echo '<td class="gauche" valign=top><input type="hidden" id="id" name="id" value="'.$id.'"><textarea name="appreciation" id="appreciation'.$cle.'" rows=12 cols=55 class="text ui-widget-content ui-corner-all">'.$commentaire.'</textarea><br /><div style="text-align:center"><input type="Submit" id="Valider_Appreciation'.$cle.'" name="Valider_Appreciation" value="'.$Langue['BTN_ENREGISTRER'].'"></div></form></td></tr></table>';
			  echo '</td><td width=50% valign=top class="droite"><p class="textdroite">';
			  $texte=$Langue['LBL_APPRECIATION_SIGNATURE'];
			  $complement=$cle;
			  $adresse_plus='Charge_Dialog("index2.php?module=livrets&action=detailview_ls&id_eleve='.$id_eleve.'&annee_scolaire_ls='.$annee_scolaire_ls.'");';
			  include "commun/signature_electronique.php";
			  echo '</p></td></tr>';
			  echo '</table>';
			  echo '<script language="Javascript">';
			  echo '$(document).ready(function()';
			  echo '{';
			  echo '$("#Valider_Appreciation'.$cle.'").button();';
	?>
			  $("#form_<?php echo $cle; ?>").submit(function(event)
			  {
				var bValid = true;
				var results = $(this).serialize();
				if (!checkValue($("#appreciation<?php echo $cle; ?>"))) { bValid=false; }
				event.preventDefault();
				if ( bValid )
				{
				  Message_Chargement(2,1);
				  var $form = $( this );
				  url = $form.attr( 'action' );
				  data = $form.serialize();
				  var request = $.ajax({type: "POST", url: url, data: data});
				  request.done(function(msg)
				  {
					decoupe=msg.split("-");
					Charge_Dialog("index2.php?module=livrets&action=detailview_ls&id_eleve="+decoupe[0]+"&annee_scolaire_ls="+decoupe[1]);
				  });
				}
				else
				{
				  $("#msg_ok<?php echo $cle; ?>").fadeIn( 1000 );
				  $("#msg_ok<?php echo $cle; ?>").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div>');
				  setTimeout(function()
				  {
					$("#msg_ok<?php echo $cle; ?>").effect("blind",1000);
				  }, 3000 );
				}
			  });
	<?php
			  echo '});';
			  echo '</script>';
			  echo '</div>';
		  }
		}  
		else
		// Signature du directeur présente
		{
		  echo '<table cellspacing=0 cellpadding=0 border=0 width=100%><tr><td width=100% valign=top>';
		  echo '<div class="ui-widget ui-widget-content ui-corner-all ui-div-separation-form"><p class="explic"><strong>'.$Langue['LBL_APPRECIATION_DIRECTEUR'].'</strong></p>'; 
		  $req_appreciation=mysql_query("SELECT * FROM `livrets_appreciation` WHERE id_eleve='".$id_eleve."' AND trimestre='$cle' AND annee='".$annee_scolaire_ls."' AND id_util='$id_prof' AND type_util='D'");
		  if (mysql_num_rows($req_appreciation)!="")
		  {
			echo '<p class=explic>'.mysql_result($req_appreciation,0,'appreciation').'</p>';
		  }
		  include "commun/signature_electronique.php";
		  echo '</div></td><td valign=top><div class="ui-widget ui-widget-content ui-corner-all ui-div-separation-form margin10_gauche"><p class="explic"><strong>'.$Langue['LBL_APPRECIATION_PARENTS'].'</strong></p>';
		  // On passe alors aux parents : ont-ils signé ?
			$parametre='livrets|'.$cle.'|'.$annee_scolaire_ls.'|E|'.$id_eleve;
			$req_signature=mysql_query("SELECT * FROM `signatures` WHERE parametre='$parametre'");
  			$texte=$Langue['LBL_APPRECIATION_SIGNATURE'];
			$complement=$cle;
			$adresse_plus="";
			if (mysql_num_rows($req_signature)=="")
			// Pas de signature
			{
			  if ($_SESSION['type_util']=="E")
			  {
			    include "commun/signature_electronique.php";
			  }
			  else
			  {
			    echo '<div class="ui-state-error ui-state-default ui-corner-all margin10_haut margin10_bas marge10_tout textcentre" style="width:400px;">'.$Langue['LBL_APPRECIATION_ATTENTE_SIGNATURE'].'</div>';
			  }
			}
			else
			{
			  include "commun/signature_electronique.php";
			}  
			echo '</div></td></tr></table>';	
		}  
	}
	echo '</div>';
  }  
}

if ($gestclasse_config_plus['appreciation_livrets']=="1" && $gestclasse_config_plus['signature_livrets']=="0")
{
  echo '<br /><div class="ui-widget ui-widget-header ui-corner-all ui-div-separation-form">'.$Langue['LBL_APPRECIATION_COMMENTAIRES'].'</div>';

  foreach ($liste_choix['livret_decoupage_'.$gestclasse_config_plus['decoupage_livret']] AS $cle => $value)
  {
    // On vérifie que l'enseignant a mis une appréciation et l'a signée
    echo '<div class="ui-widget ui-widget-content ui-corner-all ui-div-separation-form2">';
	echo '<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form">'.$value.'</div>';
	  if ($id_prof==$_SESSION['id_util'])
	  {
		  echo '<table cellspacing=0 cellpadding=0 border=0 style="width:100%"><tr><td width=50% valign=top class="textgauche">';
	      echo '<form id="form_'.$cle.'" name="form_'.$cle.'" action=index2.php method=POST>';
		  echo '<div id="msg_ok'.$cle.'"></div>';
		  echo '<input type="hidden" id="module" name="module" value="livrets">';
		  echo '<input type="hidden" id="action" name="action" value="save_appreciation">';
		  echo '<input type="hidden" id="trimestre" name="trimestre" value="'.$cle.'">';
		  echo '<input type="hidden" id="id_util" name="id_util" value="'.$id_prof.'">';
		  echo '<input type="hidden" id="id_eleve" name="id_eleve" value="'.$id_eleve.'">';
		  echo '<input type="hidden" id="type_util" name="type_util" value="P">';
		  echo '<input type="hidden" id="annee_scolaire_ls" name="annee_scolaire_ls" value="'.$annee_scolaire_ls.'">';
		  echo '<table cellspacing=0 cellpadding=0 class="tableau_editview2" style="padding-top:0px;margin-top:0px"><tr>';
		  echo '<td class="droite" valign=top nowrap>'.$Langue['LBL_APPRECIATION_COMMENTAIRES'].' :&nbsp;</td>';
		  $req_appreciation=mysql_query("SELECT * FROM `livrets_appreciation` WHERE id_eleve='".$id_eleve."' AND trimestre='$cle' AND annee='".$annee_scolaire_ls."' AND id_util='".$id_prof."' AND type_util='P'");
		  if (mysql_num_rows($req_appreciation)=="") { $id="";$commentaire=""; } else { $commentaire=str_replace("<br>","\r\n",mysql_result($req_appreciation,0,'appreciation'));$id=mysql_result($req_appreciation,0,'id'); }
		  echo '<td class="gauche" valign=top><input type="hidden" id="id" name="id" value="'.$id.'"><textarea name="appreciation" id="appreciation'.$cle.'" rows=12 cols=55 class="text ui-widget-content ui-corner-all">'.$commentaire.'</textarea><br /><div style="text-align:center"><input type="Submit" id="Valider_Appreciation'.$cle.'" name="Valider_Appreciation" value="'.$Langue['BTN_ENREGISTRER'].'"></div></form></td></tr></table>';
		  echo '</td></tr>';
		  echo '</table>';
		  echo '<script language="Javascript">';
		  echo '$(document).ready(function()';
		  echo '{';
		  echo '$("#Valider_Appreciation'.$cle.'").button();';
?>
		  $("#form_<?php echo $cle; ?>").submit(function(event)
		  {
			var bValid = true;
			var results = $(this).serialize();
			if (!checkValue($("#appreciation<?php echo $cle; ?>"))) { bValid=false; }
			event.preventDefault();
			if ( bValid )
			{
			  Message_Chargement(2,1);
			  var $form = $( this );
			  url = $form.attr( 'action' );
			  data = $form.serialize();
			  var request = $.ajax({type: "POST", url: url, data: data});
			  request.done(function(msg)
			  {
			    decoupe=msg.split("-");
			    Charge_Dialog("index2.php?module=livrets&action=detailview_ls&id_eleve="+decoupe[0]+"&annee_scolaire_ls="+decoupe[1]);
			  });
			}
			else
			{
  		      $("#msg_ok<?php echo $cle; ?>").fadeIn( 1000 );
  			  $("#msg_ok<?php echo $cle; ?>").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div>');
    		  setTimeout(function()
			  {
				$("#msg_ok<?php echo $cle; ?>").effect("blind",1000);
			  }, 3000 );
			}
		  });
<?php
		  echo '});';
		  echo '</script>';
	}
	else
    // Appréciation et signature par l'enseignant
	{
	  // On regarde si c'est un élève qui est connecté car, dans ce cas, on ne montre les appréciations que si l'enseignant et le directeur ont signé
		  $req_appreciation=mysql_query("SELECT * FROM `livrets_appreciation` WHERE id_eleve='".$id_eleve."' AND trimestre='$cle' AND annee='".$annee_scolaire_ls."' AND id_util='".$id_prof."' AND type_util='P'");
		  if (mysql_num_rows($req_appreciation)!="")
		  {
		    echo '<div class="ui-widget ui-widget-content ui-corner-all ui-div-separation-form"><p class="explic"><strong>'.$Langue['LBL_APPRECIATION_ENSEIGNANT'].'</strong></p>'; 
			echo '<p class=explic>'.mysql_result($req_appreciation,0,'appreciation').'</p>';
		    echo '</div>';

	    // On vérifie alors que le directeur a mis une appréciation et l'a signée
			  if ($_SESSION['type_util']=="D")
			  {
				  echo '<div class="ui-widget ui-widget-content ui-corner-all ui-div-separation-form"><p class="explic"><strong>'.$Langue['LBL_APPRECIATION_DIRECTEUR'].'</strong></p>'; 
				  echo '<table cellspacing=0 cellpadding=0 border=0 style="width:100%"><tr><td width=50% valign=top class="textgauche">';
				  echo '<form id="form_'.$cle.'" name="form_'.$cle.'" action=index2.php method=POST>';
				  echo '<div id="msg_ok'.$cle.'"></div>';
				  echo '<input type="hidden" id="module" name="module" value="livrets">';
				  echo '<input type="hidden" id="action" name="action" value="save_appreciation">';
				  echo '<input type="hidden" id="trimestre" name="trimestre" value="'.$cle.'">';
				  echo '<input type="hidden" id="id_util" name="id_util" value="'.$id_prof.'">';
				  echo '<input type="hidden" id="id_eleve" name="id_eleve" value="'.$id_eleve.'">';
				  echo '<input type="hidden" id="type_util" name="type_util" value="D">';
				  echo '<input type="hidden" id="annee_scolaire_ls" name="annee_scolaire_ls" value="'.$annee_scolaire_ls.'">';
				  echo '<table cellspacing=0 cellpadding=0 class="tableau_editview2" style="padding-top:0px;margin-top:0px"><tr>';
				  echo '<td class="droite" valign=top nowrap>'.$Langue['LBL_APPRECIATION_COMMENTAIRES'].' :&nbsp;</td>';
				  $req_appreciation=mysql_query("SELECT * FROM `livrets_appreciation` WHERE id_eleve='".$id_eleve."' AND trimestre='$cle' AND annee='".$annee_scolaire_ls."' AND id_util='".$id_prof."' AND type_util='D'");
				  if (mysql_num_rows($req_appreciation)=="") { $id="";$commentaire=""; } else { $commentaire=str_replace("<br>","\r\n",mysql_result($req_appreciation,0,'appreciation'));$id=mysql_result($req_appreciation,0,'id'); }
				  echo '<td class="gauche" valign=top><input type="hidden" id="id" name="id" value="'.$id.'"><textarea name="appreciation" id="appreciation'.$cle.'" rows=12 cols=55 class="text ui-widget-content ui-corner-all">'.$commentaire.'</textarea><br /><div style="text-align:center"><input type="Submit" id="Valider_Appreciation'.$cle.'" name="Valider_Appreciation" value="'.$Langue['BTN_ENREGISTRER'].'"></div></form></td></tr></table>';
				  echo '</td></tr>';
				  echo '</table>';
				  echo '<script language="Javascript">';
				  echo '$(document).ready(function()';
				  echo '{';
				  echo '$("#Valider_Appreciation'.$cle.'").button();';
?>
				  $("#form_<?php echo $cle; ?>").submit(function(event)
				  {
					var bValid = true;
					var results = $(this).serialize();
					if (!checkValue($("#appreciation<?php echo $cle; ?>"))) { bValid=false; }
					event.preventDefault();
					if ( bValid )
					{
					  Message_Chargement(2,1);
					  var $form = $( this );
					  url = $form.attr( 'action' );
					  data = $form.serialize();
					  var request = $.ajax({type: "POST", url: url, data: data});
					  request.done(function(msg)
					  {
						decoupe=msg.split("-");
						Charge_Dialog("index2.php?module=livrets&action=detailview_ls&id_eleve="+decoupe[0]+"&annee_scolaire_ls="+decoupe[1]);
					  });
					}
					else
					{
					  $("#msg_ok<?php echo $cle; ?>").fadeIn( 1000 );
					  $("#msg_ok<?php echo $cle; ?>").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div>');
					  setTimeout(function()
					  {
						$("#msg_ok<?php echo $cle; ?>").effect("blind",1000);
					  }, 3000 );
					}
				  });
	<?php
				  echo '});';
				  echo '</script>';
				  echo '</div>';
			  }
			  else
			  {
				  $req_appreciation=mysql_query("SELECT * FROM `livrets_appreciation` WHERE id_eleve='".$id_eleve."' AND trimestre='$cle' AND annee='".$annee_scolaire_ls."' AND id_util='".$id_prof."' AND type_util='D'");
				  if (mysql_num_rows($req_appreciation)!="")
				  {
				    echo '<div class="ui-widget ui-widget-content ui-corner-all ui-div-separation-form"><p class="explic"><strong>'.$Langue['LBL_APPRECIATION_DIRECTEUR'].'</strong></p>'; 
					echo '<p class=explic>'.mysql_result($req_appreciation,0,'appreciation').'</p>';
				    echo '</div>';
				  }
			  }
		  }
		}
	echo '</div>';
  }  
}
?>
