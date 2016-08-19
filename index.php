<?php
	require_once 'kfile.php';
	// Declare the class
class AustPostApi {
	
	// Constructor
	function AustPostAPI($auth_key,$api_url = 'https://auspost.com.au/api/') {
		 $this->apiURL = $api_url;
		 $this->auth_key = $auth_key;
	}

	public function getRemoteData($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  'Auth-Key: ' . $this->auth_key
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$contents = curl_exec ($ch);
		curl_close ($ch);
		return json_decode($contents,true);
	}
	public function getPostCode($data)
	{
		$edeliver_url = "{$this->apiURL}postcode/search.json?q=$data";
		$results = $this->getRemoteData($edeliver_url);
		
		if (isset($results['error']))
			throw new Exception($results['error']['errorMessage']);
		
		//return $results['postage_result']['total_cost'];
		return $results;
	}

}

// Create instance with key
$auspos = new AustPostAPI($akey);

if (isset($_GET['p'])){ 
	$postcode = rawurldecode($_GET["p"]);
	//echo "<p>p: $postcode</p>";
} 

if(((filter_var($postcode, FILTER_VALIDATE_INT)!==FALSE ) && strlen((string)$postcode)==4 )|| (preg_match('/^[a-z\040\.\-]+$/i', $postcode) && strlen($postcode)>2)){

	if($postcode){
		$result=$auspos->getPostCode(urlencode($postcode));

		echo "<p>";
		if($result){
			foreach ($result as $localities) {
				if(is_array($localities)){
					foreach ($localities as $locality){
						foreach ($locality as $location){
							if(count($location)==1){
								echo $locality["postcode"]." - ".$locality["location"].", ". $locality["state"] ."</br>";
								break;
							} else {
								echo $location["postcode"]." - ".$location["location"].", ". $location["state"] ."</br>";							
								//break; 
							} 
						}
					}				
				}
			}		
		}
		echo("</p>");
	}
}
?>
