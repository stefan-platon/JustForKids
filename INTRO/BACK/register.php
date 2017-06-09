<?php
include ("conectare_db.php");

$sql = 'begin user_pachet.register_user(:p_f_name,:p_s_name,:p_username,:p_password,:nr_rand,:p_img,:p_email,:p_bday,:p_relation,:t_f_name,:t_s_name,:t_username,:t_password,:t_nr_rand,:t_img,:t_email);END;';
$stid = oci_parse($connection, $sql);
/*verific daca numele contine caractere invalide */
if(preg_match('/\W/', $_POST["name"]))
{
    session_start();
    $_SESSION["mesaj_err"] = "Numele tau contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if(strlen($_POST["name"] > 29))
{
    session_start();
    $_SESSION["mesaj_err"] = "Numele tau contine prea multe caractere!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
oci_bind_by_name($stid, ":p_f_name", $_POST["name"]);

/*verific daca prenumele contine caractere invalide */
if(preg_match('/\W/', $_POST["surname"]))
{
    session_start();
    $_SESSION["mesaj_err"] = "Prenumele tau contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if(strlen($_POST["surname"] > 29))
{
    session_start();
    $_SESSION["mesaj_err"] = "Preumele tau contine prea multe caractere!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
oci_bind_by_name($stid, ":p_s_name", $_POST["surname"]);

/*verific daca numele tutorelui contine caractere invalide */
if(preg_match('/\W/', $_POST["t_name"]))
{
    session_start();
    $_SESSION["mesaj_err"] = "Numele tutorelui contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if(strlen($_POST["t_name"] > 29))
{
    session_start();
    $_SESSION["mesaj_err"] = "Numele tutorelui contine prea multe caractere!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
oci_bind_by_name($stid, ":t_f_name", $_POST["t_name"]);

/*verific daca prenumele tutorelui contine caractere invalide */
if(preg_match('/\W/', $_POST["t_surname"]))
{
    session_start();
    $_SESSION["mesaj_err"] = "Preumele tutorelui contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if(strlen($_POST["t_surname"] > 29))
{
    session_start();
    $_SESSION["mesaj_err"] = "Prenumele tutorelui contine prea multe caractere!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
oci_bind_by_name($stid, ":t_s_name", $_POST["t_surname"]);

oci_bind_by_name($stid, ":p_relation", $_POST["relation"]);

/* verific forma email-ului userului */
$domain = ltrim(stristr($_POST["email"], '@'), '@');
$user   = stristr($_POST["email"], '@', TRUE);
/*verific daca trunchiul emailului contine caractere invalide */
if(preg_match('/\W/', $user))
{
    session_start();
    $_SESSION["mesaj_err"] = "Emailul tau contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if (empty($user) || empty($domain) || !checkdnsrr($domain))
{
    session_start();
    $_SESSION["mesaj_err"] = "Email invalid pentru jucator!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if(strlen($_POST["email"] > 49))
{
    session_start();
    $_SESSION["mesaj_err"] = "Emailul tau contine prea multe caractere!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}

/* verific daca mailul introdus pentru jucator nu e existent deja */
$sql20 = 'select email from player where email like :p_email';
$stid20 = oci_parse($connection, $sql20);
oci_bind_by_name($stid20, ":p_email", $_POST["email"]);
if(!oci_execute($stid20))
{
    session_start();
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
else
{
    if(($row20 = oci_fetch_array($stid20, OCI_BOTH)) != false)
    {
        session_start();
        $_SESSION["mesaj_err"] = "Emailul introdus pentru tine e existent deja.";
        header('Location: ../FRONT/HTML/pagina_eroare_register.html');
        exit;
    }
    else
    {
        oci_bind_by_name($stid, ":p_email", $_POST["email"]);
    }
}


/* verific email-ul tutorului */
$domain = ltrim(stristr($_POST["t_email"], '@'), '@');
$user   = stristr($_POST["t_email"], '@', TRUE);
/*verific daca trunchiul emailului tutorelui contine caractere invalide */
if(preg_match('/\W/', $user))
{
    session_start();
    $_SESSION["mesaj_err"] = "Emailul tutorelui contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if (empty($user) || empty($domain) || !checkdnsrr($domain))
{
    session_start();
    $_SESSION["mesaj_err"] = "Email invalid pentru tutore!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if(strlen($_POST["t_email"] > 29))
{
    session_start();
    $_SESSION["mesaj_err"] = "Emailul tutorelui contine prea multe caractere!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
/* verific daca mailul introdus pentru tutore nu e existent deja */
$sql20 = 'select email from tutor where email like :t_email';
$stid20 = oci_parse($connection, $sql20);
oci_bind_by_name($stid20, ":t_email", $_POST["t_email"]);
if(!oci_execute($stid20))
{
    session_start();
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
else
{
    if(($row20 = oci_fetch_array($stid20, OCI_BOTH)) != false)
    {
        session_start();
        $_SESSION["mesaj_err"] = "Emailul introdus pentru tutore e existent deja.";
        header('Location: ../FRONT/HTML/pagina_eroare_register.html');
        exit;
    }
    else
    {
        oci_bind_by_name($stid, ":t_email", $_POST["t_email"]);
    }
}

/* verific daca parola si parola repetata coincid */
if(preg_match('/\W/', $_POST["psw"]))
{
    session_start();
    $_SESSION["mesaj_err"] = "Parola ta contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if(preg_match('/\W/', $_POST["r_psw"]))
{
    session_start();
    $_SESSION["mesaj_err"] = "Parola ta repetata contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if($_POST["psw"] != $_POST["r_psw"])
{
    session_start();
    $_SESSION["mesaj_err"] = "Parola si parola repetata pentru contul tau nu coincid!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}

if(preg_match('/\W/', $_POST["t_psw"]))
{
    session_start();
    $_SESSION["mesaj_err"] = "Parola tutorelui contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if(preg_match('/\W/', $_POST["t_r_psw"]))
{
    session_start();
    $_SESSION["mesaj_err"] = "Parola tutorelui repetata contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if($_POST["t_psw"] != $_POST["t_r_psw"])
{
    session_start();
    $_SESSION["mesaj_err"] = "Parola si parola repetata pentru contul tutorelui nu coincid!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}

/* criptez parola userului */
$nr_random_p = rand();
oci_bind_by_name($stid, ":nr_rand", $nr_random_p);
$parola_completa_p = $_POST["psw"] . $nr_random_p;
$parola_hash_p = hash('ripemd160', $parola_completa_p);
oci_bind_by_name($stid, ":p_password", $parola_hash_p);
if(strlen($parola_hash_p > 999))
{
    session_start();
    $_SESSION["mesaj_err"] = "Parola ta contine prea multe caractere!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
/* criptez parola tutorelui */
$nr_random_t = rand();
oci_bind_by_name($stid, ":t_nr_rand", $nr_random_t);
$parola_completa_t = $_POST["t_psw"] . $nr_random_t;
$parola_hash_t = hash('ripemd160', $parola_completa_t);
oci_bind_by_name($stid, ":t_password", $parola_hash_t);
if(strlen($parola_hash_t > 999))
{
    session_start();
    $_SESSION["mesaj_err"] = "Parola tutorelui contine prea multe caractere!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
/* verific daca numele de utilizator introdus pentru tutore nu e existent deja */
/* verific daca numele de utilizator contine caractere invalide */
if(preg_match('/\W/', $_POST["t_username"]))
{
    session_start();
    $_SESSION["mesaj_err"] = "Numele de utilizator al tutorelui contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if(strlen($_POST["t_username"] > 29))
{
    session_start();
    $_SESSION["mesaj_err"] = "Numele de utilizator al tutorelui contine prea multe caractere!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
$sql2 = 'select username from tutor where username like :t_usr';
$stid2 = oci_parse($connection, $sql2);
oci_bind_by_name($stid2, ":t_usr", $_POST["t_username"]);
if(!oci_execute($stid2))
{
    session_start();
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if(($row2 = oci_fetch_array($stid2, OCI_BOTH)) != false)
{
    $sql3 = 'select hash from passwords where username like :t_usr';
    $stid3 = oci_parse($connection, $sql3);
    oci_bind_by_name($stid3, ":t_usr", $_POST["t_username"]);
    if(!oci_execute($stid3))
    {
        session_start();
        $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
        header('Location: ../FRONT/HTML/pagina_eroare_register.html');
        exit;
    }
    else
    {
        if($row3[0] = $parola_hash_t)
        {
            oci_bind_by_name($stid, ":t_username", $_POST["t_username"]);
        }
        else
        {
            session_start();
            $_SESSION["mesaj_err"] = "Numele de utilizator introdus pentru tutore e existent deja. Daca acel tutore are deja cont, ai gresit parola lui.";
            header('Location: ../FRONT/HTML/pagina_eroare_register.html');
            exit;
        }
    }
}
else
{
    oci_bind_by_name($stid, ":t_username", $_POST["t_username"]);
}

/* verific daca numele de utilizator introdus pentru jucator nu e existent deja */
/* verific daca numele utilizatorului contine caractere invalide */
if(preg_match('/\W/', $_POST["username"]))
{
    session_start();
    $_SESSION["mesaj_err"] = "Numele tau de utilizator contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if(strlen($_POST["username"] > 29))
{
    session_start();
    $_SESSION["mesaj_err"] = "Numele tau de utilizator contine prea multe caractere!";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
$sql2 = 'select username from player where username like :p_usr';
$stid2 = oci_parse($connection, $sql2);
oci_bind_by_name($stid2, ":p_usr", $_POST["username"]);
if(!oci_execute($stid2))
{
    session_start();
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
else
{
    if(($row2 = oci_fetch_array($stid2, OCI_BOTH)) != false)
    {
        session_start();
        $_SESSION["mesaj_err"] = "Numele de utilizator introdus pentru tine e existent deja.";
        header('Location: ../FRONT/HTML/pagina_eroare_register.html');
        exit;
    }
    else
    {
        oci_bind_by_name($stid, ":p_username", $_POST["username"]);
    }
}

/* verific poza de profil a utilizatorului */
$target_dir = "../../IMG/PROFILE/";
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
        session_start();
        $_SESSION["mesaj_err"] = "Fisierul nu este fisier adevarat!";
        header('Location: ../FRONT/HTML/pagina_eroare_register.html');
        exit;
    }
}
// Allow certain file formats
if($ext != "jpg" && $ext != "png" && $ext != "jpeg") {
    session_start();
    $_SESSION["mesaj_err"] = "Se accepta doar formatele JPG, PNG si JPEG.";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    oci_bind_by_name($stid, ":p_img", $target_file);
} else {
    session_start();
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata... Poza nu a putut fi preluata.";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}

/* verific poza de profil a tutorelui */
$target_dir2 = "../../IMG/PROFILE/";
$info2 = pathinfo($_FILES['fileToUpload2']['name']);
$ext2 = $info2['extension']; // get the extension of the file
$newname2 = $_POST["t_username"].".".$ext2;
$target_file2 = $target_dir2 . $newname2;
$uploadOk2 = 1;
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload2"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        session_start();
        $_SESSION["mesaj_err"] = "Fisierul nu este fisier adevarat!";
        header('Location: ../FRONT/HTML/pagina_eroare_register.html');
        exit;
    }
}
// Allow certain file formats
if($ext2 != "jpg" && $ext2 != "png" && $ext2 != "jpeg") {
    session_start();
    $_SESSION["mesaj_err"] = "Se accepta doar formatele JPG, PNG si JPEG.";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}
if (move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_file2)) {
    oci_bind_by_name($stid, ":t_img", $target_file2);
} else {
    session_start();
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata... Poza nu a putut fi preluata.";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}

/* formatez data nasterii */
$data_nastere = $_POST["day"] . '/' . $_POST["month"] . '/' . $_POST["year"];
oci_bind_by_name($stid, ":p_bday", $data_nastere);

/* daca nu sunt probleme introduc in baza de date */
if(!oci_execute($stid))
{
    session_start();
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
    header('Location: ../FRONT/HTML/pagina_eroare_register.html');
    exit;
}


header('Location: ../FRONT/HTML/login_frame.html');
?>
