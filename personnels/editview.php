<?php
// Récupération des informations
  foreach ($tableau_variable['personnels'] AS $cle)
  {
    $tableau_variable['personnels'][$cle['nom']]['value'] = "";
  }

  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `profs` WHERE id = '" . $_GET['id'] . "'");
    foreach ($tableau_variable['personnels'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['personnels'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
		$derniere_connexion=mysql_result($req,0,'derniere_connexion');
  }
  else
  {
    $tableau_variable['personnels']['date_entree']['value'] = date("Y-m-d");
		$derniere_connexion="0000-00-00";
  }

  $tpl = new template("personnels");
  $tpl->set_file("gform","editview.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }
  
  foreach ($tableau_variable['personnels'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Form($cle));
  }
  $tpl->set_var("DERNIERE_CONNEXION", $derniere_connexion);

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

  /* On vérifie que le type d'utilisateur peut avoir un compte de connexion */
  $("#type").change(function()
  {
    var selected=$("#type").val();
    if (selected=="P" || selected=="D")
    {
      $("#identifiant").removeAttr("disabled");
      $("#passe").removeAttr("disabled");
      $("#identifiant").removeClass("input_disabled");
      $("#passe").removeClass("input_disabled");
    }
    else
    {
      $("#identifiant").attr("disabled", "disabled");
      $("#passe").attr("disabled", "disabled");
      $("#identifiant").addClass("input_disabled");
      $("#passe").addClass("input_disabled");
    }
  });
  
  $("#identifiant").blur(function()
  {
    if ($("#identifiant").val()!="")
    {
      $("#Enregistrer").button({disabled:true});
      $("#Enregistrer2").button({disabled:true});
      $("#EnregistrerFermer").button({disabled:true});
      $("#EnregistrerFermer2").button({disabled:true});
      $("#EnregistrerNouveau").button({disabled:true});
      $("#EnregistrerNouveau2").button({disabled:true});
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
            $("#EnregistrerNouveau").button({disabled:false});
            $("#EnregistrerNouveau2").button({disabled:false});
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
    if (!checkValue($("#civilite"))) { bValid=false; }
    if (!checkValue($("#nom"))) { bValid=false; }
    if (!checkValue($("#prenom"))) { bValid=false; }
    if (!checkValue($("#type"))) { bValid=false; }
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
    if ($("#date_naissance").val()!="")
    {
      if (!checkRegexp($("#date_naissance"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
	}
    if ($("#date_entree_en").val()!="")
    {
      if (!checkRegexp($("#date_entree_en"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
	}
    if ($("#date_derniere_inspection").val()!="")
    {
      if (!checkRegexp($("#date_derniere_inspection"),<?php echo $date_regexp[$Format_Date_PHP]; ?>)) { bValid=false; }
	}
    if ($("#email").val()!="")
    {
      if (!checkRegexp($("#email"),/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i)) { bValid=false; }
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
      updateTips("save","personnels","<?php echo $Langue['LBL_FICHE_PERSONNEL']; ?>");
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#id").val(msg);
        if (action_save=="nouveau")
        {
          updateTips("success","personnels","<?php echo $Langue['LBL_CREER_PERSONNEL']; ?>");
        }
        else
        {
          updateTips("success","personnels","<?php echo $Langue['LBL_FICHE_PERSONNEL']; ?>");
        }
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
      });
    }
    else
    {
      updateTips("error","personnels","<?php echo $Langue['LBL_FICHE_PERSONNEL']; ?>");
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

  /* On met à jour le champ Type à l'ouverture de la page */
  $("#type").change();
});
</script>
