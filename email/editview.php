<?php
// Récupération des informations
  foreach ($tableau_variable['email'] AS $cle)
  {
    $tableau_variable['email'][$cle['nom']]['value'] = "";
  }
  
  $destinataire="";
  $titre="";
  $messagerie="";
  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `email` WHERE id = '" . $_GET['id'] . "'");
		$destinataire=mysql_result($req,0,'type_expediteur').mysql_result($req,0,'id_expediteur');
		$tableau_variable['email']['titre']['value']="Re : ".mysql_result($req,0,'titre');
//	$tableau_variable['email']['messagerie']['value']='> '.str_replace("<br />","\n> ",mysql_result($req,0,'messagerie'));
  }

  $tpl = new template("email");
  $tpl->set_file("gform","editview.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  foreach ($tableau_variable['email'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }
  $tpl->set_var("DATE", date($Format_Date_PHP));
  $tpl->set_var("EXPEDITEUR", $_SESSION['nom_util']);
  
  $msg='<select name="id_destinataire[]" id="id_destinataire" multiple="multiple" size="10" style="width:500px">';
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
    $annee=date("Y");
  }
  else
  {
    if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee=date("Y")-1; } else { $annee=date("Y"); }
  }
  if ($_SESSION['type_util']!="E")
  {
    $msg .='<optgroup label="'.$Langue['LBL_ENSEIGNANTS'].'">'; 
    $req=mysql_query("SELECT * FROM `profs` WHERE (date_sortie='0000-00-00' OR date_sortie>='".date("Y-m-d")."') AND (type='D' OR type='P') ORDER BY nom ASC, prenom ASC");
    for ($i=1;$i<=mysql_num_rows($req);$i++)
    {
      $msg=$msg.'<option value="'.mysql_result($req,$i-1,'type').mysql_result($req,$i-1,'id').'"';
			if (mysql_result($req,$i-1,'type').mysql_result($req,$i-1,'id')==$destinataire) { $msg .=' SELECTED'; }
      $msg=$msg.'>'.mysql_result($req,$i-1,'profs.nom').' '.mysql_result($req,$i-1,'profs.prenom').'</option>';
    }
		$msg .='</optgroup>';
    $req=mysql_query("SELECT * FROM `classes` WHERE annee='".$annee."' ORDER BY nom_classe ASC");
    for ($i=1;$i<=mysql_num_rows($req);$i++)
    {
	  $msg .='<optgroup label="'.mysql_result($req,$i-1,'nom_classe').'">';
	  $id_classe=mysql_result($req,$i-1,'id');
      $req2=mysql_query("SELECT eleves.*,eleves_classes.* FROM `eleves`, `eleves_classes` WHERE eleves.id=eleves_classes.id_eleve AND eleves_classes.id_classe='".$id_classe."' AND (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>='".date("Y-m-d")."') ORDER BY eleves.nom ASC, eleves.prenom ASC");
	  for ($j=1;$j<=mysql_num_rows($req2);$j++)
	  {
        $msg=$msg.'<option value="E'.mysql_result($req2,$j-1,'eleves.id').'"';
  	    if ("E".mysql_result($req2,$j-1,'id')==$destinataire) { $msg .=' SELECTED'; }
        $msg=$msg.'>'.mysql_result($req2,$j-1,'eleves.nom').' '.mysql_result($req2,$j-1,'eleves.prenom').'</option>';
	  }
  	  $msg .='</optgroup>';
    }  
    $msg .='</select><br /><label class="petit">'.$Langue['MSG_SELECTION_CLASSE'].'</label>';
  }  
  else
  {
		$req=mysql_query("SELECT profs.* FROM `profs` WHERE (profs.date_sortie='0000-00-00' OR profs.date_sortie>='".date("Y-m-d")."') AND profs.type='D' ORDER BY nom ASC, prenom ASC");
    $msg .='<optgroup label="'.$Langue['LBL_DIRECTION'].'">'; 
    $msg=$msg.'<option value="D'.mysql_result($req,0,'profs.id').'"';
    if ("D".mysql_result($req,0,'profs.id')==$destinataire) { $msg .=' SELECTED'; }
    $msg=$msg.'>'.mysql_result($req,0,'profs.nom').' '.mysql_result($req,0,'profs.prenom').'</option>';
    $msg .='</optgroup>';
		$req=mysql_query("SELECT profs.*, classes_profs.* FROM `profs`, `classes_profs` WHERE (profs.date_sortie='0000-00-00' OR profs.date_sortie>='".date("Y-m-d")."') AND profs.type='P' AND classes_profs.id_prof=profs.id AND classes_profs.id_classe='".$_SESSION['id_classe_cours']."' ORDER BY profs.nom ASC, profs.prenom ASC");
		if (mysql_num_rows($req)!="")
		{
			$msg .='<optgroup label="'.$Langue['LBL_ENSEIGNANTS'].'">'; 
			for ($j=1;$j<=mysql_num_rows($req);$j++)
			{
				$msg=$msg.'<option value="'.mysql_result($req,$j-1,'profs.type').mysql_result($req,$j-1,'profs.id').'"';
				if (mysql_result($req,$j-1,'profs.type').mysql_result($req,$j-1,'profs.id')==$destinataire) { $msg .=' SELECTED'; }
				$msg=$msg.'>'.mysql_result($req,$j-1,'profs.nom').' '.mysql_result($req,$j-1,'profs.prenom').'</option>';
			}
			$msg .='</optgroup>';
		}
    $msg .='</select>';
  }
  $tpl->set_var("DESTINATAIRE",$msg);

  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
	$("#ajout_pj").button();
  $("#Envoyer").button();
  $("#Annuler").button();
  $("#Envoyer2").button();
  $("#Annuler2").button();
  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
	$("#dialog-form").bind( "dialogclose",function()
	{
		parent.calcul.location.href='email/supprimer_pj.php?id='+$("#pj_id").val();
	});
	
	$("#ajout_pj").click(function(event)
	{
	  event.preventDefault();
	  Charge_Dialog2("index2.php?module=email&action=joindre_pj","<?php echo $Langue['LBL_AJOUTER_PJ']; ?>");
	});
	
  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Envoyer").click(function()
  {
    action_save="detail";
  });
  $("#form_editview").submit(function(event)
  {
	var bValid = true;
	var results = $(this).serialize();
    if (!checkValue($("#titre"))) { bValid=false; }
	ed = tinyMCE.get('messagerie');
	if (ed.getContent()=='')
	{
	  bValid=false;
	}
	else
	{
	  $('#messagerie_e').val(ed.getContent());
	}
    if (results.indexOf("id_destinataire",0)==-1)
    {
	  bValid=false;
	  $("#id_destinataire").addClass( "ui-state-error" );
    }
    event.preventDefault();
    if ( bValid )
    {
      Message_Chargement(10,1);
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#id").val(msg);
        updateTips("success","email","<?php echo $Langue['LBL_MESSAGE']; ?>");
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","email","<?php echo $Langue['LBL_MESSAGE']; ?>");
      action_save="rien";
    }
  });
	
  $("#id_destinataire").multiselect(
  {
    header:true,
    selectAll: true,
    noneSelectedText: "<?php echo $Langue['MSG_SELECT_DESTINATAIRES']; ?>",
    selectedText: "# <?php echo $Langue['MSG_SELECTED_DESTINATAIRES']; ?>",
    checkAllText: "<?php echo $Langue['MSG_COCHER_TOUT']; ?>",
		uncheckAllText: "<?php echo $Langue['MSG_DECOCHER_TOUT']; ?>"
  });

  $("#Envoyer2").click(function()
  {
    action_save="detail";
    $("#form_editview").submit();
  });
});
</script>
