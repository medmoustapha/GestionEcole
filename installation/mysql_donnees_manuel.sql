INSERT INTO `profs` (id,nom,prenom,civilite,identifiant,passe,date_entree,type) VALUES ('1','directeur','directeur','1','directeur','e10adc3949ba59abbe56e057f20f883e','2000-01-01','D');

INSERT INTO `etablissement` (`parametre`, `valeur`) VALUES
('nom', ''),
('adresse', ''),
('zone', 'A'),
('debut_annee_scolaire', '2012-08-01'),
('fin_annee_scolaire', '2013-07-31'),
('decoupage_etab', '2');

INSERT INTO `config` (parametre,valeur) VALUES ('sauve_bdd','2012-02-01');
