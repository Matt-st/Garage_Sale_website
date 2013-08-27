<?php
//connect to the database
$connection=mysql_connect('*******','*******','********','******') or die('Could not connect to MySQL database: ' . mysql_error());
mysql_select_db("*******") or die("Could not find the database." . mysql_error());
?>