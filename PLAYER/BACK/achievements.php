<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 24-May-17
 * Time: 01:32
 */

$stid = oci_parse($connection, 'SELECT a.name, a.description FROM achievements_link al join player p on al.player_id = p.player_id join achievements a on a.achievement_id = al.achievement_id where p.username = :username');
$username = 'Abaza.Carla';
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$i = 1;
while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    echo '<tr class="ach_r"> ';
    echo '<td class="c1"> ';
    echo '<div class="title">' . $i . '. ' . $row[0] . '</div> </td>';
    echo '<td class="c2"><h2></h2></td></tr>';

    echo '<tr class="ach_r">
        <td class="c1">
            <div class = "icon">
                <img src="../../../IMG/OTHER/achievement.png" height="75" alt="Ach1">
            </div>
        </td>';
    echo '<td class="c2">
        <div class = "text_box">
            <p>' . $row[1] . '</p>
        </div>
    </td>
    </tr>';
    $i++;
}