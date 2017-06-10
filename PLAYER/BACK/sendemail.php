<?php
if(isset($_POST["email"]))
{
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
        $_SESSION["mesaj_err"] = "Am intampinat o problema...";
        header('Location: ../FRONT/HTML/pagina_eroare_register.html');
        exit;
    }
    /*verific daca emailul exista in baza noastra de date */
    // in tabela player
    $proprietar = 'nimeni';
    include ("conectare_db.php");
    $sql1 = 'select username from player where email like :p_email';
    $stid1 = oci_parse($connection, $sql1);
    oci_bind_by_name($stid1, ":p_email", $_POST["email"]);
    if(!oci_execute($stid1))
    {
        session_start();
        $_SESSION["mesaj_err"] = "Am intampinat o problema...";
        header('Location: ../FRONT/HTML/pagina_eroare_register.html');
        exit;
    }
    else
    {
        if(($row1 = oci_fetch_array($stid1, OCI_BOTH)) != false)
        {
            $proprietar = 'player';
        }
    }
    // in tabela tutor
    $sql2 = 'select username from tutor where email like :p_email';
    $stid2 = oci_parse($connection, $sql2);
    oci_bind_by_name($stid2, ":p_email", $_POST["email"]);
    if(!oci_execute($stid2))
    {
        session_start();
        $_SESSION["mesaj_err"] = "Am intampinat o problema...";
        header('Location: ../FRONT/HTML/pagina_eroare_register.html');
        exit;
    }
    else
    {
        if(($row2 = oci_fetch_array($stid2, OCI_BOTH)) != false)
        {
            $proprietar = 'tutor';
        }
    }
    if($proprietar == 'nimeni')
    {
        session_start();
        $_SESSION["mesaj_err"] = "Emailul nu exista in baza de date!";
        header('Location: ../FRONT/HTML/pagina_eroare_register.html');
        exit;
    }
    //emailul este valid si existent in baza de date
    require '../../only_req_files/PHPMailerAutoload.php';
    $nr_random = rand();
    $sql20 = "update passwords set hash = ".$nr_random.", random_string = ".$nr_random." where username like :p_name";
    $stid20 = oci_parse($connection, $sql20);
    if($proprietar == 'player')
        oci_bind_by_name($stid20, ":p_name", $row1[0]);
    else
        oci_bind_by_name($stid20, ":p_name", $row2[0]);
    if(!oci_execute($stid20))
    {
        $e = oci_error($stid20);
        echo "Something went wrong :( <br/>";
        echo "Error: " . $e['message'];
        exit;
    }

    $msg = "Parola noua este ".$nr_random.".\nVa rugam sa o schimbati cat mai curand.";
    $msg = wordwrap($msg,70);
    $headers = 'From: platonfanica@gmail.com';
    if(mail("platonfanica@yahoo.com","My subject",$msg, $headers) === FALSE)
    {
        session_start();
        $_SESSION["mesaj_err"] = "Conectarea la serverul SMTP esuata!";
        header('Location: ../FRONT/HTML/pagina_eroare_register.html');
        exit;
    }

    /*
    $mail = new PHPMailer;

    $mail->isSMTP();                            // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                     // Enable SMTP authentication
    $mail->Username = 'platonfanica@gmail.com';          // SMTP username
    $mail->Password = 'fanica09071997'; // SMTP password
    $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                          // TCP port to connect to

    $mail->addAddress($_POST["email"]);   // Add a recipient

    $mail->isHTML(true);  // Set email format to HTML

    $bodyContent = 'Parola noua este '.$nr_random.'\nVa rugam sa o schimbati cat mai curand.';

    $mail->Subject = 'Resetare parola';
    $mail->Body    = $bodyContent;

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }
    */
}
?>
