<?php
include 'defines.php';

require_once __DIR__ . '/vendor/autoload.php'; // change path as needed

$fb = new \JanuSoftware\Facebook\Facebook([
  'app_id' => FACEBOOK_APP_ID,
  'app_secret' => FACEBOOK_APP_SECRET,
  'default_graph_version' => 'v17.0',
  //'default_access_token' => '{access-token}', // optional
]);

try {
  // If you provided a 'default_access_token', the '{access-token}' is optional.
  $response = $fb->get('/me', '{access-token}');
} catch(\JanuSoftware\Facebook\Exception\ResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(\JanuSoftware\Facebook\Exception\SDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$me = $response->getGraphNode();
echo 'Logged in as ' . $me->getField('name');