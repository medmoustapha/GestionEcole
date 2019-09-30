<?php
  session_start();

  if (!file_exists("config.php"))
  {
    Header("Location:installation/index.php");
  }
  
  if (isset($_GET['faire']))
  {
    $adresse="statistiques.php?cle=".$_GET['cle']."&version=".$_GET['version']."&zone=".$_GET['zone']."&type=".$_GET['type'];
  }
  else
  {
    $adresse="vide.html";
  }
?>
<title>Gest'Ecole</title>
<link rel="shortcut icon" href="images/favicon.ico" />
<FRAMESET COLS="0,0,100%" FRAMEBORDER="no">
  <FRAME SRC="vide.html" NAME="radio" ID="radio">
  <FRAME SRC="<?php echo $adresse; ?>" NAME="calcul" ID="calcul">
  <FRAME SRC="index_principal.php" NAME="principal" ID="principal">
</FRAMESET>