<?php
  $cle=$_GET['cle'];
  $type=$_GET['type'];
  $zone=$_GET['zone'];
  $version=$_GET['version'];

  Header("Location:http://www.doxconception.com/stats/statistiques.php?cle=$cle&type=$type&zone=$zone&version=$version");
?>