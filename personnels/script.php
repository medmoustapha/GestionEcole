<?php
  session_start();
  include "../config.php";

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
  
  include "../commun/fonctions.php";
  include "../commun/phplib/php/template.inc";

  Connexion_DB();
	
  include "parametres_variables.php";

  $tableau_personnalisation=explode("|",$_SESSION['tableau_personnels']);
  $tableau_recherche2=explode("|",$_SESSION['recherche_personnels']);
  
// Récupération des informations
  foreach ($tableau_variable['personnels'] AS $cle)
  {
    $tableau_variable['personnels'][$cle['nom']]['value'] = "";
  }

  if ($_SESSION['affiche_presents']=="0")
  {
    $req = mysql_query("SELECT * FROM `profs` ORDER BY nom ASC, prenom ASC");
    $tpl->set_var("PRESENTS",'<input type="checkbox" id="affiche_presents" name="affiche_presents" value="1"> '.$Langue['LBL_UNIQUEMENT_PRESENTS']);
  }
  else
  {
    $req = mysql_query("SELECT * FROM `profs` WHERE date_sortie='0000-00-00' OR date_sortie>='".date("Y-m-d")."' ORDER BY nom ASC, prenom ASC");
    $tpl->set_var("PRESENTS",'<input type="checkbox" id="affiche_presents" name="affiche_presents" checked value="0"> '.$Langue['LBL_UNIQUEMENT_PRESENTS']);
  }

	$aColumns = array( 'id', 'nom', 'type', 'date_naissance', 'date_entree', 'date_sortie', 'date_entree_en', 'date_derniere_inspection', 'echelon', 'tel', 'tel2', 'portable', 'email', 'identifiant' );
	$sIndexColumn = "id";
	$sTable = "profs";
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
	}
	$sOrder = "";
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
				 	mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	$sWhere = "";
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}	
$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";
	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(`".$sIndexColumn."`)
		FROM   $sTable
	";
	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( $aColumns[$i] == "version" )
			{
				/* Special output formatting for 'version' column */
				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
			}
			else if ( $aColumns[$i] != ' ' )
			{
				/* General output */
				$row[] = $aRow[ $aColumns[$i] ];
			}
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>
