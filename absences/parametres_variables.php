<?php
if ($gestclasse_config_plus['etendue_annee_scolaire']=="1")
{
  $annee_p=$_SESSION['annee_scolaire'];
}
else
{
  $annee_p=$_SESSION['annee_scolaire']+1;
}
$debut=str_replace('-','',$gestclasse_config_plus['debut_annee_scolaire']);
$fin=str_replace('-','',$gestclasse_config_plus['fin_annee_scolaire']);

$tableau_variable['justificatifs'] = Array(
  "id" => 				 Array( "type" => "single",
								          "nom" => "id",
                          "obligatoire"=>"0",
								          "longueur" => "20"),
  "date_debut" =>  Array( "type" => "date",
								          "nom" => "date_debut",
								          "min"=> $_SESSION['annee_scolaire'].$debut,
								          "max"=> $annee_p.$fin,
													"tabindex"=>"3",
                          "obligatoire"=>"1"),
  "date_fin" => 	 Array( "type" => "date",
								          "nom" => "date_fin",
								          "min"=> $_SESSION['annee_scolaire'].$debut,
								          "max"=> $annee_p.$fin,
													"tabindex"=>"5",
                          "obligatoire"=>"1"),
  "heure_debut" => Array( "type" => "liste",
                          "nom" => "heure_debut",
													"tabindex"=>"4",
                          "obligatoire"=>"0",
								          "nomliste" => "absence_debut"),
  "heure_fin" =>   Array( "type" => "liste",
                          "nom" => "heure_fin",
                          "obligatoire"=>"0",
													"tabindex"=>"6",
								          "nomliste" => "absence_fin"),
  "motif" =>       Array( "type" => "texte",
                          "obligatoire"=>"1",
								          "largeur" => "80",
								          "hauteur" => "5",
													"tabindex"=>"7",
								          "nom" => "motif"),
  "type" =>        Array( "type" => "liste",
                          "obligatoire"=>"0",
													"tabindex"=>"2",
                          "nom" => "type",
								          "nomliste" => "type_justificatif"),
  "id_eleve" => 	 Array( "type" => "single",
								          "nom" => "id_eleve",
                          "obligatoire"=>"0",
								          "longueur" => "20"),
);

$tableau_recherche['absence'] = Array(
  'eleve' => Array(	"type" => "varchar",
										"nom" => "eleve",
										"longueur" => "255",
										"tabindex"=>"1",
										"recherche" => "1"),
	'justificatif' => Array("type" => "liste",
													"nom" => "justificatif",
													"nomliste" => "type_justificatif",
													"option" => "cle",
										"tabindex"=>"2",
													"option_vide" => "1"),
  'motif' => Array(	"type" => "varchar",
										"nom" => "motif",
										"tabindex"=>"3",
										"longueur" => "255")
);
?>