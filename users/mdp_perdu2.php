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
	
	$type_recherche=substr($_GET['id_recherche'],0,1);
	$id_recherche=substr($_GET['id_recherche'],1,strlen($_GET['id_recherche']));
	if ($type_recherche=="P")
	{
	  $req=mysql_query("SELECT * FROM `profs` WHERE id='$id_recherche'");
		$type=$Langue['LBL_TYPE_ENSEIGNANT'];
		$identifiant=mysql_result($req,0,'identifiant');
		$email1=mysql_result($req,0,'email');
		$email2="";
	}
	else
	{
	  $req=mysql_query("SELECT * FROM `eleves` WHERE id='$id_recherche'");
		$type=$Langue['LBL_TYPE_ELEVE'];
		$identifiant=mysql_result($req,0,'identifiant');
		if (mysql_result($req,0,'email_pere')!="") { $email1=mysql_result($req,0,'email_pere'); }
		if (mysql_result($req,0,'email_mere')!="") { $email2=mysql_result($req,0,'email_mere'); }
	}
?>
<table cellspacing="0" cellpadding=0 border=0 style="width:100%">
<tr>
<td width="50%;text-align:center"><div class="ui-widget ui-widget-content ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE1']; ?></div></td>
<td width="50%;text-align:center"><div class="ui-widget ui-widget-content ui-widget-header ui-corner-all" style="width:50%;text-align:center;padding:10px"><?php echo $Langue['LBL_ETAPE2']; ?></div></td>
</tr>
</table>
<br />
<div id="erreur_mdp"></div>
<p class="explic textgauche"><?php echo $Langue['EXPLI_QUESTION_SECRETE']; ?></p>
<form name="form-mdp" id="form-mdp" action="users/verif_mdp_perdu2.php" method="post">
<input type="hidden" id="id_recherche" name="id_recherche" value="<?php echo $id_recherche; ?>">
<input type="hidden" id="type_recherche" name="type_recherche" value="<?php echo $type_recherche; ?>">
<table cellspacing=0 cellpadding=0 class="tableau_editview">
<tr>
  <td class="droite" width=50%><label class="label_class"><?php echo $Langue['LBL_IDENTIFIANT']; ?> :</label></td>
  <td class="gauche" width=50%><label class="label_detail"><?php echo $identifiant; ?></label></td>
</tr>
<tr>
  <td class="droite" width=50%><label class="label_class"><?php echo $Langue['LBL_TYPE_UTIL']; ?> :</label></td>
  <td class="gauche" width=50%><label class="label_detail"><?php echo $type; ?></label></td>
</tr>
<tr>
  <td class="droite" width=50%><label class="label_class" style="width:400px"><?php echo $Langue['LBL_QUESTION_SECRETE']; ?> :</label></td>
  <td class="gauche" width=50%><label class="label_detail"><?php echo $liste_choix['questionsecrete'][mysql_result($req,0,'questionsecrete')]; ?></label></td>
</tr>
<tr>
  <td class="droite" width=50%><label class="label_class" style="width:400px"><?php echo $Langue['LBL_REPONSE_QUESTION']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=50%><input type="text" class="text ui-widget-content ui-corner-all" id="reponse" name="reponse" value="" size=50 maxlength=255></td>
</tr>
<tr>
  <td class="droite" width=30%><label class="label_class" style="width:400px"><?php echo $Langue['EXPLI_QUESTION_SECRETE3']; ?> <font color="#FF0000">*</font> :</label></td>
  <td class="gauche" width=70%><select class="text ui-widget-content ui-corner-all" id="email_contact" name="email_contact">
<?php
  if ($email1!="") { echo '<option value="1">'.$email1.'</option>'; }
  if ($email2!="") { echo '<option value="2">'.$email2.'</option>'; }
?>
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
				$("#erreur_mdp").fadeIn( 1000 );
			  if (msg=="E")
				{
					$("#erreur_mdp").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 10px;"><strong><?php echo $Langue['ERR_REPONSE_QUESTION']; ?></strong></div></div><br>');
					setTimeout(function()
					{
						$("#erreur_mdp").effect("blind",1000);
					}, 3000 );
				}
				else
				{
					$("#erreur_mdp").html('<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 10px;"><?php echo $Langue['EXPLI_QUESTION_SECRETE4']; ?><b>'+msg+'</b><?php echo $Langue['EXPLI_QUESTION_SECRETE5']; ?></div></div><br>');
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

