<?php

require_once 'config.php';
require_once 'fpdf/fpdf.php';

//prvo da proverimo je l post poslao podatke
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $training_plan_id = $_POST['training_plan_id'];
    $trainer_id = 0;//fora je ovde sto mi kad registrujemo korisnika ne dodeljujemo mu trenera nego tek kasnije 
    $photo_path = $_POST['photo_path'];
    $access_card_pdf = "";//ovo se nes automatski popunjava

    $sql = "INSERT INTO members 
    (first_name, last_name, email, phone_number, photo_path, 
    training_plan_id, trainer_id, access_card_pdf_path)  
    VALUES  (?,?,?,?,?,?,?,?)";

    $run = $conn->prepare($sql);
    $run->bind_param(
        "sssssiis",
        $first_name,
        $last_name,
        $email,
        $phone_number,
        $photo_path,
        $training_plan_id,
        $trainer_id,
        $access_card_pdf
    );
    $run->execute();

    $member_id = $conn->insert_id;//ovo je id od ubacenog membera i on nam sluzi da mozemo nesto da menjamo za tog membera

    $pdf = new FPDF();//ovo sto smo ucitali gore
    $pdf->AddPage();//za fpdf postoji dokumentacija i pise ovo
    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(40, 10, 'Access Card');//pravi se celija i pisu se pozicija gde ce tekst da bude
    $pdf->Ln();//nova linija
    $pdf->Cell(40, 10, 'Member ID: ' . $member_id);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Name: ' . $first_name . " " . $last_name);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Email: ' . $email);
    $pdf->Ln();

    $filename = 'access_cards/access_card_' . $member_id . ".pdf";//naziv fajla gde se cuvaju podaci
    $pdf->Output('F', $filename);

    //sad preko member id moramo da upisemo u access pdf odgovarajucu rutu
    $sql="UPDATE members SET access_card_pdf_path = '$filename' WHERE member_id='$member_id'";
    $conn->query($sql);
    $conn->close();

    $_SESSION['success_message'] = "Clan teretane je uspesno dodat";//mi smo napravili ovu success mess
    header('location:admin_dashboard.php');
    exit();
}

