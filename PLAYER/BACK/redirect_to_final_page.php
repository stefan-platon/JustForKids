<?php

session_start();
for($i=0;$i<10;$i++)
    echo $_POST['q'+$i];

?>