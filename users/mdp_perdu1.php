<?php
  session_start();

  include "../config.php";

  if (!isset($_SESSION['language_application']))
  {
    mysql_connect($gestclasse_config['param_connexion']['serveur'],$gestclasse_config['param_connexion']['user'],$gestclasse_config['param_connexion']['passe']);
    @mysql_select_db($gestclasse_config['param_connexion']['base']);
		$req=mysql_query("SELECT * FROM `config` WHERE parametre='langue_defaut'");
		if (mysql_num_rows($req)=="")
		{
				$_SESSION['language_application']="fr-FR";
		}
		else
		{
				$_SESSION['language_application']=mysql_result($req,0,'valeur');
		}
  }

  include "../langues/fr-FR/config.php";
  include "../langues/fr-FR/commun.php";
  foreach ($Langue_Application AS $cle => $value)
  {
	  $Langue[$cle]=$Langue_Application[$cle];
  }
  if ($_SESSION['language_application']!="fr-FR")
  {
		if (file_exists("../langues/".$_SESSION['language_application']."/config.php"))
		{
			include "../langues/".$_SESSION['language_application']."/config.php";
		}
		if (file_exists("../langues/".$_SESSION['language_application']."/commun.php"))
		{
			include "../langues/".$_SESSION['language_application']."/commun.php";
			foreach ($Langue_Application AS $cle => $value)
			{
				$Langue[$cle]=$Langue_Application[$cle];
			}
		}
  }

  include "../config_parametre.php";
  
  include "../commun/fonctions.php";
  include "../commun/phplib/php/template.inc";

  Connexion_DB();
?>
<table cellspacing="0" cellpadding=0 border=0 style="width:100%">
<tr>
<td width="50%;text-align:center"><div class="ui-widget ui-widget-content ui-widget-header ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE1']; ?></div></td>
<td width="50%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE2']; ?></div></td>
</tr>
</table>
<br />
<div id="erreur_mdp"></div>
<p class="explic textgauche"><?php echo $Langue['EXPLI_QUESTION_SECRETE']; ?></p>
<form name="form-mdp" id="form-mdp" action="users/verif_mdp_perdu1.php" method="post">
<table cellspacing=0 cellpadding=0 class="tableau_editview">
<tr>
  <td class="droite" width=30%><label class="label_class"><?php echo $Langue['LBL_IDENTIFIANT']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=70%><input type="text" class="text ui-widget-content ui-corner-all" id="mdp_identifiant" name="mdp_identifiant" value="" size=50 maxlength=255></td>
</tr>
<tr>
  <td class="droite" width=30%><label class="label_class"><?php echo $Langue['LBL_TYPE_UTIL']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=70%><select class="text ui-widget-content ui-corner-all" id="mdp_type" name="mdp_type">
	<option value="P"><?php echo $Langue['LBL_TYPE_ENSEIGNANT']; ?></option>
	<option value="E"><?php echo $Langue['LBL_TYPE_ELEVE']; ?></option>
	</select>
	</td>
</tr>
<tr>
  <td class="centre" width=100% colspan=2><button id="mdp_valider"><?php echo $Langue['BTN_VALIDER']; ?></button></td>
</tr>
</table>
</form>
<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#mdp_valider").button();

  $("#form-mdp").submit(function(event)
  {
		var bValid = true;
		$("#erreur_mdp").fadeIn( 1000 );
		$("#erreur_mdp").html('<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 10px;"><?php echo $Langue['MSG_VERIFICATION']; ?></div></div><br>');
    if (!checkValue($("#mdp_identifiant"))) { bValid=false; }
    
    event.preventDefault();
    if ( bValid )
    {
      var $form = $( this );
      url = $form.attr( 'action' );
      data = $form.serialize();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
			  if (msg!='P' && msg!='E' && msg!='Q' && msg!='M')
				{
				  $("#dialog-mdp").load("users/mdp_perdu2.php?id_recherche="+msg);
				}
				else
				{
					$("#erreur_mdp").fadeIn( 1000 );
					duree=3000;
				  switch (msg)
					{
					  case "P":
				      $("#erreur_mdp").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 10px;"><strong><?php echo $Langue['ERR_IDENTIFIANT_MDP_ENSEIGNANT']; ?></strong></div></div><br>');
						  break;
					  case "E":
				      $("#erreur_mdp").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 10px;"><strong><?php echo $Langue['ERR_IDENTIFIANT_MDP_ELEVE']; ?></strong></div></div><br>');
						  break;
					  case "Q":
				      $("#erreur_mdp").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 10px;"><strong><?php echo $Langue['MSG_QUESTION_SECRETE_INDEFINIE'].$Langue['MSG_QUESTION_SECRETE_INDEFINIE2'].'<br>'.$Langue['MSG_QUESTION_SECRETE_INDEFINIE4']; ?></strong></div></div><br>');
							duree=6000;
						  break;
					  default:
				      $("#erreur_mdp").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 10px;"><strong><?php echo $Langue['MSG_QUESTION_SECRETE_INDEFINIE'].$Langue['MSG_QUESTION_SECRETE_INDEFINIE3'].'<br>'.$Langue['MSG_QUESTION_SECRETE_INDEFINIE4']; ?></strong></div></div><br>');
							duree=6000;
						  break;
					}
					setTimeout(function()
          {
            $("#erreur_mdp").effect("blind",1000);
          }, duree );
				}
      });
    }
    else
    {
			$("#erreur_mdp").fadeIn( 1000 );
			$("#erreur_mdp").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 10px;"><strong><?php echo $Langue['ERR_FORMULAIRE']; ?></strong></div></div><br>');
			setTimeout(function()
			{
				$("#erreur_mdp").effect("blind",1000);
			}, 3000 );
    }
  });
});
</script>

