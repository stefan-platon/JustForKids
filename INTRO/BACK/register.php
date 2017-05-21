<?php
include ("conectare_db.php");

$sql = 'begin user_pachet.register_user(:p_f_name,:p_s_name,:p_username,:p_password,:nr_rand,:p_img,:p_email,:p_bday,:p_relation,:t_f_name,:t_s_name,:t_username,:t_password,:t_nr_rand,:t_img,:t_email);END;';
$stid = oci_parse($connection, $sql);
oci_bind_by_name($stid, ":p_f_name", $_POST["name"]);
oci_bind_by_name($stid, ":p_s_name", $_POST["surname"]);
oci_bind_by_name($stid, ":p_relation", $_POST["relation"]);
oci_bind_by_name($stid, ":t_f_name", $_POST["t_name"]);
oci_bind_by_name($stid, ":t_s_name", $_POST["t_surname"]);

/* verific email-ul userului */
$domain = ltrim(stristr($_POST["email"], '@'), '@');
$user   = stristr($_POST["email"], '@', TRUE);

if (empty($user) || empty($domain) || !checkdnsrr($domain))
{
    echo "Email invalid pentru jucator!";
    $handle = fopen('register.php', 'r');
    fclose($handle);
}
else
{
    oci_bind_by_name($stid, ":p_email", $_POST["email"]);
}

/* verific email-ul tutorului */
$domain = ltrim(stristr($_POST["t_email"], '@'), '@');
$user   = stristr($_POST["t_email"], '@', TRUE);

if (empty($user) || empty($domain) || !checkdnsrr($domain))
{
    echo "Email invalid pentru tutore!";
    $handle = fopen('register.php', 'r');
    fclose($handle);
}
else
{
    oci_bind_by_name($stid, ":t_email", $_POST["t_email"]);
}

/* verific daca parola si parola repetata coincid */
if($_POST["psw"] != $_POST["r_psw"])
{
    echo "Parolele pentru contul tau nu coincid !";
}
if($_POST["t_psw"] != $_POST["t_r_psw"])
{
    echo "Parolele pentru contul tutorelui nu coincid !";
}

/* criptez parola userului */
$nr_random_p = rand();
oci_bind_by_name($stid, ":nr_rand", $nr_random_p);
$parola_completa_p = $_POST["psw"] . $nr_random_p;
$parola_hash_p = hash('ripemd160', $parola_completa_p);
oci_bind_by_name($stid, ":p_password", $parola_hash_p);

/* criptez parola tutorelui */
$nr_random_t = rand();
oci_bind_by_name($stid, ":t_nr_rand", $nr_random_t);
$parola_completa_t = $_POST["t_psw"] . $nr_random_t;
$parola_hash_t = hash('ripemd160', $parola_completa_t);
oci_bind_by_name($stid, ":t_password", $parola_hash_t);

/* verific daca numele de utilizator introdus pentru tutore nu e existent deja */
$sql2 = 'select username from tutor where username like :t_usr';
$stid2 = oci_parse($connection, $sql2);
oci_bind_by_name($stid2, ":t_usr", $_POST["t_username"]);
if(!oci_execute($stid2))
{
    $error_flag2 = 0;
    $e2 = oci_error($stid2);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e2['message'];
}
if(($row2 = oci_fetch_array($stid2, OCI_BOTH)) != false)
{
    $sql3 = 'select hash from passwords where username like :t_usr';
    $stid3 = oci_parse($connection, $sql3);
    oci_bind_by_name($stid3, ":t_usr", $_POST["t_username"]);
    if(!oci_execute($stid3))
    {
        $error_flag3 = 0;
        $e3 = oci_error($stid3);
        echo "Something went wrong :( <br/>";
        echo "Error: " . $e3['message'];
    }
    else
    {
        if($row3[0] = $parola_hash_t)
        {
            oci_bind_by_name($stid, ":t_username", $_POST["t_username"]);
        }
        else
        {
            echo "Numele de utilizator introdus pentru tutore e deja existent!";
            $handle = fopen('register.php', 'r');
            fclose($handle);
        }
    }
}
else
{
    oci_bind_by_name($stid, ":t_username", $_POST["t_username"]);
}

/* verific daca numele de utilizator introdus pentru jucator nu e existent deja */
$sql2 = 'select username from player where username like :p_usr';
$stid2 = oci_parse($connection, $sql2);
oci_bind_by_name($stid2, ":p_usr", $_POST["username"]);
if(!oci_execute($stid2))
{
    $error_flag2 = 0;
    $e2 = oci_error($stid2);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e2['message'];
}
else
{
    if(($row2 = oci_fetch_array($stid2, OCI_BOTH)) != false)
    {
        echo "Numele de utilizator introdus pentru jucator e deja existent!";
        $handle = fopen('register.php', 'r');
        fclose($handle);
    }
    else
    {
        oci_bind_by_name($stid, ":p_username", $_POST["username"]);
    }
}

/* verific poza de profil a utilizatorului */
$target_dir = "../IMG/PROFILE/";
$info = pathinfo($_FILES['fileToUpload']['name']);
$ext = $info['extension']; // get the extension of the file
$newname = $_POST["username"].".".$ext;
$target_file = $target_dir . $newname;
$uploadOk = 1;
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Allow certain file formats
if($ext != "jpg" && $ext != "png" && $ext != "jpeg") {
    echo "Sorry, only JPG, and PNG files are allowed.";
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    echo " Your picture was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        oci_bind_by_name($stid, ":p_img", $target_file);
    } else {
        echo "Sorry, there was an error uploading your picture.";
    }
}

/* verific poza de profil a tutorelui */
$target_dir = "../IMG/PROFILE/";
$info = pathinfo($_FILES['fileToUpload2']['name']);
$ext = $info['extension']; // get the extension of the file
$newname = $_POST["t_username"].".".$ext;
$target_file2 = $target_dir . $newname;
$uploadOk = 1;
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload2"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Allow certain file formats
if($ext != "jpg" && $ext != "png" && $ext != "jpeg") {
    echo "Sorry, only JPG, and PNG files are allowed.";
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    echo " Your picture was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_file)) {
        oci_bind_by_name($stid, ":t_img", $target_file2);
    } else {
        echo "Sorry, there was an error uploading tutor's picture.";
    }
}

/* formatez data nasterii */
$data_nastere = $_POST["day"] . '/' . $_POST["month"] . '/' . $_POST["year"];
oci_bind_by_name($stid, ":p_bday", $data_nastere);

/* daca nu sunt probleme introduc in baza de date */
if(!oci_execute($stid))
{
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
oci_free_statement($stid);
oci_close($connection);
header('Location: ../FRONT/HTML/login_frame.html');
?>
