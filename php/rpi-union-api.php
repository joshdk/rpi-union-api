<?php
//Requires php-pear-HTTP-Request2
include("HTTP/Request2.php");


//Wrapper class for the RPI Union API
class rpiunionapi{
	private $apikey;
	private $debug;


	//Class constructor
	//ex. $api = new rpiunionapi();
	public function __construct(){
		$this->apikey=NULL;
	}


	//Set sebug status
	//ex. $api->setdebug(true);
	public function set_debug($debug){
		$this->debug=$debug;
	}


	//Print data, if debugging is turned on
	//ex. $this->debug("nope, no bugs here...");
	private function debug($text){
		if($this->debug){
			echo "$text";
		}
	}


	//Sets the API key
	//ex. $api->set_apikey("...");
	public function set_apikey($apikey){
		$this->apikey=$apikey;
	}


	//Gets a student's name, given their RSCID or RIN
	//Documented here: <https://clubs.union.rpi.edu/?content_id=260>
	//ex. $api->get_name(array("rcsid"=>"jackss"),$name);
	//ex. $api->get_name(array("rin"=>"123456789"),$name);
	public function get_name($options,&$name){
		$request = new HTTP_Request2('http://api.union.rpi.edu/query.php',HTTP_Request2::METHOD_GET);
		$request->getUrl()->setQueryVariables(array(
			"task"=>"GetUser",
			"apikey"=>$this->apikey,
		));

		if(array_key_exists("rcsid",$options)){
			$request->getUrl()->setQueryVariable("rcsid",$options["rcsid"]);
		}else if(array_key_exists("rin",$options)){
			$request->getUrl()->setQueryVariable("rin",$options["rin"]);
		}else{
			return false;
		}

		if(array_key_exists("middlename",$options)){
			$request->getUrl()->setQueryVariable("middlename",NULL);
		}

		$data=json_decode($request->send()->getBody());
		$this->debug("rpiapi->get_name() returned code ".$data->status->statusCode." (".$data->status->statusText.")");
		if($data->status->statusCode==0){
			$name=$data->result->name;
			return true;
		}
		return false;
	}

}


?>
