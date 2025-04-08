<?php

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //ovde pise member i trainer kod posta zato sto to pise u selectu u formi - member i trainer
    $member_id = $_POST['member'];
    $trainer_id = $_POST['trainer'];

    $sql = "UPDATE members SET trainer_id=? WHERE member_id=$member_id";
    $run = $conn->prepare($sql);
    $run->bind_param('i', $trainer_id);

    $run->execute();

    $_SESSION['success_message'] = "Trener je uspesno dodeljen clanu";

    header('location:admin_dashboard.php');
    exit();

}