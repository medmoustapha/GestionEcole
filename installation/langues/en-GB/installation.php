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
$Format_Date_PHP = "Y-m-d";  
$Sens_Ecriture = "ltr";
$Langue_Valeur = "en";
$Langue_Licence = "en";		// fr ou en


/********************************************/
/*																					*/
/* Définitions des intitulés dans la langue */
/*																					*/
/********************************************/

$Langue_Installation=Array(
		'BTN_RETOUR_ETAPE' => "Back",

		'BTN_ETAPE1' => "Start",
    'LBL_ETAPE1_TITRE' => "Installation - Step 1",
		'LBL_ETAPE1_EXPL1' => "Welcome to the Gest'Ecole installation module.",
		'LBL_ETAPE1_EXPL2' => "Thank you for choosing Gest'Ecole. We hope that our software will meet your expectations for managing your school.",
		'LBL_ETAPE1_EXPL3' => "The installation will take place in 6 steps :",
		'LBL_ETAPE1_EXPL4' => "Step 1",
		'LBL_ETAPE1_EXPL4B' => "The installation 6 steps",
		'LBL_ETAPE1_EXPL5' => "Step 2",
		'LBL_ETAPE1_EXPL5B' => "License",
		'LBL_ETAPE1_EXPL6' => "Step 3",
		'LBL_ETAPE1_EXPL6B' => "Check your server parameters",
		'LBL_ETAPE1_EXPL7' => "Step 4",
		'LBL_ETAPE1_EXPL7B' => "Information about your database",
		'LBL_ETAPE1_EXPL8' => "Step 5",
		'LBL_ETAPE1_EXPL8B' => "Information about your school",
		'LBL_ETAPE1_EXPL9' => "Step 6",
		'LBL_ETAPE1_EXPL9B' => "Install and create database",
		'LBL_ETAPE1_EXPL10' => "To start the installation, click <b>Start</b>.",
		'LBL_ETAPE1_EXPL11' => "Cordially,",
		'LBL_ETAPE1_EXPL12' => "The DOX Conception development team",

    'LBL_ETAPE2_TITRE' => "Installation - Step 2",
		'LBL_ETAPE2_EXPL1' => "Read the Gest'Ecole license.",
		'LBL_ETAPE2_EXPL2' => "Check the box below if you accept the license and click <b>Step 3 : Check the server parameters</b>.",
		'LBL_ETAPE2_EXPL3' => "I accept the terms of the license",
		'LBL_ETAPE2_EXPL4' => "Step 3 : Check the server parameters",

    'LBL_ETAPE3_TITRE' => "Installation - Step 3",
		'LBL_ETAPE3_EXPL1' => "PHP Version (>= PHP 5.2.0)",
		'LBL_ETAPE3_EXPL1B' => "Your version",
		'LBL_ETAPE3_EXPL2' => "MySQL Support",
		'LBL_ETAPE3_EXPL2B' => "On",
		'LBL_ETAPE3_EXPL2T' => "Off",
		'LBL_ETAPE3_EXPL3' => "Sessions directory",
		'LBL_ETAPE3_EXPL3B' => "Writable",
		'LBL_ETAPE3_EXPL3T' => "Not writable",
		'LBL_ETAPE3_EXPL4' => "Writable directories",
		'LBL_ETAPE3_EXPL4B' => "Root directory",
		'LBL_ETAPE3_EXPL5' => "Step 4 : Information about your database",
		'LBL_ETAPE3_OK' => "OK",
		'LBL_ETAPE3_ERREUR' => "Error",

    'LBL_ETAPE4_TITRE' => "Installation - Step 4",
		'LBL_ETAPE4_EXPL1' => "Information about your database",
		'LBL_ETAPE4_EXPL2' => "Hostname",
		'LBL_ETAPE4_EXPL3' => "Database",
		'LBL_ETAPE4_EXPL3B' => "Create",
		'LBL_ETAPE4_EXPL4' => "User",
		'LBL_ETAPE4_EXPL5' => "Password",
		'LBL_ETAPE4_EXPL6' => "Confirm password",
		'LBL_ETAPE4_EXPL7' => "<b>root</b> password",
		'LBL_ETAPE4_EXPL8' => "Etape 5 : Information about your school",
		'LBL_ETAPE4_EXPL9' => "Please complete and/or correct the field in red.",

    'LBL_ETAPE5_TITRE' => "Installation - Step 5",
		'LBL_ETAPE5_EXPL1' => "Information about your school",
		'LBL_ETAPE5_EXPL2' => "School name",
		'LBL_ETAPE5_EXPL3' => "Address",
		'LBL_ETAPE5_EXPL4' => "Zone",
		'LBL_ETAPE5_EXPL4B' => "(only France)",
		'LBL_ETAPE5_EXPL5' => "Other country",
		'LBL_ETAPE5_EXPL6' => "The school year is",
		'LBL_ETAPE5_EXPL6B' => "a calendar year (begins January 1 and finishes December 31)",
		'LBL_ETAPE5_EXPL6T' => "straddles two calendar years",
		'LBL_ETAPE5_EXPL7' => "School year beginning",
		'LBL_ETAPE5_EXPL8' => "School year end",
		'LBL_ETAPE5_EXPL9' => "If the school year straddles two calendar years, you should make it last 12 months. For example, if the school year begins August 1, have it end on 31 July of the following year.",
		'LBL_ETAPE5_EXPL10' => "Information about the school principal",
		'LBL_ETAPE5_EXPL11' => "Civility",
		'LBL_ETAPE5_EXPL11B' => "Mr.",
		'LBL_ETAPE5_EXPL11T' => "Mrs.",
		'LBL_ETAPE5_EXPL11Q' => "Ms.",
		'LBL_ETAPE5_EXPL12' => "Lastname",
		'LBL_ETAPE5_EXPL13' => "Firstname",
		'LBL_ETAPE5_EXPL14' => "Login",
		'LBL_ETAPE5_EXPL15' => "Password",
		'LBL_ETAPE5_EXPL16' => "Confirm password",
		'LBL_ETAPE5_EXPL17' => "Etape 6 : Create the database",

    'LBL_ETAPE6_TITRE' => "Installation - Step 6",
		'LBL_ETAPE6_FAIT' => "Done",
		'LBL_ETAPE6_STOP' => "Installation stopped.",
		'LBL_ETAPE6_EXPL1' => "Testing the connection to the database...",
		'LBL_ETAPE6_EXPL2' => "Create the database",
		'LBL_ETAPE6_EXPL3' => "Create the user",
		'LBL_ETAPE6_EXPL4' => "Error connecting to 'root'...",
		'LBL_ETAPE6_EXPL5' => "Connection succeeded to",
		'LBL_ETAPE6_EXPL6' => "Error connecting to",
		'LBL_ETAPE6_EXPL7' => "Create the tables...",
		'LBL_ETAPE6_EXPL8' => "Create the default data...",
		'LBL_ETAPE6_EXPL9' => "Create the language data...",
		'LBL_ETAPE6_EXPL10' => "Create the custom data...",
		'LBL_ETAPE6_EXPL11' => "Create the file",
		'LBL_ETAPE6_EXPL12' => "The installation is finished.",
		'LBL_ETAPE6_EXPL13' => "Delete the directory <i>installation</i> in the root directory.",
		'LBL_ETAPE6_EXPL14' => "Click <b>Login to Gest'Ecole</b> and connect you by using the login",
		'LBL_ETAPE6_EXPL14B' => "and the password defined in the previous step.",
		'LBL_ETAPE6_EXPL15' => "Once connected, visit the tab <b>Configuration</b> to complete the software configuration and <u>to make a backup of the database</u>.",
		'LBL_ETAPE6_EXPL16' => "Error connecting to the database",
		'LBL_ETAPE6_EXPL17' => "Login to Gest'Ecole",
);