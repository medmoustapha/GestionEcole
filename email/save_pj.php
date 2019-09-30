<?php
	function Construit_Id_PJ($extension)
	{

		$Chaine="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";

		do
		{
			$id_cree="";
			$trouve=0;
			for ($i=1;$i<=100;$i++)
			{
				$position = mt_rand(0,61);
				$id_cree = $id_cree . substr($Chaine,$position,1);
			}
			$req = mysql_query("SELECT * FROM `email` WHERE pj = '".$id_cree.".".$extension."'");
			if (mysql_num_rows($req)!="") { $trouve = 1; }
		} while ($trouve==1);

		return $id_cree;
	}

  $content_dir = 'cache/email/';
  $tmp_file = $_FILES['pj']['tmp_name'];

  if(is_uploaded_file($tmp_file))
  {
	  $info = pathinfo($_FILES['pj']['name']);
		$extension = $info['extension'];
    $name_file = str_replace(" ","_",$_FILES['pj']['name']);
		$pj=Construit_Id_PJ($extension).'.'.$extension;
    if(move_uploaded_file($tmp_file, $content_dir . $pj))
    {
      echo '<script language="javascript" type="text/javascript">parent.principal.formUploadCallback(\''.$name_file.'\',\''.$pj.'\');</script>';
    }
    else
    {
      echo '<script language="javascript" type="text/javascript">parent.principal.formUploadCallback(\'move\',\'\');</script>';
    }
  }
  else
  {
    echo '<script language="javascript" type="text/javascript">parent.principal.formUploadCallback(\'upload\',\'\');</script>';
  }
?>