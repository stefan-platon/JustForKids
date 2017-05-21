<?php
include ("conectare_db.php");
$sql = "SELECT random_string from passwords where username = :v_usrn";
$stid = oci_parse($connection, $sql);
oci_bind_by_name($stid, ":v_usrn", $_POST["username"]);
if(!oci_execute($stid))
{
    $error_flag = 0;
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
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
        $e2 = oci_error($stid2);
        echo "Something went wrong :( <br/>";
        echo "Error: " . $e2['message'];
    }
    oci_free_statement($stid2);
    oci_close($connection);
    if($rezultat2 == 'p')
    {
        header('Location: ../../../PLAYER/FRONT/HTML/logged_user_frame.html');
    }
    else if ($rezultat2 == 't')
    {
        header('Location: ../../../TUTOR/FRONT/HTML/logged_tutor_frame.html');
    }
    else if ($rezultat2 == 'a')
    {
        header('Location: ../../../ADMIN/FRONT/HTML/logged_tutor_frame.html');
    }
    else
    {
        echo "<script type='text/javascript'>";
        echo "alert('failed!')";
        echo "</script>";
    }
}
else
{
    echo "<script type='text/javascript'>";
    echo "alert('failed!')";
    echo "</script>";
}

?>