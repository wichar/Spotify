<?php
//include('adodb5/adodb.inc.php');

$databasetype = 'mysql';
$server       = 'localhost';
$user         = 'root';
$password     = '9*gC5=>H=u9Z>OWZ2VO`,U.pHVu~22';
$database     = 'spotify';


$conn =  mysql_connect($server, $user, $password);
if (!$conn) {
    die('No pudo conectarse: ' . mysql_error());
}
//echo 'Conectado satisfactoriamente';


// Ejecutar la consulta
//$resultado = mysql_query($consulta);

//mysql_close($conn);


