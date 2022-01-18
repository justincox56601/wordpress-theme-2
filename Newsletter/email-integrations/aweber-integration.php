<?php
/*
danavirsarria@gmail.com
DragonBallSuper!@#123
*/




class Aweber{
    //init properties
    public $authUrl = "https://auth.aweber.com/oauth2/authorize";
    public $tokenUrl = "https://auth.aweber.com/oauth2/token";
    public $clientId = "P03MIXjbZa5pIDDe7eeD1S3XX7BKY263";
    public $redirectUri = "urn:ietf:wg:oauth:2.0:oob";
    public $scopes = array(
        'account.read',
        "list.read",
        "list.write",
        "subscriber.write",
        "subscriber.read",
        "email.read",
        "email.write",
        "subscriber.read-extended"
    );
    public $authCode;
    public $codeVerifier;
    public $accessToken;
    public $refreshToken;
    public $time;
    
    //constructor
    function __construct(){
        if(get_option("aweber_init")){
            //do stuff
            $this->load_properties();
            if($this->accessToken){
                if(time() > $this->time){
                    //need to refresh the access token
                    $this->refresh_token();
                }
            }
            
        }else{
            add_option("aweber_init");
            $this->save_properties();
            echo "added the init option";
        }
        //echo "init option: " . get_option("aweber_init") . "<br>";
    }

    //Interface Methods
    public function check_access_token(){
        //returns true or false depending on if the $this->email has a valid acccess token
        
    }
    
    public function get_auth_link(){
        // Generate the code challenge using the OS / cryptographic random function
        $verifierBytes = random_bytes(64);
        $codeVerifier = rtrim(strtr(base64_encode($verifierBytes), "+/", "-_"), "=");
        

        // Very important, "raw_output" must be set to true or the challenge
        // will not match the verifier.
        $challengeBytes = hash("sha256", $codeVerifier, true);
        $codeChallenge = rtrim(strtr(base64_encode($challengeBytes), "+/", "-_"), "=");

        // State token, a uuid is fine here
        $state = uniqid();

        $query = http_build_query(array(
            "repsonse_type" => "code",
            "client_id" => $this->clientId,
            "redirect_uri" => $this->redirectUri,
            "scope" => implode(" ",$this->scopes),
            "code_challenge" => $codeChallenge,
            "code_challenge_method" => "S256"
        ));

        $this->codeVerifier = $codeVerifier;
        $this->save_properties();

        
        return $this->authUrl . "?" . $query;
    }

    public function get_access_token($code){
        $this->authCode = $code;
        $this->save_properties();

        $resp = $this->get_pcke();
        $this->accessToken = $resp->access_token;
        $this->refreshToken = $resp->refresh_token;
        $this->time = time() + $resp->expires_in;
        $this->save_properties();
        echo $resp;
    }

    public function get_email_lists(){
        
    }

    public function add_new_subscriber($args){
        
    }

    public function get_collection($method, $url, $data){
        //uses cURL to talk with the API
        if($method == "POST"){
            $post = TRUE;
        }else{
            $post = false;
        }
        $handle = curl_init();
        curl_setopt_array($handle, array(
            CURLOPT_URL => $url,
            CURLOPT_POST => $post,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = curl_exec($handle);
        curl_close($handle);
        return $response;

    }

    public function get_auth_code(){
        // Generate the code challenge using the OS / cryptographic random function
        $verifierBytes = random_bytes(64);
        $codeVerifier = rtrim(strtr(base64_encode($verifierBytes), "+/", "-_"), "=");
        $this->codeVerifier = $codeVerifier;

        // Very important, "raw_output" must be set to true or the challenge
        // will not match the verifier.
        $challengeBytes = hash("sha256", $codeVerifier, true);
        $codeChallenge = rtrim(strtr(base64_encode($challengeBytes), "+/", "-_"), "=");
        $state = uniqid();
        $args = array(
            "response_type" => "code",
            "client_id" => $this->clientId,
            "redirect_uri" => $this->redirectUri,
            "scope" => implode(" ", $this->scopes),
            "state" => $state,
            "code_challenge" => $codeChallenge,
            "code_challenge_method" => "S256"
        );

        $this->save_properties();

        return $this->authUrl . "?" . http_build_query($args);

    }

    public function get_pcke(){
        $args = array(
            "grant_type" => "authorization_code",
            "code" => $this->authCode,
            "client_id" => $this->clientId,
            "code_verifier" => $this->codeVerifier,
        );
        
        $url = $this->tokenUrl . "?" . http_build_query($args);
        echo $url;
        $response = $this->get_collection("POST", $url, "");
        $response = json_decode($response);
        return $response;

    }

    public function refresh_token(){
        //access token is expired, need to refresh it.
    }

    private function save_properties(){
        $properties = json_encode(array(
            "authUrl"           => $this->authUrl,
            "tokenUrl"          => $this->tokenUrl,
            "clientId"          => $this->clientId,
            "redirectUri"       => $this->redirectUri,
            "scopes"            => $this->scopes,
            "authCode"          => $this->authCode,
            "codeVerifier"      => $this->codeVerifier,
            "accessToken"       => $this->accessToken,
            "refreshToken"      => $this->refreshToken,
            "time"              => $this->time,
            
        ));
        
        update_option("aweber_init", $properties);

    }
    
    private function load_properties(){
        $prop = json_decode(get_option("aweber_init"), true);
        foreach($prop as $key => $value){
            $this->$key = $value;

        }
        
        //return $prop;
    }
    
   
}