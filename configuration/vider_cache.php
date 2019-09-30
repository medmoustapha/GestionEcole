<?php
    function rrmdir($dir)
    {
		$fp = opendir($dir);
		if ( $fp ) 
		{
			while ( $f = readdir($fp) ) 
			{
				$file = $dir . "/" . $f;
				if ( $f == "." || $f == ".." || $f == "logo_ecole.png" || $f == "logo_ecole.jpg" ) 
				{
					continue;
				}
				else if ( is_dir($file) ) 
				{
					rrmdir($file);
				}
				else 
				{
					unlink($file);
				}
			}
			closedir($fp);
	   
			rmdir($dir);
		}
    }
    rrmdir('accueil/subpanels/cache');
    rrmdir('cache/restore');
    rrmdir('cache/update');
    rrmdir('cache/upload');
    mkdir('accueil/subpanels/cache');
	mkdir("cache/restore");
	mkdir("cache/update");
	mkdir("cache/upload");
	
	$fp=opendir("cache/photos");
	if ($fp)
	{
	  while ($f=readdir($fp))
	  {
	    if ( $f != "." && $f != "..")
		{
	      if (substr($f,strlen($f)-9,9)=="_temp.jpg")
		  {
		    unlink("cache/photos/".$f);
		  }
		}
	  }
	}
?>