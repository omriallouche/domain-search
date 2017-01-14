<?php
class Resellerclub {

    private $API_URL;
    private $API_KEY;
    private $RESELLER_ID;

    private $_testMode;

    function __construct($RESELLER_ID = '437448', $API_KEY = 'tHpAJCjErbY9EvXImA2tNqkX7DqYVHik', $TEST_MODE = false)
    {
        $this->RESELLER_ID = $RESELLER_ID;
        $this->API_KEY = $API_KEY;
        $this->_testMode = $TEST_MODE;

        $this->API_URL = $this->_testMode ? "https://test.httpapi.com/api" : "https://httpapi.com/api";
    }

    public function getApiUrl(){
        return $this->API_URL;
    }
    public function getApiKey(){
        return $this->API_KEY;
    }
    public function getResellerId(){
        return $this->RESELLER_ID;
    }
    public function isTestMode(){
        return $this->_testMode;
    }
    private function getActionData($action, $params = array()){
        $url = $this->API_URL;
        $isPost = false;
        $postField = "";
        switch($action){
            case "domains_available":
                $url .= "/domains/available.json";
                break;
            default:
                throw new Exception(__METHOD__ . " needs a proper action.");
                break;
        }

        if($isPost) {
            $postField .= "auth-userid=" . $this->RESELLER_ID;
            $postField .= '&api-key=' . $this->API_KEY;
            foreach($params as $key => $val){
                if(is_array($val)){
                    foreach($val as $val2){
                        $postField .= "&" . $key . "=" . $val2;
                    }
                }
                else {
                    $postField .= "&" . $key . "=" . $val;
                }
            }
        }
        else{
            $url .= "?auth-userid=" . $this->RESELLER_ID;
            $url .= '&api-key=' . $this->API_KEY;
            foreach($params as $key => $val){
                if(is_array($val)){
                    foreach($val as $val2){
                        $url .= "&" . $key . "=" . $val2;
                    }
                }
                else {
                    $url .= "&" . $key . "=" . $val;
                }
            }
        }
        return array($isPost, $url, $postField);
    }

    private function _call($url, $isPost = false, $postField = null, $returnTransfer = true){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($isPost == true){
            if(is_array($postField)){
                $postField = http_build_query($postField);
            }
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postField);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $returnTransfer);
        $curl_result = curl_exec($ch);
        curl_close($ch);

        return $curl_result;
    }

    function getJsonResult($string){
        if(empty($string)){
            throw new Exception("Empty Response.");
        }
        else {
            $json = json_decode($string, true);

            if($json === FALSE) throw new Exception("JSONException");
            else{
                if(isset($json['status'])) {
                    switch (strtolower($json['status']))
                    {
                        case "error":
                            if(isset($json['message'])) {
                                throw new Exception($json['message']);
                            }
                            elseif(isset($json['error'])) {
                                throw new Exception($json['error']);
                            }
                            break;
                    }
                }
                return $json;
            }

        }
    }

    function checkDomainAvailability($domain){
        $domain = strtolower($domain);
        list($slug, $tld) = explode('.', $domain, 2);

        $available = false;

        $params = array(
            "suggest-alternative" => "false",
            "domain-name" => $slug,
            "tlds" => $tld
        );
        $urlAndPostFields = $this->getActionData("domains_available", $params);

        $curl_result = $this->_call($urlAndPostFields[1], $urlAndPostFields[0], $urlAndPostFields[2]);
        if(!empty($curl_result)){
            $json_array = $this->getJsonResult($curl_result);
            $available = ($json_array["$slug.$tld"]["status"] == "available");
        }

        if($available) {
            $res  = array(
                "status"    => true,
                "message"   => "Available!"
            );
        } else {
            $res  = array(
                "status"     => false,
                "message"   => "Unavailable"
            );
        }

        return $res;
        
    }

}
