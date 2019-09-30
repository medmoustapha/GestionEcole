<div class="titre_page"><?php echo $Langue['LBL_TITRE_ACCUEIL']; ?></div>
<div class="aide"><button id="personnaliser"><?php echo $Langue['BTN_AJOUTER_PANNEAU']; ?></button>&nbsp;<button id="aide"><?php echo $Langue['BTN_AIDE']; ?></button></div>

<table cellpadding=0 cellspacing=0 border=0 style="width:100%;" class="marge10_haut">
<tr>
  <td style="vertical-align:top;" class="marge10_droite">
	  <div class="ui-widget ui-widget-content ui-corner-all">
		<div class="ui-widget ui-widget-header ui-corner-all marge5_tout"><?php echo $Langue['LBL_PERSONNE_CONNECTEE']; ?></div>
		<p class="marge10_tout textcentre font14"><strong><?php echo $_SESSION['nom_util']; ?></strong></p>
		<p class="marge10_bas marge10_gauche marge10_droite textgauche"><?php echo $Langue['LBL_TYPE_CONNECTE']; ?> : <strong><?php echo $liste_choix['type_connecte'][$_SESSION['type_util']]; ?></strong>
	  </div>
	  <br />
	  <div id="mois_du_jour"></div>
<?php
  if ($_SESSION['type_util']!="E")
  {
    if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
		{
			$annee_a=date("Y");
		}
		else
		{
			if (date("n")<=$gestclasse_config_plus['mois_annee_scolaire']) { $annee_a=date("Y")-1; } else { $annee_a=date("Y"); }
		}
		
		$req=mysql_query("SELECT * FROM `etablissement".$annee_a."` WHERE parametre='cooperative_presente'");

		if (mysql_num_rows($req)!="")
		{
			if (mysql_result($req,0,'valeur')=="1")
			{
				$req=mysql_query("SELECT * FROM `etablissement".$annee_a."` WHERE parametre='cooperative_repartition'");
				if (mysql_result($req,0,'valeur')=="E")
				{
					$req52=mysql_query("SELECT classes.*,classes_profs.* FROM `classes`, `classes_profs` WHERE classes_profs.id_prof='".$_SESSION['id_util']."' AND classes.id=classes_profs.id_classe AND classes.annee='".$annee_a."' AND (classes_profs.type='T' OR classes_profs.type='E') ORDER BY classes_profs.type DESC");
					if (mysql_num_rows($req52)!="")
					{
						$id_classe=mysql_result($req52,0,'classes.id');
						$req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$annee_a."` WHERE ligne_comptable NOT LIKE '5%' AND id_classe='".$id_classe."'");
						$total=mysql_result($req,0,'total');
?>
						<br />
						<div class="ui-widget ui-widget-content ui-corner-all">
						<div class="ui-widget ui-widget-header ui-corner-all marge5_tout"><?php echo $Langue['LBL_COOPERATIVE_SCOLAIRE']; ?></div>
						<p class="marge10_haut marge10_gauche marge10_droite textcentre"><?php echo $Langue['LBL_COOPERATIVE_SOLDE_CLASSE']; ?></p>
						<p class="marge10_tout textcentre font14"><?php echo '<strong>'.number_format($total,2,',',' ').' &euro;</strong>'; ?></p>
						</div>
<?php
					}
				}
				else
				{
					$req=mysql_query("SELECT SUM(montant) AS total FROM `cooperative".$annee_a."` WHERE ligne_comptable NOT LIKE '5%'");
					$total=mysql_result($req,0,'total');
?>
					<br />
					<div class="ui-widget ui-widget-content ui-corner-all">
					<div class="ui-widget ui-widget-header ui-corner-all marge5_tout"><?php echo $Langue['LBL_COOPERATIVE_SCOLAIRE']; ?></div>
						<p class="marge10_haut marge10_gauche marge10_droite textcentre"><?php echo $Langue['LBL_COOPERATIVE_SOLDE_ECOLE']; ?></p>
						<p class="marge10_tout textcentre font14"><?php echo '<strong>'.number_format($total,2,',',' ').' &euro;</strong>'; ?></p>
					</div>
<?php
				}
			}
		}
  }
?>
  </td>
  <td style="vertical-align:top;width:100%">
	
<?php 
	$larg=$_SESSION['largeur_ecran']-310;

	$req_panneau_droite=mysql_query("SELECT * FROM `accueil_perso` WHERE id_util='".$_SESSION['id_util']."' AND colonne='2' ORDER BY ordre ASC");
	if (mysql_num_rows($req_panneau_droite)!="")
	{
	  $larg=$larg-310;
	}

	// Recherche s'il y a une mise à jour
	if ($_SESSION['type_util']=="D")
	{
	  require_once("commun/magpierss/rss_fetch.inc");
	  $rss2 = fetch_rss("http://www.doxconception.com/site/index.php?option=com_ninjarsssyndicator&feed_id=4&format=raw");
	  if (is_array($rss2->items))
	  {
			// on ne recupere que les elements les + recents
			$items2 = array_slice($rss2->items,0);
			foreach ($items2 as $item2)
			{
				$version=$item2['title'];
			}
	    $d_version=explode(".",$version);
			$d_version2=explode(".",$gestclasse_config_plus['version_de_l_application']);
			$affichage=false;
			if ($d_version[0]>$d_version2[0]) 
			{ 
				$affichage=true; 
			}
			else
			{
	  	  if ($d_version[0]==$d_version2[0])
				{
					if ($d_version[1]>$d_version2[1]) { $affichage=true; }
				}
			}
			if ($affichage==true)
			{
				echo '<div class="ui-widget" style="float:center;min-width:'.$larg.'px"><div class="ui-state-error ui-corner-all marge10_tout textcentre" style="margin-bottom: 10px;"><strong>'.$Langue['MSG_MAJ_DISPONIBLE'].'</strong></div></div>';
			}
	  }	
	}

	// Affichage des anniversaires
	include "subpanels/anniversaire.php"; 
?>
	<table cellpadding=0 cellspacing=0 border=0 style="width:100%">
	<tr>
		<td style="width:100%;vertical-align:top">
		<div class="column" id="colonne1" style="float:none;">
		<?php
		$req_panneau=mysql_query("SELECT * FROM `accueil_perso` WHERE id_util='".$_SESSION['id_util']."' AND colonne='1' ORDER BY ordre ASC");
		if (mysql_num_rows($req_panneau)=="")
		{
			$id=Construit_Id("accueil_perso",10);
			$req_panneau=mysql_query("INSERT INTO `accueil_perso` (id,id_util,subpanel,titre,ordre,colonne,parametre) VALUES ('$id','".$_SESSION['id_util']."','news','".$Langue['LBL_PANNEAU_NEWS']."','1','1','T|5')");
			$req_panneau=mysql_query("SELECT * FROM `accueil_perso` WHERE id_util='".$_SESSION['id_util']."' AND colonne='1' ORDER BY ordre ASC");
		}
		for ($i_panneau=1;$i_panneau<=mysql_num_rows($req_panneau);$i_panneau++)
		{
			$id_panneau=mysql_result($req_panneau,$i_panneau-1,'id');
			$titre=mysql_result($req_panneau,$i_panneau-1,'titre');
			$parametre=mysql_result($req_panneau,$i_panneau-1,'parametre');
			$param=explode('|',$parametre);
			include "subpanels/".mysql_result($req_panneau,$i_panneau-1,'subpanel').".php";
		}
		?>
		</div>
<?php
		if (mysql_num_rows($req_panneau_droite)!="")
		{
?>
	    </td>
	    <td style="vertical-align:top;" class="marge10_gauche">
		  <div class="column" id="colonne2" style="float:none;">
		  <?php
			  for ($i_panneau=1;$i_panneau<=mysql_num_rows($req_panneau_droite);$i_panneau++)
			  {
					$id_panneau=mysql_result($req_panneau_droite,$i_panneau-1,'id');
					$titre=mysql_result($req_panneau_droite,$i_panneau-1,'titre');
					$parametre=mysql_result($req_panneau_droite,$i_panneau-1,'parametre');
					$param=explode('|',$parametre);
					include "subpanels/".mysql_result($req_panneau_droite,$i_panneau-1,'subpanel').".php";
			  }
		  ?>
		  </div>
<?php
		}
?>
	    </td>
	  </tr>
	  </table>	
  </td>
</tr>
</table>

<script language="Javascript">
$(document).ready(function()
{
  $("#mois_du_jour").datepicker();
  
  $("#personnaliser").button();
  $("#personnaliser").click(function()
  {
    Charge_Dialog("index2.php?module=accueil&action=ajouter_panneau","<?php echo $Langue['LBL_PERSONNALISER_ACCUEIL']; ?>");
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
			window.open("http://www.doxconception.com/site/index.php/directeur-page-daccueil.html","Aide");
<?php
			break;
		case "P":
?>
			window.open("http://www.doxconception.com/site/index.php/prof-page-daccueil.html","Aide");
<?php
			break;
		case "E":
?>
			window.open("http://www.doxconception.com/site/index.php/parent-page-daccueil.html","Aide");
<?php
			break;
		}
?>
  });
  
  $( "#colonne1" ).sortable(
  {
		zIndex: 500,
		update: function()
		{
		  var ordre=$("#colonne1").sortable("serialize");
		  Message_Chargement(2,1);
		  var request = $.ajax({type: "POST", url: "index2.php", data: ordre+"&module=accueil&action=save_ordre_panneau"});
		  request.done(function()
		  {
				Message_Chargement(2,0);
		  });
		}
  });

	$( "#colonne1" ).disableSelection();

  $( "#colonne2" ).sortable(
  {
    zIndex: 500,
    update: function()
	  {
		  var ordre=$("#colonne2").sortable("serialize");
		  Message_Chargement(2,1);
		  var request = $.ajax({type: "POST", url: "index2.php", data: ordre+"&module=accueil&action=save_ordre_panneau2"});
		  request.done(function()
		  {
				Message_Chargement(2,0);
			});
		}
  });

//	$( "#colonne2" ).disableSelection();

  $( "#dialog:ui-dialog" ).dialog( "destroy" );

});

  function Ajouter_News()
  {
    Charge_Dialog("index2.php?module=accueil&action=editview","<?php echo $Langue['LBL_CREER_NEWS']; ?>");
	}
	
  function Ajouter_Tache()
  {
    Charge_Dialog("index2.php?module=accueil&action=editview_tache","<?php echo $Langue['LBL_CREER_TACHE']; ?>");
	}

  function Parametrer_Panneau(id)
  {
    Charge_Dialog2("index2.php?module=accueil&action=parametrer_panneau&id="+id,"<?php echo $Langue['LBL_PARAMETRER_PANNEAU']; ?>");
	}

  function Supprimer_Panneau(id)
  {
    $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_SUPPRIMER_PANNEAU']; ?></div>');

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_SUPPRIMER_PANNEAU']; ?>",
			resizable: false,
			draggable: false,
			height:200,
			width:450,
			modal: true,
			buttons:[
			{
				text: "<?php echo $Langue['BTN_SUPPRIMER']; ?>",
				click: function()
				{
					Message_Chargement(4,1);
					var request = $.ajax({type: "POST", url: "index2.php", data: "module=accueil&action=delete_panneau&id="+id});
					request.done(function(msg)
					{
						if (msg="radio")
						{
							parent.radio.location.href="vide.html";
						}
						$( "#dialog-confirm" ).dialog( "close" );
						$("#tabs").tabs("load",0);
						Message_Chargement(1,0);
					});
				}
			},
			{
				text: "<?php echo $Langue['BTN_ANNULER2']; ?>",
				click: function()
				{
					$( this ).dialog( "close" );
				}
			}]
	  });
  }

  function Modif_News(id)
  {
    Charge_Dialog("index2.php?module=accueil&action=editview&id="+id,"<?php echo $Langue['LBL_MODIFIER_NEWS']; ?>");
	}

  function Supprimer_News(id)
  {
    $( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_SUPPRIMER_NEWS']; ?></div>');

		$( "#dialog-confirm" ).dialog(
    {
      title: "<?php echo $Langue['LBL_SUPPRIMER_NEWS']; ?>",
			resizable: false,
			draggable: false,
			height:200,
			width:450,
			modal: true,
			buttons:[
			{
				text: "<?php echo $Langue['BTN_SUPPRIMER']; ?>",
				click: function()
				{
					Message_Chargement(4,1);
					var request = $.ajax({type: "POST", url: "index2.php", data: "module=accueil&action=delete_news&id="+id});
					request.done(function()
					{
						$( "#dialog-confirm" ).dialog( "close" );
						$("#tabs").tabs("load",0);
//              document.location.href="#haut_page";
						Message_Chargement(1,0);
					});
				}
			},
			{
				text: "<?php echo $Langue['BTN_ANNULER2']; ?>",
				click: function()
				{
					$( this ).dialog( "close" );
				}
			}]
	  });
  }
</script>
