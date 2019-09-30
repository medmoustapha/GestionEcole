<?php
/**
 * Modèle de traduction de la procédure d'installation de Gest'Ecole
 * Utiliser ce fichier pour traduire la procédure d'installation de Gest'Ecole
 * Envoyer l'ensemble des fichiers traduits à doxconception@gmail.com
 */
 
/**
 * translation template of the Gest'Ecole install procedure
 * Use this file to translate the Gest'Ecole install procedure
 * Send all the translated files at doxconception@gmail.com
 */
 
/**
 * XXXXX translation
 * @author Translator Name <translator@email.tld>
 * @version 201x-xx-xx
 */

/**********************************************/
/*											  										*/
/* Définitions du format d'affichage de dates */
/*											  										*/
/**********************************************/

// Formats possibles pour les dates (Fonction PHP) : d/m/Y, d-m-Y, d.m.Y	 m/d/Y, m-d-Y, m.d.Y	 Y/m/d, Y-m-d, Y.m.d	 Y/d/m, Y-d-m, Y.d.m, 
//													 d/Y/m, d-Y-m, d.Y.m 	 m/Y/d, m-Y-d, m.Y.d
$Format_Date_PHP = "d/m/Y";  
$Sens_Ecriture = "ltr";
$Langue_Valeur = "fr";
$Langue_Licence = "fr";		// fr ou en


/********************************************/
/*																					*/
/* Définitions des intitulés dans la langue */
/*																					*/
/********************************************/

$Langue_Installation=Array(
		'BTN_RETOUR_ETAPE' => "Retour à l'étape précédente",

		'BTN_ETAPE1' => "Commencer",
    'LBL_ETAPE1_TITRE' => "Procédure d'installation - Etape 1",
		'LBL_ETAPE1_EXPL1' => "Bienvenue sur le programme d'installation de Gest'Ecole",
		'LBL_ETAPE1_EXPL2' => "Nous tenons tout d'abord à vous remercier d'avoir choisi Gest'Ecole. Nous espérons que notre logiciel répondra à vos attentes en matière de gestion de votre établissement scolaire.",
		'LBL_ETAPE1_EXPL3' => "Cette procédure se déroulera en 6 étapes :",
		'LBL_ETAPE1_EXPL4' => "&Eacute;tape 1",
		'LBL_ETAPE1_EXPL4B' => "Présentation de la procédure d'installation",
		'LBL_ETAPE1_EXPL5' => "&Eacute;tape 2",
		'LBL_ETAPE1_EXPL5B' => "Acceptation de la licence",
		'LBL_ETAPE1_EXPL6' => "&Eacute;tape 3",
		'LBL_ETAPE1_EXPL6B' => "Vérification des paramètres du serveur",
		'LBL_ETAPE1_EXPL7' => "&Eacute;tape 4",
		'LBL_ETAPE1_EXPL7B' => "Informations sur la base de données",
		'LBL_ETAPE1_EXPL8' => "&Eacute;tape 5",
		'LBL_ETAPE1_EXPL8B' => "Informations sur votre établissement",
		'LBL_ETAPE1_EXPL9' => "&Eacute;tape 6",
		'LBL_ETAPE1_EXPL9B' => "Installation et création de la base de données",
		'LBL_ETAPE1_EXPL10' => "Pour commencer l'installation, cliquez sur le bouton <b>Commencer</b>.",
		'LBL_ETAPE1_EXPL11' => "Cordialement,",
		'LBL_ETAPE1_EXPL12' => "L'équipe de développement DOX Conception",

    'LBL_ETAPE2_TITRE' => "Procédure d'installation - Etape 2",
		'LBL_ETAPE2_EXPL1' => "Pour pouvoir poursuivre l'installation, lisez attentivement la licence de Gest'Ecole ci-dessous.",
		'LBL_ETAPE2_EXPL2' => "Si vous acceptez cette licence, cochez la case ci-dessous puis cliquez sur le bouton <b>Etape 3 : Vérification de la configuration</b>.",
		'LBL_ETAPE2_EXPL3' => "J'accepte les termes de la licence de Gest'Ecole.",
		'LBL_ETAPE2_EXPL4' => "Etape 3 : Vérification de la configuration",

    'LBL_ETAPE3_TITRE' => "Procédure d'installation - Etape 3",
		'LBL_ETAPE3_EXPL1' => "Version de PHP (doit être supérieur à PHP 5.2.0)",
		'LBL_ETAPE3_EXPL1B' => "Version actuelle",
		'LBL_ETAPE3_EXPL2' => "Support MySQL",
		'LBL_ETAPE3_EXPL2B' => "Activé",
		'LBL_ETAPE3_EXPL2T' => "Non activé",
		'LBL_ETAPE3_EXPL3' => "Répertoire des sessions",
		'LBL_ETAPE3_EXPL3B' => "Inscriptible",
		'LBL_ETAPE3_EXPL3T' => "Non inscriptible",
		'LBL_ETAPE3_EXPL4' => "Répertoires inscriptibles",
		'LBL_ETAPE3_EXPL4B' => "Répertoire racine",
		'LBL_ETAPE3_EXPL5' => "Etape 4 : Informations de connexion",
		'LBL_ETAPE3_OK' => "OK",
		'LBL_ETAPE3_ERREUR' => "Erreur",

    'LBL_ETAPE4_TITRE' => "Procédure d'installation - Etape 4",
		'LBL_ETAPE4_EXPL1' => "Informations de connexion à la base de données de Gest'Ecole",
		'LBL_ETAPE4_EXPL2' => "Hostname",
		'LBL_ETAPE4_EXPL3' => "Base de données",
		'LBL_ETAPE4_EXPL3B' => "A créer",
		'LBL_ETAPE4_EXPL4' => "Utilisateur",
		'LBL_ETAPE4_EXPL5' => "Mot de passe",
		'LBL_ETAPE4_EXPL6' => "Confirmation Mot de passe",
		'LBL_ETAPE4_EXPL7' => "Mot de passe de l'utilisateur <b>root</b>",
		'LBL_ETAPE4_EXPL8' => "Etape 5 : Informations sur votre établissement",
		'LBL_ETAPE4_EXPL9' => "Veuillez compléter et/ou corriger les champs en rouge.",

    'LBL_ETAPE5_TITRE' => "Procédure d'installation - Etape 5",
		'LBL_ETAPE5_EXPL1' => "Informations sur votre établissement",
		'LBL_ETAPE5_EXPL2' => "Nom de l'établissement",
		'LBL_ETAPE5_EXPL3' => "Adresse",
		'LBL_ETAPE5_EXPL4' => "Zone",
		'LBL_ETAPE5_EXPL4B' => "(France uniquement)",
		'LBL_ETAPE5_EXPL5' => "Autres",
		'LBL_ETAPE5_EXPL6' => "L'année scolaire est",
		'LBL_ETAPE5_EXPL6B' => "sur une année civile (débute le 1er janvier et se termine le 31 décembre)",
		'LBL_ETAPE5_EXPL6T' => "à cheval sur deux années civiles",
		'LBL_ETAPE5_EXPL7' => "Début de l'année scolaire",
		'LBL_ETAPE5_EXPL8' => "Fin de l'année scolaire",
		'LBL_ETAPE5_EXPL9' => "Si l'année scolaire est à cheval sur deux années civiles, nous vous conseillons de la faire durer 12 mois. Par exemple, si l'année scolaire commence le 1er août, faites-la se terminer le 31 juillet de l'année suivante.",
		'LBL_ETAPE5_EXPL10' => "Informations sur le directeur de l'établissement",
		'LBL_ETAPE5_EXPL11' => "Civilité",
		'LBL_ETAPE5_EXPL11B' => "Monsieur",
		'LBL_ETAPE5_EXPL11T' => "Madame",
		'LBL_ETAPE5_EXPL11Q' => "Mademoiselle",
		'LBL_ETAPE5_EXPL12' => "Nom",
		'LBL_ETAPE5_EXPL13' => "Prénom",
		'LBL_ETAPE5_EXPL14' => "Identifiant",
		'LBL_ETAPE5_EXPL15' => "Mot de passe",
		'LBL_ETAPE5_EXPL16' => "Confirmation du mot de passe",
		'LBL_ETAPE5_EXPL17' => "Etape 6 : Création des tables de la base de données",

    'LBL_ETAPE6_TITRE' => "Procédure d'installation - Etape 6",
		'LBL_ETAPE6_FAIT' => "Fait",
		'LBL_ETAPE6_STOP' => "Procédure d'installation stoppée.",
		'LBL_ETAPE6_EXPL1' => "Test de la connexion à la base de données...",
		'LBL_ETAPE6_EXPL2' => "Création de la base de données",
		'LBL_ETAPE6_EXPL3' => "Création de l'utilisateur",
		'LBL_ETAPE6_EXPL4' => "Erreur de connexion pour 'root'...",
		'LBL_ETAPE6_EXPL5' => "Connexion réussie pour",
		'LBL_ETAPE6_EXPL6' => "Erreur de connexion pour",
		'LBL_ETAPE6_EXPL7' => "Création des tables...",
		'LBL_ETAPE6_EXPL8' => "Création des données par défaut...",
		'LBL_ETAPE6_EXPL9' => "Création des données de langue...",
		'LBL_ETAPE6_EXPL10' => "Création des données personnalisées...",
		'LBL_ETAPE6_EXPL11' => "Création du fichier",
		'LBL_ETAPE6_EXPL12' => "La procédure d'installation est terminée.",
		'LBL_ETAPE6_EXPL13' => "Nous vous conseillons de supprimer le dossier <i>installation</i> à la racine de votre application.",
		'LBL_ETAPE6_EXPL14' => "Cliquez sur le bouton <b>Se connecter à Gest'Ecole</b> ci-dessous puis connectez-vous en utilisant l'identifiant",
		'LBL_ETAPE6_EXPL14B' => "et le mot de passe défini à l'étape précédente.",
		'LBL_ETAPE6_EXPL15' => "Une fois connecté, nous vous conseillons de visiter l'onglet <b>Configuration</b> pour terminer la configuration de votre application et <u>faire une sauvegarde de la base de données actuelle</u>.",
		'LBL_ETAPE6_EXPL16' => "Erreur de connexion à la base de données",
		'LBL_ETAPE6_EXPL17' => "Se connecter à Gest'Ecole",
);