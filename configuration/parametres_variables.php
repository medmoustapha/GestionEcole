<?php
$listes_personnalisables['D']=Array("niveaux"=>$Langue['LBL_LISTES_NIVEAU'],
                                    "matieres_b"=>$Langue['LBL_LISTES_MATIERES_B'],
                                    "matieres_cj"=>$Langue['LBL_LISTES_MATIERES_CJ'],
                                    "categ_biblio_ecole"=>$Langue['LBL_LISTES_CATEG_BIBLIO_ECOLE'],
                                    "categ_biblio_classe"=>$Langue['LBL_LISTES_CATEG_BIBLIO_CLASSE']);

$listes_personnalisables['P']=Array("matieres_b"=>$Langue['LBL_LISTES_MATIERES_B'],
                                    "matieres_cj"=>$Langue['LBL_LISTES_MATIERES_CJ'],
                                    "categ_biblio_classe"=>$Langue['LBL_LISTES_CATEG_BIBLIO_CLASSE']);
                                 
$listes_colonne=Array("niveaux"=>"1",
                      "matieres_b"=>"2",
                      "matieres_cj"=>"1",
                      "categ_biblio_ecole"=>"1",
                      "categ_biblio_classe"=>"1");

$listes_auteurs=Array("niveaux"=>"D",
                      "matieres_b"=>"P",
                      "matieres_cj"=>"P",
                      "categ_biblio_ecole"=>"D",
                      "categ_biblio_classe"=>"P");

$listes_table=Array("niveaux"=>"cahierjournal|classes_niveaux|competences|competences_categories|controles|devoirs|eleves_classes|moyenne_classe|param_persos",
                    "matieres_b"=>"controles",
                    "matieres_cj"=>"cahierjournal|devoirs",
                    "categ_biblio_ecole"=>"bibliotheque",
                    "categ_biblio_classe"=>"bibliotheque");

$listes_colonne_table=Array("niveaux"=>"id_niveau|id_niveau|id_niveau|id_niveau|id_niveau|id_niveau|id_niveau|id_niveau|niveau_en_cours",
							"matieres_b"=>"id_matiere",
							"matieres_cj"=>"id_matiere|id_matiere",
							"categ_biblio_ecole"=>"id_cat",
							"categ_biblio_classe"=>"id_cat");

?>
