<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 01-Jun-17
 * Time: 22:29
 */
include ("conectare_db.php");
session_start();

$name = $_POST["name"];

if(preg_match('/\W/', $name))
{
    session_start();
    $_SESSION["mesaj_err"] = "Numele tau contine caractere invalide!";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}

$surname = $_POST["surname"];


if(preg_match('/\W/', $surname))
{
    session_start();
    $_SESSION["mesaj_err"] = "Prenumele tau contine caractere invalide!";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}


$username = $_POST["username"];


/* verific daca numele de utilizator introdus pentru tutore nu e existent deja */
/* verific daca numele utilizatorului contine caractere invalide */
if(preg_match('/\W/', $username))
{
    session_start();
    $_SESSION["mesaj_err"] = "Numele tau de utilizator contine caractere invalide!";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}
$sql2 = 'select username from tutor where username like :p_usr';
$stid2 = oci_parse($connection, $sql2);
oci_bind_by_name($stid2, ":p_usr", $username);
if(!oci_execute($stid2))
{
    session_start();
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}
else
{
    if(($row2 = oci_fetch_array($stid2, OCI_BOTH)) != false)
    {
        session_start();
        $_SESSION["mesaj_err"] = "Numele de utilizator " . $username . " e existent deja.";
        header('Location: ../FRONT/HTML/eroare_editare.html');
        exit;
    }
}


$email = $_POST["email"];

/* verific forma email-ului userului */
$domain = ltrim(stristr($email, '@'), '@');
$user   = stristr($email, '@', TRUE);
/*verific daca trunchiul emailului contine caractere invalide */
if(preg_match('/\W/', $user))
{
    session_start();
    $_SESSION["mesaj_err"] = "Emailul tau contine caractere invalide!";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}
if (empty($user) || empty($domain) || !checkdnsrr($domain))
{
    session_start();
    $_SESSION["mesaj_err"] = "Email invalid pentru jucator!";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}

/* verific daca mailul introdus pentru tutore nu e existent deja */
$sql20 = 'select email from tutor where email like :p_email';
$stid20 = oci_parse($connection, $sql20);
oci_bind_by_name($stid20, ":p_email", $email);
if(!oci_execute($stid20))
{
    session_start();
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}
else
{
    if(($row20 = oci_fetch_array($stid20, OCI_BOTH)) != false)
    {
        session_start();
        $_SESSION["mesaj_err"] = "Emailul introdus pentru tine e existent deja.";
        header('Location: ../FRONT/HTML/eroare_editare.html');
        exit;
    }
}

$stid = oci_parse($connection, 'UPDATE tutor SET first_name = :name, last_name = :surname, username = :username, email = :email where tutor_id = :tutor_id');
oci_bind_by_name($stid, ':name', $name);
oci_bind_by_name($stid, ':surname', $surname);
oci_bind_by_name($stid, ':username', $username);
oci_bind_by_name($stid, ':email', $email);
oci_bind_by_name($stid, ':tutor_id', $_POST['tutor_id']);

$result = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);
if (!$result) {
    echo oci_error();
}
oci_free_statement($stid);

session_unset();
session_destroy();

session_start();
$_SESSION['username'] = $username;
$_SESSION['tutor_id'] = $_POST['tutor_id'];
