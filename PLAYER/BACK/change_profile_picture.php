<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 03-Jun-17
 * Time: 18:09
 */

session_start();
$username = $_SESSION["username"];

include ("conectare_db.php");
/* verific poza de profil a utilizatorului */
$target_dir = "../../../IMG/PROFILE/";
$info = pathinfo($_FILES['fileToUpload']['name']);
$ext = $info['extension']; // get the extension of the file
$newname = $_SESSION["username"].".".$ext;
$target_file = $target_dir . $newname;
$uploadOk = 1;
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        session_start();
        $_SESSION["mesaj_err"] = "Fisierul nu este fisier adevarat!";
        header('Location: ../FRONT/HTML/eroare_editare.html');
        exit;
    }
}
// Allow certain file formats
if($ext != "jpg" && $ext != "png" && $ext != "jpeg") {
    session_start();
    $_SESSION["mesaj_err"] = "Se accepta doar formatele JPG, PNG si JPEG.";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}

$sql = 'update PLAYER set LINK = :p_img WHERE USERNAME=:username';
$stid = oci_parse($connection, $sql);

if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    oci_bind_by_name($stid, ":p_img", $target_file);
    oci_bind_by_name($stid, ":username", $username);
    oci_execute($stid,OCI_COMMIT_ON_SUCCESS);
} else {
    session_start();
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata... Poza nu a putut fi preluata.";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}

