<?php

class Analytics {
    //properties
    public $visits = 0;
    public $uniqueVisits = 0;
    public $visitors = array();
    public $signUps = 0;

    //constructor
    function __construct(){
        if(get_option("analytics_init")){
            $this->load_analytics();
        }else{
            echo "creating analytics option<br>";
            add_option("analytics_init");
            $this->save_analytics();
        }
    }

    private function save_analytics(){
        $data = array(
            "visits" => $this->visits,
            "uniqueVisits" => $this->uniqueVisits,
            "visitors" => $this->visitors,
            "signUps" => $this->signUps,
        );
        $data = json_encode($data);

        update_option("analytics_init", $data);
    }

    private function load_analytics(){
        $data = json_decode(get_option("analytics_init"), true);
        foreach($data as $key=>$value){
            $this->$key = $value;
        }
    }

    public function add_visitor($visitor, $refer){
        $this->visits++;
        //echo "adding new visitor<br>";
        $unique = TRUE;
        foreach($this->visitors as $v){
            if($v["ip"] == $visitor){
                $unique = FALSE;
                break;
            }
        }
        if($unique){
            $temp = array(
                "ip" => $visitor,
                "date" => time(),
                "refer" => $refer
            );
            $this->uniqueVisits++;
            array_unshift($this->visitors, $temp);
            
        }
        $this->save_analytics();
    }

    public function get_data(){
        //returns data value points to javascript to draw to canvase
    }

    public function clear_data(){ //use this to clear data to start over and test
        $this->visits = 0;
        $this->uniqueVisits = 0;
        $this->visitors = array();
        $this->signUps = 0;
        $this->save_analytics();
    }

    public function fake_data(){ //use this to test out larger data sets
        $this->visits = 15000;
        $this->uniqueVisits = 13000;
        $this->signUps = 10000;
        $this->save_analytics();
    }
}