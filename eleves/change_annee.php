<?php
  $annee=$_POST['annee'];
  echo str_replace('<select','<select onChange="Change_Classe()"',Liste_Classes("id_classe","form",$annee,"","",false));
?>