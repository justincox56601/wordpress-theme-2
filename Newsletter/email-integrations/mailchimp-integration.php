<?php

class Mailchimp {
    //properties
    private $clientId = "313367340099";
    private $clientSecret = "2abdb892a80fc6057a2c49e0112f4c681c912a197d665b2485";
    private $redirectUri = "http://mcredirect.justincox.tech/";
    public $authorizeUri = "https://login.mailchimp.com/oauth2/authorize";
    public $accessTokenUri = "https://login.mailchimp.com/oauth2/token";
    public $baseUri = "https://login.mailchimp.com/oauth2/";
    public $metadataUri = "https://login.mailchimp.com/oauth2/metadata";

    public $accessToken;
    public $dc;
    public $userId;
    public $apiEndpoint;
    public $lists = array();
    public $activeList;
    


    //constructor
    function __construct(){
        $data = get_option("mailchimp_init");
        if($data != NULL && $data !== False){
            $this->load_mailchimp_data();
            $this->activeList = get_option("active_email");
        }else{
            add_option("mailchimp_init");
            $this->save_mailchimp_data();
        }
    }

    //Interface Methods
    public function check_access_token(){
        //returns true or false depending on if there has a valid acccess token
        if($this->accessToken){
            return true;
        }else{
            return false;
        }
    }

    public function get_auth_link(){
        $args = array(
            "response_type" => "code",
            "client_id" => $this->clientId,
            "redirect_uri" => $this->redirectUri            
        );
        $url = $this->authorizeUri . '?' . http_build_query($args);
        return $url;
    }

    public function get_access_token($code){
        $data = array(
            "grant_type" => "authorization_code",
            "client_id" => $this->clientId,
            "client_secret" => $this->clientSecret,
            "code" =>$code,
            "redirect_uri" => $this->redirectUri
        );

        $url = $this->accessTokenUri;
        $response = json_decode($this->post_curl($url, $data));
        $this->accessToken =  $response->access_token;
        $header = array(
            "User-Agent: oauth2-draft-v10",
            "Host: login.mailchimp.com",
            "Accept: application/json",
            "Authorization: OAuth " . $this->accessToken,
        );

        $resp = $this->get_curl($this->metadataUri, $header);
        $resp = json_decode($resp);
        $this->dc = $resp->dc;
        $this->userId = $resp->user_id;
        $this->apiEndpoint = $resp->api_endpoint . "/3.0";

        $this->get_email_lists();

        $this->save_mailchimp_data();
    }
        
    public function add_new_subscriber($args){
        extract($args);
        $url = $this->apiEndpoint . "/lists/" . $this->activeList . "/members";
        $data = json_encode(array(
            "email_address" => sanitize_email($email),
            "status" => $status
        ));

        $header = array(
            "User-Agent: oauth2-draft-v10",
            //"Host: login.mailchimp.com",
            "Accept: application/json",
            "Authorization: OAuth " . $this->accessToken,
        );

        return $this->post_curl($url, $data, $header);
        
    }

    public function get_email_lists(){
        $url = $this->apiEndpoint . "/lists";
        $resp = json_decode($this->get_curl($url));
        $temp = array();
        foreach($resp->lists as $list){
            $temp[] = array(
                "name" => $list->name,
                "id" => $list->id,
            );
            $this->lists = $temp;
        }
        $this->activeList = $this->lists[1]["id"];
        $this->save_mailchimp_data();
    }

    public function get_curl($url, $header=array()){
        //optional header argument for access token
        
        $handle = curl_init();
        curl_setopt_array($handle, array(
            CURLOPT_USERPWD => "user:" . $this->accessToken,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = curl_exec($handle);
        curl_close($handle);
        return $response;
    }

    public function post_curl($url, $data, $header=array()){
        //data has to be an array
        $handle = curl_init();
        if($this->accessToken){
            $password = "user: " . $this->accessToken;
        }else{
            $password = '';
        }
        curl_setopt_array($handle, array(
            CURLOPT_USERPWD => $password,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = curl_exec($handle);
        curl_close($handle);
        if($response === false){
            return "Error";
        }
        else
        {
            return $response;
        }

        
    }

    

    private function load_mailchimp_data(){
        //loads all of th data from mailchimp_init option
        $data = json_decode(get_option("mailchimp_init"));
        foreach($data as $key=>$value){
            $this->$key = $value;
        }
    }

    private function save_mailchimp_data(){
        $data = array(
            "accessToken" => $this->accessToken,
            "dc" => $this->dc,
            "userId" => $this->userId,
            "apiEndpoint" => $this->apiEndpoint,
            "lists" => $this->lists,
            "activeList" => $this->activeList
        );

        $data = json_encode($data);
        update_option("mailchimp_init", $data);
    }

    public function wipe_data(){
        update_option("mailchimp_init", "");
    }
}