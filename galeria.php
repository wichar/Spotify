<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require ( 'conecction.php' );

$select = "SELECT * FROM playlists";
mysql_select_db($database);
$result = mysql_query($select, $conn);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
	<title>Title of the document</title>
	<style type="text/css">

	#contenedor{
		width: 100%;
		margin: 0 auto;
		height: 800px;
		text-align: center;
		background: white;
	}

	.listas {
		/*background-color: #FF9900;*/
		height: 50px;
		margin: auto;
		width: 50%; 
	}

	</style>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){

		$(document).on('click', '.ver_tracks', function(){

			if( $(this).parent().find('.tracks').is(':visible') ){
				$(this).text('Abrir');
				$(this).parent().find('.tracks').slideUp();
			}else{
				$(this).text('Cerrar');
				$(this).parent().find('.tracks').slideDown();
			}

		});

	});
	</script>

</head>
<body style="background-color: black;">
	<div id="contenedor" width="100%" >
		<div class="listas" >

			<h2>Listas participantes</h2>
			<?php 
			$html = '';

			while( $l = mysql_fetch_assoc( $result ) ){

				$html .= '<div style="float: left; border: 1px solid; padding: 10px 10px 10px 10px;">
							<stron>'.$l['nombre_playlist'].' <a href="javascript:void(0);" class="ver_tracks">Abrir</a></strong>';

							$tracks = "SELECT * FROM tracks WHERE id_playlist = '".$l['id_playlist']."' ";
							mysql_select_db($database);
							$result2 = mysql_query($tracks, $conn);

							$html .= '<div class="tracks" style="display: none">';
							while($t = mysql_fetch_assoc($result2)){
											 
								$html .= '<p><iframe src="https://embed.spotify.com/?uri=spotify:track:'.$t['id_track'].'" width="300" height="380" frameborder="0" allowtransparency="true"></iframe></p>';
								$html .= '<hr>';

							}
					 
				$html .= '</div>';
			}

			echo $html;
			?>

		</div>
	</div>
</body>

</html>