<?php
include("conectare_db.php");

$sql = 'begin :rezultat :=  admin_pachet.insert_joc(:v_nume,:v_dif,:v_descr,:v_instr,:v_domeniu,:i_link,:g_link); END;';
$stid = oci_parse($connection, $sql);
if (preg_match('/\W/', $_POST["nume"]) && $_POST["nume"] != null) {
    session_start();
    $_SESSION["mesaj_err"] = "Textul contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_admin.html');
    exit;
}
oci_bind_by_name($stid, ":v_nume", $_POST["nume"]);
oci_bind_by_name($stid, ":v_dif", $_POST["dificultate"]);
if (preg_match('/\W/', $_POST["descr"]) && $_POST["descr"] != null) {
    session_start();
    $_SESSION["mesaj_err"] = "Textul contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_admin.html');
    exit;
}
oci_bind_by_name($stid, ":v_descr", $_POST["descr"]);
if (preg_match('/\W/', $_POST["instr"]) && $_POST["instr"] != null) {
    session_start();
    $_SESSION["mesaj_err"] = "Textul contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_admin.html');
    exit;
}
oci_bind_by_name($stid, ":v_instr", $_POST["instr"]);
oci_bind_by_name($stid, ":v_domeniu", $_POST["domeniu"]);
$target_dir = "../IMG/JOCURI/";
$info = pathinfo($_FILES['fileToUpload']['name']);
$ext = $info['extension']; // get the extension of the file
$newname = $_POST["nume"] . "." . $ext;
$target_file = $target_dir . $newname;
$uploadOk = 1;
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Allow certain file formats
if ($ext != "jpg" && $ext != "png" && $ext != "jpeg") {
    echo "Sorry, only JPG, and PNG files are allowed.";
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    echo "Sorry, your picture was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        oci_bind_by_name($stid, ":i_link", $target_file);
        oci_bind_by_name($stid, ":g_link", $_POST["game_path"]);
        oci_bind_by_name($stid, ':rezultat', $rezultat, 100);
        if (!oci_execute($stid)) {
            $e = oci_error($stid);
            echo "Something went wrong :( <br/>";
            echo "Error: " . $e['message'];
        }
        oci_free_statement($stid);
        oci_close($connection);
        echo $rezultat;
    } else {
        echo "Sorry, there was an error uploading your picture.";
    }
}
?>