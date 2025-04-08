<?php

session_start();//sesija je kao neka mala memorija na serveru/browseru koja sacuva neke podatke


$servername = "localhost";
$db_username = "root";
$db_password = "";
$database_name = "teretana";
//POVEZIVANJE SA BAZOM
$conn = mysqli_connect($servername, $db_username, $db_password, $database_name);

if (!$conn) {
    die("Neuspesna konekcija");
}