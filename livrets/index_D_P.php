<?php
  if ($_SESSION['type_util']!="D")
  {
    $req=mysql_query("SELECT * FROM `classes_profs` WHERE id_classe='".$_SESSION['id_classe_cours']."' AND id_prof='".$_SESSION['id_util']."'");
    if (mysql_num_rows($req)!="")
    {
      $type_user_pour_classe=mysql_result($req,0,'type');
    }
  }
  else
  {
    $type_user_pour_classe="T";
  }

  if (!isset($_SESSION['trimestre_en_cours']))
  {
    $_SESSION['trimestre_en_cours']=$gestclasse_config_plus['decoupage_livret'].Trouve_Trimestre($gestclasse_config_plus);
  }

  $plus_modifiable=false;
?>
<div class="titre_page"><?php echo $Langue['LBL_LISTE_EVALUATIONS']; ?></div>
<div class="aide"><button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div>

<table cellpadding=0 cellspacing=0 style="width:100%" border=0 class="tableau_bouton">
<tr>
  <td class="droite" valign=middle nowrap>
    <div class="ui-widget ui-state-default ui-corner-right floatdroite ui-div-listview textcentre"><?php echo Liste_Periode('trimestre_s',$_SESSION['trimestre_en_cours']); ?>
    </div>
    <div class="ui-widget ui-state-default floatdroite ui-div-listview textcentre"><?php echo $Langue['LBL_NIVEAU']; ?> :
      <?php echo Liste_Niveaux('niveaux_s','form',$_SESSION['niveau_en_cours'],$_SESSION['id_classe_cours'],false); ?>
    </div>
    <?php
      if ($_SESSION['type_util']=="D")
      {
        echo '<div class="ui-widget ui-state-default floatdroite ui-div-listview textcentre">'.$Langue['LBL_CLASSE'].' : ';
        echo Liste_Classes("classes_s",'form',$_SESSION['annee_scolaire'],$_SESSION['id_classe_cours'],'',true);
        echo '</div><div class="ui-widget ui-state-default ui-corner-left floatdroite ui-div-listview textcentre">'.$Langue['LBL_ANNEE_SCOLAIRE_COURS'].' : ';
        echo Liste_Annee("annee_s",'form',$_SESSION['annee_scolaire']);
      }
      else
      {
        echo '<div class="ui-widget ui-state-default ui-corner-left floatdroite ui-div-listview textcentre">'.$Langue['LBL_CLASSE'].' : ';
        echo Liste_Classes("classes_s",'form',$_SESSION['annee_scolaire'],$_SESSION['id_classe_cours'],$_SESSION['id_util'],false);
      }
    ?>
    </div>
  </td>
</tr>
<tr>
  <td class="gauche">
    <button id="ajouter_controle"><?php echo $Langue['BTN_AJOUTER_EVALUATION']; ?></button>&nbsp;<button id="liste_competences"><?php echo $Langue['LBL_COMPETENCES_LISTE_COMPETENCES']; ?></button>&nbsp;<button id="imprimer"><?php echo $Langue['BTN_IMPRIMER']; ?></button>
  </td>
</tr>
</table>
<?php
  // On vérifie que le titulaire utilise la fonctionnalité
  if (strpos($gestclasse_config_plus['onglet_P'],"livrets")===false)
  {
    echo '<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout textcentre"><strong>'.$Langue['MSG_FONCTIONNALITE_PAS_UTILISEE'].'</strong></div></div>';
    $ok=1;
  }
  else
  {
    if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
	  {
	    $annee_a=$_SESSION['annee_scolaire'];
	  }
	  else
	  {
	    if ($_SESSION['trimestre_en_cours']=="T1" || $_SESSION['trimestre_en_cours']=="P1" || $_SESSION['trimestre_en_cours']=="P2") { $annee_a=$_SESSION['annee_scolaire']; } else { $annee_a=$_SESSION['annee_scolaire']+1; }
	  }
	  $ok=0;
	  // On vérifie qu'une classe est bien sélectionnée
	  if ($_SESSION['id_classe_cours']=="")
	  // Pas de classe sélectionnée
	  {
			$ok=1;
			echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all margin10_haut marge10_tout textcentre"><strong>'.$Langue['MSG_SELECTIONNER_CLASSE'].'</strong></div></div>';
	  }
	  else
	  // Une classe sélectionnée
	  {
			// On regarde si des compétences existent
			$req_categorie=mysql_query("SELECT * FROM `competences` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND supprime='0000-00-00'");
			if (mysql_num_rows($req_categorie)=="")
			// Pas de compétence
			{
				$ok=2;
				echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all margin10_haut marge10_tout textcentre"><strong>'.$Langue['MSG_CREER_COMPETENCES'].'</strong></div></div>';
			}
			else
			// Des compétences existent : on peut afficher les contrôles
			{
				// On regarde s'il y a des contrôles ou non
				$req_liste=mysql_query("SELECT * FROM `listes` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND nom_liste='matieres_b'");
				if ($type_user_pour_classe=="T" || $type_user_pour_classe=="E" || $_SESSION['type_util']=="D")
				{
					if (mysql_num_rows($req_liste)!="")
					{
						$req_controle=mysql_query("SELECT controles.*, listes.* FROM `controles`,`listes` WHERE controles.id_classe='".$_SESSION['id_classe_cours']."' AND controles.id_niveau='".$_SESSION['niveau_en_cours']."' AND controles.trimestre='".$_SESSION['trimestre_en_cours']."' AND controles.id_matiere=listes.id ORDER BY listes.ordre ASC, date ASC");
					}
					else
					{
						$req_controle=mysql_query("SELECT * FROM `controles` WHERE id_classe='".$_SESSION['id_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND trimestre='".$_SESSION['trimestre_en_cours']."' ORDER BY id_matiere ASC, date ASC");
					}
				}
				else
				{
					if (mysql_num_rows($req_liste)!="")
					{
						$req_controle=mysql_query("SELECT controles.*, listes.* FROM `controles`,`listes` WHERE controles.id_prof='".$_SESSION['id_util']."' AND controles.id_classe='".$_SESSION['id_classe_cours']."' AND controles.id_niveau='".$_SESSION['niveau_en_cours']."' AND controles.trimestre='".$_SESSION['trimestre_en_cours']."' AND controles.id_matiere=listes.id ORDER BY listes.ordre ASC, date ASC");
					}
					else
					{
						$req_controle=mysql_query("SELECT * FROM `controles` WHERE id_prof='".$_SESSION['id_util']."' AND id_classe='".$_SESSION['id_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND trimestre='".$_SESSION['trimestre_en_cours']."' ORDER BY id_matiere ASC, date ASC");
					}
				}
				
				if (mysql_num_rows($req_controle)=="")
				// S'il n'y a pas de contrôle
				{
					$ok2=false;
					echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all margin10_haut marge10_tout textcentre"><strong>'.$Langue['MSG_AJOUTER_EVALUATION'].'</strong></div></div>';
				}
				else
				// S'il y a des contrôles, on affiche le tableau
				{
					$ok2=true;
					// Création des entêtes
					echo '<div id="listview"><table id="listing_donnees_livrets" class="display" cellpadding=0 cellspacing=0><thead><tr><th class="centre">'.$Langue['LST_NUMERO_COLONNE'].'</th><th class="centre">'.$Langue['LST_ELEVES'].'</th><th class="centre">'.$Langue['LST_LIVRET_SCOLAIRE'].'</th>';
					$width='{ "sWidth": "50px", "bSortable": false },{ "sWidth": "250px", "bSortable": false },{ "sWidth": "20px", "bSortable": false },';

					for($i=1;$i<=mysql_num_rows($req_controle);$i++)
					{
						$id_m=mysql_result($req_controle,$i-1,'controles.id_matiere');
						$req_matiere=mysql_query("SELECT * FROM `listes` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND nom_liste='matieres_b' AND id='".$id_m."'");
						if (mysql_num_rows($req_matiere)=="") { $matiere=$liste_choix['matieres_b'][$id_m]; } else { $matiere=mysql_result($req_matiere,0,'intitule'); }
						$mat=explode("|",$matiere);
						if (mysql_num_rows($req_matiere)!="") { $mat[1]=$mat[1]; }
						echo '<th class="centre"><a class="someClass" onMouseOver="Livrets_L_Voir_Tooltil(\''.Date_Convertir(mysql_result($req_controle,$i-1,'controles.date'),'Y-m-d',$Format_Date_PHP).'\',\''.str_replace('"','&quot;',str_replace("'","\'",$mat[1])).'\',\''.str_replace('"','&quot;',str_replace("'","\'",mysql_result($req_controle,$i-1,'controles.descriptif'))).'\',\''.mysql_result($req_controle,$i-1,'controles.coefficient').'\')" href="#null" onClick="Livrets_L_Change_Controle(\''.mysql_result($req_controle,$i-1,'controles.id').'\')">'.$i.'<br />'.$mat[0].'</a></th>';
						$width=$width.'{ "sWidth": "50px", "bSortable": false },';
					}
					echo '</tr></thead><tbody>';
					// Création des lignes
					$req_eleve=mysql_query("SELECT eleves_classes.*, eleves.* FROM `eleves_classes`, `eleves` WHERE eleves_classes.id_classe='".$_SESSION['id_classe_cours']."' AND eleves_classes.id_niveau='".$_SESSION['niveau_en_cours']."' AND eleves.id=eleves_classes.id_eleve AND (eleves.date_entree<='".$gestclasse_config_plus['fin_'.$_SESSION['trimestre_en_cours']]."' AND (eleves.date_sortie='0000-00-00' OR eleves.date_sortie>='".$gestclasse_config_plus['debut_'.$_SESSION['trimestre_en_cours']]."')) ORDER BY eleves.nom ASC, eleves.prenom ASC");
					for ($i=1;$i<=mysql_num_rows($req_eleve);$i++)
					{
						// On recherche si un des livrets a été signé par l'enseignant
						$signature="livrets|".$_SESSION['trimestre_en_cours']."|".$_SESSION['annee_scolaire']."|P|".mysql_result($req_eleve,$i-1,'eleves.id');
						$req_signature=mysql_query("SELECT * FROM `signatures` WHERE parametre='$signature' AND type_util='P'");
						if (mysql_num_rows($req_signature)!="") { $plus_modifiable=true; }

						// Affichage du tableau des évaluations
						echo '<tr><td class="centre">'.$i.'</td><td class="gauche"><a title="'.$Langue['SPAN_VOIR_FICHE_ELEVE'].'" href="#null" onClick="Livrets_L_Charge_Eleve(\''.mysql_result($req_eleve,$i-1,'eleves.id').'\')">'.mysql_result($req_eleve,$i-1,'eleves.nom').' '.mysql_result($req_eleve,$i-1,'eleves.prenom').'</a></td>';
						echo '<td class="centre" style="padding:3px"><a title="'.$Langue['SPAN_VOIR_LIVRET_SCOLAIRE'].'" href="#null" onClick="Livrets_L_Charge_Livret(\''.mysql_result($req_eleve,$i-1,'eleves.id').'\')"><img src="images/livret_scolaire_petit.gif" width=12 height=12 border=0></a></td>';
						for ($j=1;$j<=mysql_num_rows($req_controle);$j++)
						{
							$date_controle=mysql_result($req_controle,$j-1,'controles.date');
							$class=true;
							if ($date_controle<mysql_result($req_eleve,$i-1,'eleves.date_entree')) { $class=false; }
							if (mysql_result($req_eleve,$i-1,'eleves.date_sortie')!='0000-00-00' && $date_controle>mysql_result($req_eleve,$i-1,'eleves.date_sortie')) { $class=false; }
							if ($class==false)
							{
								echo '<td class="centre sorti">&nbsp;</td>';
							}
							else
							{
								$req_result=mysql_query("SELECT * FROM `controles_resultats` WHERE id_controle='".mysql_result($req_controle,$j-1,'controles.id')."' AND id_eleve='".mysql_result($req_eleve,$i-1,'eleves.id')."'");
								if (mysql_num_rows($req_result)=="") { echo '<td class="centre"><font color="#FF0000"><strong>'.$Langue['LBL_ABSENT'].'</strong></font></td>'; } else { echo '<td class="centre">&nbsp;</td>'; }
							}
						}
						echo '</tr>';
					}
					echo '</tbody></table></div>';
				}
			}
	  }
  }
?>

<script language="Javascript">
$(document).ready(function()
{ 
  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-livrets-scolaires.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-livrets-scolaires.html","Aide");
<?php } ?>
  });

  /* Création des boutons */
<?php if ($ok!=0) { ?>
  $("#ajouter_controle").button({ disabled: true });
  $("#imprimer").button({ disabled: true });
  <?php if ($ok==1) { ?>
    $("#niveaux_s").attr('disabled', true );
    $("#liste_competences").button({ disabled: true });
  <?php } else { ?>
    $("#liste_competences").button({ disabled: false });
  <?php } ?>
<?php } else { ?>
  $("#ajouter_controle").button({ disabled: false });
  $("#liste_competences").button({ disabled: false });
  <?php if ($type_user_pour_classe=="T" || $type_user_pour_classe=="E" || $_SESSION['type_util']=="D") { ?>
  $("#imprimer").button({ disabled: false });
  <?php } else { ?>
  $("#imprimer").button({ disabled: true });
  <?php } ?>

<?php if ($ok2==true) { ?>
  /* Création du tableau de données */
	var oTable_livrets=$('#listing_donnees_livrets').dataTable
  (
    {
      "bJQueryUI": true,
      "bProcessing": true,
      "aaSorting": [[ 1, "asc" ]],
      "bPaginate": false,
      "bLengthChange": false,
      "bInfo": false,
      "bSort": false,
      "sScrollX": "100%",
      "aoColumns": [ <?php echo substr($width,0,strlen($width)-1); ?> ],
      "oLanguage":
      {
        "sProcessing":   "<?php echo $Langue['LBL_TRAITEMENT']; ?>",
        "sLengthMenu": "<?php echo $Langue['LBL_ELEMENT_AFFICHES']; ?>",
        "sZeroRecords": "<?php echo $Langue['LBL_NO_DATA']; ?>",
        "sInfo": "<?php echo $Langue['LBL_ELEMENT_AFFICHES2']; ?>",
        "sInfoEmpty": "<?php echo $Langue['LBL_NO_DATA2']; ?>",
        "sInfoFiltered": "<?php echo $Langue['LBL_RESULT_RECHERCHE']; ?>",
        "sSearch": "<?php echo $Langue['LBL_RECHERCHER_DATA']; ?>",
        "oPaginate":
        {
          "sFirst":    "<?php echo $Langue['LBL_DEBUT']; ?>",
          "sPrevious": "<?php echo $Langue['LBL_PRECEDENT']; ?>",
          "sNext":     "<?php echo $Langue['LBL_SUIVANT']; ?>",
          "sLast":     "<?php echo $Langue['LBL_FIN']; ?>"
        }
      },
    }
  );
  <?php if ($Sens_Ecriture=="ltr") { ?>
		new FixedColumns( oTable_livrets, { "iLeftColumns": 3,"sHeightMatch": "auto" } );
  <?php } ?>
  <?php } ?>

<?php } ?>
	$("#imprimer").click(function()
  {
    Charge_Dialog("index2.php?module=livrets&action=detailview_imprimer","<?php echo $Langue['LBL_IMPRESSION']; ?>");
	});

	$("#liste_competences").click(function()
  {
    Charge_Dialog("index2.php?module=livrets&action=liste_competences","<?php echo $Langue['LBL_LISTE_COMPETENCES']; ?>");
	});

	$("#ajouter_controle").click(function()
  {
    Charge_Dialog("index2.php?module=livrets&action=editview","<?php echo $Langue['LBL_SAISIE_EVALUATION']; ?>");
	});

  /* Choix du niveau en cours */
	$("#niveaux_s").change(function()
  {
     Message_Chargement(1,1);
     var url="users/change_niveau.php";
     var data="niveau_choisi="+$("#niveaux_s").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
	});

  /* Choix de la classe en cours */
<?php if ($_SESSION['type_util']=="D") { ?>
  /* Choix de l'année en cours */
	$("#annee_s").change(function()
  {
     Message_Chargement(1,1);
     var url="users/change_annee.php";
     var data="annee_choisi="+$("#annee_s").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
	});

	$("#classes_s").change(function()
  {
     Message_Chargement(1,1);
     var url="users/change_classe.php";
     var data="classe_choisie="+$("#classes_s").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
	});
<?php } else { ?>
	$("#classes_s").change(function()
  {
    if ($("#classes_s").val()!="")
    {
      Message_Chargement(1,1);
      var url="users/change_classe.php";
      var data="classe_choisie="+$("#classes_s").val();
      var request = $.ajax({type: "POST", url: url, data: data});
      request.done(function(msg)
      {
        $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
        $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
      });
    }
	});
<?php } ?>

  /* Choix du trimestre ou de la période */
  $("#trimestre_s").change(function()
  {
     Message_Chargement(1,1);
     var url="users/change_trimestre.php";
     var data="trimestre_choisi="+$("#trimestre_s").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function(msg)
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
       $("#tabs").tabs({ load: function() { Message_Chargement(1,0); } });
     });
	});

<?php if ($plus_modifiable==true) { ?>
    $("#ajouter_controle").button({ disabled: true });
<?php } ?>
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

});

function Livrets_L_Voir_Tooltil(date,matiere,descriptif,coefficient)
{
  msg="<p class=explic><u><?php echo $Langue['LBL_TOOLTIP_DESCRIPTIF_EVALUATION']; ?></u> : "+descriptif+"</p>";
  msg=msg+"<p class=explic><u><?php echo $Langue['LBL_TOOLTIP_DATE_EVALUATION']; ?></u> : "+date+"</p>";
  msg = msg+"<p class=explic><u><?php echo $Langue['LBL_TOOLTIP_MATIERE_EVALUATION']; ?></u> : "+matiere+"</p>";
<?php if ($gestclasse_config_plus['calcul_moyenne']=="C") { ?>
  msg = msg+"<p class=explic><u><?php echo $Langue['LBL_COEFFICIENT']; ?></u> : "+coefficient+"</p>";
<?php } ?>
  $(".someClass").tipTip(
  {
    defaultPosition:'top',
		direction:'<?php echo $Sens_Ecriture; ?>',
		delay:100,
		maxWidth: 'auto',
		content:msg
  });
}

function Livrets_L_Change_Controle(id)
{
	Charge_Dialog("index2.php?module=livrets&action=detailview&id="+id,"<?php echo $Langue['LBL_FICHE_EVALUATION']; ?>");
}

function Livrets_L_Charge_Livret(id)
{
	Charge_Dialog("index2.php?module=livrets&action=detailview_ls&id_eleve="+id,"<?php echo $Langue['LBL_LIVRET_SCOLAIRE_ELEVE']; ?>");
}

function Livrets_L_Charge_Eleve(id)
{
	Charge_Dialog("index2.php?module=eleves&action=detailview&id="+id,"<?php echo $Langue['LBL_FICHE_ELEVE']; ?>");
}
</script>
