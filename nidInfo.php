<?php

function getInfo($nid,$dob){
	$api = 'https://api.versionx10.co/Svv/X2X2.php?nid='.$nid.'&dob='.$dob;
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $api);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36'
	));
	$content = curl_exec($curl);
	curl_close($curl);
	return $content;
}

class NID{
	private $nid,$dob;
	

    public function __construct($nid,$dob){
        $this->nid = $nid;
		$this->dob = $dob;
    }

	function info(){
		return getInfo($this->nid,$this->dob);
	}
}
