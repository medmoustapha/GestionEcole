<?php
// Récupération des informations
  foreach ($tableau_variable['taches'] AS $cle)
  {
    $tableau_variable['taches'][$cle['nom']]['value'] = "";
  }

  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `taches` WHERE id = '" . $_GET['id'] . "'");
    foreach ($tableau_variable['taches'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['taches'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
  }
  
  $tpl = new template("accueil");
  $tpl->set_file("gform","editview_tache.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  foreach ($tableau_variable['taches'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }
  $tpl->set_var("ID_UTIL",$_SESSION['id_util']);
  $tpl->set_var("TYPE_UTIL",$_SESSION['id_util']);
  
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
  $("#aide_fenetre").button();
  $("#aide_fenetre").click(function(event)
  {
		event.preventDefault();
<?php
  switch ($_SESSION['type_util'])
	{
	  case "D":
?>
			window.open("http://www.doxconception.com/site/index.php/directeur-page-daccueil/article/15-cas-particulier-du-panneau-taches.html","Aide");
<?php
			break;
		case "P":
?>
			window.open("http://www.doxconception.com/site/index.php/prof-page-daccueil/article/15-cas-particulier-du-panneau-taches.html","Aide");
<?php
			break;
		case "E":
?>
			window.open("http://www.doxconception.com/site/index.php/parent-page-daccueil/article/15-cas-particulier-du-panneau-taches.html","Aide");
<?php
			break;
		}
?>
  });

  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer").click(function()
  {
    action_save="editview_tache";
  });
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
    if (!checkValue($("#titre"))) { bValid=false; }
    event.preventDefault();
    if ( bValid )
    {
      updateTips("save","accueil","<?php echo $Langue['LBL_MODIFIER_TACHE']; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#id").val(msg);
        if (action_save=="nouveau")
        {
          updateTips("success","accueil","<?php echo $Langue['LBL_CREER_TACHE']; ?>");
        }
        else
        {
          updateTips("success","accueil","<?php echo $Langue['LBL_MODIFIER_TACHE']; ?>");
        }
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","accueil","<?php echo $Langue['LBL_MODIFIER_TACHE']; ?>");
      action_save="rien";
    }
  });
  
  $("#Enregistrer2").click(function()
  {
    action_save="editview_tache";
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
});
</script>
