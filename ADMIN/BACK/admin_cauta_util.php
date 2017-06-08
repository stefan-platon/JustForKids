<?php
    include("conectare_db.php");
    $error_flag = 1;
    if (isset($_POST["uid"]) || isset($_POST["uname"])) {

        $flag=true;
        if ($_POST["uname"]!=null && $_POST['uid']==0) {
            $stid = oci_parse($connection, "SELECT player_id,first_name,last_name,username,email FROM player where username like '%".$_POST["uname"]."%'");
            if(preg_match('/\W/', $_POST["uname"])){
                session_start();
                $_SESSION["mesaj_err"] = "Textul contine caractere invalide!";
                header('Location: ../FRONT/HTML/pagina_eroare_admin.html');
                exit;
            }
            if(!oci_execute($stid))
            {
                $error_flag = 0;
                $e = oci_error($stid);
                echo "Something went wrong :( <br/>";
                echo "Error: " . $e['message'];
            }
        }else
            if ($_POST['uid']!=0 && $_POST["uname"]==null) {
                $stid = oci_parse($connection, "SELECT player_id,first_name,last_name,username,email FROM player where player_id =".$_POST["uid"]);
                if(!oci_execute($stid))
                {
                    $error_flag = 0;
                    $e = oci_error($stid);
                    echo "Something went wrong :( <br/>";
                    echo "Error: " . $e['message'];
                }
            }else
                if ($_POST['uid']!=0 && $_POST["uname"]!=null) {
                    $stid = oci_parse($connection, "SELECT player_id,first_name,last_name,username,email FROM player where player_id=".$_POST["uid"]." and username like '%".$_POST["uname"]."%'");
                    if(preg_match('/\W/', $_POST["uname"])){
                        session_start();
                        $_SESSION["mesaj_err"] = "Textul contine caractere invalide!";
                        header('Location: ../FRONT/HTML/pagina_eroare_admin.html');
                        exit;
                    }
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
                $result = '<table border="1">' . '<tr>' . '<td>Player ID</td>' . '<td>First Name</td>' . '<td>Last Name</td>' . '<td>Username</td>' . '<td>Email</td>' . '</tr>';

            while ($flag != false) {
                if ($row['PLAYER_ID'] != null)
                    $result = $result . "<tr><td>" . $row['PLAYER_ID'] . "</td><td>" . $row['FIRST_NAME'] . "</td><td>" . $row['SECOND_NAME'] . "</td><td>" . $row['USERNAME'] . "</td><td>" . $row['EMAIL'] . "</td></tr>";
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
