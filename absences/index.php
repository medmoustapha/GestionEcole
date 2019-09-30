<?php
  if ($_SESSION['type_util']=="E")
	{
	  include "absences/index_E.php";
	}
	else
	{
	  include "absences/index_D_P.php";
	}
?>