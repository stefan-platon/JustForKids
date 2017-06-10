<?php
session_start();
if($_SESSION['secret']!=$_POST['secret'])
    header('Location: ../../../INTRO/FRONT/HTML/session_error.html');
if(!$_SESSION['online'] === true || !$_SESSION['rights'] == 'player')
    header('Location: ../../../INTRO/FRONT/HTML/login_framee.html');

include("conectare_db.php");
$query = 'select domain_name , icon_link from domains';
$stid = oci_parse($connection, $query);
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
$domain_title = array();
$cont = 0;
while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    $domain_title[$cont] = $row;
    $cont = $cont + 1;
}
oci_free_statement($stid);

oci_close($connection);
$_SESSION['last_page']="select_domain_test.php";
?>
<html>

<head>
    <link rel="stylesheet" href="../FRONT/CSS/select_domain_test.css">
</head>

<body>
<div class="container">
    <div class="page-title">Selectati domeniul si tipul testului</div>
    <form action="redirect_to_test.php" method="post">
        <div class="list-items">
            <?php for ($i = 0; $i < count($domain_title); $i++) { ?>
                <div class="item">
                    <div class="item-title">
                        <?php echo $domain_title[$i][0]; ?>
                    </div>

                    <label class="label-item">
                        <input type="radio" class="label-input" name="domain"
                               value="<?php echo $domain_title[$i][0]; ?>">
                        <img src="<?php echo $domain_title[$i][1]; ?>" class="item-thumbnail">
                    </label>

                </div>
            <?php } ?>
            <div class="item-filler"></div>
        </div>
        <br>
        <div class="warning">Cand apasati "START" testul va incepe automat!</div>
        <br>
        <div class="list-tip">
            <div class="tip">
                <div class="tip-title">
                    Text
                </div>

                <label class="label-item">
                    <input type="radio" class="label-input" name="tip"
                           value="Text">
                    <img src="../../IMG/OTHER/HOURGLASS.jpg" class="item-thumbnail">
                </label>
            </div>
            <div class="tip">
                <div class="tip-title">
                    Variante de raspuns
                </div>

                <label class="label-item">
                    <input type="radio" class="label-input" name="tip"
                           value="Variante">
                    <img src="../../IMG/OTHER/HOURGLASS.jpg" class="item-thumbnail">
                </label>
            </div>
            <div class="tip-filler"></div>
        </div>
        <div class="submit">
            <input type="submit" value="START" class="submit-button">
        </div>
        <input type="hidden" name="secret" value="<?php if(session_status()==PHP_SESSION_NONE)session_start();echo $_SESSION['secret'];?>"/>
    </form>
</div>
</body>
</html>
