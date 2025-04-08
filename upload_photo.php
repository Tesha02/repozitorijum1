<?php

$photo = $_FILES['photo'];
$photo_name=$photo['name'];//ime slike, mozemo ovo uokviriti u basename

$photo_path='member_photos/'.$photo_name;

$allowed_ext=['jpg','jpeg','png','gif'];
$ext=pathinfo($photo_name,PATHINFO_EXTENSION);//uzima ekstenziju od naseg fajla

//sad proveravamo da l se ekstenzija od naseg fajla nalazi u dozvoljenom nizu ekstenzija
//ovo in array je kao foreach
if(in_array($ext,$allowed_ext) && $photo['size']<2000000) {
    //sad prebacujemo sliku na nas server
    move_uploaded_file($photo['tmp_name'], $photo_path);

    echo json_encode(['success'=>true, 'photo_path'=>$photo_path]);//samo ih pretvara u json format a echo ih ispisuje u tom formatu
    //ovde ovaj success je onaj success u admin dashboard u if jsonResponse.success
    //i ovaj photopath i error su isto iz ovog admindashboarda
}else {
    echo json_encode(['success'=>false, 'error'=>'Invalid file']);
}

//var_dump($photo);//cisto ono sta sve imamo,prikazuje se u preview kod networka
//sa ovim smo zavrsili uploadovanje slike
//photopath se koristi za prikazivanje slika(ovo u bazi)