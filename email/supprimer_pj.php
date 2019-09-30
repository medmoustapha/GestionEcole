<?php
  $id_p=explode(", ",$_GET['id']);
	for ($j=0;$j<count($id_p);$j++)
	{
	  if ($j==count($id_p)-1) { $id_p[$j]=substr($id_p[$j],0,strlen($id_p[$j])-1); }
	  unlink("../cache/email/".$id_p[$j]);
	}
?>