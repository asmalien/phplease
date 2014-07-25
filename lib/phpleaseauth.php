<?php

namespace PHPlease;

class PHPleaseAuth {
    # API Endpoint (Host)
    private $_host = 'http://api.xrel.to/api/';

    # Request Token method
    private $_request_token_url = 'http://api.xrel.to/api/oauth/temp_token';

    # Authorize method
    private $_authorize_url = 'http://api.xrel.to/api/oauth/authorize';

    # Access Token method
    private $_access_token_url = 'http://api.xrel.to/api/oauth/access_token';

    # Constructor
    public function __construct($consumer_key = null, $consumer_secret = null, $oauth_token = null, $oauth_secret = null) {
        $this->_signature = new OAuthSignatureMethod_HMAC_SHA1();
        $this->_consumer = new OAuthConsumer($consumer_key, $consumer_secret);
        $this->_token = (!empty($oauth_token) && !empty($oauth_secret)) ? new OAuthConsumer($oauth_token, $oauth_secret) : null;
    }

    # Get a request token
    public function request_token($callback) {
        $params = [];
        $params['oauth_callback'] = $callback;

        $response = $this->OAuthRequest($this->_request_token_url, 'GET', $params);
        $token = OAuthUtil::parse_parameters($response);

        if(!empty($token['oauth_token']) && !empty($token['oauth_token_secret'])) {
            $this->_token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
        } else {
            throw new \Exception($response);
        }

        return $token;
    }

    # Get the authroize url
    public function authorize_url($token) {
        return $this->_authorize_url . '?oauth_token=' . $token;
    }

    # Create access tokens to sign requests
    public function access_token($strVerifier) {
        $params = [];
        $params['oauth_verifier'] = $strVerifier;

        $response = $this->OAuthRequest($this->_access_token_url, 'POST', $params);
        $token = OAuthUtil::parse_parameters($response);

        if(!empty($token['oauth_token']) && !empty($token['oauth_token_secret'])) {
            $this->_token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
        } else {
            throw new \Exception($response);
        }

        return $token;
    }

    # Performs a "signed" GET request to the provider server
    public function signed($func, $params = []) {
        return $this->parse_json($this->OAuthRequest($func, 'GET', $params));
    }

    # Performs an unsigned GET request to the provider server
    public function unsigned($func, $params = []) {
        return $this->parse_json($this->curly($this->_host . $func . '.json', $params));
    }

    # Performs an OAuth signed request
    private function OAuthRequest($url, $method, $params) {
        if(strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
            $url = $this->_host . $url . '.json';
        }

        $request = OAuthRequest::from_consumer_and_token($this->_consumer, $this->_token, $method, $url, $params);
        $request->sign_request($this->_signature, $this->_consumer, $this->_token);

        return ($method === 'GET') ? $this->curly($request->to_url()) : $this->curly($request->get_normalized_http_url(), $request->to_postdata(), $method);
    }

    # Performs an HTTP request to the provider server
    private function curly($url, $data = false, $method = 'GET') {
        $ch = curl_init();

        if(is_array($data)) {
            $data = array_filter($data);
        }

        if($method == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);

            if($data !== false) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        } else {
            if($data !== false) {
                if(is_array($data)) {
                    $dataTokens = array();

                    foreach($data as $key => $value) {
                        array_push($dataTokens, urlencode($key).'='.urlencode($value));
                    }

                    $data = implode('&', $dataTokens);
                }

                curl_setopt($ch, CURLOPT_URL, $url.'?'.$data);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $contents = curl_exec($ch);
        curl_close($ch);

        return $contents;
    }

    # Decodes a JSON string
    private function parse_json($data) {
        if(substr($data, 0, 10) == '/*-secure-' && substr($data, -2) == '*/') {
            return json_decode(trim(substr($data, 10, -2)), 1)['payload'];
        }

        return false;
    }
}
