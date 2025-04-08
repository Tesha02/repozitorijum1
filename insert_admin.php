<?php

require_once 'config.php';

$username = 'ivan';
$password = 'sifra123';

echo $password . "</br>";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo $hashed_password;//enkriptuje password tako da ne bude kao plain tekst u bazi podataka

$sql = "INSERT INTO admins(username,password) VALUES (?,?)";
$run = $conn->prepare($sql);
$run->bind_param("ss", $username, $hashed_password);
$run->execute();

//OVO SAD JE CYSECOR OBRISAO JER JE SLUZILO SAMO KAO DA UBACIMO
//ADMINA SA KRIPTOVANOM SIFROM