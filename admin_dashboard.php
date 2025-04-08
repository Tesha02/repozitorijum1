<?php

require_once 'config.php';//ovde se startuje sesija

//echo "Usao si na platformu</br>";

//var_dump($_SESSION);//pokazuje nam id admina od onog sa kojim smo se ulogovali na stranici index
//mi mozemo da obrisemo cooki preko inspecta i onda ce se kao obrisati ova sesija,
//sesije cesto koriste kolacice(cookie) da bi identifikovale korisnika

// if(isset($_SESSION['admin_id'])) {
//     echo "Korisnik je ulogovan";
// }else {
//     echo "Korisnik nije ulogovan";
// }ovo je cisto za proveru

if (!isset($_SESSION['admin_id'])) {
    //die("Korisnik nije ulogovan");//die zavrsava kod ceo, mada je bolje da je prebacimo na formu
    header('location:index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <title>Admin dashboard</title>
</head>

<body>
    <?php
    if (isset($_SESSION['success_message'])) { ?>
        <!-- ovaj bootstrap je cisto da bude malo lepse -->
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['success_message'];
            unset($_SESSION['success_message']);//uvek moramo na kraju da unistimo sesiju
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php } ?>



    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h2>Members List</h2>
                <!--link isto moze da salje get/post zahtev-->
                <a href="export.php?what=members" class="btn btn-success btn-sm">Export</a>
                <!-- ovo preko urla mozemo da manipulisemo i preko ovog urla sa ?what znamo sa cim radimo -->
                <table class="table table-stripped">
                    <thead>
                        <tr>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Email</th>
                            <th>Phone number</th>
                            <th>Trainer</th>
                            <th>Photo</th>
                            <th>Training Plan</th>
                            <th>Access Card</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //OBJASNJAVAO JE LEFT I RIGHT JOIN - uglavnom ako je left join onda leva tabela ima prednost,
                        //i njeni rez se ispisuju a ovi od druge tabele ako ne postoje onda se pise null
                        //ako ovde napisemo slozeniji uslov onda dole mozemo samo da pisemo echo $r['name']
                        $sql = "SELECT * FROM members";//nismo morali ono da petljamo sa upitom unutar training plana da bi se prikazalo ime
                        //vec smo to mogli kod ovog upita da to napravimo samo ce ovaj upit biti slozeniji
                        //ja znam sql pa ono
                        $run = $conn->query($sql);
                        $results = $run->fetch_all(MYSQLI_ASSOC);//dobijamo sve informacije od svih korisnika
                        $select_members = $results;

                        foreach ($results as $result): ?>
                            <tr>
                                <td><?php echo $result['first_name']; ?></td>
                                <!-- ovih td treba da imamo isti broj kao ovih gore th -->
                                <td><?php echo $result['last_name']; ?></td>
                                <td><?php echo $result['email']; ?></td>
                                <td><?php echo $result['phone_number']; ?></td>
                                <td><?php

                                $id = $result['trainer_id'];
                                $sql = "SELECT first_name,last_name FROM trainers WHERE trainer_id=?";
                                $run = $conn->prepare($sql);
                                $run->bind_param('i', $id);
                                $run->execute();

                                $r1 = $run->get_result();
                                $r1 = $r1->fetch_assoc();

                                if ($r1) {
                                    echo "<b>" . $r1['first_name'] . " " . $r1['last_name'] . "</b>";
                                } else {
                                    echo "Trener nije dodeljen";
                                }

                                ?></td>
                                <td><img width="60px" src=<?php echo $result['photo_path']; ?>></td>
                                <td><?php

                                $plan_id = $result['training_plan_id'];
                                $sql = "SELECT name FROM training_plans WHERE plan_id=?";
                                $run = $conn->prepare($sql);
                                $run->bind_param('i', $plan_id);
                                $run->execute();

                                $r = $run->get_result();
                                $r = $r->fetch_assoc();//jer dobijamo samo 1 rez
                                //cisto kao provera
                                if ($r) {
                                    echo $r['name'];//CYSECOR JE RADIO PREKO select * i bukv je isto
                                } else {
                                    echo "Nema plana";
                                }

                                ?></td>
                                <td><a target="_blank" href=<?php echo $result['access_card_pdf_path']; ?>>Access card</a>
                                </td>
                                <td><?php echo $result['created_at']; ?></td>
                                <td>
                                    <form action="delete_member.php" method="POST">
                                        <button type="submit" name="member_id" value=<?php echo $result['member_id']; ?>>DELETE</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>

            <div class="col-md-12">
                <h2>Trainers List</h2>
                <a href="export.php?what=trainers" class="btn btn-success btn-sm">Export</a>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Email</th>
                            <th>Phone number</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM trainers";
                        $run = $conn->query($sql);//kod query se odmah i executuje kod
                        
                        $results = $run->fetch_all(MYSQLI_ASSOC);
                        $select_trainers = $results;
                        foreach ($results as $result): ?>

                            <tr>
                                <td><?php echo $result['first_name']; ?></td>
                                <td><?php echo $result['last_name']; ?></td>
                                <td><?php echo $result['email']; ?></td>
                                <td><?php echo $result['phone_number']; ?></td>
                                <td><?php echo $result['created_at']; ?></td>

                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <h2>Register Member</h2>
                <form action="register_member.php" method="POST" enctype="multipart/form-data">
                    <!-- ovo enctype je samo zbog uploada slika -->
                    First Name: <input class="form-control" type="text"
                        name="first_name"><br><!-- ovi name su jako bitni jer preko toga vucemo podatke preko post zahteva -->
                    Last Name: <input class="form-control" type="text" name="last_name"><br>
                    Email: <input class="form-control" type="email" name="email"><br>
                    Phone Number: <input class="form-control" type="text" name="phone_number"><br>
                    Training Plan:
                    <select class="form-control" name="training_plan_id">
                        <option value="" disabled selected>Training Plan</option>

                        <?php
                        $sql = "SELECT * FROM training_plans";
                        $run = $conn->query($sql);//mozemo odmah da pisemo query jer ne menjamo sql upit ni cime
                        $results = $run->fetch_all(MYSQLI_ASSOC);//fetch assoc koristimo kad dobijamo 1 resenje kao red a ovde dobijamo vise redova od upita
                        
                        foreach ($results as $result) {
                            echo "<option value='" . $result['plan_id'] . "'>" . $result['name'] . "</option>";
                        }//!!!!!!!!!!!!!!!!MNOGO BITAN DEO KODA ZA RAZUMEVANJE!!!!!!!!!!!!!!!!!!!
                        ?>

                    </select><br>
                    <input type="hidden" name="photo_path" id="photoPathInput">

                    <div id="dropzone-upload" class="dropzone"></div>

                    <input class="btn btn-primary mt-3" type="submit" value="Register Member">
                </form>
            </div>

            <div class="col-md-6">
                <h2>Register Trainer</h2>
                <form action="register_trainer.php" method="post">
                    First Name: <input class="form-control" type="text" name="first_name"><br>
                    Last Name: <input class="form-control" type="text" name="last_name"><br>
                    Email: <input class="form-control" type="email" name="email"><br>
                    Phone Number: <input class="form-control" type="text" name="phone_number"><br>
                    <input class="btn btn-primary" type="submit" value="Register Trainer">
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h2>Assign Trainer to Member</h2>
                <form action="assign_trainer.php" method="POST">
                    <label for="">Select Member</label>
                    <select name="member" class="form-select">
                        <?php //bitan ne ovaj value jbg da se zna kako kome i sta
                        foreach ($select_members as $member): ?>
                            <option value="<?php echo $member['member_id'] ?>">
                                <?php echo $member['first_name'] . " " . $member['last_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="">Select Trainer</label>
                    <select name="trainer" class="form-select">
                        <?php
                        foreach ($select_trainers as $trainer): ?>
                            <option value="<?php echo $trainer['trainer_id'] ?>">
                                <?php echo $trainer['first_name'] . " " . $trainer['last_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn btn-primary">Assign Trainer</button>
                </form>
            </div>
        </div>
    </div>

    <?php $conn->close(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script>
        //umesto crtice ide veliko slovo zato ne pise dropzone-upload nego ovako
        Dropzone.options.dropzoneUpload = {
            url: "upload_photo.php",
            paramName: "photo",
            maxFilesSize: 20,
            acceptedFiles: "image/*",
            init: function () {
                this.on("success", function (file, response) {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        //photopathinput je onaj div gore i tu stavljamo kad se uploadujemo sliku da imamo sacuvano url slike
                        document.getElementById('photoPathInput').value = jsonResponse.photo_path;
                    } else {
                        console.error(jsonResponse.error);
                    }
                });
            }
        };
    </script>

</body>

</html>