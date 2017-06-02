<?php

session_start();
$answers = $_SESSION['userAnswers'];

?>
<html>

<head>
    <link rel="stylesheet" href="../FRONT/CSS/final_page.css">
</head>

<body>
<div class="container">
    <div class="format"></div>
    <div class="score">
        <?php echo "Scor final: " . $_SESSION['score'] . "/10" ?>
    </div>
    <br>
    <table class="final-command">
        <td class="column">
            <form action="">
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
        for ($i = 0; $i < 10; $i++) {
            if ($_SESSION['test'][1][$i][3] == $answers[$i]) {
                echo '<p><span style="color:gold">' . ($i + 1) . ')</span> ' . $_SESSION['test'][0][$i][1] . '</p>';
                echo "<p>Ati raspuns: <span style='color:greenyellow'>" . $answers[$i] . '</span></p>';
                echo "<p>Raspunsul corect este: " . $_SESSION['test'][1][$i][3] . '</p>';
            } elseif ($answers[$i] == "null") {
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