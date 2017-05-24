<?php

$username="student";
$password="STUDENT";
$connection_string="localhost/xe";

$connection=oci_connect($username,$password,$connection_string);
if(!$connection)
    echo "Conexiune esuata.";
