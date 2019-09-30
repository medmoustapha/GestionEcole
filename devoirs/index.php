<?php
  $date=date("Y-m-d");
  $id_classe=$_SESSION['id_classe_cours'];
  $id_niveau=$_SESSION['niveau_en_cours'];
  
  $req=mysql_query("SELECT * FROM `devoirs` WHERE id_classe='$id_classe' AND id_niveau='$id_niveau' AND date_faire>='$date' AND date_donnee<='$date' ORDER BY date_faire ASC, date_donnee ASC");  
?>
<div class="titre_page" style="text-align:left"><?php echo $Langue['LBL_DEVOIRS']; ?></div>
<div class="aide"><button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div>
<br><br><br><br>

<input type="button" id="Imprimer_Detail" name="Imprimer_Detail" value="<?php echo $Langue['BTN_IMPRIMER']; ?>">
<?php
  $date_donnee="0000-00-00";
  $fait=false;
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
    if ($date_donnee!=mysql_result($req,$i-1,'date_faire'))
	{
	  if ($fait==true) { echo '</tbody></table><br /><br />'; }
	  echo '<div class="sous_titre_page">'.$Langue['LBL_DEVOIRS_A_FAIRE_POUR'].' '.Date_Convertir(mysql_result($req,$i-1,'date_faire'),"Y-m-d",$Format_Date_PHP).'</div>';
	  echo '<table class="display" cellpadding=0 cellspacing=0 style="width:100%;margin-top:5px"><thead><tr>';
	  echo '<th class="ui-state-default" style="width:14%" align=center>'.$Langue['LBL_DONNES_LE'].'</th><th class="ui-state-default" style="width:14%" align=center>'.$Langue['LBL_MATIERE'].'</th><th class="ui-state-default" style="width:72%" align=center>'.$Langue['LBL_TRAVAIL_DEMANDE'].'</th></tr>';
	  echo '</thead><tbody>';
	  $date_donnee=mysql_result($req,$i-1,'date_faire');
	  $fait=true;
	  $ligne="even";
    }
    echo '<tr class="'.$ligne.'"><td style="width:14%" align=center valign=top>'.Date_Convertir(mysql_result($req,$i-1,'date_donnee'),"Y-m-d",$Format_Date_PHP).'</td>';
    $req2=mysql_query("SELECT * FROM `listes` WHERE id='".mysql_result($req,$i-1,'id_matiere')."'");
    if (mysql_num_rows($req2)!="")
    {
      $valeur=mysql_result($req2,0,'intitule');
    }
    else
    {
      $valeur=$liste_choix['matieres_cj'][mysql_result($req,$i-1,'devoirs.id_matiere')];
    }
    echo '<td style="width:14%" align=center valign=top>'.$valeur.'</td>';
    echo '<td style="width:72%" align=left valign=top><ul class="explic" style="padding:0px;padding-left:10px;margin-top:0px;margin-bottom:0px;"><li>'.str_replace("\n","</li><li>",mysql_result($req,$i-1,'contenu')).'</li></ul></td></tr>';
	if ($ligne=="even") { $ligne="odd"; } else { $ligne="even"; }
  }
?>
</tbody></table>
<input type="button" id="Imprimer_Detail2" name="Imprimer_Detail2" value="<?php echo $Langue['BTN_IMPRIMER']; ?>">
<script language="Javascript">
$(document).ready(function()
{
  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();		
    window.open("http://www.doxconception.com/site/index.php/parent-devoirs/article/150-voir-les-devoirs-de-votre-enfant.html","Aide");
  });

  $("#Imprimer_Detail").button({disabled:false});
  $("#Imprimer_Detail2").button({disabled:false});
  $("#Imprimer_Detail").click(function()
  {
    window.open("index2.php?module=devoirs&action=imprimer_lancer","Impression");
  });
  $("#Imprimer_Detail2").click(function()
  {
    window.open("index2.php?module=devoirs&action=imprimer_lancer","Impression");
  });
});
</script>
