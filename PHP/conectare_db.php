<?php
//Oracle DB user name
$username = 'STUDENT';

// Oracle DB user password
$password = 'STUDENT';

// Oracle DB connection string
$connection_string = 'localhost/xe';

//Connect to an Oracle database
$connection = oci_pconnect(
$username,
$password,
$connection_string
);

if(!$connection)
    echo "Conexiune esuata.";
?>