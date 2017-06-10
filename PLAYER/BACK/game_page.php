<?php
session_start();
if($_SESSION['secret']!=$_POST['secret'])
    header('Location: ../../../INTRO/FRONT/HTML/session_error.html');
if(!$_SESSION['online'] === true || !$_SESSION['rights'] == 'player')
    header('Location: ../../../INTRO/FRONT/HTML/login_frame.html');
if($_SESSION['last_page']!="game_list.php" && $_SESSION['last_page']!="game_page.php")
    header('Location: ../FRONT/HTML/logged_user_frame.html');

include("conectare_db.php");
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
$_SESSION['last_page']="game_page.php";
?>
<html>

<head>
    <link rel="stylesheet" href="../FRONT/CSS/game_page.css">
</head>

<body>

<div class="container">
    <div class="page-title">
        <?php echo $_POST['game_title']?>
    </div>
    <br>
    <div class="game-info">
        <?php echo $game_info[1];?>
    </div>
    <br>
    <div class="game-window">
        GAME WINDOW
    </div>

    <br>

    <div>
        <form action="../FRONT/HTML/logged_user_frame.html">
            <input type="submit" name="return" value="Prima pagina" class="button">
        </form>
    </div>
</div>

</body>

</html>
