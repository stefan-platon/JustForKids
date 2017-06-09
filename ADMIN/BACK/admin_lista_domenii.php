<?php
session_start();
if($_SESSION['secret']!=$_POST['secret'])
    header("Location:../FRONT/HTML/session_error.html");
else
    session_write_close();

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    include("conectare_db.php");

    $query = "SELECT d.domain_id, d.domain_name, d.number_of_questions, d.number_of_games, d.subject_type from domains d";

    $stid = oci_parse($connection, $query);

    oci_execute($stid);

    if (($row = oci_fetch_array($stid, OCI_BOTH)) != false)
        $flag = true;
    else
        $flag = false;
    $flag_primar = $flag;
    echo "<div class = 'clasa_2'>";
    if ($flag_primar == false)
        $result = "No result was found.";
    else
    {
        $result = '<table border="1">' . '<tr>' . '<td>ID</td>' . '<td>Nume</td>' . '<td>Intrebari</td>' . '<td>Jocuri</td>' . '<td>Tip</td>' . '</tr>';
    }

    while ($flag != false) {
        if ($row['DOMAIN_ID'] != null)
            $result = $result . "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td></tr>";
        if (($row = oci_fetch_array($stid, OCI_BOTH)) != false)
            $flag = true;
        else
            $flag=false;
    }

    if ($flag_primar != false) {
        $result = $result . "</table>";
        oci_free_statement($stid);
    } else
        $flag = false;

    echo $result;
    echo "</div>";
    oci_close($connection);
}
?>
