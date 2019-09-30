<?php
// Récupération des informations
  foreach ($tableau_variable['classes'] AS $cle)
  {
    $tableau_variable['classes'][$cle['nom']]['value'] = "";
  }

  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `classes` WHERE id = '" . $_GET['id'] . "'");
    foreach ($tableau_variable['classes'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['classes'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
    // Recherche du titulaire de la classe
		$req_cahier=mysql_query("SELECT * FROM `cahierjournal` WHERE id_classe='".$_GET['id']."'");
		$req_livret=mysql_query("SELECT * FROM `controles` WHERE id_classe='".$_GET['id']."'");
		if (mysql_num_rows($req_cahier)=="" && mysql_num_rows($req_livret)=="")
		{
      $req2 = mysql_query("SELECT * FROM `classes_profs` WHERE id_classe = '" . $_GET['id'] . "' AND type='T'");
      $liste_titulaire=Liste_Profs("id_titulaire",'form','',mysql_result($req2,0,'id_prof'),'R',false,true,'','4');
		}
		else
		{
      $req2 = mysql_query("SELECT * FROM `classes_profs` WHERE id_classe = '" . $_GET['id'] . "' AND type='T'");
      $liste_titulaire='<label class="label_detail">'.Liste_Profs("id_titulaire",'value','',mysql_result($req2,0,'id_prof'),'R',false,true,'','4').'<input type="hidden" name="id_titulaire[]" id="id_titulaire" value="'.mysql_result($req2,0,'id_prof').'"></label>';
		}
	  $titulaire_id=mysql_result($req2,0,'id_prof');
    // Recherche des décharges de la classe
    $req2 = mysql_query("SELECT * FROM `classes_profs` WHERE id_classe = '" . $_GET['id'] . "' AND type='E'");
    $liste_profs="";
    for ($i=1;$i<=mysql_num_rows($req2);$i++)
    {
      $liste_profs=$liste_profs.mysql_result($req2,$i-1,'id_prof')."|";
    }
    $liste_decharge=Liste_Profs("id_decharge",'form','',substr($liste_profs,0,strlen($liste_profs)-1),'R',true,false,$titulaire_id,'5');
    // Recherche des décloisonnements de la classe
    $req2 = mysql_query("SELECT * FROM `classes_profs` WHERE id_classe = '" . $_GET['id'] . "' AND type='D'");
    $liste_profs="";
    for ($i=1;$i<=mysql_num_rows($req2);$i++)
    {
      $liste_profs=$liste_profs.mysql_result($req2,$i-1,'id_prof')."|";
    }
    $liste_decloisonnement=Liste_Profs("id_decloisonnement",'form','',substr($liste_profs,0,strlen($liste_profs)-1),'R',true,false,$titulaire_id,'6');
    // Recherche des ATSEM de la classe
    $req2 = mysql_query("SELECT * FROM `classes_profs` WHERE id_classe = '" . $_GET['id'] . "' AND type='S'");
    $liste_profs="";
    for ($i=1;$i<=mysql_num_rows($req2);$i++)
    {
      $liste_profs=$liste_profs.mysql_result($req2,$i-1,'id_prof')."|";
    }
    $liste_atsem=Liste_Profs("id_atsem",'form','',substr($liste_profs,0,strlen($liste_profs)-1),'S',true,false,'','7');
    // Recherche des intervenants extérieurs de la classe
    $req2 = mysql_query("SELECT * FROM `classes_profs` WHERE id_classe = '" . $_GET['id'] . "' AND type='I'");
    $liste_profs="";
    for ($i=1;$i<=mysql_num_rows($req2);$i++)
    {
      $liste_profs=$liste_profs.mysql_result($req2,$i-1,'id_prof')."|";
    }
    $liste_intervenant=Liste_Profs("id_intervenant",'form','',substr($liste_profs,0,strlen($liste_profs)-1),'I',true,false,'','8');
    // Recherche des autres intervenants de la classe
    $req2 = mysql_query("SELECT * FROM `classes_profs` WHERE id_classe = '" . $_GET['id'] . "' AND type='U'");
    $liste_profs="";
    for ($i=1;$i<=mysql_num_rows($req2);$i++)
    {
      $liste_profs=$liste_profs.mysql_result($req2,$i-1,'id_prof')."|";
    }
    $liste_autre=Liste_Profs("id_autre",'form','',substr($liste_profs,0,strlen($liste_profs)-1),'U',true,false,'','9');
    // Année
    $liste_annee=Liste_Annee("annee",'form',mysql_result($req,0,'annee'),'3');
    // Niveaux
    $msg="";
    $req=mysql_query("SELECT listes.*, classes_niveaux.* FROM `listes`,`classes_niveaux` WHERE classes_niveaux.id_classe='" . $_GET['id'] . "' AND classes_niveaux.id_niveau=listes.id ORDER BY ordre ASC");
    for ($i=1;$i<=mysql_num_rows($req);$i++)
    {
      $msg=$msg.mysql_result($req,$i-1,'listes.id').'|';
    }
    $msg=substr($msg,0,strlen($msg)-1);
    $liste_niveaux=Liste_Niveaux("id_niveau",'form',$msg,'',true,'2');
  }
  else
  {
    $liste_titulaire=Liste_Profs("id_titulaire",'form','','','R',false,true,'','4');
    $liste_decharge=Liste_Profs("id_decharge",'form','','','R',true,false,'','5');
    $liste_decloisonnement=Liste_Profs("id_decloisonnement",'form','','','R',true,false,'','6');
    $liste_atsem=Liste_Profs("id_atsem",'form','','','S',true,false,'','7');
    $liste_intervenant=Liste_Profs("id_intervenant",'form','','','I',true,false,'','8');
    $liste_autre=Liste_Profs("id_autre",'form','','','U',true,false,'','9');
    $liste_annee=Liste_Annee("annee",'form',$_SESSION['annee_scolaire'],'3');
    $liste_niveaux=Liste_Niveaux("id_niveau",'form','','',true,'2');
  }

  $tpl = new template("classes");
  $tpl->set_file("gform","editview.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }
  
  foreach ($tableau_variable['classes'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }
  $tpl->set_var("TITULAIRE",$liste_titulaire);
  $tpl->set_var("DECHARGE",$liste_decharge);
  $tpl->set_var("DECLOISONNEMENT",$liste_decloisonnement);
  $tpl->set_var("ATSEM",$liste_atsem);
  $tpl->set_var("INTERVENANT",$liste_intervenant);
  $tpl->set_var("AUTRE",$liste_autre);
  $tpl->set_var("ANNEE",$liste_annee);
  $tpl->set_var("NIVEAUX",$liste_niveaux);

  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer").button();
  $("#EnregistrerFermer").button();
  $("#EnregistrerNouveau").button();
  $("#Annuler").button();
  $("#Enregistrer2").button();
  $("#EnregistrerFermer2").button();
  $("#EnregistrerNouveau2").button();
  $("#Annuler2").button();
  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });

  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer").click(function()
  {
    action_save="detail";
  });
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
		var results = $(this).serialize();
    if (!checkValue($("#nom_classe"))) { bValid=false; }
    if (results.indexOf("id_niveau",0)==-1)
    {
      $("#id_niveau").addClass( "ui-state-error" );
      bValid=false;
    }
    event.preventDefault();
    if ( bValid )
    {
      updateTips("save","classes","<?php echo $Langue['LBL_FICHE_CLASSE']; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#id").val(msg);
        if (action_save=="nouveau")
        {
          updateTips("success","classes","<?php echo $Langue['BTN_CREER_CLASSE']; ?>");
        }
        else
        {
          updateTips("success","classes","<?php echo $Langue['LBL_FICHE_CLASSE']; ?>");
        }
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","classes","<?php echo $Langue['LBL_FICHE_CLASSE']; ?>");
      action_save="rien";
    }
  });

  $("#Enregistrer2").click(function()
  {
    action_save="detail";
    $("#form_editview").submit();
  });

  $("#EnregistrerNouveau").click(function()
  {
    action_save="nouveau";
    $("#form_editview").submit();
  });

  $("#EnregistrerFermer").click(function()
  {
    action_save="fermer";
    $("#form_editview").submit();
  });

  $("#EnregistrerNouveau2").click(function()
  {
    action_save="nouveau";
    $("#form_editview").submit();
  });

  $("#EnregistrerFermer2").click(function()
  {
    action_save="fermer";
    $("#form_editview").submit();
  });

  $("#id_titulaire").change(function()
  {
    id_titulaire=$("#id_titulaire").val();
    $("#liste_decharge").html('<img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MISE_A_JOUR']; ?>');
    $("#liste_decloisonnement").html('<img src="images/loading.gif" width=16 height=16 border=0> <?php echo $Langue['MSG_MISE_A_JOUR']; ?>');
<?php if (isset($_GET['id'])) { ?>
    var request = $.ajax({type: "POST", url: "index2.php", data: "module=classes&action=change_decharge&id_titulaire="+id_titulaire+"&id_classe=<?php echo $_GET['id']; ?>" });
    var request2 = $.ajax({type: "POST", url: "index2.php", data: "module=classes&action=change_decloisonnement&id_titulaire="+id_titulaire+"&id_classe=<?php echo $_GET['id']; ?>" });
<?php } else { ?>
    var request = $.ajax({type: "POST", url: "index2.php", data: "module=classes&action=change_decharge&id_titulaire="+id_titulaire+"&id_classe=" });
    var request2 = $.ajax({type: "POST", url: "index2.php", data: "module=classes&action=change_decloisonnement&id_titulaire="+id_titulaire+"&id_classe=" });
<?php } ?>
    request.done(function(msg)
    {
        $("#liste_decharge").html(msg);
    });
    request2.done(function(msg)
    {
        $("#liste_decloisonnement").html(msg);
    });
  });
  
<?php if (!isset($_GET['id'])) { ?>
  $("#id_titulaire").change();
<?php } ?>
});
</script>
