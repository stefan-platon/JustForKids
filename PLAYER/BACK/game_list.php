<?php
session_start();
if($_SESSION['secret']!=$_POST['secret'])
    header('Location: ../../../INTRO/FRONT/HTML/session_error.html');
if(!$_SESSION['online'] === true || !$_SESSION['rights'] == 'player')
    header('Location: ../../../INTRO/FRONT/HTML/logged_user_frame.html');
if($_SESSION['last_page']!="select_domain.php" && $_SESSION['last_page']!="game_list.php")
    header('Location: ../../../INTRO/FRONT/HTML/logged_user_frame.html');

include("conectare_db.php");
$query = 'select name, difficulty, description, icon_link from games where domain_id=:domain_id';
$stid = oci_parse($connection, $query);
$domain_id = $_POST["domain"];
oci_bind_by_name($stid, ":domain_id", $domain_id);
if(!oci_execute($stid))
{
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
    die;
}
$games_list=array();
$cont=0;

while (($row = oci_fetch_array($stid, OCI_BOTH)) != false){
    $games_list[$cont]=$row;
    $cont=$cont+1;
}
oci_free_statement($stid);
$query = 'select domain_name from domains where domain_id=:domain_id';
$stid = oci_parse($connection, $query);
$the_domain=$_POST['domain'];
oci_bind_by_name($stid, ":domain_id", $the_domain);
if(!oci_execute($stid))
{
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}

if(($row = oci_fetch_array($stid, OCI_BOTH)) != false){
    $domain_title=$row;
}else{
    echo 'Problem baws :(';
}

oci_free_statement($stid);

oci_close($connection);
$_SESSION['last_page']="game_list.php";
?>
<html>

<head>
    <link rel="stylesheet" href="../FRONT/CSS/game_list.css">
</head>

<body>

<div class="container">
    <div class="page-title">
        <?php echo 'Jocuri pentru '; echo $domain_title[0];?>
    </div>

    <table class="list-games" cellspacing="20">
        <?php for ($i = 0; $i < count($games_list); $i++) { ?>
        <form action="game_page.php" method="post">
            <tr>
                <td class="game-title">
                    <?php echo $games_list[$i][0]; ?>
                </td>
                <td class="game-thumbnail">
                        <input type="image" class="game-thumbnail" src="<?php echo $games_list[$i][5]; ?>" name="game_title" value="<?php echo $games_list[$i][0]?>">
                </td>
                <td class="game-description">
                    <?php echo $games_list[$i][2] ?>
                </td>
                    <?php switch($games_list[$i][1]){
                        case 1:{
                            echo '<td class="game-difficulty" style="color: green">';echo'EASY';echo '</td>';
                            break;
                        }
                        case 2:{
                            echo '<td class="game-difficulty" style="color: yellow">';echo'MEDIUM';echo '</td>';
                            break;
                        }
                        case 3: {
                            echo '<td class="game-difficulty" style="color: red">';echo'HARD';echo '</td>';
                            break;
                        }

                    } ?>
            </tr>
            <input type="hidden" name="secret" value="<?php if(session_status()==PHP_SESSION_NONE)session_start();echo $_SESSION['secret'];?>"/>
        </form>
        <?php } ?>
    </table>

</div>
</body>

</html>