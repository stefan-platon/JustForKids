<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 10-Jun-17
 * Time: 04:59
 */

include ("conectare_db.php");

echo '<table class="tabel_news">
    <tr class="news_tr">';

$stid = oci_parse($connection, 'select titlu, continut, to_char(data,\'dd.mm.yyyy\') from updates');
oci_execute($stid);
while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
    echo '<td class="news_td"><div class = "item_header">' . $row[0] . '</div></td>';
    echo '<td class="news_td">' . $row[2] . '</td></tr>';
    echo '<tr class="news_content_tr">';
    echo '<td class="news_td"><div class = "content">'. $row[1] .'</div></td></tr>';
}

echo '</table>';