<?php
  $req_bilan=mysql_query("SELECT * FROM `cooperative_bilan` WHERE annee='".$_SESSION['cooperative_scolaire']."'");
  $clos=mysql_result($req_bilan,0,'clos');

// Récupération des informations
  foreach ($tableau_variable['cooperative'] AS $cle)
  {
    $tableau_variable['cooperative'][$cle['nom']]['value'] = "";
  }

  if (isset($_GET['id']))
  {
    $req = mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` WHERE id = '" . $_GET['id'] . "'");
    foreach ($tableau_variable['cooperative'] AS $cle)
    {
      if (mysql_result($req,0,$cle['nom'])!="") { $tableau_variable['cooperative'][$cle['nom']]['value'] = mysql_result($req,0,$cle['nom']); }
    }
  }

  $tpl = new template("cooperative");
  $tpl->set_file("gform","detailview.html");
  $tpl->set_block('gform','formulaire','liste_bloc');

  foreach ($Langue AS $cle => $value)
  {
    $tpl->set_var(strtoupper($cle),$value);
  }

  foreach ($tableau_variable['cooperative'] AS $cle)
  {
    $tpl->set_var(strtoupper($cle['nom']), Variables_Affiche($cle));
  }
  
  $req2=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id='".mysql_result($req,0,'tiers')."'");
  $tpl->set_var("TIERS",mysql_result($req2,0,'nom'));
  
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
  $("#Modifier").button({disabled:<?php if ($clos=="1" || mysql_result($req,0,'pointe')=="1") { echo "true"; } else { echo "false"; } ?>});
  $("#Annuler").button();
  $("#Modifier2").button({disabled:<?php if ($clos=="1" || mysql_result($req,0,'pointe')=="1") { echo "true"; } else { echo "false"; } ?>});
  $("#Annuler2").button();
  $("#Annuler").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  $("#Annuler2").click(function()
  {
    $("#dialog-form").dialog( "close" );
  });
  
  $("#Modifier").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=editview&id=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_SAISIE_MODIFIER_DEPENSE']; ?>");
  });
  
  $("#Modifier2").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=editview&id=<?php echo $_GET['id']; ?>","<?php echo $Langue['LBL_SAISIE_MODIFIER_DEPENSE']; ?>");
  });
});
</script>
