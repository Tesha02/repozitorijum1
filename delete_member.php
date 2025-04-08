<?php

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //echo $_POST['member_id'];radi
    $member_id = $_POST['member_id'];

    $sql = "DELETE FROM members WHERE member_id=?";
    $run = $conn->prepare($sql);
    $run->bind_param('i', $member_id);
    $message = "";

    if ($run->execute()) {//mada posto radimo na dugme tesko da mozemo da posaljemo pogresan id pa nam ovo iskr i ne treba
        $message = "Clan je obrisan";
    } else {
        $message = "Clan nije obrisan";
    }

    $_SESSION['success_message'] = $message;
    //ove dve zadnje linije koda moraju biti ovde da automatski vrate na
    //pocetnu stranu desavanje kad se ova php skripta zavrsi
    header('location: admin_dashboard.php');
    exit();//exit je obavezan da se php skripta ne nastavi da se izvrsava u pozadini

}