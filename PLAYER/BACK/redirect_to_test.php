<?php
session_start();
if($_SESSION['secret']!=$_POST['secret'])
    header('Location: ../../../INTRO/FRONT/HTML/session_error.html');
if(!$_SESSION['online'] === true || !$_SESSION['rights'] == 'player')
    header('Location: ../../../INTRO/FRONT/HTML/login_frame.html');
if($_SESSION['last_page']!="select_domain_test.php")
    header('Location: ../FRONT/HTML/logged_user_frame.html');

include('conectare_db.php');
$_SESSION['domain']=$_POST['domain'];

//iau din baza de date dificultatea corespunzatoare userului
$query = 'select pd.difficulty from player p join player_dates pd on p.player_id=pd.player_id where p.username=:username';
$stid = oci_parse($connection, $query);
$username = $_SESSION['username'];

oci_bind_by_name($stid, ":username", $username);
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Something went wrong :( <br/>";
    echo "Error: " . $e['message'];
}
if (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    $user = $row;
}

oci_free_statement($stid);

oci_close($connection);

//setez timpul pentru test in functie de dificultate
$_SESSION['difficulty'] = $user[0];

switch ($_SESSION['difficulty']) {
    case 1:
        $_SESSION['max_time'] = -1;
        break;
    case 2:
        $_SESSION['max_time'] = 5*60;
        break;
    case 3:
        $_SESSION['max_time'] = 3*60;
        break;
}
//redirectez la pagina cu tipul de test corespunzator alegerii utilizatorului
switch ($_POST['tip']) {
    case 'Text':
        $location="test_text.php";
        break;
    case 'Variante':
        $location="test_variante.php";
        break;
}
$_SESSION['last_page']="redirect_to_test.php";
?>
<html>
<head></head>
<body>
<script src="../JAVASCRIPT/redirect.js">submitForm()</script>
<form action="<?php echo $location;?>" id="submit-secret">
    <input type="hidden" name="secret" value="<?php if(session_status()==PHP_SESSION_NONE)session_start();echo $_SESSION['secret'];?>"/>
</form>
</body>
</html>
