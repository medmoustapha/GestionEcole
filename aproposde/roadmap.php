<table cellpadding=0 cellspacing=0 border=0 style="width:100%">
<tr>
  <td class="textgauche" style="width:50%;"><div class="titre_page"><?php echo $Langue['LBL_PLAN_DEVELOPPEMENT']; ?></div></td>
  <td class="textdroite" style="width:50%;"><button id="retour"><?php echo $Langue['BTN_RETOUR']; ?></button></td>
</tr>
</table>
<br />
<div class="ui-widget"><div class="ui-state-highlight ui-corner-all marge10_tout textcentre"><strong><?php echo $Langue['LBL_IDEES_APPLICATION']; ?> : <a href="http://www.doxconception.com" target=_new>http://www.doxconception.com</a></strong></div></div>
<br />
<div style="direction:ltr;">
<div class="ui-widget ui-widget-header ui-corner-all ui-div-separation-form marge10_tout" style="text-align:left !important"><u>Version en cours</u> : Gest'Ecole version 2.1 - Sortie le 01/12/2012</div>
<p class=explic align=center><b>La version 2 est accès sur l'amélioration générale de l'application, notamment en fonction des remarques des utilisateurs.</b></p>
<ul class=explic>
  <li><b>Ouverture de l'application</b></li>
  <ul class=explic>
    <li>Traduction de la procédure d'installation.</li>
  </ul>
  <li><b>Amélioration de l'application</b></li>
  <ul class=explic>
    <li>Amélioration du temps de chargement des pages.</li>
    <li>Ajout d'un formulaire de recherche dans les vues en liste.</li>
    <li>Possibilité de personnaliser les vues en liste en choisissant les colonnes à afficher.</li>
    <li>Ajout d'une colonne de numérotation des lignes dans les vues en liste.</li>
    <li>Ajout d'une option de numérotation des pages lors des impressions.</li>
		<li>Procédure de redéfinition de mot de passe en cas de perte.</li>
    <li>Définition des tabindex dans les formulaires de saisie afin que les passages par tabulation d'un champ à un autre se fassent dans un ordre logique (et non comme actuellement où le passage par tabulation se fait d'un champ au suivant dans l'affichage).</li>
  </ul>
  <li><b>Amélioration des modules existants</b></li>
  <ul class=explic>
    <li><u>Page d'accueil</u> : Ajout de panneaux dans la colonne de droite : Calcul d'itinéraires, Calculette, Ecoute Radio.</li>
    <li><u>Personnels</u> : Ajout d'une liste de choix en haut et en bas de la fiche d'un personnel pour faciliter la navigation entre les fiches du personnel.</li>
    <li><u>Classes</u> : Ajout d'une liste de choix en haut et en bas de la fiche d'une classe pour faciliter la navigation entre les fiches des classes.</li>
    <li><u>Elèves</u> : Ajout d'une liste de choix en haut et en bas de la fiche d'un élève pour faciliter la navigation entre les fiches des élèves.</li>
    <li><u>Registres d'appel</u> : Ajout d'un onglet Absences pour les parents leur permettant de voir les absences justifiées et celles non justifiées.</li>
    <li><u>Livrets scolaires</u> : Ajout d'une liste de choix en haut et en bas des livrets scolaires pour faciliter la navigation entre les livrets.</li>
    <li><u>Bibliothèque</u> :</li>
	  <ul class=explic>
        <li>Impression de la fiche d'un livre et de la liste des livres.</li>
        <li>Possibilité de réserver un livre.</li>
	  </ul>
    <li><u>Messagerie</u> :</li>
	  <ul class=explic>
				<li>Ajout d'une liste de choix en haut et en bas de la fiche d'un message pour faciliter la navigation entre les messages.</li>
        <li>Possibilité d'ajouter des pièces jointes dans un message.</li>
        <li>Impression d'un message.</li>
	  </ul>
    <li><u>Agenda</u> : Impression d'une réunion / d'un rendez-vous, impression de l'agenda journalier, mensuel ou annuel.</li>
  </ul>
  <li><b>Maintenance de l'application</b></li>
  <ul class=explic>
    <li>Mise à jour des différentes librairies, plug-ins et classes PHP utilisées dans Gest'Ecole.</li>
  </ul>
  <li><b>Correction des bugs signalés par les utilisateurs.</b></li>
</ul>

<p class="explic">&nbsp;</p>
<p class="explic">&nbsp;</p>

<div class="ui-widget ui-widget-header ui-corner-all ui-div-separation-form marge10_tout" style="text-align:left !important">Prochaines versions</div>

<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form" style="text-align:left !important">Gest'Ecole version 2.2 - Sortie prévue le 02/03/2013</div>
<ul class=explic>
    <li><b>Amélioration de l'application</b></li>
		<ul class=explic>
			<li>Amélioration du temps de chargement des pages par l'utilisation des requêtes AJAX en cache (poursuite des développements de la version 2.1).</li>
			<li>Préparation au passage à la version 3.0 et à l'approche modulaire de l'application.</li>
		</ul>
		<li><b>Personnalisation de l'application</b></li>
		<ul class=explic>
		  <li>Possibilité de personnaliser les libellés et le nom des onglets.</li>
			<li>Possibilité de personnaliser l'ordre des onglets.</li>
		</ul>
		<li><b>Amélioration des modules existants</b></li>
		<ul class=explic>
			<li><u>Personnels</u> : Ajout de fonction d'import et d'export de données.</li>
			<li><u>Elèves</u></li>
			<ul class=explic>
				<li>Ajout de fonction d'import et d'export de données.</li>
				<li>Nouveaux champs pour les élèves en relation avec les élections des parents d'élèves : Electeurs (case à cocher stipulant si la personne est électeur – à ne cocher qu’une fois par famille) - Parents élus – Titulaire / Suppléant.</li>
				<li>Nouveaux documents à imprimer pour le directeur : certificat de radiation, certificat de scolarité, certificat d’inscription, certificat de fréquentation, étiquettes des adresses des parents d’élèves, liste des parents élus.</li>
				<li>Nouveaux documents à imprimer pour les enseignants : étiquettes pour les protège-cahiers, page de garde, anniversaires de la classe mois par mois.</li>
				<li>Gestion des fratries (<b>Sous réserve</b>)</li>
			</ul>
			<li><u>Registre d'appels / Livrets scolaires</u> : Ajout des absences excusées ou non dans les livrets scolaires.</li>
			<li><u>Configuration</u> : Réorganisation de la configuration.</li>
		</ul>
    <li>Améliorations diverses des modules existants (nous attendons vos propositions d'amélioration).</li>
</ul>
<p class="explic">&nbsp;</p>

<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form" style="text-align:left !important">Gest'Ecole version 2.3</div>
<p class=explic align=center><b>Une version 2.3 sortira peut-être au début du mois de juin en fonction des disponibilités des développeurs.</b></p>
<p class="explic">&nbsp;</p>

<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form" style="text-align:left !important">Gest'Ecole version 3.0 - Sortie prévue le 01/09/2013</div>
<p class=explic align=center><b>La version 3 sera accès sur l'approche modulaire de l'application et la mise en place de modules additionnels optionnels.<br>La version 3 poursuivra aussi l'amélioration de l'application engagée dans les versions 2 avec l'ajout de fonctionnalités dans les modules existants.</b></p>
<ul class=explic>
    <li><u>Registre d'appel</u> : Gestion des retards.</li>
    <li><u>Livrets scolaires</u> : Prise en charge du socle commun.</li>
    <li><u>Bibliothèque</u> : Interfaçage avec une BDD (sous réserve de possibilité technique).</li>
    <li><u>Messagerie</u> : Gestion des dossiers de classement.</li>
    <li><u>Module additionnel optionnel</u> : Gestion du B2i.</li>
</ul>
<p class="explic">&nbsp;</p>

<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form" style="text-align:left !important">Gest'Ecole version 3.1 - Sortie prévue le 02/01/2014</div>
<ul class=explic>
    <li><u>Livrets scolaires</u> : Représentation graphique des résultats.</li>
    <li><u>Agenda</u> : Alerte x minutes avant une réunion/un rendez-vous.</li>
    <li><u>Module additionnel optionnel</u> : Cartable électronique.</li>
</ul>
<p class="explic">&nbsp;</p>

<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form" style="text-align:left !important">Gest'Ecole version 3.2 - Sortie prévue le 05/04/2014</div>
<ul class=explic>
    <li><u>Coopérative scolaire</u> : Edition des autres pages du document officiel de l’OCCE.</li>
    <li><u>Gestion documentaire</u> : Saisie d’informations complémentaires sur les fichiers : description, version.</li>
    <li><u>Module additionnel optionnel</u> : Gestion des présences pour le restaurant scolaire, la garderie du matin, du soir, des études du soir.</li>
</ul>

<p class="explic">&nbsp;</p>
<p class="explic">&nbsp;</p>

<div class="ui-widget ui-widget-header ui-corner-all ui-div-separation-form marge10_tout" style="text-align:left !important">Anciennes versions</div>

<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form" style="text-align:left !important">Gest'Ecole version 2.0 - Sortie le 01/09/2012</div>
<p class=explic align=center><b>La version 2 est accès sur l'amélioration générale de l'application, notamment en fonction des remarques des utilisateurs.</b></p>
<ul class=explic>
  <li><b>Ouverture de l'application</b></li>
  <ul class=explic>
    <li>Prise en charge des années scolaires pour les DOM-TOM et la Corse (actuellement, seules les zones A, B et C sont disponibles).</li>
    <li>Possibilité de définir les paramètres des années scolaires pour d'autres pays (début et fin d'année scolaire).</li>
    <li>Possibilité de traduire l'application.</li>
    <li>Possibilité de définir le format de date.</li>
  </ul>
  <li><b>Amélioration de l'application</b></li>
  <ul class=explic>
    <li>Conservation des options de recherche, de tri et de pagination lors du rechargement des listes (après création et modification d'un élément de la liste).</li>
    <li>Ajout de tooltips sur certains éléments (dans la liste des évaluations, tooltip comprenant des informations détaillées sur l'évaluation lorsqu'on place le pointeur de la souris sur l'entête de la liste, dans la colonne Justificatifs du registre d'appel, tooltip comprenant les informations sur le justificatif, etc.)</li>
    <li>Envoi de certains paramètres d'impression depuis la fiche d'un élément.</li>
    <li><u>Mise en place de l'aide en ligne</u> : un bouton Aide permet à l'utilisateur d'accéder au guide utilisateur en ligne en fonction de la section où il se trouve.</li>
    <li><u>Mise en place d'un éditeur WYSIWYG pour certains champs</u> : Certains champs pouvant nécessiter une mise en forme (actualités saisies par un utilisateur, déroulement d'une séance, détail d'une réunion, message sur la page de connexion, etc.) sont dorénavant gérés via un éditeur WYSIWYG.</li>
  </ul>
  <li><b>Amélioration des modules existants</b></li>
  <ul class=explic>
    <li><u>Page d'accueil</u> : Ajout de panneaux dans la colonne de droite : Annuaire téléphonique, Fête du jour, Rechercher.</li>
    <li><u>Personnels et Elèves</u> : Ajout de champs dans les formulaires de saisie, ajout d'une photo du personnel ou de l'élève (upload de la photo ou capture depuis une webcam), trombinoscope, possibilité de sauvegarder des documents liés au personnel ou à l'élève.</li>
    <li><u>Registres d'appel</u> : Possibilité de définir si les registres d'appel sont signés électroniquement ou non.</li>
    <li><u>Livrets scolaires</u> :</li>
	  <ul class=explic>
        <li>Possibilité de définir de nouveaux paramètres (début et fin des différentes périodes et trimestres, nouveau mode de calcul des résultats pour chaque compétence).</li>
	    <li>Possibilité de définir si les livrets scolaires sont signés électroniquement ou non et si les appréciations sont saisies via Gest'Ecole.</li>
		<li>Ajout du logo de l'établissement dans les livrets scolaires.</li>
	  </ul>
    <li><u>Messagerie</u> : Amélioration de la prise en charge des messages à destinataires multiples (un seul message créé pour tous les destinataires au lieu d'un message par destinataire).</li>
    <li><u>Agenda</u> : Possibilité pour le directeur d'ajouter, modifier et supprimer des jours fériés et des vacances scolaires.</li>
    <li><u>Configuration</u> :</li>
	  <ul class=explic>
	    <li>Possibilité d'installer une langue.</li>
		<li>Possibilité d'uploader le logo de l'établissement.</li>
		<li>Possibilité de nettoyer le cache de Gest'Ecole.</li>
	  </ul>
  </ul>
  <li><b>Maintenance de l'application</b></li>
  <ul class=explic>
    <li>Mise à jour des différentes librairies, plug-ins et classes PHP utilisées dans Gest'Ecole.</li>
  </ul>
</ul>
<p class="explic">&nbsp;</p>

<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form" style="text-align:left !important">Gest'Ecole version 1.2 - Sortie le 01/06/2012</div>
<ul class=explic>
  <li><b>Ajout de modules</b></li>
  <ul class=explic>
    <li><u>Directeur</u> : Coopérative scolaire, Gestion documentaire, Calendrier scolaire.</li>
    <li><u>Enseignants</u> : Coopérative scolaire, Gestion documentaire, Calendrier scolaire.</li>
    <li><u>Parents</u> : Gestion documentaire, Calendrier scolaire.</li>
  </ul>
  <li><b>Pour tous</b> : Ajout de panneaux dans la page d'accueil en fonction des nouveaux modules.</li>
  <li>Améliorations diverses en fonction des remarques des utilisateurs.</li>
</ul>
</div>
<p class="explic">&nbsp;</p>

<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form" style="text-align:left !important">Gest'Ecole version 1.1 - Sortie le 30/03/2012</div>
<ul class=explic>
  <li><b>Ajout de modules</b></li>
  <ul class=explic>
    <li><u>Directeur</u> : Gestion d'une bibliothèque d'école, Webmail interne.</li>
    <li><u>Enseignants</u> : Gestion d'une bibliothèque d'école, Webmail interne.</li>
    <li><u>Parents</u> : Webmail interne.</li>
  </ul>
  <li><b>Complétion des modules</b></li>
  <ul class=explic>
    <li><u>Directeur</u> :
      <ul class=explic>
        <li>Registre d'appel : Possibilité de signer électroniquement les registres d'appel.</li>
        <li>Livrets scolaires : Possibilité de mettre une appréciation et de signer électroniquement les livrets scolaires.</li>
      </ul>
    </li>
    <li><u>Enseignants</u> : Possibilité de mettre une appréciation sur les livrets scolaires.</li>
    <li><u>Parents</u> : Possibilité de signer électroniquement les livrets scolaires.</li>
  </ul>
  <li><b>Pour tous</b> : Ajout de panneaux dans la page d'accueil en fonction des nouveaux modules.</li>
  <li>Améliorations diverses en fonction des remarques des utilisateurs.</li>
</ul>
<p class="explic">&nbsp;</p>

<div class="ui-widget ui-widget-header ui-state-default ui-corner-all ui-div-separation-form" style="text-align:left !important">Gest'Ecole version 1.0 - Sortie le 01/03/2012</div>
<ul class=explic>
  <li>Lancement de la version 1.0 de Gest'Ecole, basée sur les retours utilisateurs de la version 4.0 de Gest'Classe. L'application a été entièrement réécrite en utilisant les possibilités du Web 2.0.</li>
  <li><b>Modules disponibles</b></li>
  <ul class=explic>
    <li><u>Directeur</u> : Accueil, Gestion des personnels, Gestion des classes, Registre des élèves, Registre d'appels, Livrets scolaires, Cahier-Journal.</li>
    <li><u>Enseignants</u> : Accueil, Registre des élèves, Registre d'appels, Livrets scolaires, Cahier-Journal.</li>
    <li><u>Parents</u> : Accueil, Livrets scolaires, Activités du jour, Devoirs.</li>
  </ul>
  <li>Les autres modules de Gest'Classe seront rajoutés dans Gest'Ecole au cours des prochaines mises à jour.</li>
</ul>
<div class="textdroite"><button id="retour2"><?php echo $Langue['BTN_RETOUR']; ?></button></div>
<script language="Javascript">
$(document).ready(function()
{
  $("#retour").button();
  $("#retour").click(function(event)
  {
    Message_Chargement(1,1);
    $( "#Menu_principal" ).load("index2.php?module=aproposde&action=index#haut_page");
    event.preventDefault();
  });
  $("#retour2").button();
  $("#retour2").click(function(event)
  {
    Message_Chargement(1,1);
    $( "#Menu_principal" ).load("index2.php?module=aproposde&action=index#haut_page");
    event.preventDefault();
  });
});
Message_Chargement(1,0);
</script>
