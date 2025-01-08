<?php  namespace App\Libraries\Facebook;


Class Facebook extends \Facebook\Facebook {

    const APP_ACCESS_TOKEN_ENV_NAME = 'FACEBOOK_DEFAULT_ACCESS_TOKEN';
	
	
	
    /**
     * Sends a GET request to Graph and returns the result.
     *
     * @param string                  $endpoint
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     * @param string|null             $graphVersion
     *
     * @return FacebookResponse
     *
     * @throws FacebookSDKException
     */
    public function get($endpoint, $accessToken = null, $eTag = null, $graphVersion = null)
    {
		if(is_null($accessToken)) $this->setDefaultAccessToken();
		
        return $this->sendRequest(
            'GET',
            $endpoint,
            $params = [],
            $accessToken,
            $eTag,
            $graphVersion
        );
    }	
	
	
	 /**
     * Sets the default access token to use with requests.
     *
     * @param AccessToken|string $accessToken The access token to save.
     *
     * @throws \InvalidArgumentException
     */
    public function setDefaultAccessToken($accessToken = null)
    {
        if (is_string($accessToken)) {
            $this->defaultAccessToken = new AccessToken($accessToken);

            return;
        }

        if ($accessToken instanceof AccessToken) {
            $this->defaultAccessToken = $accessToken;

            return;
        }
		
		if(!empty(getenv(static::APP_ACCESS_TOKEN_ENV_NAME))){
            $this->defaultAccessToken = getenv(static::APP_ACCESS_TOKEN_ENV_NAME);
            return;
		}

        throw new \InvalidArgumentException('The default access token must be of type "string" or Facebook\AccessToken');
    }
	
}


?>