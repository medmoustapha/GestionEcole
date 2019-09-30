CREATE TABLE IF NOT EXISTS `absences` (
  `date` date NOT NULL,
  `id_eleve` varchar(20) NOT NULL,
  `matin` int(4) NOT NULL,
  `apres_midi` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `absences_justificatifs` (
  `id` varchar(20) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `heure_debut` varchar(1) NOT NULL,
  `heure_fin` varchar(1) NOT NULL,
  `id_eleve` varchar(20) NOT NULL,
  `type` varchar(2) NOT NULL,
  `motif` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `accueil_news` (
  `id` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `id_prof` varchar(5) NOT NULL,
  `cible` varchar(1) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `accueil_perso` (
  `id` varchar(10) NOT NULL,
  `id_util` varchar(20) NOT NULL,
  `subpanel` varchar(50) NOT NULL,
  `titre` varchar(50) NOT NULL,
  `ordre` int(5) NOT NULL,
  `colonne` int(3) NOT NULL,
  `parametre` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bibliotheque` (
  `id` varchar(20) NOT NULL,
  `id_cat` varchar(10) NOT NULL,
  `id_prof` varchar(5) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `resume` text NOT NULL,
  `couverture` text NOT NULL,
  `editeur` varchar(100) NOT NULL,
  `collection` varchar(100) NOT NULL,
  `auteur` varchar(255) NOT NULL,
  `etat` varchar(2) NOT NULL,
  `isbn` varchar(17) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bibliotheque_emprunt` (
  `id` varchar(20) NOT NULL,
  `id_util` varchar(20) NOT NULL,
  `type_util` varchar(1) NOT NULL,
  `id_livre` varchar(20) NOT NULL,
  `date_emprunt` date NOT NULL,
  `date_retour` date NOT NULL,
  `reservation` int(2) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cahierjournal` (
  `id` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `id_prof` varchar(5) NOT NULL,
  `id_classe` varchar(5) NOT NULL,
  `id_matiere` varchar(10) NOT NULL,
  `id_niveau` varchar(10) NOT NULL,
  `contenu` text NOT NULL,
  `parent` text NOT NULL,
  `objectifs` text NOT NULL,
  `materiel` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `classes` (
  `id` varchar(5) NOT NULL,
  `nom_classe` varchar(255) NOT NULL,
  `annee` year(4) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `classes_niveaux` (
  `id` varchar(10) NOT NULL,
  `id_classe` varchar(5) NOT NULL,
  `id_niveau` varchar(10) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `classes_profs` (
  `id` varchar(10) NOT NULL,
  `id_classe` varchar(5) NOT NULL,
  `id_prof` varchar(5) NOT NULL,
  `type` varchar(1) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `competences` (
  `id` varchar(20) NOT NULL,
  `id_cat` varchar(10) NOT NULL,
  `code` varchar(10) NOT NULL,
  `intitule` text NOT NULL,
  `ordre` int(10) NOT NULL,
  `id_prof` varchar(5) NOT NULL,
  `id_niveau` varchar(10) NOT NULL,
  `supprime` date NOT NULL,
  `cree` date NOT NULL,
  `statistiques` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `competences_categories` (
  `id` varchar(10) NOT NULL,
  `id_prof` varchar(5) NOT NULL,
  `id_niveau` varchar(10) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `id_parent` varchar(10) NOT NULL,
  `ordre` int(10) NOT NULL,
  `supprime` date NOT NULL,
  `cree` date NOT NULL DEFAULT '2012-01-22',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `config` (
  `parametre` varchar(50) NOT NULL,
  `valeur` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `contacts_eleves` (
  `id` varchar(64) NOT NULL,
  `id_eleve` varchar(64) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `lien` varchar(255) NOT NULL,
  `adresse` text NOT NULL,
  `tel` varchar(50) NOT NULL,
  `tel2` varchar(50) NOT NULL,
  `portable` varchar(50) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `controles` (
  `id` varchar(20) NOT NULL,
  `id_classe` varchar(5) NOT NULL,
  `id_prof` varchar(5) NOT NULL,
  `id_niveau` varchar(10) NOT NULL,
  `id_matiere` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `trimestre` varchar(2) NOT NULL,
  `descriptif` varchar(255) NOT NULL,
  `coefficient` int(4) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `controles_competences` (
  `id` varchar(20) NOT NULL,
  `id_controle` varchar(20) NOT NULL,
  `id_competence` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `controles_resultats` (
  `id_eleve` varchar(20) NOT NULL,
  `id_controle` varchar(20) NOT NULL,
  `id_competence` varchar(20) NOT NULL,
  `resultat` varchar(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cooperative` (
  `id` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `mode` varchar(1) NOT NULL,
  `ligne_comptable` varchar(5) NOT NULL,
  `piece` varchar(255) NOT NULL,
  `id_classe` varchar(5) NOT NULL,
  `montant` double(10,2) NOT NULL,
  `pointe` int(2) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `releve` varchar(255) NOT NULL,
  `banque` varchar(4) NOT NULL,
  `tiers` varchar(20) NOT NULL,
  `reference_bancaire` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cooperative_bilan` (
  `annee` year(4) NOT NULL,
  `clos` int(2) NOT NULL,
  `nb_200` int(4) NOT NULL,
  `nb_100` int(4) NOT NULL,
  `nb_50` int(4) NOT NULL,
  `nb_20` int(4) NOT NULL,
  `nb_10` int(4) NOT NULL,
  `nb_5` int(4) NOT NULL,
  `nb_2` int(4) NOT NULL,
  `nb_1` int(4) NOT NULL,
  `nb_05` int(4) NOT NULL,
  `nb_02` int(4) NOT NULL,
  `nb_01` int(4) NOT NULL,
  `nb_005` int(4) NOT NULL,
  `nb_002` int(4) NOT NULL,
  `nb_001` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cooperative_tiers` (
  `id` varchar(7) NOT NULL,
  `nom` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `dates_speciales` (
  `date` date NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `devoirs` (
  `id` varchar(20) NOT NULL,
  `id_prof` varchar(5) NOT NULL,
  `id_classe` varchar(5) NOT NULL,
  `id_niveau` varchar(10) NOT NULL,
  `id_matiere` varchar(10) NOT NULL,
  `date_donnee` date NOT NULL,
  `date_faire` date NOT NULL,
  `contenu` text NOT NULL,
  `id_seance` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `eleves` (
  `id` varchar(20) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `sexe` int(3) NOT NULL,
  `date_naissance` date NOT NULL,
  `lieu_naissance` varchar(255) NOT NULL,
  `adresse` text NOT NULL,
  `nom_pere` varchar(255) NOT NULL,
  `prenom_pere` varchar(255) NOT NULL,
  `civilite_pere` varchar(1) NOT NULL,
  `adresse_pere` text NOT NULL,
  `tel_pere` varchar(50) NOT NULL,
  `tel2_pere` varchar(50) NOT NULL,
  `portable_pere` varchar(50) NOT NULL,
  `email_pere` varchar(255) NOT NULL,
  `nom_mere` varchar(255) NOT NULL,
  `nom_jf_mere` varchar(255) NOT NULL,
  `prenom_mere` varchar(255) NOT NULL,
  `civilite_mere` varchar(1) NOT NULL,
  `adresse_mere` text NOT NULL,
  `tel_mere` varchar(50) NOT NULL,
  `tel2_mere` varchar(50) NOT NULL,
  `portable_mere` varchar(50) NOT NULL,
  `email_mere` varchar(255) NOT NULL,
  `date_entree` date NOT NULL,
  `date_sortie` date NOT NULL,
  `identifiant` varchar(100) NOT NULL,
  `passe` varchar(255) NOT NULL,
  `assurance_rc` varchar(255) NOT NULL,
  `assurance_ia` varchar(255) NOT NULL,
  `infos_compl` text NOT NULL,
  `sixieme` int(2) NOT NULL,
  `derniere_connexion` date NOT NULL,
  `recevoir_email_pere` int(2) NOT NULL,
  `recevoir_email_mere` int(2) NOT NULL,
  `responsable_pere` int(2) NOT NULL,
  `responsable_mere` int(2) NOT NULL,
  `questionsecrete` varchar(1) NOT NULL,
  `reponse` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `eleves_classes` (
  `id` varchar(20) NOT NULL,
  `id_eleve` varchar(20) NOT NULL,
  `id_classe` varchar(5) NOT NULL,
  `id_niveau` varchar(10) NOT NULL,
  `redoublement` int(4) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `email` (
  `id` varchar(50) NOT NULL,
  `id_expediteur` varchar(20) NOT NULL,
  `type_expediteur` varchar(1) NOT NULL,
  `id_destinataire` text NOT NULL,
  `etat` text NOT NULL,
  `id_dossier_exp` text NOT NULL,
  `id_dossier_dest` text NOT NULL,
  `titre` varchar(255) NOT NULL,
  `messagerie` text NOT NULL,
  `date` datetime NOT NULL,
  `pj` text NOT NULL,
  `pj_nom` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `etablissement` (
  `parametre` varchar(50) NOT NULL,
  `valeur` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `listes` (
  `id` varchar(10) NOT NULL,
  `nom_liste` varchar(255) NOT NULL,
  `intitule` varchar(255) NOT NULL,
  `ordre` int(10) NOT NULL,
  `id_prof` varchar(5) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `livrets_appreciation` (
  `id` varchar(20) NOT NULL,
  `id_eleve` varchar(20) NOT NULL,
  `id_util` varchar(5) NOT NULL,
  `type_util` varchar(1) NOT NULL,
  `trimestre` varchar(2) NOT NULL,
  `annee` year(4) NOT NULL,
  `appreciation` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `moyenne_classe` (
  `id_classe` varchar(5) NOT NULL,
  `id_niveau` varchar(20) NOT NULL,
  `marquage` int(4) NOT NULL,
  `periode1` decimal(5,2) NOT NULL,
  `periode2` decimal(5,2) NOT NULL,
  `periode3` decimal(5,2) NOT NULL,
  `periode4` decimal(5,2) NOT NULL,
  `periode5` decimal(5,2) NOT NULL,
  `statistiques` varchar(1) NOT NULL DEFAULT 'G'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `param_persos` (
  `id_prof` varchar(20) NOT NULL,
  `annee` year(4) NOT NULL,
  `id_classe_cours` varchar(5) NOT NULL,
  `theme` varchar(50) NOT NULL,
  `affiche` tinyint(2) NOT NULL,
  `niveau_en_cours` varchar(10) NOT NULL,
  `devoirs` tinyint(2) NOT NULL,
  `personnels` text NOT NULL,
  `classes` text NOT NULL,
  `eleves` text NOT NULL,
  `bibliotheque` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `param_profs_plus` (
  `id_prof` varchar(5) NOT NULL,
  `parametre` varchar(50) NOT NULL,
  `valeur` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `profs` (
  `id` varchar(5) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `civilite` varchar(1) NOT NULL,
  `civilite_conjoint` varchar(1) NOT NULL,
  `adresse` text NOT NULL,
  `tel` varchar(50) NOT NULL,
  `tel2` varchar(50) NOT NULL,
  `portable` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_naissance` date NOT NULL,
  `nom_conjoint` varchar(255) NOT NULL,
  `prenom_conjoint` varchar(255) NOT NULL,
  `tel_conjoint` varchar(50) NOT NULL,
  `tel2_conjoint` varchar(50) NOT NULL,
  `port_conjoint` varchar(50) NOT NULL,
  `identifiant` varchar(255) NOT NULL,
  `passe` varchar(100) NOT NULL,
  `type` varchar(2) NOT NULL,
  `date_entree` date NOT NULL,
  `date_sortie` date NOT NULL,
  `infos_compl` text NOT NULL,
  `derniere_connexion` date NOT NULL,
  `recevoir_email` int(2) NOT NULL,
  `date_entree_en` date NOT NULL,
  `date_derniere_inspection` date NOT NULL,
  `echelon` varchar(10) NOT NULL,
  `questionsecrete` varchar(1) NOT NULL,
  `reponse` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `reunions` (
  `id` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `resume` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `id_util` text NOT NULL,
  `type` varchar(1) NOT NULL,
  `id_saisie` varchar(21) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `signatures` (
  `id` varchar(20) NOT NULL,
  `id_util` varchar(20) NOT NULL,
  `type_util` varchar(1) NOT NULL,
  `date` date NOT NULL,
  `parametre` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `taches` (
  `id` varchar(50) NOT NULL,
  `id_util` varchar(20) NOT NULL,
  `type_util` varchar(1) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `tache` text NOT NULL,
  `priorite` varchar(1) NOT NULL,
  `etat` varchar(1) NOT NULL,
  `date_echeance` date NOT NULL,
  `heure_echeance` time NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `vacances` (
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `zone` varchar(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
