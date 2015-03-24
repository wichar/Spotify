<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require ( 'src/defines.php' );
require ( 'conecction.php' );

$task = addslashes( $_REQUEST['task'] );

switch ( $task ) {
	case 'getTracks'       : getTracks();       break;
	case 'newPlayList'     : newPlayList();     break;
	case 'addTrack'        : addTrack();        break;
	case 'guardarPlayList' : guardarPlayList(); break;
}

function newPlayList(){

	global $api;

	$api->setAccessToken(  $_SESSION['access_token'] );

	$nombre = $_POST['nombre'];
	$iduser = $_POST['iduser'];

	$info = array('name' => $nombre);

	$result = $api->createUserPlaylist( $iduser, $info );

   $_SESSION['id_new_playlist'] = $result->id;

}

function getTracks(){

	global $api;

	$api->setAccessToken(  $_SESSION['access_token'] );

	$iduser     = $_POST['iduser'];
	$idplaylist = $_POST['idplaylist'];

	$result = $api->getUserPlaylistTracks( $iduser, $idplaylist );

	$html  = '';
	$html .= '<h2>Lista de canciones</h2>';

	$i = 1;
	foreach( $result->items as $tracks ) :

		$html .= '<p> '.$i.' '.$tracks->track->name.' - <span id="'.$tracks->track->id.'" class="agregar" style="cursor: pointer;">Agregar</span></p>';
		$html .= '<hr>';
		$i++;

	endforeach;

	echo $html;

}

function addTrack(){

	global $api;

	$api->setAccessToken(  $_SESSION['access_token'] );

	$iduser  = $_POST['iduser'];
	$tracks  = $_POST['track'];

	$api->addUserPlaylistTracks( $iduser, $_SESSION['id_new_playlist'], $tracks );

	$result = $api->getUserPlaylistTracks( $iduser, $_SESSION['id_new_playlist'] );

	$html = '';
	$html .= '<h2>Lista de canciones</h2>';

	$i = 1;
	foreach( $result->items as $track ) :

		$html .= '<p><iframe src="https://embed.spotify.com/?uri=spotify:track:'.$track->track->id.'" width="300" height="80" frameborder="0" allowtransparency="true"></iframe></p>';
		$html .= '<hr>';
		$i++;

	endforeach;

	$html .= '<div height="30px;"><input type="button" id="guardar" value="Guardar"/></div>';

	echo $html;

}

function guardarPlayList(){

	global $api, $conn, $database;
	$api->setAccessToken(  $_SESSION['access_token'] );

	$iduser     = $_POST['iduser'];
	$user_name  = $api->me()->display_name;
	$user_email = $api->me()->email;

	$playlist      = $api->getUserPlaylist($iduser, $_SESSION['id_new_playlist']);
	$id_playlist   = $playlist->id;
	$name_playlist = $playlist->name;

	$tracks = $api->getUserPlaylistTracks( $iduser, $id_playlist );

    $select = "SELECT COUNT(id) AS cantidad FROM users WHERE id_spotify = '".$iduser."' ";
    mysql_select_db($database);
    $result = mysql_query($select, $conn);
    $datos  = mysql_fetch_assoc($result);

	if( $datos['cantidad'] == 0 ){

		$insert_user ="INSERT INTO users (id_spotify, nombre, email, fecha_registro) VALUES('".$iduser."', '".$user_name."', '".$user_email."', NOW())";
        mysql_select_db($database);
        $retval = mysql_query( $insert_user, $conn );	

		$insert_playlist ="INSERT INTO playlists (id_playlist, id_user, nombre_playlist, fecha_registro) VALUES('".$id_playlist."', '".$iduser."', '".$name_playlist."', NOW())";
        mysql_select_db($database);
        $retval = mysql_query( $insert_playlist, $conn );

        foreach( $tracks->items as $track ) :

			$insert_track ="INSERT INTO tracks (id_playlist, id_track, nombre_track, fecha_registro) VALUES('".$id_playlist."', '".$track->track->id."', '".$track->track->name."', NOW())";
	        mysql_select_db($database);
	        $retval = mysql_query( $insert_track, $conn );

        endforeach;


	}else{

	    $select = "SELECT COUNT(id) AS cantidad FROM playlists WHERE id_user = '".$iduser."' ";
	    mysql_select_db($database);
	    $result = mysql_query($select, $conn);
	    $datos  = mysql_fetch_assoc($result);

	    if( $datos['cantidad'] == 0 ){

			$insert_playlist ="INSERT INTO playlists (id_playlist, id_user, nombre_playlist, fecha_registro) VALUES('".$id_playlist."', '".$iduser."', '".$name_playlist."', NOW())";
	        mysql_select_db($database);
	        $retval = mysql_query( $insert_playlist, $conn );

	        foreach( $tracks->items as $track ) :

				$insert_track ="INSERT INTO tracks (id_playlist, id_track, nombre_track, fecha_registro) VALUES('".$id_playlist."', '".$track->track->id."', '".$track->track->name."', NOW())";
		        mysql_select_db($database);
		        $retval = mysql_query( $insert_track, $conn );

	        endforeach;

	    }

	}


}