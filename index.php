<?php
//ovaj fpdf je za pdf fajl
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    //echo $_POST['username'] . "</br>";
    //echo $_POST['password'];

    $sql = "SELECT admin_id,password FROM admins where username= ?";

    $run = $conn->prepare($sql);//priprema sql za izvrsavanje
    $run->bind_param("s", $username);//ubacuje username umesto ovog ?
    $run->execute();//izvrsava sql
    $results = $run->get_result();//pakuje rezultat
    

    //var_dump($results);//nas ovde jedino sto zanima jeste numrows,
    //sto prikazuje koliko nam redova vraca iz tabele

    if ($results->num_rows == 1) {
        $admin = $results->fetch_assoc();//dobijanje jednog reda podataka iz rezultata SQL upita,
        //dohvaća jedan red rezultata kao asocijativni niz (array), gde su imena kolona iz baze ključevi u nizu.
        //$admin['password'] === $password ovo i ovo dole u if-u je isto
        //samo je ovo dole(password verify) zbog kriptovane sifre
        if (password_verify($password, $admin['password'])) {//prvo ide password u plain tekstu
            //echo "Password postoji";//ovo je sad trenutak kad treba
            //da admina prebacimo u aplikaciju
            $_SESSION['admin_id']=$admin['admin_id'];
            $conn->close();
            header('location:admin_dashboard.php');
            
        } else {
            //echo "Password ne postoji";
            $_SESSION['error'] = "Netacan password";//ovo je ona sesija sto smo je startali na pocetku
            $conn->close();
            header('location:index.php');//ovo je redirect
            //cisto da kad se osvezava stranica nam se ne pojavljuje onaj prozor gore
            exit;//uglavnom(ili uvek nmp) posle redirecta izvrsavamo exit
            //kako se ovo dole posle ne bi izvrsavalo po automatu
        }
    } else {
        $_SESSION['error'] = "Netacan username";
        $conn->close();
        header('location:index.php');
        exit;
    }


}// else {
//     echo "Podaci nisu poslati";
// }ovo je sluzilo samo da se vidi da l forma salje podatke


// if ($_SERVER['REQUEST_METHOD'] == "POST") {
//     echo "Podaci poslati";
// } else {
//     "Podaci nisu poslati";
// }ovo je cisto kao provera da li radi forma

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>

<body>

    <?php
    if (isset($_SESSION['error'])) {
        echo $_SESSION['error'];//ispisuje iznad forme gresku
        unset($_SESSION['error']);
    }

    ?>

    <!-- ovo action="" znaci da ce se forma submitovati na isti ovaj file (index.php) a mozemo da ukucamo neki drugi file gde ce mu biti logika-->
    <!--nakon kliktanja dugmeta login forma salje podatke onoj stranici koja je definisana u njenom action,
    sto znaci da preko post varijable u ovoj stranici mozemo doci do tih podataka -->
    <form action="" method="post">
        Username: <input type="text" name="username"></br>
        Password: <input type="password" name="password"></br>
        <button type="submit">Login</button>
    </form>
</body>

</html>