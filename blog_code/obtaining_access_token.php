<?php
    include 'defines.php';

    // load graph-sdk files
    require_once __DIR__ . '/vendor/autoload.php';

    // facebook credentials array
    $creds = array(
        'app_id' => FACEBOOK_APP_ID,
        'app_secret' => FACEBOOK_APP_SECRET,
        'default_graph_version' => 'v17.0',
        'persistent_data_handler' => 'session'
    );

    // create facebook object
    $facebook = new \JanuSoftware\Facebook\Facebook( $creds );

    // helper
    $helper = $facebook->getRedirectLoginHelper();

    // oauth object
    $oAuth2Client = $facebook->getOAuth2Client();

    if ( isset( $_GET['code'] ) ) { // get access token
        try {
            $accessToken = $helper->getAccessToken();
        } catch ( \JanuSoftware\Facebook\Exception\ResponseException $e ) { // graph error
            echo 'Graph returned an error ' . $e->getMessage();
        } catch ( \JanuSoftware\Facebook\Exception\SDKException $e ) { // validation error
            echo 'Facebook SDK returned an error ' . $e->getMessage();
        }

        if ( !$accessToken->isLongLived() ) { // exchange short for long
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken( $accessToken );
            } catch ( \JanuSoftware\Facebook\Exception\SDKException $e ) {
                echo 'Error getting long lived access token ' . $e->getMessage();
            }
        }

        echo '<pre>';
        var_dump( $accessToken );

        $accessToken = (string) $accessToken;
        echo '<h1>Long Lived Access Token</h1>';
        print_r( $accessToken );
    } else { // display login url
        $permissions = [
            'public_profile'
        ];
        $loginUrl = $helper->getLoginUrl( FACEBOOK_REDIRECT_URI, $permissions );
    
        echo '<a href="' . $loginUrl . '">
            Login With Facebook
        </a>';
    }
