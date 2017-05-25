<?php
include("conectare_db.php");
$error_flag = 1;
if (isset($_POST["uid"]) || isset($_POST["uname"])) {

    $flag=true;
    if ($_POST["uname"]!=null && $_POST['uid']==0) {
        $stid = oci_parse($connection, "SELECT t.tutor_id,t.first_name,t.last_name,t.username,t.email,p.username,p.email FROM tutor t join player p on t.tutor_id = p.tutor_id where LOWER(t.username) = ".$_POST["uname"]);
        if(!oci_execute($stid))
        {
            $error_flag = 0;
            $e = oci_error($stid);
            echo "Something went wrong :( <br/>";
            echo "Error: " . $e['message'];
        }
    }else
        if ($_POST['uid']!=0 && $_POST["uname"]==null) {
            $stid = oci_parse($connection, "SELECT t.tutor_id,t.first_name,t.last_name,t.username,t.email,p.username,p.email FROM tutor t join player p on t.tutor_id = p.tutor_id where t.tutor_id =".$_POST["uid"]);
            if(!oci_execute($stid))
            {
                $error_flag = 0;
                $e = oci_error($stid);
                echo "Something went wrong :( <br/>";
                echo "Error: " . $e['message'];
            }
        }else
            if ($_POST['uid']!=0 && $_POST["uname"]!=null) {
                $stid = oci_parse($connection, "SELECT t.tutor_id,t.first_name,t.last_name,t.username,t.email,p.username,p.email FROM tutor t join player p on t.tutor_id = p.tutor_id where t.tutor_id=".$_POST["uid"]." and t.username like '%".$_POST["uname"]."%'");
                if(!oci_execute($stid))
                {
                    $error_flag = 0;
                    $e = oci_error($stid);
                    echo "Something went wrong :( <br/>";
                    echo "Error: " . $e['message'];
                }
            }else
                $flag=false;
    if($error_flag == 1) {
        if ($flag != false)
            if (($row = oci_fetch_array($stid, OCI_BOTH)) != false)
                $flag = true;
            else
                $flag = false;
        $flag_primar = $flag;
        if ($flag_primar == false)
            $result = "No result with given parameters was found.";
        else
            $result = '<table border="1">' . '<tr>' . '<td>Tutor ID</td>' . '<td>First Name</td>' . '<td>Last Name</td>' . '<td>Username</td>' . '<td>Email</td>' . '<td>Player Username</td>' . '<td>Player Email</td></tr>';

        while ($flag != false) {
            if ($row['TUTOR_ID'] != null)
                $result = $result . "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] . "</tr>";
            if (($row = oci_fetch_array($stid, OCI_BOTH)) != false)
                $flag = true;
            else
                $flag = false;
        }
        if ($flag_primar != false) {
            $result = $result . "</table>";
            oci_free_statement($stid);
        }
        echo $result;
    }
    oci_close($connection);
}
?>

</body>

</html>
