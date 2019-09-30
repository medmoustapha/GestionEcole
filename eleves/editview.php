<?php
// Récupération des informations
  foreach ($tableau_variable['eleves'] AS $cle)
  {
    $tableau_variable['eleves'][$cle['nom']]['value'] = "";
  }

  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `eleves` WHERE id = '" . $_GET['id'] . "'");
    foreach ($tableau_variable['eleves'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['eleves'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
	$derniere_connexion=mysql_result($req,0,'derniere_connexion');
  }
  else
  {
    $tableau_variable['eleves']['date_entree']['value'] = date("Y-m-d");
	$derniere_connexion="0000-00-00";
  }

  $tpl = new template("eleves");
  $tpl->set_file("gform","editview_".$_SESSION['type_util'].".html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  foreach ($tableau_variable['eleves'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }
  $tpl->set_var("DERNIERE_CONNEXION", $derniere_connexion);

  $debut=1;
	$tabindex=33;
  if (isset($_GET['id']))
  {
	  $req2=mysql_query("SELECT * FROM `contacts_eleves` WHERE id_eleve='".$_GET['id']."' ORDER BY nom ASC");
	  for ($i=1;$i<=mysql_num_rows($req2);$i++)
	  {
			$tpl->set_var('CONTACT_NOM_'.$i,'<input tabindex='.$tabindex.' type="text" class="text ui-widget-content ui-corner-all" id="contact_nom'.$i.'" name="contact_nom'.$i.'" value="'.mysql_result($req2,$i-1,'nom').'" size=25 maxlength=255>');
			$tabindex++;
			$tpl->set_var('CONTACT_LIEN_'.$i,'<input tabindex='.$tabindex.' type="text" class="text ui-widget-content ui-corner-all" id="contact_lien'.$i.'" name="contact_lien'.$i.'" value="'.mysql_result($req2,$i-1,'lien').'" size=20 maxlength=255>');
			$tabindex++;
			$tpl->set_var('CONTACT_ADRESSE_'.$i,'<input tabindex='.$tabindex.' type="text" class="text ui-widget-content ui-corner-all" id="contact_adresse'.$i.'" name="contact_adresse'.$i.'" value="'.mysql_result($req2,$i-1,'adresse').'" size=40 maxlength=255>');
			$tabindex++;
			$tpl->set_var('CONTACT_TEL_'.$i,'<input tabindex='.$tabindex.' type="text" class="text ui-widget-content ui-corner-all" id="contact_tel'.$i.'" name="contact_tel'.$i.'" value="'.mysql_result($req2,$i-1,'tel').'" size=10 maxlength=255>');
			$tabindex++;
			$tpl->set_var('CONTACT_TEL2_'.$i,'<input tabindex='.$tabindex.' type="text" class="text ui-widget-content ui-corner-all" id="contact_tel2'.$i.'" name="contact_tel2'.$i.'" value="'.mysql_result($req2,$i-1,'tel2').'" size=10 maxlength=255>');
			$tabindex++;
			$tpl->set_var('CONTACT_PORTABLE_'.$i,'<input tabindex='.$tabindex.' type="text" class="text ui-widget-content ui-corner-all" id="contact_portable'.$i.'" name="contact_portable'.$i.'" value="'.mysql_result($req2,$i-1,'portable').'" size=10 maxlength=255>');
			$tabindex++;
	  }
	  $debut=mysql_num_rows($req2)+1;
  }
  for ($i=$debut;$i<=5;$i++)
  {
    $tpl->set_var('CONTACT_NOM_'.$i,'<input tabindex='.$tabindex.' type="text" class="text ui-widget-content ui-corner-all" id="contact_nom'.$i.'" name="contact_nom'.$i.'" value="" size=25 maxlength=255>');
		$tabindex++;
    $tpl->set_var('CONTACT_LIEN_'.$i,'<input tabindex='.$tabindex.' type="text" class="text ui-widget-content ui-corner-all" id="contact_lien'.$i.'" name="contact_lien'.$i.'" value="" size=20 maxlength=255>');
		$tabindex++;
    $tpl->set_var('CONTACT_ADRESSE_'.$i,'<input tabindex='.$tabindex.' type="text" class="text ui-widget-content ui-corner-all" id="contact_adresse'.$i.'" name="contact_adresse'.$i.'" value="" size=40 maxlength=255>');
		$tabindex++;
    $tpl->set_var('CONTACT_TEL_'.$i,'<input tabindex='.$tabindex.' type="text" class="text ui-widget-content ui-corner-all" id="contact_tel'.$i.'" name="contact_tel'.$i.'" value="" size=10 maxlength=255>');
		$tabindex++;
    $tpl->set_var('CONTACT_TEL2_'.$i,'<input tabindex='.$tabindex.' type="text" class="text ui-widget-content ui-corner-all" id="contact_tel2'.$i.'" name="contact_tel2'.$i.'" value="" size=10 maxlength=255>');
		$tabindex++;
    $tpl->set_var('CONTACT_PORTABLE_'.$i,'<input tabindex='.$tabindex.' type="text" class="text ui-widget-content ui-corner-all" id="contact_portable'.$i.'" name="contact_portable'.$i.'" value="" size=10 maxlength=255>');
		$tabindex++;
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
  $("#Annuler").button();
  $("#Enregistrer2").button();
  $("#EnregistrerFermer2").button();
  $("#Annuler2").button();
  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });

  /* On vérifie que l'identifiant fourni est déjà attribué ou non */
  $("#identifiant").blur(function()
  {
    if ($("#identifiant").val()!="")
    {
      $("#Enregistrer").button({disabled:true});
      $("#Enregistrer2").button({disabled:true});
      $("#EnregistrerFermer").button({disabled:true});
      $("#EnregistrerFermer2").button({disabled:true});
	  <?php if ($_SESSION['type_util']=="D") { ?>
      $("#EnregistrerNouveau").button({disabled:true});
      $("#EnregistrerNouveau2").button({disabled:true});
	  <?php } ?>
      $("#verif_id").html('<font style="color:#000000"><?php echo $Langue['MSG_VERIFICATION_ID']; ?></font>');
      url="index2.php";
      data="module=users&action=verif_id&identifiant="+$("#identifiant").val()+"&id="+$("#id").val();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        switch (msg)
        {
          case "1":
            $("#verif_id").html('<font style="color:#008000"><?php echo $Langue['MSG_ID_LIBRE']; ?></font>');
            $("#Enregistrer").button({disabled:false});
            $("#Enregistrer2").button({disabled:false});
            $("#EnregistrerFermer").button({disabled:false});
            $("#EnregistrerFermer2").button({disabled:false});
            <?php if ($_SESSION['type_util']=="D") { ?>
            $("#EnregistrerNouveau").button({disabled:false});
            $("#EnregistrerNouveau2").button({disabled:false});
			<?php } ?>
            break;
          case "2":
            $("#verif_id").html('<font style="color:#FF0000"><?php echo $Langue['MSG_ID_ATTRIBUE']; ?></font>');
            break;
        }
      });
    }
  });

  /* On vérifie que le formulaire est correctement complété. Si oui, on le sauvegarde */
  $("#Enregistrer").click(function()
  {
    action_save="detail";
  });
  
  $("#form_editview").submit(function(event)
  {
		var bValid = true;
    if (!checkValue($("#sexe"))) { bValid=false; }
    if (!checkValue($("#nom"))) { bValid=false; }
    if (!checkValue($("#prenom"))) { bValid=false; }
    if (!checkRegexp($("#date_naissance"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
    if (!checkRegexp($("#date_entree"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
    if ($("#date_sortie").val()!="")
    {
      if (!checkRegexp($("#date_sortie"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
      else
      {
        if (checkRegexp($("#date_entree"),<?php echo $date_regexp[$Format_Date_PHP]; ?>))
        {
          if (Compare_Date($("#date_entree").val(),$("#date_sortie").val(),'<?php echo $Format_Date_PHP; ?>')<0)
          {
            $("#date_entree").addClass( "ui-state-error" );
            $("#date_sortie").addClass( "ui-state-error" );
            bValid=false;
          }
          else
          {
            $("#date_entree").removeClass( "ui-state-error" );
            $("#date_sortie").removeClass( "ui-state-error" );
          }
        }
      }
    }
    if ($("#email_pere").val()!="")
    {
      if (!checkRegexp($("#email_pere"),/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i)) { bValid=false; }
    }
    if ($("#email_mere").val()!="")
    {
      if (!checkRegexp($("#email_mere"),/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i)) { bValid=false; }
    }
    <?php   if (!isset($_GET['id'])) { ?>
      if ($("#identifiant").val()!="")
      {
        if (!checkValue($("#passe"))) { bValid=false; }
      }
      else
      {
        $("#passe").removeClass( "ui-state-error" );
      }
    <?php } ?>
    event.preventDefault();
    if ( bValid )
    {
      updateTips("save","eleves","<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#id").val(msg);
        if (action_save=="nouveau")
        {
          updateTips("success","eleves","<?php echo $Langue['BTN_CREER_ELEVE']; ?>");
        }
        else
        {
          updateTips("success","eleves","<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
        }
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","eleves","<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
      action_save="rien";
    }
  });
  
  $("#Enregistrer2").click(function()
  {
    action_save="detail";
    $("#form_editview").submit();
  });

<?php if ($_SESSION['type_util']=="D") { ?>
  $("#EnregistrerNouveau").button();
  $("#EnregistrerNouveau2").button();
  $("#EnregistrerNouveau").click(function()
  {
    action_save="nouveau";
    $("#form_editview").submit();
  });
  $("#EnregistrerNouveau2").click(function()
  {
    action_save="nouveau";
    $("#form_editview").submit();
  });
<?php } ?>

  $("#EnregistrerFermer").click(function()
  {
    action_save="fermer";
    $("#form_editview").submit();
  });

  $("#EnregistrerFermer2").click(function()
  {
    action_save="fermer";
    $("#form_editview").submit();
  });
});
</script>
