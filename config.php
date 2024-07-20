<!DOCTYPE html>
<html>
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 <title></title>
 </head>
<body>

<?php

$host= 'localhost';
$dbUser= 'root';
$dbPass= '';
$dbName = 'RocketCV';
$dbConn=mysqli_connect($host, $dbUser, $dbPass);
if(!$dbConn) {
die('Cannot initiate a connection with the server.');
}

if (!mysqli_select_db($dbConn,$dbName))
{
   $sql = "CREATE DATABASE IF NOT EXISTS RocketCV";
   if ($queryResource=mysqli_query($dbConn,$sql))
   {
       echo "The database has been successfully created. <br>";
   }
   else
   {
       echo "Error with creating the database.";
   } 
}

?>

</body>
</html>
