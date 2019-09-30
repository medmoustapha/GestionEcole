<?php
// Récupération des informations
  foreach ($tableau_variable['news'] AS $cle)
  {
    $tableau_variable['news'][$cle['nom']]['value'] = "";
  }

  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `accueil_news` WHERE id = '" . $_GET['id'] . "'");
    foreach ($tableau_variable['news'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['news'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
  }
  
  $tpl = new template("accueil");
  $tpl->set_file("gform","editview_".$_SESSION['type_util'].".html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  foreach ($tableau_variable['news'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }

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
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-page-daccueil/article/78-cas-particulier-du-panneau-news.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-page-daccueil/article/78-cas-particulier-du-panneau-news.html","Aide");
<?php } ?>
  });

  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer").click(function()
  {
    action_save="edit";
  });
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
    if (!checkValue($("#titre"))) { bValid=false; }
    if (!checkRegexp($("#date"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
		ed = tinyMCE.get('contenu');
		if (ed.getContent()=='')
		{
			bValid=false;
		}
		else
		{
			$('#contenu_e').val(ed.getContent());
		}
    event.preventDefault();
    if ( bValid )
    {
      updateTips("save","accueil","<?php echo $Langue['LBL_MODIFIER_NEWS']; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#id").val(msg);
        if (action_save=="nouveau")
        {
          updateTips("success","accueil","<?php echo $Langue['LBL_CREER_NEWS']; ?>");
        }
        else
        {
          updateTips("success","accueil","<?php echo $Langue['LBL_MODIFIER_NEWS']; ?>");
        }
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","accueil","<?php echo $Langue['LBL_MODIFIER_NEWS']; ?>");
      action_save="rien";
    }
  });
  
  $("#Enregistrer2").click(function()
  {
    action_save="edit";
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
