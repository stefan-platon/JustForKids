<?php
include ("conectare_db.php");
$error_flag = 1;
if($_POST["cauta_a_id"]!=null)
{
    $sql = "SELECT a.achievement_id, a.name, a.description from achievements a where a.achievement_id = :v_id";
    $stid = oci_parse($connection, $sql);
    oci_bind_by_name($stid, ":v_id", $_POST["cauta_a_id"]);
    if(!oci_execute($stid))
    {
        $error_flag = 0;
        $e = oci_error($stid);
        echo "Something went wrong :( <br/>";
        echo "Error: " . $e['message'];
    }
}
else if($_POST["name_r"]!=null)
{
    $sql = "SELECT a.achievement_id, a.name, a.description from achievements a 
            where LOWER(a.name) = LOWER(:v_name)";
    $stid = oci_parse($connection, $sql);
    $var1 = $_POST["name_r"];
    oci_bind_by_name($stid, ":v_name", $var1);
    if(!oci_execute($stid))
    {
        $error_flag = 0;
        $e = oci_error($stid);
        echo "Something went wrong :( <br/>";
        echo "Error: " . $e['message'];
    }   
}
else
{
    $sql = "SELECT a.achievement_id, a.name, a.description from achievements a 
            where a.description like :v_descr";
    $stid = oci_parse($connection, $sql);
    $var2 = '%'.$_POST["descr_r"].'%';
    oci_bind_by_name($stid, ":v_descr", $var2);
    if(!oci_execute($stid))
    {
        $error_flag = 0;
        $e = oci_error($stid);
        echo "Something went wrong :( <br/>";
        echo "Error: " . $e['message'];
    }
}
$flag = 0;
if($error_flag == 1)
{
    if(($row = oci_fetch_array($stid, OCI_BOTH)) != false)
    {
        $flag = 1;
        echo "<table border=\"1\">";
        echo "<tr><th>ID</th><th>Nume</th><th>Descriere</th></tr>";
        echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>";
    }
    while (($row = oci_fetch_array($stid, OCI_BOTH)) != false)
    {
        echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>";
    }
    if($flag == 1)
    {
        echo "</table>";
    }
    else
    {
        echo "Nu s-au gasit rezultate.";
    }
}
oci_free_statement($stid);
oci_close($connection);
?>
