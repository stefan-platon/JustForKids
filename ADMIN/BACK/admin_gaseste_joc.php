<?php
include ("conectare_db.php");
$error_flag = 1;
if($_POST["cauta_j_id"]!=null)
{
    $sql = "SELECT g.game_id, g.name, g.difficulty, g.description, g.instructions, d.domain_name from games g join domains d on d.domain_id = g.domain_id
            where g.game_id = :v_id";
    $stid = oci_parse($connection, $sql);
    oci_bind_by_name($stid, ":v_id", $_POST["cauta_j_id"]);
    if(!oci_execute($stid))
    {
        $error_flag = 0;
        $e = oci_error($stid);
        echo "Something went wrong :( <br/>";
        echo "Error: " . $e['message'];
    }
}
else if($_POST["cauta_j_nume"]!=null)
{
    $sql = "SELECT g.game_id, g.name, g.difficulty, g.description, g.instructions, g.domain_id from games g join domains d on d.domain_id = g.domain_id
            where LOWER(g.name) = LOWER(:v_name)";
    $stid = oci_parse($connection, $sql);
    $var1 = $_POST["cauta_j_nume"];
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
    $sql = "SELECT g.game_id, g.name, g.difficulty, g.description, g.instructions, g.domain_id from games g join domains d on d.domain_id = g.domain_id
            where g.domain_id like :v_dom and g.description like :v_descr and g.instructions like :v_instr and g.difficulty like :v_dif ";
    $stid = oci_parse($connection, $sql);
    $var2 = '%'.$_POST["domeniu"].'%';
    $var3 = '%'.$_POST["j_descr_seq"].'%';
    $var4 = '%'.$_POST["j_instr_seq"].'%';
    $var5 = '%'.$_POST["dificultate"].'%';
    oci_bind_by_name($stid, ":v_dom", $var2);
    oci_bind_by_name($stid, ":v_descr", $var3);
    oci_bind_by_name($stid, ":v_instr", $var4);
    oci_bind_by_name($stid, ":v_dif", $var5);
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
        echo "<tr><th>ID</th><th>Dificultate</th><th>Descriere</th><th>Instructiuni</th><th>Domeniu</th></tr>";
        echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td></tr>";
    }
    while (($row = oci_fetch_array($stid, OCI_BOTH)) != false)
    {
        echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td></tr>";
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
