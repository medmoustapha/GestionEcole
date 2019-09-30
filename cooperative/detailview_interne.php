<?php
  $req_bilan=mysql_query("SELECT * FROM `cooperative_bilan` WHERE annee='".$_SESSION['cooperative_scolaire']."'");
  $clos=mysql_result($req_bilan,0,'clos');

  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE id = '" . $_GET['id'] . "'");
  }

  $tpl = new template("cooperative");
  $tpl->set_file("gform","detailview_interne.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  $tpl->set_var("DATE",Date_Convertir(mysql_result($req,0,'date'),'Y-m-d',$Format_Date_PHP));
  $tpl->set_var("MONTANT",number_format(mysql_result($req,0,'montant'),2,',',' ').' &euro;');
  $tpl->set_var("LIBELLE",mysql_result($req,0,'libelle'));
  if (mysql_result($req,0,'id_classe')!="")
  {
    $req2=mysql_query("SELECT * FROM `classes` WHERE id='".mysql_result($req,0,'id_classe')."'");
    $tpl->set_var("ID_CLASSE",mysql_result($req2,0,'nom_classe'));
  }
  else
  {
    $tpl->set_var("ID_CLASSE",$Langue['LBL_SAISIE_POT_COMMUN']);
  }

  $tpl->parse('liste_bloc','formulaire',true);
  $tpl->pparse("affichage","gform");
?>

<script language="Javascript">
$(document).ready(function()
{
  /* Initialisation de la page, notamment des boutons */
  $("#Modifier_Interne").button({disabled:<?php if ($clos=="1") { echo "true"; } else { echo "false"; } ?>});
  $("#Annuler_Interne").button();
  $("#Modifier2_Interne").button({disabled:<?php if ($clos=="1") { echo "true"; } else { echo "false"; } ?>});
  $("#Annuler2_Interne").button();
  $("#Annuler_Interne").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  $("#Annuler2_Interne").click(function()
  {
    $("#dialog-niveau2").dialog( "close" );
  });
  
  $("#Modifier_Interne").click(function()
  {
    Charge_Dialog2("index2.php?module=cooperative&action=editview_interne&id=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_SAISIE_MODIFIER_FONCTIONNEMENT_INTERNE']; ?>");
  });
  
  $("#Modifier2_Interne").click(function()
  {
    Charge_Dialog2("index2.php?module=cooperative&action=editview_interne&id=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_SAISIE_MODIFIER_FONCTIONNEMENT_INTERNE']; ?>");
  });
});
</script>
