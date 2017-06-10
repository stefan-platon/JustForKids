<?php

session_start();
if ($_SESSION['secret'] != $_POST['secret'])
    header('Location: ../../../INTRO/FRONT/HTML/session_error.html');
if (!$_SESSION['online'] === true || !$_SESSION['rights'] == 'player')
    header('Location: ../../../INTRO/FRONT/HTML/login_frame.html');
if($_SESSION['last_page']!="redirect_to_final_page.php" && $_SESSION['last_page']!="final_page.php")
    header('Location: ../FRONT/HTML/logged_user_frame.html');

//deschid sesiunea si incarc raspunsurile date de utilizator
$answers = $_SESSION['userAnswers'];

$_SESSION['last_page']="final_page.php";
?>
<html>

<head>
    <link rel="stylesheet" href="../FRONT/CSS/final_page.css">
</head>

<body>
<div class="container">
    <!--divul cu clasa format are doar rol in spatierea elementelor pe pagina-->
    <div class="format"></div>
    <div class="score">
        <?php echo "Scor final: " . $_SESSION['score'] . "/10" ?>
    </div>
    <br>
    <table class="final-command">
        <td class="column">
            <form action="../FRONT/HTML/logged_user_frame.html">
                <button type="submit" class="button">Pagina principala</button>
            </form>
        </td>

        <td class="column">
            <form action="">
                <button type="submit" class="button">ATOM</button>
            </form>
        </td>

        <td class="column">
            <form action="">
                <button type="submit" class="button">Trimite EMAIL</button>
            </form>
        </td>
    </table>
    <br>
    <!--afisez dificultatea testului-->
    <div class="test-stats">
        <?php
        switch ($_SESSION['difficulty']) {
            case 1: {
                echo "<p>Dificultate: <span style='color: greenyellow'>EASY</span></p>";
                break;
            }
            case 2: {
                echo "<p>Dificultate: <span style='color: yellow'>MEDIUM</span></p>";
                break;
            }
            case 3: {
                echo "<p>Dificultate: <span style='color: red'>HARD</span></p>";
                break;
            }
        }
        //afisez intrebarile atat raspunsurile date de utilizator cat si pe cele corecte
        for ($i = 0; $i < 10; $i++) {
            if ($_SESSION['test'][1][$i][3] == $answers[$i]) {
                echo '<p><span style="color:gold">' . ($i + 1) . ')</span> ' . $_SESSION['test'][0][$i][1] . '</p>';
                echo "<p>Ati raspuns: <span style='color:greenyellow'>" . $answers[$i] . '</span></p>';
                echo "<p>Raspunsul corect este: " . $_SESSION['test'][1][$i][3] . '</p>';
            } elseif ($answers[$i] == "null" || $answers[$i] == null) {
                echo '<p><span style="color:gold">' . ($i + 1) . ')</span> ' . $_SESSION['test'][0][$i][1] . '</p>';
                echo "<p><span style='color:yellow'>Nu ati rapsuns la aceasta intrebare.</span></p>";
                echo "<p>Raspunsul corect este: " . $_SESSION['test'][1][$i][3] . '</p>';
            } else {
                echo '<p><span style="color:gold">' . ($i + 1) . ')</span> ' . $_SESSION['test'][0][$i][1] . '</p>';
                echo "<p>Ati raspuns: <span style='color:red'>" . $answers[$i] . '</span></p>';
                echo "<p>Raspunsul corect este: " . $_SESSION['test'][1][$i][3] . '</p>';
            }
            echo "<br>";
        }
        ?>
    </div>

    <div class="format"></div>
</div>

</body>

</html>
