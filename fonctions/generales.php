<?php

// Fonction qui retirer les accents d'une chaine
function retirer_accents($str, $charset='utf-8')
{
	$str= str_replace("é", "e", $str);
	
	$str = str_replace(array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û'),array('e', 'e', 'e', 'e', 'a', 'a', 'i', 'i', 'o', 'u', 'u'), $str);
	$str = str_replace(array('É', 'È', 'Ê', 'Ë', 'À', 'Â', 'Î', 'Ï', 'Ô', 'Ù', 'Û'),array('e', 'e', 'e', 'e', 'a', 'a', 'i', 'i', 'o', 'u', 'u'), $str);

    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace("#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#", "\1", $str);
    $str = preg_replace("#&([A-za-z]{2})(?:lig);#", "\1", $str); 
    $str = preg_replace("#&[^;]+;#", "", $str); 
	
	$str = str_replace("'", "-", $str);
    return $str;
}

// Fonction qui vérifie le format et la validité d'une date
function verifier_date($date)
{
	if (preg_match('#^([0-9]{2})([/-])([0-9]{2})\2([0-9]{4})$#', $date, $m) == 1 && checkdate($m[3], $m[1], $m[4]))
	{
		$resultat =  true;
	}
	else
	{
  		$resultat =  false;
	}
	
	return $resultat;
}

?>