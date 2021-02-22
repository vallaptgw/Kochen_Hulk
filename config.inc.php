<?php
	/* Verbindung zum Server*/
	$server = 'localhost';
	$benutzer = 'hrl_kino';
	$passwort = 'kino';
	$datenbank = 'hrl_kino';
	
	
	$connect = new mysqli($server, $benutzer, $passwort , $datenbank);
	$errorNumber = mysqli_connect_errno();
	
	if($errorNumber != 0)
		echo 'Verbindungsfehler '.$errorNumber.': <br>'.mysqli_connect_error().' <br>';
?>