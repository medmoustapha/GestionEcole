<?php
  if (!isset($_SESSION['tableau_coop_journal_classe']))
  {
	// 0 : Longueur		1 : Page		2 : Colonne ordonnée		3 : Sens ordonnancement
    $_SESSION['tableau_coop_journal_classe']="30|0|0|asc";
  }
  
  if (!isset($_SESSION['recherche_repartition']))
  {
		$_SESSION['recherche_repartition']='|||||';
  }

  $tableau_personnalisation=explode("|",$_SESSION['tableau_coop_journal_classe']);
  $tableau_recherche2=explode("|",$_SESSION['recherche_repartition']);
  
	$tableau_recherche['repartition']['date']['valeur_recherche']=$tableau_recherche2[0];
	$tableau_recherche['repartition']['libelle']['valeur_recherche']=$tableau_recherche2[1];
	$tableau_recherche['repartition']['ligne_comptable']['valeur_recherche']=$tableau_recherche2[2];
	$tableau_recherche['repartition']['tiers']['valeur_recherche']=$tableau_recherche2[3];
	$tableau_recherche['repartition']['banque']['valeur_recherche']=$tableau_recherche2[4];
	$tableau_recherche['repartition']['mode']['valeur_recherche']=$tableau_recherche2[5];
	
  $req_coop=mysql_query("SELECT * FROM `etablissement".$_SESSION['cooperative_scolaire']."` WHERE parametre='cooperative_repartition'");
  $gestclasse_config_plus['cooperative_repartition']=mysql_result($req_coop,0,'valeur');

  $req_bilan=mysql_query("SELECT * FROM `cooperative_bilan` WHERE annee='".$_SESSION['cooperative_scolaire']."'");
  $clos=mysql_result($req_bilan,0,'clos');

  foreach ($tableau_variable['cooperative'] AS $cle)
  {
    $tableau_variable['cooperative'][$cle['nom']]['value'] = "";
  }
  $tableau_variable['cooperative']['ligne_comptable']['nomliste'] = 'cooperative_ligne_plus';
  
  $msg='<select class="text ui-widget-content ui-corner-all" id="cooperative_scolaire" name="cooperative_scolaire">';
  for ($i=2010;$i<=$_SESSION['cooperative_scolaire']+2;$i++)
  {
    if (table_ok($gestclasse_config['param_connexion']['base'],"cooperative".$i)==true)
    {
			$msg=$msg.'<option value="'.$i.'"';
			if ($i==$_SESSION['cooperative_scolaire']) { $msg=$msg.' SELECTED'; }
			$j=$i+1;
			$msg=$msg.'>'.$i.'-'.$j.'</option>';
		}
  }
  $msg=$msg.'</select>';

  $msg2='<select class="text ui-widget-content ui-corner-all" id="cooperative_visuel" name="cooperative_visuel">';
  $msg2=$msg2.'<option value="journal_general"';
  if ($_SESSION['cooperative_visuel']=='journal_general') { $msg2=$msg2."SELECTED"; } 
  $msg2=$msg2.'>'.$Langue['LBL_JOURNAL'].'</option>';
  if ($gestclasse_config_plus['cooperative_repartition']=="E")
  {
    $msg2=$msg2.'<option value="journal_general_classe"';
    if ($_SESSION['cooperative_visuel']=='journal_general_classe') { $msg2=$msg2."SELECTED"; } 
    $msg2=$msg2.'>'.$Langue['LBL_JOURNAL_CLASSE'].'</option>';
  }
  $msg2=$msg2.'<option value="grand_livre"';
  if ($_SESSION['cooperative_visuel']=='grand_livre') { $msg2=$msg2."SELECTED"; } 
  $msg2=$msg2.'>'.$Langue['LBL_GRAND_LIVRE'].'</option>';
  $msg2=$msg2.'<option value="balance"';
  if ($_SESSION['cooperative_visuel']=='balance') { $msg2=$msg2."SELECTED"; } 
  $msg2=$msg2.'>'.$Langue['LBL_BALANCE'].'</option>';
  $msg2=$msg2.'</select>';

  $msg_tiers='<select tabindex=104 name="recherche_tiers" class="text ui-widget-content ui-corner-all" id="recherche_tiers">';
  $msg_tiers=$msg_tiers.'<option value=""></option>';
  $msg_tiers=$msg_tiers.'<option value="401"';
	if ($tableau_recherche2[3]=="401") { $msg_tiers=$msg_tiers.' SELECTED'; }
	$msg_tiers=$msg_tiers.'>'.$Langue['LBL_RECHERCHE_TOUS_FOURNISSEURS'].'</option>';
  $req=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id LIKE '401-%' ORDER BY nom ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
		$msg_tiers=$msg_tiers.'<option value="'.mysql_result($req,$i-1,'id').'"';
		if ($tableau_recherche2[3]==mysql_result($req,$i-1,'id')) { $msg_tiers=$msg_tiers.' SELECTED'; }
		$msg_tiers=$msg_tiers.'>'.mysql_result($req,$i-1,'nom').'</option>';
  }
  $msg_tiers=$msg_tiers.'<option value="411"';
	if ($tableau_recherche2[3]=="411") { $msg_tiers=$msg_tiers.' SELECTED'; }
	$msg_tiers=$msg_tiers.'>'.$Langue['LBL_RECHERCHE_TOUS_CLIENTS'].'</option>';
  $req=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id LIKE '411-%' ORDER BY nom ASC");
  for ($i=1;$i<=mysql_num_rows($req);$i++)
  {
		$msg_tiers=$msg_tiers.'<option value="'.mysql_result($req,$i-1,'id').'"';
		if ($tableau_recherche2[3]==mysql_result($req,$i-1,'id')) { $msg_tiers=$msg_tiers.' SELECTED'; }
		$msg_tiers=$msg_tiers.'>'.mysql_result($req,$i-1,'nom').'</option>';
  }
  $msg_tiers=$msg_tiers.'<select>';

  echo '<div class="titre_page">'.$Langue['LBL_JOURNAL_CLASSE2'].'</div>
    <div class="aide"><button id="aide">'.$Langue['BTN_AIDE'].'</button></div>
		<table cellpadding=0 cellspacing=0 width=100% border=0 class="tableau_bouton">
		<tr>
			<td class="droite" nowrap>
			<div class="ui-widget ui-state-default ui-corner-right floatdroite ui-div-listview textcentre">'.$Langue['LBL_SAISIE_VISUEL'].' : '.$msg2.'</div>
			<div class="ui-widget ui-state-default ui-corner-left floatdroite ui-div-listview textcentre">'.$Langue['LBL_SAISIE_EXERCICE'].' : '.$msg.'</div>
			</td>
		</tr>
		<tr>
			<td class="gauche">
			<button name="ajouter_ticket" id="ajouter_ticket">'.$Langue['BTN_JOURNAL_SAISIR_OPERATION'].'</button>&nbsp;
			<button name="transfert_compte" id="transfert_compte">'.$Langue['BTN_JOURNAL_CAISSE_BANQUE'].'</button>&nbsp;
			<button name="rapprochement_bancaire" id="rapprochement_bancaire">'.$Langue['BTN_JOURNAL_RAPPROCHEMENT'].'</button>&nbsp;
			<button name="repartir_classes" id="repartir_classes">'.$Langue['BTN_JOURNAL_REPARTITION'].'</button>&nbsp;
			<button name="bilan_coop" id="bilan_coop">'.$Langue['LBL_BILAN_COMPTABLE'].'</button>&nbsp;
			<button id="Rechercher_Liste">'.$Langue['BTN_RECHERCHE_CIBLEE'].'</button>&nbsp;
			<button name="imprimer_doc" id="imprimer_doc">'.$Langue['BTN_IMPRIMER'].'</button>
			</td>
		</tr>
		</table>
		<div class="ui-widget marge10_bas" id="affiche_recherche_repartition" style="visibility:hidden;display:none;"><div class="contour_recherche ui-corner-all">
		<table cellpadding=0 cellspacing=0 width=100% border=0>
		<tr>
			<td class="textgauche" width=100%>
				<div class="titre_recherche">'.$Langue['LBL_RECHERCHE_CIBLEE'].'</div>
			</td>
		</tr>
		<tr>
			<td class="textgauche" width=100%>
					<div class="recherche"><label class="label_recherche">'.$Langue['LBL_SAISIE_DATE'].' :</label><label class="label_recherche_champ">'.Recherche_Cle($tableau_recherche['repartition']['date']).'</label></div>
					<div class="recherche"><label class="label_recherche">'.$Langue['LBL_SAISIE_TIERS'].' :</label><label class="label_recherche_champ">'.$msg_tiers.'</label></div>
					<div class="recherche"><label class="label_recherche">'.$Langue['LBL_SAISIE_LIBELLE'].' :</label><label class="label_recherche_champ">'.Recherche_Cle($tableau_recherche['repartition']['libelle']).'</label></div>
					<div class="recherche"><label class="label_recherche">'.$Langue['LBL_SAISIE_BANQUE'].' :</label><label class="label_recherche_champ">'.Recherche_Cle($tableau_recherche['repartition']['banque']).'</label></div>
					<div class="recherche"><label class="label_recherche">'.$Langue['LBL_SAISIE_LIGNE_COMPTABLE'].' :</label><label class="label_recherche_champ">'.Recherche_Cle($tableau_recherche['repartition']['ligne_comptable']).'</label></div>
					<div class="recherche"><label class="label_recherche">'.$Langue['LBL_SAISIE_MODE_PAIEMENT'].' :</label><label class="label_recherche_champ">'.Recherche_Cle($tableau_recherche['repartition']['mode']).'</label></div>
			</td>
		</tr>
		<tr>
			<td class="textdroite" width=100%>
				<button tabindex=108 id="vider_repartition" name="vider_repartition">'.$Langue['BTN_VIDER'].'</button>&nbsp;<button tabindex=107 id="rechercher_repartition" name="rechercher_repartition">'.$Langue['BTN_RECHERCHER'].'</button>
			</td>
		</tr>
		</table>
		</div></div>
		
		<table id="listing_donnees_journalc" class="display" cellpadding=0 cellspacing=0 style="width:100%">
		<thead>
		<tr>
		<th style="width:100px" class="centre">'.$Langue['LBL_SAISIE_DATE'].'</th>
		<th style="width:350px" class="centre">'.$Langue['LBL_SAISIE_LIBELLE'].'</th>
		<th class="centre">&nbsp;</th>
		<th class="centre">&nbsp;</th>
		<th class="centre">&nbsp;</th>
		<th style="width:200px" class="centre">'.$Langue['LBL_JOURNAL_CLASSE_MODE'].'</th>
		<th class="centre">&nbsp;</th>
		<th class="centre">&nbsp;</th>
		<th style="width:30px" class="centre">'.$Langue['LBL_SAISIE_POINTE'].'</th>';
  $width='{ "sWidth": "100px", "bSortable": false },';
  $width=$width.'{ "sWidth": "350px", "bSortable": false },';
  $width=$width.'{ "bVisible": false },';
  $width=$width.'{ "bVisible": false },';
  $width=$width.'{ "bVisible": false },';
  $width=$width.'{ "sWidth": "200px", "bSortable": false },';
  $width=$width.'{ "bVisible": false },';
  $width=$width.'{ "bVisible": false },';
  $width=$width.'{ "sWidth": "30px", "bSortable": false },';
  
  $req_classe = mysql_query("SELECT * FROM `classes` WHERE annee='".$_SESSION['cooperative_scolaire']."' ORDER BY nom_classe ASC");
  for ($i=1;$i<=mysql_num_rows($req_classe);$i++)
  {
    echo '<th style="width:100px" class="centre">'.mysql_result($req_classe,$i-1,'nom_classe').'</th>';
		$total[mysql_result($req_classe,$i-1,'id')]=0;
    $width=$width.'{ "sWidth": "100px", "bSortable": false },';
  }
  echo '<th style="width:100px" class="centre">'.$Langue['LBL_SAISIE_POT_COMMUN2'].'</th></tr></thead><tbody>';
  $width=$width.'{ "sWidth": "100px", "bSortable": false }';
  $total['PC']=0;
  
  $total_pointe=0;
  $total_general=0;
  $req = mysql_query("SELECT * FROM `cooperative".$_SESSION['cooperative_scolaire']."` ORDER BY date ASC, ligne_comptable ASC");

  $nbr_lignage = mysql_num_rows($req);
  for ($i=1;$i<=$nbr_lignage;$i++)
  {
    foreach ($tableau_variable['cooperative'] AS $cle)
    {
      $tableau_variable['cooperative'][$cle['nom']]['value'] = mysql_result($req,$i-1,$cle['nom']);
    }
		if (substr($tableau_variable['cooperative']['ligne_comptable']['value'],0,1)!="5")
		{
			echo '<tr><td style="width:100px" class="centre">'.Variables_Affiche($tableau_variable['cooperative']['date']).'</td>';
			$typage=1;
			if (substr($tableau_variable['cooperative']['ligne_comptable']['value'],0,1)=="1") { $typage='3'; }
			if (substr($tableau_variable['cooperative']['ligne_comptable']['value'],0,1)=="I") { $typage='2'; }
			echo '<td style="width:350px" class="gauche"><a title="'.$Langue['SPAN_JOURNAL_VOIR_FICHE'].'" href=#null onClick="Coop_Journal_Classe_DetailView_Util(\''.mysql_result($req,$i-1,'id').'\',\''.$typage.'\')">';
			if (mysql_result($req,$i-1,'tiers')!="")
			{
				$req_liste=mysql_query("SELECT * FROM `cooperative_tiers` WHERE id='".mysql_result($req,$i-1,'tiers')."'");
				$msg=mysql_result($req,$i-1,'tiers').' - '.mysql_result($req_liste,0,'nom').'<br />';
			}
			else
			{
				$msg="";
			}
			$msg=$msg.$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'ligne_comptable')].'<br />';
			$msg=$msg.mysql_result($req,$i-1,'libelle');
			echo $msg;
			echo '</a></td>';
			echo '<td>'.mysql_result($req,$i-1,'libelle').'</td>';
			echo '<td>'.mysql_result($req,$i-1,'ligne_comptable').'</td>';
			echo '<td>'.mysql_result($req,$i-1,'tiers').'</td>';
			if ($tableau_variable['cooperative']['ligne_comptable']['value']!="I")
			{
				if (substr($tableau_variable['cooperative']['ligne_comptable']['value'],0,1)!="5")
				{
					echo '<td style="width:100px" class="centre">';
					echo $liste_choix['cooperative_mode'][mysql_result($req,$i-1,'mode')].'<br>'.$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'banque')];
					echo '</td>';
					echo '<td>'.mysql_result($req,$i-1,'banque').'</td><td>'.mysql_result($req,$i-1,'mode').'</td>';
				}
				else
				{
					echo '<td style="width:100px" class="centre">';
					echo $Langue['LBL_JOURNAL_MODE']."<br>".$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'ligne_comptable')].' > '.$liste_choix['cooperative_ligne_plus'][mysql_result($req,$i-1,'banque')];
					echo '</td>';
					echo '<td>'.mysql_result($req,$i-1,'banque').'</td><td>T</td>';
				}
			}
			else
			{
				echo "<td></td><td></td><td></td>";
			}
			if (mysql_result($req,$i-1,'pointe')=="0")
			{
				echo '<td style="width:30px" class="centre"><input type="checkbox" disabled></td>';
			}
			else
			{
				echo '<td style="width:30px" class="centre"><input type="checkbox" checked disabled></td>';
				if ($tableau_variable['cooperative']['ligne_comptable']['value']!="I") { $total_pointe=$total_pointe+mysql_result($req,$i-1,'montant'); }
			}

			for ($j=1;$j<=mysql_num_rows($req_classe);$j++)
			{
				if (mysql_result($req_classe,$j-1,'id')==mysql_result($req,$i-1,'id_classe'))
				{
					echo '<td style="width:100px" class="droite">'.number_format(mysql_result($req,$i-1,'montant'),2,',',' ').' €</td>';
					$total[mysql_result($req_classe,$j-1,'id')]=$total[mysql_result($req_classe,$j-1,'id')]+mysql_result($req,$i-1,'montant');
				}
				else
				{
					echo '<td style="width:100px" class="droite">&nbsp;</td>';
				}
			}
			if ($tableau_variable['cooperative']['ligne_comptable']['value']!="I") 
			{ 
				if (mysql_result($req,$i-1,'id_classe')=="")
				{
					echo '<td style="width:100px" class="droite">'.number_format(mysql_result($req,$i-1,'montant'),2,',',' ').' €</td>';
					$total['PC']=$total['PC']+mysql_result($req,$i-1,'montant');
				}
				else
				{
					echo '<td style="width:100px" class="droite">&nbsp;</td>';
				}
			}
			else
			{
				echo '<td style="width:100px" class="droite">'.number_format((-1)*mysql_result($req,$i-1,'montant'),2,',',' ').' €</td>';
				$total['PC']=$total['PC']-mysql_result($req,$i-1,'montant');
			}
			echo '</tr>';
			if ($tableau_variable['cooperative']['ligne_comptable']['value']!="I") { $total_general=$total_general+mysql_result($req,$i-1,'montant'); }
		}
  }
  echo '<tr><td style="width:100px" class="droite">&nbsp;</td><td style="width:300px" class="gauche"><b>'.$Langue['LBL_JOURNAL_CLASSE_TOTAL'].'</b></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td style="width:200px" class="droite">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td style="width:30px" class="droite">&nbsp;</td>';
  for ($j=1;$j<=mysql_num_rows($req_classe);$j++)
  {
    echo '<td style="width:100px" class="droite"><b>'.number_format($total[mysql_result($req_classe,$j-1,'id')],2,',',' ').' €</b></td>';
  }
  echo '<td style="width:100px" class="droite"><b>'.number_format($total['PC'],2,',',' ').' €</b></td>';
  echo '</tbody></table>';
  
?>
<table cellspacing=0 cellpadding=0 border=0 style="padding-top:10px" width=100%>
<tr>
  <td width=10%>&nbsp;</td>
  <td width=35%><div class="ui-widget ui-state-default ui-corner-all marge5_tout marge10_gauche marge10_droite font16 textcentre"><?php echo $Langue['LBL_JOURNAL_SOLDE_POINTE']; ?> : <?php echo number_format($total_pointe,2,',',' ')." &euro;"; ?></div></td>
  <td width=10%>&nbsp;</td>
  <td width=35%><div class="ui-widget ui-state-default ui-corner-all marge5_tout marge10_gauche marge10_droite font16 textcentre"><?php echo $Langue['LBL_JOURNAL_SOLDE_COURANT']; ?> : <?php echo number_format($total_general,2,',',' ')." &euro;"; ?></div></td>
  <td width=10%>&nbsp;</td>
</tr>
</table>
<script language="Javascript">
$(document).ready(function()
{
  $("#aide").button();
  $("#aide").click(function(event)
  {
		event.preventDefault();		
<?php if ($_SESSION["type_util"]=="D") { ?>
		window.open("http://www.doxconception.com/site/index.php/directeur-cooperative.html","Aide");
<?php } else { ?>
		window.open("http://www.doxconception.com/site/index.php/prof-cooperative.html","Aide");
<?php } ?>
  });

  $("#ajouter_ticket").button({<?php if ($clos=="1") { echo "disabled:true"; } else { echo "disabled:false"; } ?>});
  <?php if ($gestclasse_config_plus['cooperative_repartition']=="E") { ?>
  $("#repartir_classes").button({<?php if ($clos=="1") { echo "disabled:true"; } else { echo "disabled:false"; } ?>});
  <?php } ?>
  $("#bilan_coop").button();
  $("#imprimer_doc").button();
  $("#transfert_compte").button({<?php if ($clos=="1") { echo "disabled:true"; } else { echo "disabled:false"; } ?>});
  $("#rapprochement_bancaire").button({<?php if ($clos=="1") { echo "disabled:true"; } else { echo "disabled:false"; } ?>});
  $("#ajouter_ticket").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=editview","<?php echo $Langue['LBL_JOURNAL_SAISIR_DEPENSE']; ?>");
  });

  $("#repartir_classes").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=editview_repartition","<?php echo $Langue['LBL_JOURNAL_SAISIR_REPARTITION']; ?>");
  });

  $("#transfert_compte").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=editview_transfert","<?php echo $Langue['LBL_JOURNAL_SAISIR_TRANSFERT']; ?>");
  });

  $("#imprimer_doc").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=detailview_imprimer&id_a_imprimer=1","<?php echo $Langue['LBL_IMPRESSION']; ?>");
  });

  $("#bilan_coop").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=bilan_comptable&id_a_imprimer=3","<?php echo $Langue['LBL_BILAN_COMPTABLE']; ?>");
  });

  $("#rapprochement_bancaire").click(function()
  {
    Charge_Dialog("index2.php?module=cooperative&action=rapprochement","<?php echo $Langue['LBL_SAISIE_RAPPROCHEMENT_BANCAIRE']; ?>");
  });

  /* Création du tableau de données */
	longueur_tableau=<?php echo $tableau_personnalisation[0]; ?>;
	page_tableau=<?php echo $tableau_personnalisation[1]; ?>;
	colonne_tableau=<?php echo $tableau_personnalisation[2]; ?>;
	ordre_tableau="<?php echo $tableau_personnalisation[3]; ?>";
	
  oTable_repartition=$('#listing_donnees_journalc').dataTable
  (
    {
      "bJQueryUI": true,
      "sPaginationType": "full_numbers",
      "aaSorting": [[ <?php echo $tableau_personnalisation[2]; ?>, "<?php echo $tableau_personnalisation[3]; ?>" ]],
      "aLengthMenu": [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "<?php echo $Langue['LBL_TOUS']; ?>"]],
      "iDisplayLength": <?php echo $tableau_personnalisation[0]; ?>,
			"iDisplayStart": <?php echo $tableau_personnalisation[1]; ?>,
			"sDom": '<"H"lr>t<"F"ip>',
			"fnDrawCallback": function( oSettings ) 
			{
			  faire=false;
				colonne_index=oSettings.aaSorting[0][0];
				colonne_ordre=oSettings.aaSorting[0][1];
				if (longueur_tableau!=oSettings._iDisplayLength) { faire=true;longueur_tableau=oSettings._iDisplayLength; }
//				if (page_tableau!=oSettings._iDisplayStart) { faire=true;page_tableau=oSettings._iDisplayStart; }
				if (colonne_tableau!=colonne_index) { faire=true;colonne_tableau=colonne_index; }
				if (ordre_tableau!=colonne_ordre) { faire=true;ordre_tableau=colonne_ordre; }
				if (faire==true)
				{
					url = "index2.php";
					data = "module=users&action=save_perso&module_session=coop_journal_classe&page="+oSettings._iDisplayStart+"&longueur="+oSettings._iDisplayLength+"&colonne_index="+colonne_index+"&colonne_ordre="+colonne_ordre;
					var request = $.ajax({type: "POST", url: url, data: data});
				}
			},
      "bSort" : false,
      "sScrollX": "100%",
      "aoColumns": [ <?php echo $width; ?> ],
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

  $('#cooperative_scolaire').change(function()
  {
     Message_Chargement(1,1);
     var url="cooperative/change_annee.php";
     var data="annee_choisi="+$("#cooperative_scolaire").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function()
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
     });
  });

  $('#cooperative_visuel').change(function()
  {
     Message_Chargement(1,1);
     var url="cooperative/change_visuel.php";
     var data="visuel_choisi="+$("#cooperative_visuel").val();
     var request = $.ajax({type: "POST", url: url, data: data});
     request.done(function()
     {
       $("#tabs").tabs("load",$("#tabs").tabs('option', 'selected'));
     });
  });

  $("#Rechercher_Liste").button();
	$("#Rechercher_Liste").click(function()
	{
	  if (document.getElementById('affiche_recherche_repartition').style.visibility=="hidden")
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_CACHER_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_repartition').style.visibility="visible";
		  document.getElementById('affiche_recherche_repartition').style.display="block";
			$("#recherche_date").focus();
		}
		else
		{
			$("#Rechercher_Liste").button({ label: "<?php echo $Langue['BTN_RECHERCHE_CIBLEE']; ?>" });
		  document.getElementById('affiche_recherche_repartition').style.visibility="hidden";
		  document.getElementById('affiche_recherche_repartition').style.display="none";
		}
	});

  $("#rechercher_repartition").button();
	$("#rechercher_repartition").click(function()
	{
	  recherche="";
<?php
		foreach ($tableau_recherche['repartition'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
			  echo 'oTable_repartition.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
				echo 'recherche=recherche+$("#recherche_'.$cle['nom'].'").val()+"|";';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=repartition&recherche="+recherche;
		var request = $.ajax({type: "POST", url: url, data: data});
	});

  $("#vider_repartition").button();
	$("#vider_repartition").click(function()
	{
<?php
		foreach ($tableau_recherche['repartition'] AS $cle)
		{
			if (array_key_exists('recherche',$cle))
			{
				echo '$("#recherche_'.$cle['nom'].'").val("");';
				echo 'oTable_repartition.fnFilter( $("#recherche_'.$cle['nom'].'").val(), '.$cle['recherche'].');';
			}
		}
?>
		url = "index2.php";
		data = "module=users&action=save_perso3&module_session=repartition&recherche=||||||";
		var request = $.ajax({type: "POST", url: url, data: data});
	});

	$("#rechercher_repartition").click();
<?php if ($_SESSION['recherche_repartition']!='|||||') { echo '$("#Rechercher_Liste").click();'; } ?>
});

  function Coop_Journal_Classe_DetailView_Util(id,type)
  {
    switch (type)
		{
			case "1":
				Charge_Dialog("index2.php?module=cooperative&action=detailview&id="+id,"<?php echo $Langue['LBL_SAISIE_FICHE']; ?>");
				break;
			case "2":
				Charge_Dialog2("index2.php?module=cooperative&action=detailview_interne&id="+id,"<?php echo $Langue['LBL_SAISIE_FICHE_FONCTIONNEMENT_INTERNE']; ?>");
				break;
			case "3":
				Charge_Dialog("index2.php?module=cooperative&action=detailview_ouverture&id="+id,"<?php echo $Langue['LBL_SAISIE_FICHE_OUVERTURE']; ?>");
				break;
		}
  }

</script>
