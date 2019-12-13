<?php

class Mo_Openid_Twitter_OAuth
    {
      var $key = '';
      var $secret = '';

      var $request_token = "https://twitter.com/oauth/request_token";
	  var $access_token  = "https://twitter.com/oauth/access_token";
	  var $profile		 = "https://api.twitter.com/1.1/account/verify_credentials.json";

    function __construct($client_key,$client_secret)
    {
        $this->key = $client_key; // consumer key from twitter
        $this->secret = $client_secret; // secret from twitter
    }

    function mo_twitter_get_request_token()
    {
        // Default params
        $params = array(
            "oauth_version" => "1.0",
            "oauth_nonce" => time(),
            "oauth_timestamp" => time(),
            "oauth_consumer_key" => $this->key,
            "oauth_signature_method" => "HMAC-SHA1"
         );

         // BUILD SIGNATURE
            // encode params keys, values, join and then sort.
            $keys = $this->mo_twitter_url_encode_rfc3986(array_keys($params));
            $values = $this->mo_twitter_url_encode_rfc3986(array_values($params));
            $params = array_combine($keys, $values);
            uksort($params, 'strcmp');

            // convert params to string 
            foreach ($params as $k => $v) {
				$pairs[] = $this->mo_twitter_url_encode_rfc3986($k).'='.$this->mo_twitter_url_encode_rfc3986($v);
			}
            $concatenatedParams = implode('&', $pairs);

            // form base string (first key)
            $baseString= "GET&".$this->mo_twitter_url_encode_rfc3986($this->request_token)."&".$this->mo_twitter_url_encode_rfc3986($concatenatedParams);
            // form secret (second key)
            $secret = $this->mo_twitter_url_encode_rfc3986($this->secret)."&";
            // make signature and append to params
            $params['oauth_signature'] = $this->mo_twitter_url_encode_rfc3986(base64_encode(hash_hmac('sha1', $baseString, $secret, TRUE)));

			// BUILD URL
            // Resort
            uksort($params, 'strcmp');
            // convert params to string 
            foreach ($params as $k => $v) {$urlPairs[] = $k."=".$v;}
            $concatenatedUrlParams = implode('&', $urlPairs);
            // form url
            $url = $this->request_token."?".$concatenatedUrlParams;

			// Send to cURL
			return $this->mo_twitter_http($url);
    }
	
	function mo_twitter_get_access_token($oauth_verifier,$twitter_oauth_token)
    {
        $params = array(
            "oauth_version" => "1.0",
            "oauth_nonce" => time(),
            "oauth_timestamp" => time(),
            "oauth_consumer_key" => $this->key,
			"oauth_token" => $twitter_oauth_token,
            "oauth_signature_method" => "HMAC-SHA1"
         );

		$keys = $this->mo_twitter_url_encode_rfc3986(array_keys($params));
		$values = $this->mo_twitter_url_encode_rfc3986(array_values($params));
		$params = array_combine($keys, $values);
		uksort($params, 'strcmp');

		foreach ($params as $k => $v) {
			$pairs[] = $this->mo_twitter_url_encode_rfc3986($k).'='.$this->mo_twitter_url_encode_rfc3986($v);
		}
		$concatenatedParams = implode('&', $pairs);

		$baseString= "GET&".$this->mo_twitter_url_encode_rfc3986($this->access_token)."&".$this->mo_twitter_url_encode_rfc3986($concatenatedParams);
		$secret = $this->mo_twitter_url_encode_rfc3986($this->secret)."&";
		$params['oauth_signature'] = $this->mo_twitter_url_encode_rfc3986(base64_encode(hash_hmac('sha1', $baseString, $secret, TRUE)));

		uksort($params, 'strcmp');
		foreach ($params as $k => $v) {$urlPairs[] = $k."=".$v;}
		$concatenatedUrlParams = implode('&', $urlPairs);
		$url = $this->access_token."?".$concatenatedUrlParams;
		$postData = 'oauth_verifier=' .$oauth_verifier;

		return $this->mo_twitter_http($url,$postData);
    }
		
	function mo_twitter_get_profile_signature($oauth_token,$oauth_token_secret,$screen_name)
    {
        $params = array(
            "oauth_version" => "1.0",
            "oauth_nonce" => time(),
            "oauth_timestamp" => time(),
            "oauth_consumer_key" => $this->key,
			"oauth_token" => $oauth_token,
            "oauth_signature_method" => "HMAC-SHA1",
			"screen_name" => $screen_name,
            "include_email" => "true"
         );
    
		$keys = $this->mo_twitter_url_encode_rfc3986(array_keys($params));
		$values = $this->mo_twitter_url_encode_rfc3986(array_values($params));
		$params = array_combine($keys, $values); 
		uksort($params, 'strcmp');

		foreach ($params as $k => $v) {
			$pairs[] = $this->mo_twitter_url_encode_rfc3986($k).'='.$this->mo_twitter_url_encode_rfc3986($v);
		}
		$concatenatedParams = implode('&', $pairs);
		
		$baseString= "GET&".$this->mo_twitter_url_encode_rfc3986($this->profile)."&".$this->mo_twitter_url_encode_rfc3986($concatenatedParams);
		
		$secret = $this->mo_twitter_url_encode_rfc3986($this->secret)."&". $this->mo_twitter_url_encode_rfc3986($oauth_token_secret);
		$params['oauth_signature'] = $this->mo_twitter_url_encode_rfc3986(base64_encode(hash_hmac('sha1', $baseString, $secret, TRUE)));
		
	 	uksort($params, 'strcmp');
		foreach ($params as $k => $v) {$urlPairs[] = $k."=".$v;}
		$concatenatedUrlParams = implode('&', $urlPairs);
		$url = $this->profile."?".$concatenatedUrlParams;

	    $args = array();

	    $get_response = wp_remote_get($url,$args);

	    $profile_json_output = json_decode($get_response['body'], true);

	    return  $profile_json_output;
    }

    function mo_twitter_http($url, $post_data = null)
    {       

        if(isset($post_data))
        {

	        $args = array(
		        'method' => 'POST',
		        'body' => $post_data,
		        'timeout' => '5',
		        'redirection' => '5',
		        'httpversion' => '1.0',
		        'blocking' => true
	        );

	        $post_response = wp_remote_post($url,$args);

	        return $post_response['body'];

        }
	    $args = array();

	    $get_response = wp_remote_get($url,$args);
	    $response =  $get_response['body'];
		mo_openid_start_session();

		$dirs = explode('&', $response);
		$dirs1 = explode('=', $dirs[0]);
		return $dirs1[1];

    }

    function mo_twitter_url_encode_rfc3986($input)
    {
        if (is_array($input)) {
            return array_map(array('Mo_Openid_Twitter_OAuth', 'mo_twitter_url_encode_rfc3986'), $input);
        }
        else if (is_scalar($input)) {
            return str_replace('+',' ',str_replace('%7E', '~', rawurlencode($input)));
        }
        else{
            return '';
        }
    }
}
?>