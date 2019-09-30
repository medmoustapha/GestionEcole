<?php
  switch ($_GET['document'])
  {
    case "1":
	  include "cooperative/journal_general_impression.php";
	  $contenu_html=$contenu_page;
	  break;
    case "2":
	  include "cooperative/grand_livre_impression.php";
	  $contenu_html=$contenu_page;
	  break;
    case "3":
      $_GET['impression']=1;
	  include "cooperative/bilan_comptable.php";
	  $contenu_html=$contenu_page;
	  break;
  }
  
  include "commun/impression.php";
?>