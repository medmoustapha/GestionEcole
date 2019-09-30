<?php
  if (file_exists("commun/mpdf/mpdf.php"))
  {
	  require_once('mpdf/mpdf.php');

	  $orientation=$_GET['orientation'];
	  $couleur=$_GET['couleur'];

	// Récupération du fichier de style pour l'impression
	  $filename="themes/print_".$couleur."_".$Sens_Ecriture.".css";
	  $fp=fopen($filename,"r");
	  $contenu=fread($fp,filesize($filename));
	  fclose($fp);

	  $filename2 = "document.pdf";
	  
	  if ($orientation=="P")
	  {
		$html2pdf = new mPDF('utf-8','A4','','',10,10,10,15,0,8);
	  }
	  else
	  {
		$html2pdf = new mPDF('utf-8','A4-L','','',10,10,10,15,0,8);
	  }
	  $contenu=$contenu.str_replace("EURO","&euro;",$contenu_html);
	  $html2pdf->SetDirectionality($Sens_Ecriture);
	  if ($_GET['numerotation']=="")
	  {
				$footer = array (
					'odd' => array (
						'L' => array ('content' => $mentions_legales_bas_impression,'font-size' => 8,'font-style' => '','font-family' => 'arial','color'=>'#000000'),
						'C' => array ('content' => '','font-size' => 8,'font-style' => '','font-family' => 'arial','color'=>'#000000'),
						'R' => array ('content' => '','font-size' => 8,'font-style' => '','font-family' => 'arial','color'=>'#000000'),
						'line' => 0,
					),
					'even' => array (
						'L' => array ('content' => $mentions_legales_bas_impression,'font-size' => 8,'font-style' => '','font-family' => 'arial','color'=>'#000000'),
						'C' => array ('content' => '','font-size' => 8,'font-style' => '','font-family' => 'arial','color'=>'#000000'),
						'R' => array ('content' => '','font-size' => 8,'font-style' => '','font-family' => 'arial','color'=>'#000000'),
						'line' => 0,
					),
				);
		}
		else
		{
			$footer = array (
				'odd' => array (
					'L' => array ('content' => $mentions_legales_bas_impression,'font-size' => 8,'font-style' => '','font-family' => 'arial','color'=>'#000000'),
					'C' => array ('content' => '','font-size' => 8,'font-style' => '','font-family' => 'arial','color'=>'#000000'),
					'R' => array ('content' => '{PAGENO}','font-size' => 8,'font-style' => '','font-family' => 'arial','color'=>'#000000'),
					'line' => 0,
				),
				'even' => array (
					'L' => array ('content' => $mentions_legales_bas_impression,'font-size' => 8,'font-style' => '','font-family' => 'arial','color'=>'#000000'),
					'C' => array ('content' => '','font-size' => 8,'font-style' => '','font-family' => 'arial','color'=>'#000000'),
					'R' => array ('content' => '{PAGENO}','font-size' => 8,'font-style' => '','font-family' => 'arial','color'=>'#000000'),
					'line' => 0,
				),
		  );	  
	  }
	  $html2pdf->SetFooter($footer,'');
	  if ($couleur=="G") { $html2pdf->restrictColorSpace = 1; }
	  $html2pdf->WriteHTML($contenu);

	  $html2pdf->Output();
  }
  else
  {
	  require_once('html2pdf/html2pdf.class.php');

	  $orientation=$_GET['orientation'];
	  $couleur=$_GET['couleur'];

	// Récupération du fichier de style pour l'impression
	  $filename="themes/print_".$couleur."_".$Sens_Ecriture.".css";
	  $fp=fopen($filename,"r");
	  $contenu=fread($fp,filesize($filename));
	  fclose($fp);

	  $filename2 = "document.pdf";
	  
	  if ($orientation=="P")
	  {
		$contenu=$contenu.'<page backbottom="5">';
	  }
	  else
	  {
		$contenu=$contenu.'<page orientation="landscape" format="A4" backbottom="5">';
	  }
	  if ($_GET['numerotation']=="")
	  {
	    $contenu=$contenu.'<page_footer><font style="font-family:Arial;font-size:8px">'.$mentions_legales_bas_impression.'</font></page_footer>';
	  }
	  else
	  {
	    $contenu=$contenu.'<page_footer><table cellspacing=0 cellpadding=0 style="width:100%;font-size:8px;font-family:Arial"><tr><td style="width:50%;text-align:left">'.$mentions_legales_bas_impression.'</td><td style="width:50%;text-align:right">[[page_cu]]</td></tr></table></page_footer>';
	  }
	  $contenu=$contenu.str_replace("EURO","&euro;",$contenu_html)."</page>";

	  $html2pdf = new HTML2PDF($orientation,'A4','fr', true, 'UTF-8', array(10,10,10,10));
    $html2pdf->pdf->SetDisplayMode('real');
	  $html2pdf->mirrorMargins = true;
	  $html2pdf->WriteHTML($contenu, isset($_GET['vuehtml']));

	// Enregistrement du fichier
	  $html2pdf->Output("../cache/documents/".$filename2);
  }
?>
