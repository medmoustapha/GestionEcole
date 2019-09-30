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

$tableau_variable['controles'] = Array(
  "id" => 			  Array( "type" => "single",
							 "nom" => "id",
							 "obligatoire" => "0",
							 "longueur" => "20"),
  "id_prof" => 		  Array( "type" => "single",
							 "nom" => "id_prof",
							 "obligatoire" => "0",
							 "longueur" => "5"),
  "id_classe" => 	  Array( "type" => "single",
							 "nom" => "id_classe",
							 "obligatoire" => "0",
							 "longueur" => "5"),
  "id_niveau" => 	  Array( "type" => "single",
							 "nom" => "id_niveau",
							 "obligatoire" => "0",
							 "longueur" => "10"),
  "id_matiere" =>   Array( "type" => "liste_prof",
						   "nom" => "id_matiere",
						   "obligatoire" => "0",
						   "longueur" => "10",
						   "partie" => "2",
							 "tabindex" => "2",
						   "nomliste" => "matieres_b"),
  "date" =>         Array( "type" => "date",
						   "nom" => "date",
							 "tabindex" => "1",
						   "min"=> $_SESSION['annee_scolaire'].$debut,
						   "max"=> $annee_p.$fin,
                           "obligatoire"=>"1"),
  "coefficient" => 	Array( "type" => "varchar",
						   "nom" => "coefficient",
						   "majuscule"=>"0",
						   "obligatoire" => "1",
							 "tabindex" => "5",
						   "longueur" => "4"),
  "trimestre" =>    Array( "type" => "liste",
                           "obligatoire"=>"0",
                           "nom" => "trimestre",
							 "tabindex" => "3",
						   "nomliste" => "livret_decoupage_".$gestclasse_config_plus['decoupage_livret']),
  "descriptif" => 	Array( "type" => "varchar_long",
						   "nom" => "descriptif",
						   "majuscule"=>"0",
							 "tabindex" => "6",
						   "obligatoire" => "1",
						   "longueur" => "255")
  );
?>