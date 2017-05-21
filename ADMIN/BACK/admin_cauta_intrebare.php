<?php
    include ("conectare_db.php");
    $error_flag = 1;
    if($_POST["cauta_q_id"]!=null)
    {
        $sql = "SELECT q.question_id, d.domain_name, q.question_text, q.difficulty, q.tip, a.answer1, a.answer2, a.answer3, a.correct_answer 
            from tw_questions q join questions_answers a on q.question_id = a.question_id join domains d on d.domain_id = q.domain_id 
            where q.question_id = :v_id";
        $stid = oci_parse($connection, $sql);
        oci_bind_by_name($stid, ":v_id", $_POST["cauta_q_id"]);
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
        $sql = "SELECT q.question_id, d.domain_name, q.question_text, q.difficulty, q.tip, a.answer1, a.answer2, a.answer3, a.correct_answer 
            from tw_questions q join questions_answers a on q.question_id = a.question_id join domains d on d.domain_id = q.domain_id 
            where d.domain_id like :v_domain_id and q.question_text like :v_text and q.difficulty like :v_dif and q.tip like :v_tip";
        $stid = oci_parse($connection, $sql);
        $var1 = '%'.$_POST["domeniu"].'%';
        $var2 = '%'.$_POST["q_text_seq"].'%';
        $var3 = '%'.$_POST["dificultate"].'%';
        $var4 = '%'.$_POST["tip_intrebare"].'%';
        oci_bind_by_name($stid, ":v_domain_id", $var1);
        oci_bind_by_name($stid, ":v_text", $var2);
        oci_bind_by_name($stid, ":v_dif", $var3);
        oci_bind_by_name($stid, ":v_tip", $var4);
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
            echo "<tr><th>ID</th><th>Domeniu</th><th>Text</th><th>Dif</th><th>Tip</th><th>Raspuns 1</th><th>Raspuns 2</th><th>Raspuns 3</th><th>Raspuns corect</th></tr>";
            echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td><td>".$row[5]."</td><td>".$row[6]."</td><td>".$row[7]."</td><td>".$row[8]."</td></tr>";
        }
        while (($row = oci_fetch_array($stid, OCI_BOTH)) != false)
        {
            echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td><td>".$row[5]."</td><td>".$row[6]."</td><td>".$row[7]."</td><td>".$row[8]."</td></tr>";
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