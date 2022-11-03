<?php

class Service {

    public $epin_base_url;
    public $epin_authorization;
    public $epin_api_name;
    public $epin_api_key;

    public $headers;

    public function __construct() {
        $this->epin_base_url = get_option('epin_base_url');
        $this->epin_authorization = get_option('epin_authorization');
        $this->epin_api_name = get_option('epin_api_name');
        $this->epin_api_key = get_option('epin_api_key');

        $this->headers = array(
            'Authorization: ' . $this->epin_authorization,
            'ApiName: ' . $this->epin_api_name,
            'ApiKey: ' . $this->epin_api_key
        );
    }


    public function get($uri)
    {

        try {

            echo "<p><span class=\"dashicons dashicons-yes\"></span> Bağlantı kontrol ediliyor...</p>";
            
            $client = curl_init();
            //curl_setopt($client, CURLOPT_PORT, 80);
            curl_setopt($client, CURLOPT_URL, $this->epin_base_url . $uri);
            curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($client, CURLOPT_FAILONERROR, true);
            curl_setopt($client, CURLOPT_HTTPHEADER, $this->headers);

            $data = curl_exec($client);
    
            if($data == false){

                echo "<p><span class=\"dashicons dashicons-no\"></span> Bağlantı hatası...</p>";
                echo '<p><span class=\"dashicons dashicons-no\"></span> Curl Bağlantı Hatası: ' . curl_error($client) . "</p>";

            }else{
                update_option('epin_last_update', time());
                echo "<p><span class=\"dashicons dashicons-yes\"></span> Bağlantı başarılı...</p>";
            }
    
            curl_close($client);
            return json_decode($data);


        } catch (\Throwable $th) {
            return $th;
        }

    }

}