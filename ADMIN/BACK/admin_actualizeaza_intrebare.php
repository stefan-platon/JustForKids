<?php
session_start();
if($_SESSION['secret']!=$_POST['secret'])
    header("Location:../FRONT/HTML/session_error.html");
else
    session_write_close();

    include ("conectare_db.php");
    $sql = 'begin :rezultat :=  admin_pachet.update_intrebare(:v_id,:v_domeniu,:v_text,:v_dificultate,:v_tip,:v_r1,:v_r2,:v_r3,:v_rc);END;';
    $stid = oci_parse($connection, $sql);
    oci_bind_by_name($stid, ":v_id", $_POST["q_id"]);
    oci_bind_by_name($stid, ":v_domeniu", $_POST["domeniu"]);
    if(preg_match('/\W/', $_POST["text"]) && $_POST['text']!=null){
        session_start();
        $_SESSION["mesaj_err"] = "Textul contine caractere invalide!";
        header('Location: ../FRONT/HTML/pagina_eroare_admin.html');
        exit;
    }
    oci_bind_by_name($stid, ":v_text", $_POST["text"]);
    oci_bind_by_name($stid, ":v_dificultate", $_POST["dificultate"]);
    oci_bind_by_name($stid, ":v_tip", $_POST["tip_intrebare"]);
    if(preg_match('/\W/', $_POST["r_1"]) && $_POST['r_1']!=null){
        session_start();
        $_SESSION["mesaj_err"] = "Textul contine caractere invalide!";
        header('Location: ../FRONT/HTML/pagina_eroare_admin.html');
        exit;
    }
    oci_bind_by_name($stid, ":v_r1", $_POST["r_1"]);
    if(preg_match('/\W/', $_POST["r_2"]) && $_POST['r_2']!=null){
        session_start();
        $_SESSION["mesaj_err"] = "Textul contine caractere invalide!";
        header('Location: ../FRONT/HTML/pagina_eroare_admin.html');
        exit;
    }
    oci_bind_by_name($stid, ":v_r2", $_POST["r_2"]);
    if(preg_match('/\W/', $_POST["r_3"]) && $_POST['r_3']!=null){
        session_start();
        $_SESSION["mesaj_err"] = "Textul contine caractere invalide!";
        header('Location: ../FRONT/HTML/pagina_eroare_admin.html');
        exit;
    }
    oci_bind_by_name($stid, ":v_r3", $_POST["r_3"]);
    if(preg_match('/\W/', $_POST["r_c"]) && $_POST['r_c']!=null){
        session_start();
        $_SESSION["mesaj_err"] = "Textul contine caractere invalide!";
        header('Location: ../FRONT/HTML/pagina_eroare_admin.html');
        exit;
    }
    oci_bind_by_name($stid, ":v_rc", $_POST["r_c"]);
    oci_bind_by_name($stid, ':rezultat', $rezultat, 100);
    if(!oci_execute($stid))
    {
        $e = oci_error($stid);
        echo "Something went wrong :( <br/>";
        echo "Error: " . $e['message'];
    }
    oci_free_statement($stid);
    oci_close($connection);
    echo $rezultat;
?>
