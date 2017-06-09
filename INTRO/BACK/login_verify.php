<?php
    session_start();
    include ("conectare_db.php");
    $sql = "SELECT random_string from passwords where username = :v_usrn";
    $stid = oci_parse($connection, $sql);
    /*verific daca numele de utilizator contine caractere invalide */
    if(preg_match('/\W/', $_POST["username"]))
    {
        $_SESSION["mesaj_err"] = "Numele de utilizator contine caractere invalide!";
        header('Location: ../FRONT/HTML/pagina_eroare_login.html');
        exit;
    }
    /*verific daca parola contine caractere invalide */
    if(preg_match('/\W/', $_POST["password"]))
    {
        $_SESSION["mesaj_err"] = "Parola contine caractere invalide!";
        header('Location: ../FRONT/HTML/pagina_eroare_login.html');
        exit;
    }
    oci_bind_by_name($stid, ":v_usrn", $_POST["username"]);
    if(!oci_execute($stid))
    {
        $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
        header('Location: ../FRONT/HTML/pagina_eroare_login.html');
        exit;
    }
    if(($row = oci_fetch_array($stid, OCI_BOTH)) != false)
    {
        $nr_random = $row[0];
        $parola_completa = $_POST["password"] . $nr_random;
        $parola_hash = hash('ripemd160', $parola_completa);
        $sql2 = 'begin :rezultat := user_pachet.login(:v_username,:v_password); end;';
        $stid2 = oci_parse($connection, $sql2);
        oci_bind_by_name($stid2, ":v_username", $_POST["username"]);
        oci_bind_by_name($stid2, ":v_password", $parola_hash);
        oci_bind_by_name($stid2, ':rezultat', $rezultat2, 100);
        if(!oci_execute($stid2))
        {
            $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
            header('Location: ../FRONT/HTML/pagina_eroare_login.html');
            oci_free_statement($stid2);
            oci_close($connection);
            exit;
        }
        oci_free_statement($stid2);
        //daca este player
        if($rezultat2 == 'p')
        {
            //creez sesiunea cu numele de utilizator, tipul de utilizator si data logarii
            $_SESSION['online'] = true;
            $_SESSION['username'] = $_POST["username"];
            $_SESSION['rights'] = 'player';
            $_SESSION['logged_time'] = date("d-m-Y");
            $_SESSION['secret'] = base64_encode( openssl_random_pseudo_bytes(32));
            //fac update in baza de date cu logarea
            $sql2 = 'update player set logged = 1 where username = :name';
            $stid2 = oci_parse($connection, $sql2);
            oci_bind_by_name($stid2, ":name", $_POST["username"]);
            if(!oci_execute($stid2))
            {
                $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
                header('Location: ../FRONT/HTML/pagina_eroare_login.html');
                oci_free_statement($stid2);
                oci_close($connection);
                exit;
            }
            oci_free_statement($stid2);
            //selectez id-ul playerului pentru sesiune
            $sql2 = 'select player_id from player where username = :name';
            $stid2 = oci_parse($connection, $sql2);
            oci_bind_by_name($stid2, ":name", $_POST["username"]);
            if(!oci_execute($stid2))
            {
                $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
                header('Location: ../FRONT/HTML/pagina_eroare_login.html');
                oci_free_statement($stid2);
                oci_close($connection);
                exit;
            }
            if(($row2 = oci_fetch_array($stid2, OCI_BOTH)) != false) {
                $_SESSION['player_id'] = $row2[0];
            }
            else{
                $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
                header('Location: ../FRONT/HTML/pagina_eroare_login.html');
                oci_free_statement($stid2);
                oci_close($connection);
                exit;
            }
            oci_close($connection);
            header('Location: ../../PLAYER/FRONT/HTML/logged_user_frame.html');
        }
        // daca este tutore
        else if ($rezultat2 == 't')
        {
            $_SESSION['online'] = true;
            $_SESSION['username'] = $_POST["username"];
            $_SESSION['rights'] = 'tutor';
            $_SESSION['secret'] = base64_encode( openssl_random_pseudo_bytes(32));
            //selectez id-ul tutorelui pentru sesiune
            $sql2 = 'select tutor_id from tutor where username = :name';
            $stid2 = oci_parse($connection, $sql2);
            oci_bind_by_name($stid2, ":name", $_POST["username"]);
            if(!oci_execute($stid2))
            {
                $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
                header('Location: ../FRONT/HTML/pagina_eroare_login.html');
                oci_free_statement($stid2);
                oci_close($connection);
                exit;
            }
            if(($row2 = oci_fetch_array($stid2, OCI_BOTH)) != false) {
                $_SESSION['player_id'] = $row2[0];
            }
            else{
                $_SESSION["mesaj_err"] = "A aparut o eroare neasteptata...";
                header('Location: ../FRONT/HTML/pagina_eroare_login.html');
                oci_free_statement($stid2);
                oci_close($connection);
                exit;
            }
            oci_close($connection);
            header('Location: ../../TUTOR/FRONT/HTML/logged_tutor_frame.html');
        }
        // daca este admin
        else if ($rezultat2 == 'a')
        {
            $_SESSION['online'] = true;
            $_SESSION['username'] = $_POST["username"];
            $_SESSION['rights'] = 'admin';
            $_SESSION['secret'] = base64_encode( openssl_random_pseudo_bytes(32));
            header('Location: ../../ADMIN/FRONT/HTML/admin_frame.html');
        }
        //daca exista numele de utilizator insa parola e gresita
        else
        {
            $_SESSION["mesaj_err"] = "Parola sau nume de utilizator incorecte!";
            header('Location: ../FRONT/HTML/pagina_eroare_login.html');
        }
    }
// daca numele de utilizator e gresit
    else
    {
        $_SESSION["mesaj_err"] = "Parola sau nume de utilizator incorecte!";
        header('Location: ../FRONT/HTML/pagina_eroare_login.html');
    }

?>
