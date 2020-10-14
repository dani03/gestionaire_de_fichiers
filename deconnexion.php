<?php 

session_start();

// Suppression des variables de session et de la session

$_SESSION = array();

session_destroy();
sleep(2);
Header('location: index.php');
