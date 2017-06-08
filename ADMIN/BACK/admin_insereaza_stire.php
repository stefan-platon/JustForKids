<?php
include("conectare_db.php");

$sql = 'begin :rezultat :=  admin_pachet.insert_stire(:v_titlu,:v_continut); END;';
$stid = oci_parse($connection, $sql);
if (preg_match('/\W/', $_POST["titlu"]) && $_POST["titlu"] != null) {
    session_start();
    $_SESSION["mesaj_err"] = "Textul contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_admin.html');
    exit;
}
oci_bind_by_name($stid, ":v_titlu", $_POST["titlu"]);
if (preg_match('/\W/', $_POST["content"]) && $_POST["content"] != null) {
    session_start();
    $_SESSION["mesaj_err"] = "Textul contine caractere invalide!";
    header('Location: ../FRONT/HTML/pagina_eroare_admin.html');
    exit;
}
oci_bind_by_name($stid, ":v_continut", $_POST["content"]);
oci_bind_by_name($stid, ':rezultat', $rezultat, 100);
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
oci_free_statement($stid);
oci_close($connection);
?>