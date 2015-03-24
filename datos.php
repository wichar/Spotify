<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require ( 'src/defines.php' );


$api->setAccessToken(  $_SESSION['access_token'] );

$iduser     = $api->me()->id;
$idplaylist = $api->getUserPlaylists( $iduser )->items[0]->id; 

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
		height: 500px;
		text-align: center;
		background: white;
	}

	</style>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript">

	$(document).ready(function(){

		$("#playlists > p > strong > a").click(function(){
			var idplaylist = $(this).attr('id');

			$.ajax({
			  type: "POST",
			  url: "ajax.php",
			  data: { task:"getTracks", idplaylist : idplaylist, iduser : '<?php echo $iduser; ?>' }
			})
			  .done(function( datos ) {
			    
			  	$('#tracks').hide();
			  	$('#tracks').html(datos);
			  	$('#tracks').fadeIn();

			  	$('#carga_tracks').fadeIn();

			  });

		});

		$("#crear").click(function(){

			var nombre = $("#new_playlist").val();

			$.ajax({
			  type: "POST",
			  url: "ajax.php",
			  data: { task:"newPlayList", nombre : nombre, iduser : '<?php echo $iduser; ?>' }
			})
			  .done(function( datos ) {
			  	location.reload();
			});


		});

		$(document).on( "click", ".agregar", function(){

			var idTrack = $(this).attr('id');

			$.ajax({
			  type: "POST",
			  url: "ajax.php",
			  data: { task:"addTrack", track : idTrack, iduser : '<?php echo $iduser; ?>' }
			}).done(function( datos ) {

				$(this).text('Agregado');
				$(this).removeClass('agregar');
				$(this).attr('cursor', '');
				$(this).css('opacity', '0.5');

			  	$('#carga_tracks').hide();
			  	$('#carga_tracks').html(datos);
			  	$('#carga_tracks').fadeIn();

			});

		});

		$(document).on( "click", "#guardar",function(){

			$.ajax({
			  type: "POST",
			  url: "ajax.php",
			  data: { task:"guardarPlayList", iduser : '<?php echo $iduser; ?>' }
			})
			  .done(function( datos ) {
			  	location.href='galeria.php';
			});

		});

	});

	</script>

</head>

<body style="background-color: black;">
	<div id="contenedor" width="100%" height="100%" >

		<div style="float: left; border: 1px solid #000000; width: 200px">
			<h2>Mis datos</h2>
			<p>Nombre : <?php echo $api->me()->display_name; ?></p>
			<p>Email  : <?php echo $api->me()->email; ?></p>
			<p>Avatar : <img src="<?php echo $api->me()->images[0]->url; ?>" width="50px" height="50px"/></p>
			<p>Crear PlayList<input type="text" name="new_playlist" id="new_playlist" width="100px" > <input type="button" id="crear" value="Crear"> </p>
		</div>

		<div id="playlists" style="float: left; border: 1px solid #000000; width: 200px; height: 346px; overflow-y: scroll;">
			<h2>Mis Playlist</h2>
			<?php
				$playlist = $api->getUserPlaylists( $iduser );
				//echo '<pre>'; print_r($playlist); die;
				$i = 1;

				foreach( $playlist->items as $play ) :

					if( $play->owner->id == $iduser ){
						echo '<p>'.$i.'  <img src="'.$play->images[0]->url.'" width="50px" height="50px" /> <strong><a href="javascript:void(0);" id="'.$play->id.'">'.$play->name.'</a></strong></p>';
						echo '<hr>'; 
						$i++;
					}
				endforeach;
			?>

		</div>

		<div id="tracks" style="float: left; border: 1px solid #000000; width: 200px; display: none; height: 346px; overflow-y: scroll;" ></div>
		<div id="carga_tracks" style="float: left; border: 1px solid #000000; width: 500px; display: none; height: 346px; overflow-y: scroll;"></div>

	</div>
</body>

</html>
