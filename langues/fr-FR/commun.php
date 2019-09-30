<?php
/**
 * Modèle de traduction de Gest'Ecole
 * Utiliser ce fichier pour traduire les éléments communs à toutes les parties
 * Envoyer l'ensemble des fichiers traduits à doxconception@gmail.com
 */
 
/**
 * Gest'Ecole translation template
 * Use this file to translate the elements used in all the sections
 * Send all the translated files at doxconception@gmail.com
 */
 
/**
 * XXXXX translation
 * @author Translator Name <translator@email.tld>
 * @version 201x-xx-xx
 */

/**********************************************/
/*											  */
/* Définitions du format d'affichage de dates */
/*											  */
/**********************************************/

// Formats possibles pour les dates (Fonction PHP) : d/m/Y, d-m-Y, d.m.Y	 m/d/Y, m-d-Y, m.d.Y	 Y/m/d, Y-m-d, Y.m.d	 Y/d/m, Y-d-m, Y.d.m, 
//													 d/Y/m, d-Y-m, d.Y.m 	 m/Y/d, m-Y-d, m.Y.d
$Format_Date_PHP = "d/m/Y";  
$Format_Date_Heure_PHP = "d/m/Y à H:i";  
$Format_Date_Heure_PHP_2 = "$1 à H:i";
$Sens_Ecriture = "ltr";
$Langue_Valeur = "fr";

// Champs ajoutés
$Langue_Licence = "fr";		// fr ou en														Ajouté le 12/06/2012


/********************************************/
/*											*/
/* Définitions des intitulés dans la langue */
/*											*/
/********************************************/

$Langue_Application=Array(
// Boutons
    'BTN_AIDE' => "Aide",
    'BTN_AJOUTER' => "Ajouter",
	'BTN_ANNULER' => "Fermer",
	'BTN_ANNULER2' => "Annuler",
	'BTN_DECONNEXION' => "Déconnexion",
	'BTN_ENREGISTRER' => "Enregistrer",
	'BTN_ENREGISTRER_FERMER' => "Enregistrer puis fermer",
	'BTN_IMPRIMER' => "Imprimer",
	'BTN_MODIFIER' => "Modifier",
	'BTN_S_IDENTIFIER' => "S'identifier",
	'BTN_SUPPRIMER' => "Supprimer",
	'BTN_VALIDER' => "Valider",

// Page de connexion
	'LBL_IDENTIFIANT' => "Identifiant",
	'LBL_IDENTIFICATION_COURS' => "Identification en cours...",
	'LBL_IDENTIFIEZ_VOUS' => "Identifiez-vous",
	'LBL_MOT_PASSE' => "Mot de passe",

// Sur toutes les pages
	'ERR_CHARGEMENT_ONGLET' => "La page en cours de chargement met du temps à être générée.<br>Veuillez patienter et nous excuser pour le désagrément occasionné.",
	'LBL_CHAMP_OBLIGATOIRE' => "Champs obligatoires",
	'LBL_LANGUE' => "Langue",
	'LBL_THEME' => "Thème",
	'LBL_THEME_DEFAUT' => "Thème par défaut",
	'LBL_VERSION' => "Version",

// Message lors de la saisie d'un formulaire ou le changement de page
	'ERR_FORMULAIRE' => "Veuillez compléter et/ou corriger les champs en rouge.",
	'MSG_CHARGEMENT' => "Chargement en cours...",
	'MSG_CREATION_LISTE' => "Création de la liste en cours...",
	'MSG_DECONNEXION' => "Déconnexion en cours...",
	'MSG_ENREGISTREMENT' => "Enregistrement en cours...",
	'MSG_ENVOI' => "Envoi en cours...",
	'MSG_ID_ATTRIBUE' => "Identifiant déjà attribué",
	'MSG_ID_LIBRE' => "Identifiant libre",
	'MSG_IMPRESSION' => "Impression en cours...",
	'MSG_MISE_A_JOUR' => "Mise à jour en cours...",
	'MSG_SAUVEGARDE' => "Sauvegarde en cours...",
	'MSG_SUPPRESSION' => "Suppression en cours...",
	'MSG_VERIFICATION' => "Vérification en cours...",
	'MSG_VERIFICATION_ID' => "Vérification de la disponibilité...",
	'MSG_VIDAGE_CACHE' => "Vidage du cache en cours...",
	'MSG_VIDAGE_CACHE2' => "Vidage du cache terminé...",
	
// Autres messages et intitulés
	'LBL_AM' => "Après-midi",
	'LBL_ANNEE_SCOLAIRE_COURS' => "Année scolaire",
	'LBL_LE' => "Le",
	'LBL_TOUTES_CLASSES' => "Toutes les classes",
	'LBL_IMPRESSION' => "Impression",
	'LBL_LIRE_SUITE' => "Lire la suite",
	'LBL_MATIN' => "Matin",
	'LBL_PERIODE' => "Période",
	'LBL_TRIMESTRE' => "Trimestre",
	'MSG_COCHER_TOUT' => "Cocher toutes les cases",
	'MSG_DECOCHER_TOUT' => "Décocher toutes les cases",
	'MSG_SELECT_NIVEAU' => "Sélectionnez le ou les niveaux",
	'MSG_SELECTED_NIVEAU' => "niveau(x) sélectionné(s)",
	'MSG_SELECT_PERSONNE' => "Sélectionnez la ou les personnes",
	'MSG_SELECTED_PERSONNE' => "personne(s) sélectionnée(s)",
	
// Pour les tableaux en liste
	'LBL_DEBUT' => "Début",
	'LBL_ELEMENT_AFFICHES' => "Afficher _MENU_ éléments par page", // Ne pas traduire _MENU_
	'LBL_ELEMENT_AFFICHES2' => "Enregistrements _START_ &agrave; _END_ sur un total de _TOTAL_", // Ne pas modifier _START_, _END_ et _TOTAL_
	'LBL_FIN' => "Fin",
	'LBL_NO_DATA' => "Aucune donnée trouvée",
	'LBL_NO_DATA2' => "Aucun enregistrement",
	'LBL_PRECEDENT' => "Précédent",
	'LBL_RECHERCHER_DATA' => "Rechercher :",
	'LBL_RESULT_RECHERCHE' => "(recherche sur _MAX_ enregistrements)", // Ne pas traduire _MAX_
	'LBL_SUIVANT' => "Suivant",
	'LBL_TOUS' => "Tous",
	'LBL_TRAITEMENT' => "Traitement en cours...",
	
// Impression
	'LBL_IMPRESSION_COULEUR' => "Couleur",
	'LBL_IMPRESSION_COULEUR_GRIS' => "Niveaux de gris",
	'LBL_IMPRESSION_COULEUR_OUI' => "En couleur",
  'LBL_IMPRESSION_DOCUMENTS' => "Documents à imprimer",
	'LBL_IMPRESSION_OPTIONS' => "Options d'impression",
	'LBL_IMPRESSION_ORDRE_CROISSANT' => "Ordre croissant",
	'LBL_IMPRESSION_ORDRE_DECROISSANT' => "Ordre décroissant",
	'LBL_IMPRESSION_ORIENTATION' => "Orientation",
	'LBL_IMPRESSION_ORIENTATION_PAYSAGE' => "Paysage",
	'LBL_IMPRESSION_ORIENTATION_PORTRAIT' => "Portrait",
	
// Signature électronique
  'LBL_SIGNATURE_CODE' => "Code de signature",
	'LBL_SIGNATURE_CODE2' =>"Recopier le code de signature",
	'LBL_SIGNATURE_MDP' =>"Votre mot de passe",
	'LBL_SIGNATURE_PARENTS' => "Les parents de",
	'MSG_SIGNATURE_ERREUR_MDP' =>"Mot de passe incorrect.",
	'MSG_SIGNATURE_CLE' =>"Clé",
	'MSG_SIGNATURE_LE' =>"le",
	'MSG_SIGNATURE_SIGNE_PAR' =>"Signé électroniquement par",
	'MSG_SIGNATURE_SIGNE_PAR_PARENTS' =>"Signé électroniquement par les parents de",
	
// Champs ajoutés dans la version 2.1
	'BTN_CACHER_RECHERCHE_CIBLEE' => "Cacher la recherche ciblée",
	'BTN_RECHERCHER' => "Rechercher",
	'BTN_RECHERCHE_CIBLEE' => "Faire une recherche ciblée",
	'BTN_VIDER' => "Réinitialiser",
	'LBL_ALLER_A' => "Voir la fiche de",
	'LBL_IMPRESSION_NUMEROTATION_PAGE' => "Numérotation des pages",
	'LBL_RECHERCHE_CIBLEE' => "Recherche ciblée",
	'LBL_TABLEAU_MONTRER_CACHER' => "Montrer / cacher des colonnes",
	'LST_NUMERO_COLONNE' => "N°",

	'EXPLI_QUESTION_SECRETE' => "Pour pouvoir réinitialiser votre mot de passe, veuillez compléter les champs suivants :",
	'EXPLI_QUESTION_SECRETE2' => "Pour pouvoir réinitialiser votre mot de passe, veuillez répondre à la question suivante :",
	'EXPLI_QUESTION_SECRETE3' => "Envoyer le nouveau mot de passe à l'adresse email",
	'EXPLI_QUESTION_SECRETE4' => "Vous allez recevoir un email à l\'adresse ",
	'EXPLI_QUESTION_SECRETE5' => " avec votre nouveau mot de passe. Une fois connectez avec votre nouveau mot de passe, nous vous conseillons d\'aller sur votre compte et de redéfinir votre compte.",
	'LBL_ETAPE1' => "Etape 1",
	'LBL_ETAPE2' => "Etape 2",
	'LBL_ETAPE3' => "Etape 3",
	'LBL_MDP_PERDU_RECUPERATION' => "Redéfinir votre mot de passe",
	'LBL_QUESTION_SECRETE' => "Question secrète",
	'LBL_REPONSE_QUESTION' => "Réponse",
	'LBL_TYPE_ELEVE' => "Parent d'élève",
	'LBL_TYPE_ENSEIGNANT' => "Directeur / Enseignant",
	'LBL_TYPE_UTIL' => "Type d'utilisateur",
	'LNK_MDP_PERDU' => "Mot de passe perdu ?",
	'MSG_QUESTION_SECRETE_INDEFINIE' => "Nous ne pouvons redéfinir votre mot de passe automatiquement car ",
	'MSG_QUESTION_SECRETE_INDEFINIE2' => "vous n\'avez pas défini de question secrète.",
	'MSG_QUESTION_SECRETE_INDEFINIE3' => "vous n\'avez pas saisi d\'adresse e-mail de contact.",
	'MSG_QUESTION_SECRETE_INDEFINIE4' => " Veuillez voir le directeur de l\'école ou l\'enseignant de la classe pour qu\'il redéfinisse manuellement votre mot de passe.",
	'ERR_IDENTIFIANT_MDP_ELEVE' => "Cet identifiant ne correspond à aucun élève.",
	'ERR_IDENTIFIANT_MDP_ENSEIGNANT' => "Cet identifiant ne correspond à aucun enseignant / directeur.",
	'ERR_REPONSE_QUESTION' => "La réponse à la question secrète est incorrecte.",
	'ERR_CONNEXION_INCONNU' => "L'identifiant ou le mot de passe est incorrect.",
	'ERR_CONNEXION_PASCLASSE' => "L'identifiant n'est rattaché à aucune classe. Vous ne pouvez accéder à Gest'Ecole.",
	'MSG_EMAIL_SUJET' => "Gest'Ecole : Réinitialisation de votre mot de passe",
	'MSG_EMAIL_CORPS' => "Madame, Mademoiselle, Monsieur,<br />Vous avez demandé la réinitialisation de votre mot de passe d'accès à Gest'Ecole.<br />Votre nouveau mot de passe est <b>",
	'MSG_EMAIL_CORPS2' => "</b>.<br />Cordialement,",
	'MSG_TABLEAU_CHARGEMENT_EN_COURS' => "Chargement en cours",
);
?>