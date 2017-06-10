<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 03-Jun-17
 * Time: 18:09
 */

session_start();
$tutor_id = $_SESSION["tutor_id"];
echo $tutor_id;
include ("conectare_db.php");


/* verific poza de profil a utilizatorului */
$target_dir = "../../IMG/PROFILE/";
$info = pathinfo($_FILES['fileToUpload']['name']);
$ext = $info['extension']; // get the extension of the file
$newname = $_SESSION["username"].".".$ext;
$target_file = $target_dir . $newname;
$uploadOk = 1;
echo $target_file;

if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $_SESSION["mesaj_err"] = "Fisierul nu este fisier adevarat!";
        header('Location: ../FRONT/HTML/eroare_editare.html');
        exit;
    }
}

//accept doar fisiere de tipul .jpg, .jpeg sau .png
if($ext != "jpg" && $ext != "png" && $ext != "jpeg") {
    $_SESSION["ext"] = "Extensie:" . $ext;
    $_SESSION["mesaj_err"] = "Se accepta doar formatele JPG, PNG si JPEG.";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}

$sql = 'update TUTOR set LINK = :p_img WHERE tutor_id=:tutor_id';
$stid = oci_parse($connection, $sql);

if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    oci_bind_by_name($stid, ":p_img", $target_file);
    oci_bind_by_name($stid, ":tutor_id", $tutor_id);
    oci_execute($stid,OCI_COMMIT_ON_SUCCESS);
} else {
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata... Poza nu a putut fi preluata.";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}
header('Location: ../FRONT/HTML/logged_tutor_frame.html');
