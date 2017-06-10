<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 01-Jun-17
 * Time: 22:29
 */

session_start();
include ("conectare_db.php");
if($_SESSION['secret']!=$_POST['secret'])
    header('Location: ../../../INTRO/FRONT/HTML/session_error.html');

$name = $_POST["name"];

if(preg_match('/\W/', $name))
{
    $_SESSION["mesaj_err"] = "Numele tau contine caractere invalide!";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}

$surname = $_POST["surname"];


if(preg_match('/\W/', $surname))
{
    $_SESSION["mesaj_err"] = "Prenumele tau contine caractere invalide!";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}


$username = $_POST["username"];


/* verific daca numele de utilizator introdus pentru jucator nu e existent deja */
/* verific daca numele utilizatorului contine caractere invalide */
if(preg_match('/\W/', $username))
{
    $_SESSION["mesaj_err"] = "Numele tau de utilizator contine caractere invalide!";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}
$sql2 = 'select username from player where username like :p_usr';
$stid2 = oci_parse($connection, $sql2);
oci_bind_by_name($stid2, ":p_usr", $username);
if(!oci_execute($stid2))
{
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}
else
{
    if(($row2 = oci_fetch_array($stid2, OCI_BOTH)) != false)
    {
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
    $_SESSION["mesaj_err"] = "Emailul tau contine caractere invalide!";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}
if (empty($user) || empty($domain) || !checkdnsrr($domain))
{
    $_SESSION["mesaj_err"] = "Email invalid pentru jucator!";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}

/* verific daca mailul introdus pentru jucator nu e existent deja */
$sql20 = 'select email from player where email like :p_email';
$stid20 = oci_parse($connection, $sql20);
oci_bind_by_name($stid20, ":p_email", $email);
if(!oci_execute($stid20))
{
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    exit;
}
else
{
    if(($row20 = oci_fetch_array($stid20, OCI_BOTH)) != false)
    {
        $_SESSION["mesaj_err"] = "Emailul introdus pentru tine e existent deja.";
        header('Location: ../FRONT/HTML/eroare_editare.html');
        exit;
    }
}


/* formatez data nasterii */
$birthday = $_POST["day"] . '/' . $_POST["month"] . '/' . $_POST["year"];

echo $_POST['player_id'];
$stid = oci_parse($connection, 'UPDATE player SET first_name = :name, last_name = :surname, username = :username, email = :email where player_id = :player_id');
oci_bind_by_name($stid, ':name', $name);
oci_bind_by_name($stid, ':surname', $surname);
oci_bind_by_name($stid, ':username', $username);
oci_bind_by_name($stid, ':email', $email);
oci_bind_by_name($stid, ':player_id', $_POST['player_id']);

$result = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);
if (!$result) {
    echo oci_error();
}
oci_free_statement($stid);

echo $_POST['player_id'];
$stid = oci_parse($connection, "UPDATE player_dates SET birthday = TO_DATE(:birthday,'DD/MM/YYYY') where player_id = :player_id");
oci_bind_by_name($stid, ':birthday', $birthday);
oci_bind_by_name($stid, ':player_id', $_POST['player_id']);

oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

oci_free_statement($stid);

session_unset();
session_destroy();

session_start();
$_SESSION['username'] = $username;
$_SESSION['player_id'] = $_POST['player_id'];

header('Location: ../FRONT/HTML/logged_user_frame.html');