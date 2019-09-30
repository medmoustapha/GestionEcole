<a name="haut_formulaire"></a>
<div class="textgauche"><button id="ajouter_competence"><?php echo $Langue['LBL_COMPETENCES_AJOUTER_COMPETENCE']; ?></button>&nbsp;<button id="ajouter_categorie"><?php echo $Langue['LBL_COMPETENCES_AJOUTER_CATEGORIE']; ?></button></div>
<div id="msg_ok"></div>
<div class="ui-widget"><div class="ui-state-highlight ui-corner-all margin10_haut marge10_tout"><?php echo $Langue['EXPL_COMPETENCES_CREATION']; ?><br /><?php echo $Langue['EXPL_COMPETENCES_CREATION2']; ?></div></div><br />
<?php
  if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
  {
	  $annee_debut=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $annee_fin=$_SESSION['annee_scolaire'].$gestclasse_config_plus['fin_annee_scolaire'];
  }
  else
  {
	  $annee_debut=$_SESSION['annee_scolaire'].$gestclasse_config_plus['debut_annee_scolaire'];
	  $a=$_SESSION['annee_scolaire']+1;
	  $annee_fin=$a.$gestclasse_config_plus['fin_annee_scolaire'];
  }

  $req_categorie=mysql_query("SELECT * FROM `competences_categories` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin')");
  $nb_categorie=mysql_num_rows($req_categorie);
  $ligne="ligne1";
  /* Affichage des catégories principales */
  $msg="";
  $req_categorie=mysql_query("SELECT * FROM `competences_categories` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_parent='' ORDER BY ordre ASC");
  echo '<div class="ListeCategorie">';
  for ($i=1;$i<=mysql_num_rows($req_categorie);$i++)
  {
    if ($ligne=="ligne2") { $ligne="ligne1"; } else { $ligne="ligne2"; }
    echo '<div id="ListeCategorie_'.$i.'" class="textgauche"><div class="'.$ligne.'">&nbsp;<strong>'.Convertir_En_Majuscule(mysql_result($req_categorie,$i-1,'titre')).'</strong>';
    echo '<div class="floatdroite"><a title="'.$Langue['SPAN_MODIFIER_CATEGORIE'].'" href="#null" onClick="Livrets_Comp_Changer_Categorie(\''.mysql_result($req_categorie,$i-1,'id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_SUPPRIMER_CATEGORIE'].'" href="#null" onClick="Livrets_Comp_Supprimer_Categorie(\''.mysql_result($req_categorie,$i-1,'id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a>&nbsp;</div></div>';
    /* Affichage des compétences de la catégorie */
    $id_parent=mysql_result($req_categorie,$i-1,'id');
    $req_competence=mysql_query("SELECT * FROM `competences` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_cat='$id_parent' ORDER BY ordre ASC");
    echo '<div class="ListeCompetence'.$id_parent.'">';
    for ($j=1;$j<=mysql_num_rows($req_competence);$j++)
    {
      if ($ligne=="ligne2") { $ligne="ligne1"; } else { $ligne="ligne2"; }
      echo '<div id="ListeCompetence'.$id_parent.'_'.$j.'" class="textgauche"><div class="'.$ligne.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/absence_vide.png" width=9 height=12 border=0>&nbsp;'.mysql_result($req_competence,$j-1,'code')." - ".mysql_result($req_competence,$j-1,'intitule');
      echo '<div class="floatdroite"><a title="'.$Langue['SPAN_MODIFIER_COMPETENCE'].'" href="#null" onClick="Livrets_Comp_Changer_Competence(\''.mysql_result($req_competence,$j-1,'id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_SUPPRIMER_COMPETENCE'].'" href="#null" onClick="Livrets_Comp_Supprimer_Competence(\''.mysql_result($req_competence,$j-1,'id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a>&nbsp;</div></div></div>';
    }
    echo '</div>';

    /* Affichage des catégories secondaires */
    echo '<div class="ListeCategorie'.$id_parent.'">';
    $req_categorie2=mysql_query("SELECT * FROM `competences_categories` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_parent='$id_parent' ORDER BY ordre ASC");
    for ($j=1;$j<=mysql_num_rows($req_categorie2);$j++)
    {
      if ($ligne=="ligne2") { $ligne="ligne1"; } else { $ligne="ligne2"; }
      echo '<div id="ListeCategorie'.$id_parent.'_'.$j.'" class="textgauche"><div class="'.$ligne.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/liaison.gif" width=9 height=12 border=0>&nbsp;<strong>'.mysql_result($req_categorie2,$j-1,'titre').'</strong>';
      echo '<div class="floatdroite"><a title="'.$Langue['SPAN_MODIFIER_CATEGORIE'].'" href="#null" onClick="Livrets_Comp_Changer_Categorie(\''.mysql_result($req_categorie2,$j-1,'id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_SUPPRIMER_CATEGORIE'].'" href="#null" onClick="Livrets_Comp_Supprimer_Categorie(\''.mysql_result($req_categorie2,$j-1,'id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a>&nbsp;</div></div>';
      /* Affichage des compétences de la catégorie */
      $id_parent2=mysql_result($req_categorie2,$j-1,'id');
      $req_competence=mysql_query("SELECT * FROM `competences` WHERE id_prof='".$_SESSION['titulaire_classe_cours']."' AND id_niveau='".$_SESSION['niveau_en_cours']."' AND cree<='$annee_fin' AND (supprime='0000-00-00' OR supprime>='$annee_fin') AND id_cat='".$id_parent2."' ORDER BY ordre ASC");
      echo '<div class="ListeCompetence'.$id_parent2.'">';
      for ($k=1;$k<=mysql_num_rows($req_competence);$k++)
      {
        if ($ligne=="ligne2") { $ligne="ligne1"; } else { $ligne="ligne2"; }
        echo '<div id="ListeCompetence'.$id_parent2.'_'.$k.'" class="textgauche"><div class="'.$ligne.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/absence_vide.png" width=18 height=12 border=0>&nbsp;'.mysql_result($req_competence,$k-1,'code')." - ".mysql_result($req_competence,$k-1,'intitule');
        echo '<div class="floatdroite"><a title="'.$Langue['SPAN_MODIFIER_COMPETENCE'].'" href="#null" onClick="Livrets_Comp_Changer_Competence(\''.mysql_result($req_competence,$k-1,'id').'\')"><img src="images/editer.png" width=12 height=12 border=0></a>&nbsp;<a title="'.$Langue['SPAN_SUPPRIMER_COMPETENCE'].'" href="#null" onClick="Livrets_Comp_Supprimer_Competence(\''.mysql_result($req_competence,$k-1,'id').'\')"><img src="images/supprimer.png" width=12 height=12 border=0></a>&nbsp;</div></div></div>';
      }
      echo '</div>';
      $msg .='$(".ListeCompetence'.$id_parent2.'").sortable({
        zIndex: 500,
        update: function()
        {
          var ordre=$(".ListeCompetence'.$id_parent2.'").sortable("serialize");
          Message_Chargement(2,1);
          var request = $.ajax({type: "POST", url: "index2.php", data: ordre+"&module=livrets&action=save_ordre_competences&id_cat='.$id_parent2.'"});
          request.done(function()
          {
             Message_Chargement(1,1);
             Charge_Dialog("index2.php?module=livrets&action=liste_competences","'.$Langue['LBL_COMPETENCES_LISTE_COMPETENCES'].'");
    		  });
        }
      });
  	  $(".ListeCompetence'.$id_parent2.'").disableSelection();';

      echo '</div>';
    }
    echo '</div>';
    $msg .='$(".ListeCompetence'.$id_parent.'").sortable({
      zIndex: 500,
      update: function()
      {
        var ordre=$(".ListeCompetence'.$id_parent.'").sortable("serialize");
        Message_Chargement(2,1);
        var request = $.ajax({type: "POST", url: "index2.php", data: ordre+"&module=livrets&action=save_ordre_competences&id_cat='.$id_parent.'"});
        request.done(function()
        {
           Message_Chargement(1,1);
           Charge_Dialog("index2.php?module=livrets&action=liste_competences","'.$Langue['LBL_COMPETENCES_LISTE_COMPETENCES'].'");
  		  });
      }
    });
	  $(".ListeCompetence'.$id_parent.'").disableSelection();';
    echo '</div>';
    $msg .='$(".ListeCategorie'.$id_parent.'").sortable({
        zIndex: 500,
        update: function()
        {
          var ordre=$(".ListeCategorie'.$id_parent.'").sortable("serialize");
          Message_Chargement(2,1);
          var request = $.ajax({type: "POST", url: "index2.php", data: ordre+"&module=livrets&action=save_ordre_categories&id_cat='.$id_parent.'"});
          request.done(function()
          {
             Message_Chargement(1,1);
             Charge_Dialog("index2.php?module=livrets&action=liste_competences","'.$Langue['LBL_COMPETENCES_LISTE_COMPETENCES'].'");
  		    });
        }
      });
	    $(".ListeCategorie'.$id_parent.'").disableSelection();';
  }
  echo '</div>';
  $msg .='$(".ListeCategorie").sortable({
      zIndex: 500,
      update: function()
      {
        var ordre=$(".ListeCategorie").sortable("serialize");
        Message_Chargement(2,1);
        var request = $.ajax({type: "POST", url: "index2.php", data: ordre+"&module=livrets&action=save_ordre_categories&id_cat="});
        request.done(function()
        {
           Message_Chargement(1,1);
           Charge_Dialog("index2.php?module=livrets&action=liste_competences","'.$Langue['LBL_COMPETENCES_LISTE_COMPETENCES'].'");
  		  });
      }
    });
	  $(".ListeCategorie").disableSelection();';
?>
<div class="textgauche"><button id="ajouter_competence2"><?php echo $Langue['LBL_COMPETENCES_AJOUTER_COMPETENCE']; ?></button>&nbsp;<button id="ajouter_categorie2"><?php echo $Langue['LBL_COMPETENCES_AJOUTER_CATEGORIE']; ?></button></div>

<script language="Javascript">
$(document).ready(function()
{
  $("#ajouter_categorie").button();
  $("#ajouter_categorie2").button();
  <?php if ($nb_categorie=="") { ?>
    $("#ajouter_competence").button({disabled:true});
    $("#ajouter_competence2").button({disabled:true});
  <?php } else { ?>
    $("#ajouter_competence").button({disabled:false});
    $("#ajouter_competence2").button({disabled:false});
  <?php } ?>
  $("#ajouter_categorie").click(function()
  {
    Charge_Dialog2("index2.php?module=livrets&action=editview_categorie","<?php echo $Langue['LBL_COMPETENCES_AJOUTER_CATEGORIE']; ?>");
  });
  $("#ajouter_competence").click(function()
  {
    Charge_Dialog2("index2.php?module=livrets&action=editview_competence","<?php echo $Langue['LBL_COMPETENCES_AJOUTER_COMPETENCE']; ?>");
  });
  $("#ajouter_categorie2").click(function()
  {
    Charge_Dialog2("index2.php?module=livrets&action=editview_categorie","<?php echo $Langue['LBL_COMPETENCES_AJOUTER_CATEGORIE']; ?>");
  });
  $("#ajouter_competence2").click(function()
  {
    Charge_Dialog2("index2.php?module=livrets&action=editview_competence","<?php echo $Langue['LBL_COMPETENCES_AJOUTER_COMPETENCE']; ?>");
  });

  <?php echo $msg; ?>
});

function Livrets_Comp_Changer_Categorie(id)
{
  Charge_Dialog2("index2.php?module=livrets&action=editview_categorie&id="+id,"<?php echo $Langue['LBL_COMPETENCES_MODIFIER_CATEGORIE']; ?>");
}

function Livrets_Comp_Changer_Competence(id)
{
  Charge_Dialog2("index2.php?module=livrets&action=editview_competence&id="+id,"<?php echo $Langue['LBL_COMPETENCES_MODIFIER_COMPETENCE']; ?>");
}

function Livrets_Comp_Supprimer_Categorie(id)
{
	$( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_SUPPRIMER_CATEGORIE']; ?></div><div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_SUPPRIMER_CATEGORIE2']; ?></strong></div></div>');
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog(
	{
		title: "<?php echo $Langue['LBL_COMPETENCES_SUPPRIMER_CATEGORIE']; ?>",
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
				var request = $.ajax({type: "POST", url: "index2.php", data: "module=livrets&action=delete_categorie&id="+id});
				request.done(function(msg)
				{
					$( "#dialog-confirm" ).dialog( "close" );
					Message_Chargement(4,0);
					if (msg=="ok")
					{
						Message_Chargement(1,1);
						Charge_Dialog("index2.php?module=livrets&action=liste_competences","<?php echo $Langue['LBL_COMPETENCES_LISTE_COMPETENCES']; ?>");
					}
					else
					{
						$("#dialog-form").scrollTop(0);
						$("#msg_ok").fadeIn( 1000 );
						$("#msg_ok").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_SUPPRIMER_CATEGORIE_IMPOSSIBLE']; ?></strong></div></div>');
						setTimeout(function()
						{
							$("#msg_ok").effect("blind",1000);
						}, 3000 );
					}
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

function Livrets_Comp_Supprimer_Competence(id)
{
	$( "#dialog-confirm" ).html('<div class="textgauche" style="line-height:24px;"><?php echo $Langue['MSG_SUPPRIMER_COMPETENCE']; ?></div><div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_SUPPRIMER_COMPETENCE2']; ?></strong></div></div>');
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog(
	{
		title: "<?php echo $Langue['LBL_COMPETENCES_SUPPRIMER_COMPETENCE']; ?>",
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
				var request = $.ajax({type: "POST", url: "index2.php", data: "module=livrets&action=delete_competence&id="+id});
				request.done(function(msg)
				{
					$( "#dialog-confirm" ).dialog( "close" );
					Message_Chargement(4,0);
					if (msg=="ok")
					{
						Message_Chargement(1,1);
						Charge_Dialog("index2.php?module=livrets&action=liste_competences","<?php echo $Langue['LBL_COMPETENCES_LISTE_COMPETENCES']; ?>");
					}
					else
					{
						$("#dialog-form").scrollTop(0);
						$("#msg_ok").fadeIn( 1000 );
						$("#msg_ok").html('<div class="ui-widget"><div class="ui-state-error ui-corner-all margin10_haut marge10_tout"><strong><?php echo $Langue['MSG_SUPPRIMER_COMPETENCE_IMPOSSIBLE']; ?></strong></div></div>');
						setTimeout(function()
						{
							$("#msg_ok").effect("blind",1000);
						}, 3000 );
					}
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
