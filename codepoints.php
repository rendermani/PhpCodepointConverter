<?php

function getRange($range) {
	$from = hexdec(str_replace("$","#",$range[0]));
	$to = hexdec(str_replace("$","#",$range[1]));
	echo "from:".$from.", to: ".$to."\n"; 
	echo "from:".str_replace("$","#",$range[0]).", to: ".str_replace("$","#",$range[1])."\n"; 
	$characters = array();
	for ($z = $from; $z <= $to; $z++) {	
		$character = html_entity_decode("&#".$z.";");	
		if( $character != "&#".$z.";" ) {
			$characters[$z] = $character;	
		}
		
	}
	return $characters;

}
function convertCodepoints($codepoints) {
	$characters = array();
	foreach (explode(",",$codepoints) as $key => $codepoint) {		
		$range = explode("-",$codepoint);
		if(isset($range[1])) {
			$characters = array_merge($characters,getRange($range));
		} else {
			$value = hexdec(str_replace("$","#",$range[0]));
			$character = html_entity_decode("&#".$value.";");	
			$characters[$character] = $value . ":" . $character . "\n";
		}
	}	
	return $characters;	
}

$characters = array();
$content = file_get_contents("export-codepoints.json");
$tlds = json_decode($content); 

//$tlds = [$all->tld[0]];
foreach($tlds->tld as $key => $tld) {
	if($tld->name == "sg") {
		echo "TLD: ".$tld->name."\n";
		echo ($tld->SocialDataLegalCodePoints)."\n";	
		$characters = array_merge($characters, convertCodepoints($tld->SocialDataLegalCodePoints));	
	}	
}


$searchCharacter = "Õ";
var_dump($characters);
$result = array_search($searchCharacter, $characters);
if($result > -1) {
	echo "found $searchCharacter ($result)\n";
	echo dechex($result)."\n";
	 
}
?>
