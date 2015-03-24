<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require ( 'src/defines.php' );

unset($_SESSION['access_token']);

$autorizacionUrl = $session->getAuthorizeUrl( array( 'scope' => array('user-read-email', 'playlist-modify', 'playlist-modify-public', 'playlist-modify-private' , 'streaming'	) ) );

?>

<a href="<?php echo $autorizacionUrl; ?>">loguearse con Spotify</a>