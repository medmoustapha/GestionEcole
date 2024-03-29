<?php
$tableau_variable['personnels'] = Array(
  "id" => 				  Array( "type" => "single",
								           "nom" => "id",
								           "obligatoire" => "0",
								           "longueur" => "128"),
  "nom" => 				  Array( "type" => "varchar",
								           "nom" => "nom",
						               "majuscule"=>"1",
								           "obligatoire" => "1",
													 "tabindex" => "2",
													 "recherche" => "1",
								           "longueur" => "255"),
  "prenom" => 			Array( "type" => "varchar",
								           "nom" => "prenom",
						               "majuscule"=>"0",
													 "tabindex" => "3",
								           "obligatoire" => "1",
								           "longueur" => "255"),
  "civilite" =>     Array( "type" => "liste",
                           "nom" => "civilite",
								           "obligatoire" => "1",
													 "tabindex" => "1",
								           "nomliste" => "civilite"),
  "adresse" =>    	Array( "type" => "texte",
								           "obligatoire" => "0",
								           "largeur" => "40",
													 "tabindex" => "5",
								           "hauteur" => "6",
								           "nom" => "adresse"),
  "tel" => 			    Array( "type" => "varchar",
								           "nom" => "tel",
						               "majuscule"=>"0",
													 "tabindex" => "12",
								           "obligatoire" => "0",
													 "recherche" => "9",
								           "longueur" => "50"),
  "tel2" => 			  Array( "type" => "varchar",
								           "nom" => "tel2",
						               "majuscule"=>"0",
													 "tabindex" => "13",
								           "obligatoire" => "0",
													 "recherche" => "10",
								           "longueur" => "50"),
  "portable" => 		Array( "type" => "varchar",
								           "nom" => "portable",
						               "majuscule"=>"0",
													 "tabindex" => "14",
								           "obligatoire" => "0",
													 "recherche" => "11",
								           "longueur" => "50"),
  "email" => 	 		  Array( "type" => "email",
								           "nom" => "email",
						               "majuscule"=>"0",
													 "tabindex" => "15",
								           "obligatoire" => "0",
													 "recherche" => "12",
								           "longueur" => "255"),
  "nom_conjoint" => 			 Array( "type" => "varchar",
                                  "nom" => "nom_conjoint",
						                      "majuscule"=>"1",
																	"tabindex" => "18",
								                  "obligatoire" => "0",
								                  "longueur" => "255"),
  "prenom_conjoint" => 		 Array( "type" => "varchar",
                                  "nom" => "prenom_conjoint",
						                      "majuscule"=>"0",
																	"tabindex" => "19",
								                  "obligatoire" => "0",
                                  "longueur" => "255"),
  "civilite_conjoint" =>   Array( "type" => "liste",
                                  "nom" => "civilite_conjoint",
								                  "obligatoire" => "0",
																	"tabindex" => "17",
								                  "nomliste" => "civilite"),
  "tel_conjoint" => 			 Array( "type" => "varchar",
								                  "nom" => "tel_conjoint",
						                      "majuscule"=>"0",
																	"tabindex" => "20",
								                  "obligatoire" => "0",
								                  "longueur" => "50"),
  "tel2_conjoint" => 			 Array( "type" => "varchar",
								                  "nom" => "tel2_conjoint",
						                      "majuscule"=>"0",
																	"tabindex" => "21",
								                  "obligatoire" => "0",
								                  "longueur" => "50"),
  "port_conjoint" => 		   		Array("type" => "varchar",
																		"nom" => "port_conjoint",
																		"tabindex" => "22",
																		"majuscule"=>"0",
																		"obligatoire" => "0",
																		"longueur" => "50"),
  "identifiant" => 					Array("type" => "varchar",
																	"nom" => "identifiant",
																	"majuscule"=>"0",
																	"obligatoire" => "0",
																	"tabindex" => "23",
																	"recherche" => "13",
																	"longueur" => "255"),
  "passe" => 	 		  			Array("type" => "password",
																"nom" => "passe",
																"tabindex" => "24",
																"obligatoire" => "0",
																"longueur" => "100"),
  "date_entree" =>  				Array( "type" => "date",
								           "obligatoire" => "1",
													 "tabindex" => "7",
													 "recherche" => "4",
								           "nom" => "date_entree"),
  "date_sortie" =>  				Array("type" => "date",
																	"obligatoire" => "0",
																	"tabindex" => "8",
																	"recherche" => "5",
																	"nom" => "date_sortie"),
  "date_naissance" =>Array("type" => "date",
													 "obligatoire" => "1",
													 "recherche" => "3",
													 "tabindex" => "4",
								           "nom" => "date_naissance"),
  "type" =>         Array( "type" => "liste",
													 "nom" => "type",
								           "obligatoire" => "1",
													 "recherche" => "2",
													 "tabindex" => "6",
													 'option_vide'=>"0",
													 "option" => "valeur",
								           "nomliste" => "type_user"),
  "infos_compl" =>  				Array( "type" => "texte",
								           "obligatoire" => "0",
								           "largeur" => "100",
													 "tabindex" => "25",
								           "hauteur" => "7",
								           "nom" => "infos_compl"),
  "derniere_connexion" => 		    Array( "type" => "single",
								           "nom" => "derniere_connexion",
								           "obligatoire" => "0",
								           "longueur" => "10"),
  "recevoir_email" => 	  			Array( "type" => "checkbox",
													 "tabindex" => "16",
								           "nom" => "recevoir_email"),
  "date_entree_en" =>  				Array( "type" => "date",
								           "obligatoire" => "1",
													 "tabindex" => "9",
													 "recherche" => "6",
								           "nom" => "date_entree_en"),
  "date_derniere_inspection" =>  	Array( "type" => "date",
								           "obligatoire" => "1",
													 "tabindex" => "10",
													 "recherche" => "7",
								           "nom" => "date_derniere_inspection"),
  "echelon" => 					    Array( "type" => "varchar",
								           "nom" => "echelon",
													 "majuscule"=>"0",
													 "tabindex" => "11",
													 "recherche" => "8",
								           "obligatoire" => "0",
								           "longueur" => "10"),
);

$tableau_recherche['personnels'] = Array(
  "nom" => 				  Array( "type" => "varchar",
								           "nom" => "nom",
						               "majuscule"=>"1",
								           "obligatoire" => "1",
													 "tabindex" => "1",
													 "recherche" => "1",
								           "longueur" => "255"),
  "type" =>         Array( "type" => "liste",
													 "nom" => "type",
								           "obligatoire" => "1",
													 "recherche" => "2",
													 "tabindex" => "2",
													 'option_vide'=>"0",
													 "option" => "valeur",
								           "nomliste" => "type_user"),
  "date_naissance" =>Array("type" => "date",
													 "obligatoire" => "1",
													 "recherche" => "3",
													 "tabindex" => "3",
								           "nom" => "date_naissance"),
  "date_entree" =>  				Array( "type" => "date",
								           "obligatoire" => "1",
													 "tabindex" => "4",
													 "recherche" => "4",
								           "nom" => "date_entree"),
  "date_sortie" =>  				Array("type" => "date",
																	"obligatoire" => "0",
																	"tabindex" => "5",
																	"recherche" => "5",
																	"nom" => "date_sortie"),
  "date_entree_en" =>  				Array( "type" => "date",
								           "obligatoire" => "1",
													 "tabindex" => "6",
													 "recherche" => "6",
								           "nom" => "date_entree_en"),
  "identifiant" => 					Array("type" => "varchar",
																	"nom" => "identifiant",
																	"majuscule"=>"0",
																	"obligatoire" => "0",
																	"tabindex" => "7",
																	"recherche" => "13",
																	"longueur" => "255"),
  "tel" => 			    Array( "type" => "varchar",
								           "nom" => "tel",
						               "majuscule"=>"0",
													 "tabindex" => "8",
								           "obligatoire" => "0",
													 "recherche" => "9",
								           "longueur" => "50"),
  "tel2" => 			  Array( "type" => "varchar",
								           "nom" => "tel2",
						               "majuscule"=>"0",
													 "tabindex" => "9",
								           "obligatoire" => "0",
													 "recherche" => "10",
								           "longueur" => "50"),
  "portable" => 		Array( "type" => "varchar",
								           "nom" => "portable",
						               "majuscule"=>"0",
													 "tabindex" => "10",
								           "obligatoire" => "0",
													 "recherche" => "11",
								           "longueur" => "50"),
  "email" => 	 		  Array( "type" => "email",
								           "nom" => "email",
						               "majuscule"=>"0",
													 "tabindex" => "11",
								           "obligatoire" => "0",
													 "recherche" => "12",
								           "longueur" => "255"),
  "date_derniere_inspection" =>  	Array( "type" => "date",
								           "obligatoire" => "1",
													 "tabindex" => "12",
													 "recherche" => "7",
								           "nom" => "date_derniere_inspection"),
  "echelon" => 					    Array( "type" => "varchar",
								           "nom" => "echelon",
													 "majuscule"=>"0",
													 "tabindex" => "13",
													 "recherche" => "8",
								           "obligatoire" => "0",
								           "longueur" => "10"),
);
?>