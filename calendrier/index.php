<?php
  if (!isset($_SESSION['date_en_cours_calendrier'])) { $_SESSION['date_en_cours_calendrier']=date("Y-m-d"); }
  if (!isset($_SESSION['mois_en_cours'])) { $_SESSION['mois_en_cours']=date("n"); }
  if (!isset($_SESSION['annee_en_cours'])) { $_SESSION['annee_en_cours']=date("Y"); }
  if (!isset($_SESSION['calendrier'])) { $_SESSION['calendrier']="mois"; }
  $annee_avant=date("Y")-1;
  $annee_apres=date("Y")+1;
?>

<div class="titre_page"><?php echo $Langue['LBL_AGENDA']; ?></div>
<div class="aide"><button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div>

<table cellpadding=0 cellspacing=0 style="width:100%" border=0 class="tableau_bouton">
<tr>
  <td align=center width=50%>&nbsp;</td>
  <td align=center nowrap>
<?php 
  switch ($_SESSION['calendrier'])
  {
    case "annee":
      echo '<div class="ui-widget ui-state-default ui-corner-all ui-div-separation-form">'.$Langue['LBL_ANNEE_COURS'].' : <select name="annee_en_cours" id="annee_en_cours" class="text ui-widget-content ui-corner-all">';
			for ($i=date("Y")-1;$i<=date("Y")+1;$i++)
			{
				echo '<option value="'.$i.'"';
				if ($i==$_SESSION['annee_en_cours']) { echo ' SELECTED'; }
				echo '>'.$i.'</option>';
			}
			echo '</select></div>';
      break;
    case "mois":
      echo '<div class="ui-widget ui-state-default ui-corner-all ui-div-separation-form">'.$Langue['LBL_MOIS_COURS'].' : <select name="mois_en_cours" id="mois_en_cours" class="text ui-widget-content ui-corner-all">';
			foreach ($liste_choix['mois'] as $cle => $value)
			{
				echo '<option value="'.$cle.'"';
				if ($cle==$_SESSION['mois_en_cours']) { echo ' SELECTED'; }
				echo '>'.$value.'</option>';
			}
			echo '</select>&nbsp;<select name="annee_en_cours" id="annee_en_cours" class="text ui-widget-content ui-corner-all">';
			for ($i=date("Y")-1;$i<=date("Y")+1;$i++)
			{
				echo '<option value="'.$i.'"';
				if ($i==$_SESSION['annee_en_cours']) { echo ' SELECTED'; }
				echo '>'.$i.'</option>';
			}
			echo '</select></div>';
      break;
		case "jour":
      echo '<div class="ui-widget ui-state-default ui-corner-all ui-div-separation-form">'.$Langue['LBL_DATE_COURS'].' : ';
			echo '<input type=text class="text ui-widget-content ui-corner-all" id="date_en_cours_calendrier" name="date_en_cours_calendrier" value="'.Date_Convertir($_SESSION['date_en_cours_calendrier'],"Y-m-d",$Format_Date_PHP).'" size=10 maxlength=10>';
			echo '</div>';
			break;	
  }
?>  
  </td>
  <td align=center width=50%>&nbsp;</td>
</tr>
</table>
<table cellpadding=0 cellspacing=0 style="width:100%" border=0 class="tableau_bouton">
<tr>
  <td class="gauche" width=100%>
    <button id="ajout_reunion"><?php echo $Langue['BTN_AJOUTER_RDV']; ?></button>
<?php if ($_SESSION['type_util']=="D") { ?>
	<button id="jours_feries"><?php echo $Langue['BTN_AJOUTER_FERIES']; ?></button>
	<button id="vacances"><?php echo $Langue['BTN_AJOUTER_VACANCES']; ?></button>
<?php } ?>
	<button id="Imprimer"><?php echo $Langue['BTN_IMPRIMER']; ?></button>
  </td>
  <td class="droite" valign=middle nowrap>
	  <button class="ui-widget ui-state-default <?php if ($_SESSION['calendrier']=="jour") { echo 'ui-state-active'; } ?> ui-corner-all" name="jour" id="jour" style="width:70px !important"><?php echo $Langue['LBL_JOUR']; ?></button>&nbsp;
	  <button class="ui-widget ui-state-default <?php if ($_SESSION['calendrier']=="mois") { echo 'ui-state-active'; } ?> ui-corner-all" name="mois" id="mois" style="width:70px !important"><?php echo $Langue['LBL_MOIS']; ?></button>&nbsp;
	  <button class="ui-widget ui-state-default <?php if ($_SESSION['calendrier']=="annee") { echo 'ui-state-active'; } ?> ui-corner-all" name="annee" id="annee" style="width:70px !important"><?php echo $Langue['LBL_ANNEE']; ?></button>
  </td>
</tr>
</table>
<?php
  switch ($_SESSION['calendrier'])
  {
    case "annee":
			echo '<table cellspacing=0 cellpadding=0 border=0 width=100%>';
			for ($i=1;$i<=4;$i++)
			{
				$plus2="";
				if ($i<4) { $plus2='border-bottom:15px transparent solid;'; }	
				echo '<tr>';
				for ($j=1;$j<=3;$j++)
				{
					$plus="";
					if ($j<3) 
					{ 
						if ($Sens_Ecriture=="ltr")
						{
							$plus='border-right:15px transparent solid;'; 
						}
						else
						{
							$plus='border-left:15px transparent solid;'; 
						}
					}
					echo '<td width=33% valign=top style="'.$plus.$plus2.'">';
					Affiche_Calendrier(($i-1)*3+$j,$_SESSION['annee_en_cours'],50,'court');
					echo '</td>';
				}
				echo '</tr>';
			}
			echo '</table>';
			break;
	case "mois":
	  Affiche_Calendrier($_SESSION['mois_en_cours'],$_SESSION['annee_en_cours'],120,'long');
	  break;
	case "jour":
	  echo '<table cellspacing=0 cellpadding=0 border=0 width=100%>';
	  $tableau=Array();
	  for ($i=0;$i<=23;$i++)
	  {
			for ($j=0;$j<=55;$j=$j+5)
			{
				$tableau_heure[date("H:i:s",mktime($i,$j,0,1,1,2010))]=0;
			}
	  }
	  for ($i=0;$i<=23;$i++)
	  {
			for ($j=0;$j<=55;$j=$j+30)
			{
				$heure="";
				if ($i<=9) { $heure="0".$i.':'; } else { $heure=$i.':'; }
				if ($j<=9) { $heure=$heure.'0'.$j; } else { $heure=$heure.$j; }
				$plus="border-bottom:none;";
				$plus2="";
				if ($i==23 && $j==30) { $plus=""; }
				if ($j==0) { $plus2="font-weight:bold;"; }
				if ($Sens_Ecriture=="ltr")
				{
					echo '<tr><td style="padding:3px;padding-right:40px;height:50px !important;border-right:none;'.$plus.$plus2.'" class="ui-widget-content" valign=top>';
				}
				else
				{
					echo '<tr><td style="padding:3px;padding-left:40px;height:50px !important;border-left:none;'.$plus.$plus2.'" class="ui-widget-content" valign=top>';
				}
				echo $heure;
				echo '</td>';
				echo '<td style="width:100%;'.$plus.'" class="ui-widget-content">';
				$heure .=':00';
				$id_util=$_SESSION['type_util'].$_SESSION['id_util'];
				$req=mysql_query("SELECT * FROM `reunions` WHERE date='".$_SESSION['date_en_cours_calendrier']."' AND ('".$heure."' BETWEEN heure_debut AND heure_fin) AND (id_util LIKE '".$id_util.",%' OR id_util LIKE '%,".$id_util."' OR id_util='".$id_util."' OR id_util LIKE '%,".$id_util.",%') ORDER BY heure_debut ASC, heure_fin ASC");
				for ($o=1;$o<=mysql_num_rows($req);$o++)
				{
					if (!in_array(mysql_result($req,$o-1,'id'),$tableau))
					{
						$heure_debut=mysql_result($req,$o-1,'heure_debut');
						$heure_fin=mysql_result($req,$o-1,'heure_fin');
						$decalage=(date("U",mktime(substr($heure_fin,0,2),substr($heure_fin,3,2),0,01,01,2010))-date("U",mktime(substr($heure_debut,0,2),substr($heure_debut,3,2),0,01,01,2010)))/60;
						$hauteur=100*$decalage/60-17;
						$decalage_gauche=$tableau_heure[$heure_debut]*170;
						$decalage2=-25;
						if ($heure_debut<$heure)
						{
							$decalage_horaire=(date("U",mktime(substr($heure,0,2),substr($heure,3,2),0,01,01,2010))-date("U",mktime(substr($heure_debut,0,2),substr($heure_debut,3,2),0,01,01,2010)))/60;
							$decalage2=$decalage2-100*$decalage_horaire/60;
						}
						echo '<div class="someClass ui-corner-all" onMouseOver="Calendrier_L_Voir_Tooltil(\''.substr(mysql_result($req,$o-1,'heure_debut'),0,5).'-'.substr(mysql_result($req,$o-1,'heure_fin'),0,5).'\',\''.str_replace("'","\'",$liste_choix['type_reunion'][mysql_result($req,$o-1,'type')]).'\',\''.str_replace('"','&quot;',str_replace("'","\'",mysql_result($req,$o-1,'resume'))).'\')" style="cursor:pointer;margin-left:'.$decalage_gauche.'px;background:#ffffff;margin-top:'.$decalage2.'px;padding:5px;width:500px;height:'.$hauteur.'px;position:absolute;border:3px solid '.$liste_choix['couleur_reunion'][mysql_result($req,$o-1,'type')].';background:'.$liste_choix['couleur_reunion_clair'][mysql_result($req,$o-1,'type')].'" onClick="Calendrier_L_Charge_Reunion(\''.mysql_result($req,$o-1,'id').'\')"><b>'.substr(mysql_result($req,$o-1,'heure_debut'),0,5).'-'.substr(mysql_result($req,$o-1,'heure_fin'),0,5).' : '.mysql_result($req,$o-1,'resume').'</b></div>';
						$tableau[]=mysql_result($req,$o-1,'id');
						$minute=0;
						$tableau_heure[$heure_debut]=$tableau_heure[$heure_debut]+1;
						do
						{
							$horaire=date("H:i:s",mktime(substr($heure_debut,0,2),substr($heure_debut,3,2)+$minute,0,1,1,2010));
							$tableau_heure[$horaire]=$tableau_heure[$heure_debut];
							$minute=$minute+5;
						} while ($horaire!=$heure_fin);
						$tableau_heure[$heure_fin]=$tableau_heure[$heure_fin]-1;
					}
				}
				echo '</td></tr>';
			}
	  }
	  echo '</table>';
	  break;	
  }	  
?>
<script language="Javascript">
$(document).ready(function()
{
  $("#Imprimer").button();
  $("#Imprimer").click(function()
  {
    Charge_Dialog("index2.php?module=calendrier&action=detailview_imprimer","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });

  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();		
<?php
  switch ($_SESSION['type_util'])
	{
	  case "D":
?>
			window.open("http://www.doxconception.com/site/index.php/directeur-agenda.html","Aide");
<?php
			break;
		case "P":
?>
			window.open("http://www.doxconception.com/site/index.php/prof-agenda.html","Aide");
<?php
			break;
		case "E":
?>
			window.open("http://www.doxconception.com/site/index.php/parent-agenda.html","Aide");
<?php
			break;
		}
?>
  });

  $("#annee").button();
  $("#mois").button();
  $("#jour").button();

  $("#ajout_reunion").button();
  $("#ajout_reunion").click(function()
  {
    Charge_Dialog("index2.php?module=calendrier&action=editview","<?php echo $Langue['LBL_CREER_RDV']; ?>");
  });

  $("#jours_feries").button();
  $("#jours_feries").click(function()
  {
    Charge_Dialog("index2.php?module=calendrier&action=jours_feries","<?php echo $Langue['LBL_GESTION_FERIES']; ?>");
  });

  $("#vacances").button();
  $("#vacances").click(function()
  {
    Charge_Dialog("index2.php?module=calendrier&action=vacances","<?php echo $Langue['LBL_GESTION_VACANCES']; ?>");
  });

  $("#annee").click(function()
  {
     Calendrier_L_Modif_Calendrier("annee");
  });
  $("#mois").click(function()
  {
     Calendrier_L_Modif_Calendrier("mois");
  });
  $("#jour").click(function()
  {
     Calendrier_L_Modif_Calendrier("jour");
  });
  
  $("#mois_en_cours").change(function()
  {
     Calendrier_L_Charge_Mois($("#mois_en_cours").val());
  });

  $("#annee_en_cours").change(function()
  {
     annee_en_cours=$("#annee_en_cours").val();
     Message_Chargement(1,1);
     var url="calendrier/change_annee.php";
     var data="annee_en_cours="+annee_en_cours;
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
  });
  
  $( "#date_en_cours_calendrier" ).datepicker(
  { 
    dateFormat: "<?php echo $Format_Date_Calendar; ?>", 
		showOn: "button", 
		buttonImage: "images/calendar.gif", 
		buttonImageOnly: true, 
		minDate:new Date(<?php echo $annee_avant; ?>,0,1),
		maxDate:new Date(<?php echo $annee_apres; ?>,11,31)
  });
  
  $( "#date_en_cours_calendrier" ).change(function()
  {
     date_en_cours_calendrier=$("#date_en_cours_calendrier").val();
		 Calendrier_L_Modif_Date_Calendrier(date_en_cours_calendrier);
  });
});

function Calendrier_L_Modif_Date_Calendrier(date)
{
     Message_Chargement(1,1);
     var url="calendrier/change_date.php";
     var data="date_en_cours_calendrier="+date;
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
}

function Calendrier_L_Modif_Calendrier(id)
{
     Message_Chargement(1,1);
     var url="calendrier/change_calendrier.php";
     var data="calendrier="+id;
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
}

function Calendrier_L_Charge_Reunion(id)
{
  Charge_Dialog("index2.php?module=calendrier&action=detailview&id="+id,"<?php echo $Langue['LBL_FICHE_RDV']; ?>");
}

function Calendrier_L_Charge_Mois(mois)
{
     Message_Chargement(1,1);
     var url="calendrier/change_mois.php";
     var data="nouveau_mois="+mois;
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
}

function Calendrier_L_Voir_Tooltil(date,type,descriptif)
{
  msg="<p class=explic><u><?php echo $Langue['LBL_TOOLTIP_DESCRIPTIF_REUNION']; ?></u> : "+descriptif+"</p>";
  msg=msg+"<p class=explic><u><?php echo $Langue['LBL_TOOLTIP_DATE_REUNION']; ?></u> : "+date+"</p>";
  msg = msg+"<p class=explic><u><?php echo $Langue['LBL_TOOLTIP_TYPE_REUNION']; ?></u> : "+type+"</p>";
  $(".someClass").tipTip(
  {
    defaultPosition:'top',
	direction:'<?php echo $Sens_Ecriture; ?>',
	delay:100,
	maxWidth: 'auto',
	content:msg
  });
}
</script>