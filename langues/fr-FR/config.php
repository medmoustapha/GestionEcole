<?php
/**
 * Modèle de traduction de Gest'Ecole
 * Utiliser ce fichier pour traduire les listes de choix
 * Envoyer l'ensemble des fichiers traduits à doxconception@gmail.com
 */
 
/**
 * Gest'Ecole translation template
 * Use this file to translate the selectboxes
 * Send all the translated files at doxconception@gmail.com
 */
 
/**
 * XXXXX translation
 * @author Translator Name <translator@email.tld>
 * @version 201x-xx-xx
 */

$onglet['D']=Array("accueil" => "Accueil",
                   "personnels" => "Personnels",
                   "classes" => "Classes",
                   "eleves" => "Elèves",
                   "absences" => "Absences",
                   "cahier" => "Cahier-Journal",
                   "livrets" => "Livrets",
                   "bibliotheque" => "Bibliothèque",
                   "email" => "Messagerie",
                   "cooperative" => "Coopérative",
                   "ged" => "Documents",
                   "calendrier" => "Agenda",
                   "configuration" => "Configuration",
                   "aproposde" => "A propos de",
                   );
$onglet['P']=Array("accueil" => "Accueil",
                   "eleves" => "Elèves",
                   "absences" => "Absences",
                   "cahier" => "Cahier-Journal",
                   "livrets" => "Livrets",
                   "bibliotheque" => "Bibliothèque",
                   "email" => "Messagerie",
                   "cooperative" => "Coopérative",
                   "ged" => "Documents",
                   "calendrier" => "Agenda",
                   "compte" => "Votre compte",
                   "configuration" => "Configuration",
                   "aproposde" => "A propos de",
                   );
$onglet['E']=Array("accueil" => "Accueil",
                   "cahier" => "Cahier d'activités",
                   "devoirs" => "Devoirs",
                   "absences" => "Absences",
                   "livrets" => "Livrets",
                   "email" => "Messagerie",
                   "ged" => "Documents",
                   "calendrier" => "Agenda",
                   "compte" => "Votre compte",
                   "aproposde" => "A propos de",
                   );


// Définition des listes de choix
$liste_choix = Array(
  'ouinon'=>Array("0" => "Non", "1" => "Oui"),
  'ouinon2'=>Array("0" => "&nbsp;", "1" => "Oui"),
  'sexe'=>Array("1" => "Masculin", "2" => "Féminin"),
  'civilite'=>Array("" => "", "1" => "M.", "2" => "Mme", "3" => "Mlle"),
  'civilite_long'=>Array("" => "", "1" => "Monsieur", "2" => "Madame", "3" => "Mademoiselle"),
  'ouvertferme'=>Array("1"=>"Ouvert","0"=>"Fermé"),
  
  // Type d'utilisateurs
  'type_connecte'=>Array("P"=>"Enseignant",
                         "D"=>"Directeur",
                         "E"=>"Parent d'élève"),
  'type_user'=>Array("" => "",
                     "D"=>"Directeur",
                     "P"=>"Enseignant",
                     "I"=>"Intervenant extérieur",
                     "S"=>"ATSEM",
                     "U"=>"Autre"),
  'type_user_classe'=>Array("T" => "Titulaire",
                            "E"=>"Décharge",
                            "D"=>"Décloisonnement",
                            "I"=>"Intervenant extérieur"),

  // Jours et mois
  'mois'=>Array("1" => "Janvier",
                "2" => "Février",
                "3" => "Mars",
                "4" => "Avril",
                "5" => "Mai",
                "6" => "Juin",
                "7" => "Juillet",
                "8" => "Août",
                "9" => "Septembre",
                "10" => "Octobre",
                "11" => "Novembre",
                "12" => "Décembre"),
  'jour_long'=> Array("0"=>"Dimanche",
                      "1"=>"Lundi",
                      "2"=>"Mardi",
                      "3"=>"Mercredi",
                      "4"=>"Jeudi",
                      "5"=>"Vendredi",
                      "6"=>"Samedi",
                      "7"=>"Dimanche"),
  'jour_court'=>Array("0"=>"Di",
                      "1"=>"Lu",
                      "2"=>"Ma",
                      "3"=>"Me",
                      "4"=>"Je",
                      "5"=>"Ve",
                      "6"=>"Sa",
                      "7"=>"Di"),

  // Absences / Justificatif d'absence
  'type_justificatif'=>Array('P'=>"Mot des parents",
                             'M'=>"Certificat médical",
                             'R'=>"Rencontre avec les parents",
                             'T'=>"Appel téléphonique",
                             'G'=>"Mot dans Gest'Ecole",
                             'A'=>"Autre"),
  'type_justificatif_abrege'=>Array('P'=>"MP",
                                    'M'=>"CM",
                                    'R'=>"Par",
                                    'T'=>"Tel",
                                    'G'=>"GE",
                                    'A'=>"Au"),
  'absence_debut'=>Array('M'=>"Matin",
                         'A'=>"Après-midi"),
  'absence_fin'=>  Array('D'=>"Midi",
                         'S'=>"Soir"),

  // Livrets scolaires : Découpage en trimestre / période
  'livret_decoupage_P' =>  Array("P1" => "Période 1",
                                 "P2" => "Période 2",
                                 "P3" => "Période 3",
                                 "P4" => "Période 4",
                                 "P5" => "Période 5"),
  'livret_decoupage_T' =>  Array("T1" => "Trimestre 1",
                                 "T2" => "Trimestre 2",
                                 "T3" => "Trimestre 3"),
  'livret_decoupage_livret_P' => Array("P1" => "1<sup>ère</sup> pér.",
                                       "P2" => "2<sup>ème</sup> pér.",
                                       "P3" => "3<sup>ème</sup> pér.",
                                       "P4" => "4<sup>ème</sup> pér.",
                                       "P5" => "5<sup>ème</sup> pér."),
  'livret_decoupage_livret_T' => Array("T1" => "1<sup>er</sup> trim.",
                                       "T2" => "2<sup>ème</sup> trim.",
                                       "T3" => "3<sup>ème</sup> trim."),
  'livret_decoupage_livret' => Array("P1" => "la période 1",
                                     "P2" => "la période 2",
                                     "P3" => "la période 3",
                                     "P4" => "la période 4",
                                     "P5" => "la période 5",
                                     "T1" => "le trimestre 1",
                                     "T2" => "le trimestre 2",
                                     "T3" => "le trimestre 3"),

  'matieres_cj' => Array(
                    'A' => "Français",
                    'I' => "Mathématiques",
                    'Y' => "Autre",
                    ),
  'matieres_b' => Array(
                    'A' => "FRA|Français",
                    'I' => "MAT|Mathématiques",
                    'X' => "AUT|Autre",
                    'Y' => "TRANS|Compétences transversales",
                    ),
  'matieres_stat' => Array(
						'A' => "Français",
						'B' => "Mathématiques",
						'C' => "Langue vivante, Sciences, Technologie, Histoire, Géographie et Instruction civique",
						'D' => "Matières artistiques et sportives",
						'E' => "Compétences transversales",
					),	
  'matieres_stat_court' => Array(
						'A' => "Français",
						'B' => "Mathématiques",
						'C' => "LV, Sciences, Techno, Histoire, Géo et Civisme",
						'D' => "Matières artistiques et sportives",
						'E' => "Compétences transversales",
					),
  'statistiques' => Array(
						'R' => "Aucun tableau statistique",
						'G' => "Tableau statistique Toutes matières confondues",
						'D' => "Tous les tableaux statistiques",
					),

// Bibliothèque
  'livre_a_afficher' => Array(
					'T' => "Tous les livres",
					'U' => "Tous les livres sauf ceux retirés",
					'N' => "Livres disponibles",
					'E' => "Livres empruntés",
					'R' => "Livres en retard",
					'S' => "Livres à retirer",
					'O' => "Livres retirés"),
					
  'liste_biblio'=> Array(
				'1' => "Bibliothèques de la classe et de l'école",
				'2' => "Bibliothèque de la classe uniquement",
				'3' => "Bibliothèque de l'école uniquement"),
				
   'categ_biblio_classe' => Array(
                    'ROM' => "A personnaliser via l\'onglet Configuration",
                    ),
  'etat_livre' => Array(
               "" => "",
               "N" => "Neuf",
               "B" => "Bon état",
               "M" => "Etat moyen",
               "V" => "Mauvais état",
               "S" => "A retirer",
							 "O" => "Retiré"),

  // Etat d'un email
  'etat_email' => Array(
			   "L" => "Lu par le destinataire",
			   "N" => "Non lu par le destinataire",
			   "R" => "Réponse apportée",
			   ),
  'dossiers' => Array(
			   "R" => "Messages reçus",
			   "E" => "Messages envoyés",
			   ),
			   
  // Réunions
  'type_reunion' => Array(
				""=>"Non classé",
				"C"=>"Conseil de Cycle",
				"M"=>"Conseil des Maîtres",
				"E"=>"Conseil d'Ecole",
				"P"=>"Animation pédagogique",
				"R"=>"Autre réunion",
				"N"=>"Manifestation",
				"D"=>"Rendez-vous parents",
				"A"=>"Autre"),
  'type_reunion_court' => Array(
				""=>"NC",
				"C"=>"C Cyc",
				"M"=>"C Maî",
				"E"=>"C Eco",
				"P"=>"An Péd",
				"R"=>"Réun",
				"N"=>"Manif",
				"D"=>"RDV",
				"A"=>"Aut"),
				
/* Ne pas traduire */
  'couleur_reunion' => Array(
				""=>"#c0c0c0",
				"C"=>"#ff0000",
				"M"=>"#0000ff",
				"E"=>"#00ff00",
				"P"=>"#ffff00",
				"R"=>"#ff00ff",
				"N"=>"#00ffff",
				"D"=>"#ff8000",
				"A"=>"#800080"),

/* Ne pas traduire */	
  'couleur_reunion_clair' => Array(
				""=>"#f0f0f0",
				"C"=>"#fff0f0",
				"M"=>"#f0f0ff",
				"E"=>"#f0fff0",
				"P"=>"#fffff0",
				"R"=>"#fff0ff",
				"N"=>"#f0ffff",
				"D"=>"#fff8f0",
				"A"=>"#f8f0f8"),
				
  // Taches
  'tache_priorite' => Array(
				"B" => "Basse",
				"N" => "Normale",
				"I" => "Haute",
				"U" => "Urgent"),
/*  Ne pas traduire */
  'tache_priorite_couleur' => Array(
						"B" => "#000000",
						"N" => "#008000",
						"I" => "#FF8000",
						"U" => "#FF0000"),
  'tache_etat' => Array(
			    "N" => "Non commencée",
				"E" => "En cours",
				"T" => "Terminée",
				"A" => "Annulée",
				"R" => "Reportée"),
				
  // News de la page d'accueil
  'cible_news' => Array("T" => "Tout le monde",
                        "P" => "Uniquement aux enseignants",
                        "A" => "Uniquement aux parents"),
  'news_afficher_D' => Array("T" => "Toutes les news"),
  'news_afficher_P' => Array("T" => "Toutes les news",
                             "D" => "Uniquement celles du directeur",
                             "P" => "Uniquement les votres"),
  'news_afficher_E' => Array("T" => "Toutes les news",
                             "D" => "Uniquement celles du directeur",
                             "P" => "Uniquement celles de l'enseignant"),
/*  Remplacer les éléments par ceux correspondant à votre pays */
  'news_ministere' =>  Array("M" => "Ministère de l'éducation Nationale",
                             "O" => "Bulletin Officiel",
                             "E" => "Site Eduscol",
                             "N" => "Notes officielles du Ministère",
                             ),
							 
/*  Remplacer les 4 premiers éléments par les adresses des flux RSS de votre pays */
  'flux_rss' =>        Array("M" => "http://www.education.gouv.fr/rid4/toute-l-actualite.rss",
                             "O" => "http://www.education.gouv.fr/rss/rss_bulletin_officiel.php",
                             "E" => "http://eduscol.education.fr/rid271/toute-l-actualite-du-site.rss?",
                             "N" => "http://www.education.gouv.fr/rid20/les-notes-d-information.rss",
							 "G" => "http://www.doxconception.com/site/index.php?option=com_ninjarsssyndicator&feed_id=1&format=raw",
                             ),
  'devoirs_a_afficher' => Array("J" => "Devoirs à faire pour ce jour",
								"P" => "Devoirs à faire pour le prochain jour de classe"),
								
  // Panneau Accueil
  'horoscope' => Array( "belier"=>"Bélier",
						"taureau"=>"Taureau",
						"gemeaux"=>"Gémeaux",
						"cancer"=>"Cancer",
						"lion"=>"Lion",
						"vierge"=>"Vierge",
						"balance"=>"Balance",
						"scorpion"=>"Scorpion",
						"sagittaire"=>"Sagittaire",
						"capricorne"=>"Capricorne",
						"verseau"=>"Verseau",
						"poissons"=>"Poissons"),
  'horoscope_c' => Array( "rat"=>"Rat",
  						  "buffle"=>"Buffle",
						  "tigre"=>"Tigre",
						  "chat"=>"Chat",
						  "dragon"=>"Dragon",
						  "serpent"=>"Serpent",
						  "cheval"=>"Cheval",
						  "bouc"=>"Bouc",
						  "singe"=>"Singe",
						  "coq"=>"Coq",
						  "chien"=>"Chien",
						  "cochon"=>"Cochon"),
/* Indiquez le nom de journaux de votre pays. Vous pouvez en retirer ou en ajouter */
  'journaux_actualites' => Array ("F"=>"Le Figaro",
								  "M"=>"Le Monde", 	
								  "N"=>"Le Nouvel Obs", 	
								  "P"=>"Le Parisien", 	
								  "O"=>"Le Point", 	
								  "L"=>"Libération"),
/* Indiquez les parties de votre pays */
  'journaux_categories' => Array ("U"=>"A la Une",
								  "F"=>"France",
								  "I"=>"Monde",
								  "E"=>"Economie",
								  "C"=>"Culture",
								  "S"=>"Sports"),
/* Indiquez les adresses des flux RSS pour chacun des journaux. */
  'journaux' => Array("F"=>Array("U"=>"http://rss.lefigaro.fr/lefigaro/laune",
								 "F"=>"http://www.lefigaro.fr/rss/figaro_actualite-france.xml",
								 "I"=>"http://feeds.lefigaro.fr/c/32266/f/438192/index.rss",
								 "E"=>"http://www.lefigaro.fr/rss/figaro_economie.xml",
								 "C"=>"http://www.lefigaro.fr/rss/figaro_culture.xml",
								 "S"=>"http://www.lefigaro.fr/rss/figaro_sport.xml"),
					  "M"=>Array("U"=>"http://rss.lemonde.fr/c/205/f/3050/index.rss",
								 "F"=>"http://rss.lemonde.fr/c/205/f/3054/index.rss",
								 "I"=>"http://rss.lemonde.fr/c/205/f/3052/index.rss",
								 "E"=>"http://rss.lemonde.fr/c/205/f/3055/index.rss",
								 "C"=>"http://rss.lemonde.fr/c/205/f/3060/index.rss",
								 "S"=>"http://rss.lemonde.fr/c/205/f/3058/index.rss"),
					  "N"=>Array("U"=>"http://tempsreel.nouvelobs.com/rss.xml",
								 "F"=>"http://tempsreel.nouvelobs.com/societe/rss.xml",
								 "I"=>"http://tempsreel.nouvelobs.com/monde/rss.xml",
								 "E"=>"http://tempsreel.nouvelobs.com/economie/rss.xml",
								 "C"=>"http://tempsreel.nouvelobs.com/culture/rss.xml",
								 "S"=>"http://tempsreel.nouvelobs.com/sport/rss.xml"),
					  "P"=>Array("U"=>"http://rss.leparisien.fr/leparisien/rss/une.xml",
								 "F"=>"http://rss.leparisien.fr/leparisien/rss/societe.xml",
								 "I"=>"http://rss.leparisien.fr/leparisien/rss/international.xml",
								 "E"=>"http://rss.leparisien.fr/leparisien/rss/economie.xml",
								 "C"=>"http://rss.leparisien.fr/leparisien/rss/loisirs-et-spectacles.xml",
								 "S"=>"http://rss.leparisien.fr/leparisien/rss/sports.xml"),
					  "O"=>Array("U"=>"http://www.lepoint.fr/rss.xml",
								 "F"=>"http://www.lepoint.fr/societe/rss.xml",
								 "I"=>"http://www.lepoint.fr/monde/rss.xml",
								 "E"=>"http://www.lepoint.fr/economie/rss.xml",
								 "C"=>"http://www.lepoint.fr/culture/rss.xml",
								 "S"=>"http://www.lepoint.fr/sport/rss.xml"),
					  "L"=>Array("U"=>"http://www.liberation.fr/rss/9/",
								 "F"=>"http://www.liberation.fr/rss/12/",
								 "I"=>"http://www.liberation.fr/rss/10/",
								 "E"=>"http://www.liberation.fr/rss/13/",
								 "C"=>"http://www.liberation.fr/rss/58/",
								 "S"=>"http://www.liberation.fr/rss/14/")
					 ),

/* Plan comptable spécifique à la France */
/* A vous de voir si le plan comptable de votre pays peut être adapté à celui de la France */
/* Attention : ne changez pas les clés sous peine d'empêcher le bon fonctionnement de Gest'Ecole */
  'cooperative_ligne_plus' => Array(
						"" => "",
						"1" => "REPORT",
						"110" => "110 - Report à nouveau",
						"5" => "COMPTES DE CHARGES",
						"512" => "512 - Banque",
						"514" => "514 - Banque Postale",
						"530" => "530 - Caisse en espèces",
						"6" => "COMPTES DE CHARGES",
						"6070" => "6070 - Achats de produits pour cession",
						"6180" => "6180 - Charges des activités éducatives",
						"6281" => "6281 - Cotisations versées à l'OCCE",
						"6282" => "6282 - Assurances versées",
						"6500" => "6500 - Autres charges courantes",
						"6700" => "6700 - Charges exceptionnelles",
						"6800" => "6800 - Achats de biens durables",
						"7" => "COMPTES DE PRODUITS",
						"7070" => "7070 - Vente de produits pour cession",
						"7080" => "7080 - Produits des activités éducatives",
						"7410" => "7410 - Subventions Etat, collectivités locales",
						"7420" => "7420 - Subventions d'associations",
						"7500" => "7500 - Autres produits courants",
						"7560" => "7560 - Participations volontaires des familles",
						"7600" => "7600 - Intérêts perçus (mutualisation)",
						"7700" => "7700 - Produits exceptionnels",
						"I" => "FONCTIONNEMENT INTERNE",
						"INTE" => "INTE - Répartition des crédits dans les classes"),
  'cooperative_ligne' => Array(
						"1" => "REPORT",
						"110" => "110 - Report à nouveau",
						"6" => "COMPTES DE CHARGES",
						"6070" => "6070 - Achats de produits pour cession",
						"6180" => "6180 - Charges des activités éducatives",
						"6281" => "6281 - Cotisations versées à l'OCCE",
						"6282" => "6282 - Assurances versées",
						"6500" => "6500 - Autres charges courantes",
						"6700" => "6700 - Charges exceptionnelles",
						"6800" => "6800 - Achats de biens durables",
						"7" => "COMPTES DE PRODUITS",
						"7070" => "7070 - Vente de produits pour cession",
						"7080" => "7080 - Produits des activités éducatives",
						"7410" => "7410 - Subventions Etat, collectivités locales",
						"7420" => "7420 - Subventions d'associations",
						"7500" => "7500 - Autres produits courants",
						"7560" => "7560 - Participations volontaires des familles",
						"7600" => "7600 - Intérêts perçus (mutualisation)",
						"7700" => "7700 - Produits exceptionnels"),
  'cooperative_banque' => Array(
						"" => "",
						"512" => "512 - Banque",
						"514" => "514 - Banque Postale",
						"530" => "530 - Caisse en espèces"),
  'cooperative_mode' => Array(
							"" => "",
							"C"=>"Chèque",
							"D"=>"Dépot",
							"P"=>"Prélèvement",
							"V"=>"Virement"),
// Champs ajoutés
  'type_user_impression'=>Array("" => "Tous types",									// Ajouté le 12/06/2012
								"P"=>"Directeur et Enseignants",
								"I"=>"Intervenants extérieurs",
								"S"=>"ATSEM",
								"U"=>"Autres"),

// Champs ajoutés dans la version 2.1
  'type_numerotation_impression' => Array("" => "Pages non numérotées",
										  "T" => "Toutes les pages numérotées"),
	'liste_biblio_recherche'=> Array(
				'' => "",
				'2' => "Bibliothèque de la classe",
				'3' => "Bibliothèque de l'école"),
  'cooperative_mode_recherche' => Array(
							"" => "",
							"C"=>"Chèque",
							"D"=>"Dépot",
							"P"=>"Prélèvement",
							"V"=>"Virement",
							"T"=>"Transfert"),
  'matieres_stat_court_listing' => Array(
						'A' => "FRANC",
						'B' => "MATHS",
						'C' => "LV,S,H,G,EC",
						'D' => "ARTS,EPS",
						'E' => "TRANS",
					),
  'questionsecrete' => Array(
							"" => "-- Non définie --",
							"A" => "Quel est votre film préféré ?",
							"B" => "Dans quelle ville êtes-vous né(e) ?",
							"C" => "Comment s'appelle votre meilleur(e) ami(e) ?",
							"D" => "Quel était le nom de votre premier animal de compagnie ?",
							"E" => "Comment s'appelait votre maître(sse) de CP ?",
					),
	'radio_nom' => Array(
			"ContactFM" => "Contact FM",
			"Europe1" => "Europe 1",
			"Europe2" => "Europe 2",
			"FIP" => "FIP",
			"FranceBleu" => "France Bleu",
			"FranceCulture" => "France Culture",
			"FranceInfo" => "France Info",
			"FranceInter" => "France Inter",
			"FranceMusique" => "France Musique",
			"FunRadio" => "Fun Radio",
			"LeMouv" => "Le Mouv'",
			"OuiFM" => "Ouï FM",
			"RadioClassique" => "Radio Classique",
			"Radio FG" => "Radio FG",
			"RFIMusique" => "RFI Musique",
			"RFM" => "RFM",
			"RMC" => "RMC",
			"RTL" => "RTL",
			"RTL2" => "RTL2",
			"Skyrock" => "Skyrock",
		),
	'radio_stream' => Array(
			"ContactFM" => "http://broadcast.infomaniak.ch/radio-contact-high.mp3",
			"Europe1" => "http://vipicecast.yacast.net/europe1",
			"Europe2" => "mms://viplagardere.yacast.net/encodereurope2",
			"FIP" => "http://www.tv-radio.com/station/fip_mp3/fip_mp3-128k.m3u",
			"FranceBleu" => "http://www.tv-radio.com/station/france_bleu_ile-de-france_mp3/france_bleu_ile-de-france_mp3-128k.asx",
			"FranceCulture" => "http://www.tv-radio.com/station/france_culture_mp3/france_culture_mp3-128k.asx",
			"FranceInfo" => "http://www.tv-radio.com/station/france_info/franceinfo-32k.m3u",
			"FranceInter" => "http://www.tv-radio.com/station/france_inter_mp3/france_inter_mp3-128k.asx",
			"FranceMusique" => "http://mp3.live.tv-radio.com/francemusique/all/francemusiquehautdebit.mp3",
			"FunRadio" => "http://radio.funradio.fr/funradio.asx",
			"LeMouv" => "http://www.tv-radio.com/station/le_mouv_mp3/le_mouv_mp3-128k.m3u",
			"OuiFM" => "http://broadcast.infomaniak.ch/ouifm-high.mp3",
			"RadioClassique" => "http://broadcast.infomaniak.net/radioclassique-high.mp3",
			"Radio FG" => "http://fg.impek.tv:80",
			"RFIMusique" => "http://mp3.live.tv-radio.com/rfimusiquemonde/all/rfimusiquemonde-64k.mp3",
			"RFM" => "http://vipicecast.yacast.net/rfm",
			"RMC" => "http://viphttp.yacast.net/V4/rmc/rmc.asx",
			"RTL" => "http://streaming.radio.rtl.fr/rtl-1-44-96",
			"RTL2" => "http://streaming.radio.rtl2.fr:80/rtl2-1-44-96",
			"Skyrock" => "http://player.skyrock.fm/V4/skyrock/skyrock.m3u",
		),
);
?>