<?php
session_start();

define('CLIENTID', 'fad3efd232b648b6970e010414730591');
define('SECRETID', '78605692be48490d9961bca86c17f5ba');
define('CALLBACK', 'http://dev.imaginamobile.com/ricardo/investigacion/spotify/spotify-web-api-php-master/callback.php');

require ( 'SpotifyWebAPI.php' );
require ( 'SpotifyWebAPIException.php' );
require ( 'Session.php' );
require ( 'Request.php' );

$session = new Session(CLIENTID,SECRETID,CALLBACK);
$api     = new SpotifyWebAPI();

if( isset( $_GET['code'] ) && $_SESSION['access_token'] == ''){
    $session->requestToken($_GET['code']); 
    $_SESSION['access_token'] = $session->getAccessToken(); 
}