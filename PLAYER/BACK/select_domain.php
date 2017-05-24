<?php

session_start();
if($_SESSION['online']!=true)
    header("Location:../");

include("../PLAYER/BACK/conectare_db.php");
$query = 'select domain_id, domain_name, icon_link from domains';
$stid = oci_parse($connection, $query);
if(!oci_execute($stid))
{
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
$domain_title=array();
$cont=0;
while (($row = oci_fetch_array($stid, OCI_BOTH)) != false){
    $domain_title[$cont]=$row;
    $cont=$cont+1;
}
oci_free_statement($stid);

oci_close($connection);
?>
<html>

<head>
    <meta name="select_domain" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../PLAYER/FRONT/CSS/select_domain.css">
</head>

<body>

<div class="container">
    <div class="page-title">Selectati domeniul</div>
    <form action="game_list.php" method="post">
        <div class="list-items">
            <?php for ($i = 0; $i < count($domain_title); $i++) { ?>
            <div class="item">
                <div class="item-title">
                    <?php echo $domain_title[$i][1]; ?>
                </div>
                <div class="item-thumbnail">
                    <input type="image" class="item-thumbnail" src="<?php echo $domain_title[$i][2]; ?>" name="domain" value="<?php echo $domain_title[$i][0]; ?>">
                </div>
            </div>
            <?php } ?>
            <div class="filler"></div>
        </div>
    </form>
</div>
</body>

</html>