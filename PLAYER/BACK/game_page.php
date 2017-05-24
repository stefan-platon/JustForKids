<?php

session_start();
if($_SESSION['online']!=true)
    header("Location:../");

include("../PLAYER/BACK/conectare_db.php");
$query = 'select name, instructions, game_link from games where name=:name';
$stid = oci_parse($connection, $query);
$game_name = $_POST["game_title"];
oci_bind_by_name($stid, ":name", $game_name);
if(!oci_execute($stid))
{
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
if(($row = oci_fetch_array($stid, OCI_BOTH)) != false){
    $game_info=$row;
}else{
    echo 'Problem :(';
}
oci_free_statement($stid);

oci_close($connection);
?>
<html>

<head>
    <link rel="stylesheet" href="../PLAYER/FRONT/CSS/game_page.css">
</head>

<body>

<div class="container">
    <div class="page-title">
        <?php echo $_POST['game_title']?>
    </div>

    <div class="game-info">
        <?php echo $game_info[1];?>
    </div>

    <div class="game-window">
        GAME WINDOW
    </div>
</div>

</body>

</html>
