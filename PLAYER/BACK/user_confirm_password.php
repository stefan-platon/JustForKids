<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 01-Jun-17
 * Time: 23:14
 */

session_start();
if($_SESSION['secret']!=$_POST['secret'])
    header('Location: ../../../INTRO/FRONT/HTML/session_error.html');
include ("conectare_db.php");
$username = $_SESSION["username"];
echo $username;
$sql = "SELECT random_string from passwords where username = :username";
$stid = oci_parse($connection, $sql);

//verific daca parola contine caractere invalide
if(preg_match('/\W/', $_POST["c_psw"]))
{
    $_SESSION["mesaj_err"] = "Parola contine caractere invalide!";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    oci_close($connection);
    exit;
}
oci_bind_by_name($stid, ":username", $username);

//eroare la oci_execute
if(!oci_execute($stid))
{
    $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
    header('Location: ../FRONT/HTML/eroare_editare.html');
    oci_close($connection);
    exit;
}

//verificare parola actuala
if(($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    $nr_random = $row[0];
    $parola_completa = $_POST["c_psw"] . $nr_random;
    $parola_hash = hash('ripemd160', $parola_completa);
    $sql2 = 'begin :rezultat := user_pachet.login(:v_username,:v_password); end;';
    $stid2 = oci_parse($connection, $sql2);
    oci_bind_by_name($stid2, ":v_username", $username);
    oci_bind_by_name($stid2, ":v_password", $parola_hash);
    oci_bind_by_name($stid2, ':rezultat', $rezultat2, 100);
    if (!oci_execute($stid2)) {
        $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
        header('Location: ../FRONT/HTML/eroare_editare.html');
        oci_free_statement($stid2);
        oci_close($connection);
        exit;
    }
    echo $rezultat2;
    oci_free_statement($stid2);
    //parola actuala este corecta
    if(strcmp($rezultat2 ,'p') == 0) {
        //actualizare parola


        if(preg_match('/\W/', $_POST["psw"]))
        {
            session_start();
            $_SESSION["mesaj_err"] = "Parola ta contine caractere invalide!";
            header('Location: ../FRONT/HTML/eroare_editare.html');
            exit;
        }
        if(preg_match('/\W/', $_POST["r_psw"]))
        {
            session_start();
            $_SESSION["mesaj_err"] = "Parola ta repetata contine caractere invalide!";
            header('Location: ../FRONT/HTML/eroare_editare.html');
            exit;
        }

        /* verific daca parola noua si cea repetata coincid */
        if($_POST["psw"] != $_POST["r_psw"])
        {
            session_start();
            $_SESSION["mesaj_err"] = "Parola si parola repetata pentru contul tau nu coincid!";
            header('Location: ../FRONT/HTML/eroare_editare.html');
            exit;
        }



        /* criptez parola userului */
        $nr_random_p = rand();
        $parola_completa_p = $_POST["psw"] . $nr_random_p;
        $parola_hash_p = hash('ripemd160', $parola_completa_p);

        echo $username;
        //introduc parola noua in baza de date
        $sql4 = "update PASSWORDS set HASH=:parola_hash, RANDOM_STRING=:nr_random where USERNAME=:username";
        $stid2 = oci_parse($connection, $sql4);
        oci_bind_by_name($stid2, ":username", $username);
        oci_bind_by_name($stid2, ":parola_hash", $parola_hash_p);
        oci_bind_by_name($stid2, ":nr_random", $nr_random_p);

        //eroare la oci_execute
        $result = oci_execute($stid2, OCI_COMMIT_ON_SUCCESS);
        if(!$result)
        {
            $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
            header('Location: ../FRONT/HTML/eroare_editare.html');
            oci_close($connection);
            exit;
        }
        oci_free_statement($stid2);
    }
    //parola actuala este gresita
    else {
        $_SESSION["mesaj_err"] = "Parola actuala este gresita!";
        header('Location: ../FRONT/HTML/eroare_editare.html');
    }
}

oci_close($connection);

header('Location: ../FRONT/HTML/logged_user_frame.html');