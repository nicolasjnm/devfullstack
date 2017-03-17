<?php
/*
Class Name: BWW Bluesky Integration Class
Description: Class for the Bluesky Integration
Version:     0.1
Author:      Best Worlds Web - Hern�n J. Fraind
Author URI:  http://www.bestworldsweb.com
*/
class bluesky
{
    
    //Private Properties and values
    private $token = '';
    private $url = '';
    private $cId = '';
    private $cKey = '';
    private $user_email = '';
    
    // Private Functions
    private function postData($action, $fields){
        $postvars = http_build_query($fields); 
        $ch = curl_init();
        if($this->token =='')
            $this->requestToken();
        $headers[0]="Authorization: Bearer ". $this->token;
        curl_setopt($ch, CURLOPT_URL, $this->url . $action);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $response;
    }
    private function postJson($action, $json){
        $ch = curl_init();
        if($this->token =='')
            $this->requestToken();
        $headers[0]="Authorization: Bearer ". $this->token;
        curl_setopt($ch, CURLOPT_URL, $this->url . $action);
        $headers[1] = "Content-type: application/json";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $response;
    }
     private function getData($action){
        $ch = curl_init();
        if($this->token =='')
            $this->requestToken();
        $headers[0]="Authorization: Bearer ". $this->token;
        curl_setopt($ch, CURLOPT_URL, $this->url . $action);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($ch, CURLOPT_VERBOSE, 1);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $response;
    }
    
    private function requestToken()
    {

        $action = '/oauth/token';
        $fields = array(
            'client_id' => $this->cId,
            'grant_type' => 'client_credentials',
            'client_secret' => $this->cKey        
        );
        
        $postvars = http_build_query($fields); 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url.$action);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        $this->token = $response['access_token'];
    }
    
    
    
    //Public Functions
    public function __construct(){
        $this->cId = get_option('blueSky_cid');
        $this->cKey = get_option('blueSky_ckey');
        if(get_option('blueSky_stage') == 'test')
            $this->url = 'https://staging.pathlms.com';
        else
            $this->url = 'https://www.pathlms.com';
        $this->requestToken();
    }
    
    
    public function setUserEmail($email){
        $this->user_email = $email;
    }
    
    public function getToken() {
        if($this->token =='')
            $this->requestToken();
        echo $this->token;
    }
    
    public function getUser($user){
        $action = '/api/v1/users/show';
        $fields = 'email='. $user["email"];
        $action .= '/?'.$fields;
        $resp = $this->getData($action);
        //return json_decode($resp);        
        return $resp;        
    }
    
    public function addUser($add_user){
        $action = '/api/v1/users';
        $user = array(
                'email' => $add_user["email"],
                'first_name' => $add_user["fname"],
                'last_name' => $add_user["lname"]
            );
        $fields = array('user' =>$user);
        $json = json_encode($fields);
        return $this->postJson($action, $json);
    }
    
    public function getAllEvents()
    {
        $action = '/api/v1/sellable_items';
        $resp = $this->getData($action);
        return $resp;
    }
    
    public function subscribeToEvent($user, $event)
    {
        $action = '/api/v1/orders';
        $user = array(
                'email' => $user["email"],
                'first_name' => $user["fname"],
                'last_name' => $user["lname"]
            );
        $fields2 = array('user' =>$user, 'sellable' => array($event["id"]));
        $json = json_encode($fields2);
        return $this->postJson($action, $json);
        die();
    }
    public function subscribeOrGoToEvent($user, $event)
    {
        $action = '/api/v1/orders';
        $user = array(
                'email' => $user["email"],
                'first_name' => $user["fname"],
                'last_name' => $user["lname"]
            );
        $fields2 = array('user' =>$user, 'sellable' => array($event));
        $json = json_encode($fields2);
		$suscribeData=$this->postJson($action, $json);
		return $suscribeData;
    }
	public function genToken($user){
		$user_data=$this->getUser($user);
        $action = '/api/v1/users/generate_login_token?id='.$user_data["id"];
        $fields2 = array('user' =>$user);
        $json = json_encode($fields2);
        return $this->postJson($action, $json);
	}
}
?>