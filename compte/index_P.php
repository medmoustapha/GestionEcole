<?php
// Récupération des informations
  foreach ($tableau_variable['personnels'] AS $cle)
  {
    $tableau_variable['personnels'][$cle['nom']]['value'] = "";
  }

  $req = mysql_query("SELECT * FROM `profs` WHERE id = '" . $_SESSION['id_util'] . "'");
  foreach ($tableau_variable['personnels'] AS $cle)
  {
    if ($cle['nom']!="passe2")
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['personnels'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
	}
  }
  $tableau_variable['personnels']['passe2']['value'] = "";

  $tpl = new template("compte");
  $tpl->set_file("gform","editview_P.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }
  
  foreach ($tableau_variable['personnels'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }

  if (file_exists("cache/photos/".$_SESSION['id_util'].".jpg"))
  {
    $tpl->set_var("PHOTO","cache/photos/".$_SESSION['id_util'].".jpg?".time());
  }
  else
  {
    if (mysql_result($req,0,'civilite')=="" || mysql_result($req,0,'civilite')=="1")
	{
	  $tpl->set_var("PHOTO","images/homme.png");
	}
    else
	{
	  $tpl->set_var("PHOTO","images/femme.png");
	}
  }
  
  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>
<script language="Javascript">
$(document).ready(function()
{
  $('#image_personne').click(function()
  {
    Charge_Dialog3("index2.php?module=compte&action=saisir_photo1&type=P&id_personne=<?php echo $_SESSION['id_util']; ?>","<?php echo $Langue['LBL_PHOTO_TITRE']; ?>");
  });
  
  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();		
    window.open("http://www.doxconception.com/site/index.php/prof-votre-compte.html","Aide");
  });

  /* Initialisation de la page, notamment des boutons */
  $("#Enregistrer").button();
  $("#Annuler").button();
  $("#Enregistrer2").button();
  $("#Annuler2").button();
  $("#Annuler").click(function()
  {
	    Message_Chargement(1,1);
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
  });
  $("#Annuler2").click(function()
  {
	    Message_Chargement(1,1);
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
  });

  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer").click(function()
  {
    action_save="edit";
  });
  
  $("#form_editview").submit(function(event)
  {
	var bValid = true;
    if (!checkValue($("#civilite"))) { bValid=false; }
    if (!checkValue($("#nom"))) { bValid=false; }
    if (!checkValue($("#prenom"))) { bValid=false; }
    if ($("#email").val()!="")
    {
      if (!checkRegexp($("#email"),/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i)) { bValid=false; }
    }
	if ($("#passe").val()!="" || $("#passe2").val()!="")
	{
	  if (!checkValue($("#passe"))) { bValid=false; }
	  if (!checkValue($("#passe2"))) { bValid=false; }
	  if ($("#passe").val()!=$("#passe2").val()) 
	  { 
        $("#passe").addClass( "ui-state-error" );
        $("#passe2").addClass( "ui-state-error" );
	    bValid=false; 
	  }
	}  
    event.preventDefault();
    if ( bValid )
    {
      updateTips("save","compte","<?php echo $Langue['LBL_FICHE_PERSONNEL']; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function()
      {
				Message_Chargement(2,0);
				document.location.href="#haut_page";
				$("#msg_ok_ed").fadeIn( 1000 );
				$("#msg_ok_ed").html('<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 10px;text-align:center;"><strong><?php echo $Langue['MSG_DONNEES_SAUVEGARDEES']; ?></strong></div></div>');
				setTimeout(function()
        {
            $("#msg_ok_ed").effect("blind",1000);
        }, 3000 );
      });
    }
    else
    {
			$("#msg_ok_ed").fadeIn( 1000 );
			$("#msg_ok_ed").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 10px;"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div>');
			setTimeout(function()
			{
				$("#msg_ok_ed").effect("blind",1000);
			}, 3000 );
      action_save="rien";
    }
  });
  
  $("#Enregistrer2").click(function()
  {
    action_save="edit";
    $("#form_editview").submit();
  });
});
</script>
