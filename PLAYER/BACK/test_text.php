<?php
session_start();
/*if($_SESSION['online']!=true)
    header("Location:../../INTRO/FRONT/HTML/login_content.html");*/
include("conectare_db.php");

$questions = array();
$cont = 0;
$answers = array();

$query = 'select question_id, question_text from tw_questions where difficulty=:diff';
$stid = oci_parse($connection, $query);
$diff = $_SESSION['difficulty'];
oci_bind_by_name($stid, ":diff", $diff);
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    $nr_questions[$cont] = $row;
    $cont++;
}
for ($i = 0; $i < 10; $i++)
    $ids[$i] = 0;
$qLimit = count($nr_questions) - 1;
$ids[0] = rand(1, $qLimit);
$sw = 0;
$i = 1;
while ($sw == 0 && $i < 10) {
    $ids[$i] = rand(0, $qLimit);
    for ($j = $i - 1; $j >= 0 && $sw == 0; $j--)
        if ($ids[$i] == $ids[$j])
            $sw = 1;
    if ($sw == 1) {
        $ids[$i] = rand(0, $qLimit);
        $sw = 0;
    } else
        $i++;
}
for ($i = 0; $i < 10; $i++) {
    $questions[$i][0] = $nr_questions[$ids[$i]][0];
    $questions[$i][1] = $nr_questions[$ids[$i]][1];
}

for ($qc = 0; $qc < count($questions); $qc++) {
    $query = 'select answer1, answer2, answer3, correct_answer from questions_answers where question_id=:id';
    $stid = oci_parse($connection, $query);
    oci_bind_by_name($stid, ":id", $questions[$qc][0]);
    if (!oci_execute($stid)) {
        $e = oci_error($stid);
        echo "Something went wrong :( <br/>";
        echo "Error: " . $e['message'];
    }
    if (($row = oci_fetch_array($stid, OCI_NUM)) != false) {
        $answers[$qc] = $row;
    }
}
oci_free_statement($stid);
oci_close($connection);

$i = 0;
/*$timeleft = $_SESSION['max_time'];*/

$timeleft = 10;

$_SESSION['test'][0] = $questions;
$_SESSION['test'][1] = $answers;

?>

<html>

<head>
    <link rel="stylesheet" href="../FRONT/CSS/test_text.css">
</head>

<body>
<script src="print_elements_text.js"></script>
<div class="container">
    <div class="test-time">
        <div id="text-time" class="text-time"></div>
    </div>
    <?php echo "<script type='text/javascript'>printTime(" . $timeleft . ")</script>"; ?>
    <div class="quiz">
        <div id="question" class="question"></div>
        <?php
        echo "<script type='text/javascript'>saveInfo(".json_encode($questions).")</script>";
        ?>
    </div>
    <form id="submit-test" action="redirect_to_final_page.php" class="submit-form" method="post"></form>
</div>

</body>

</html>