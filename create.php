<!DOCTYPE html>
<html>
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 <title></title>
 </head>
<body>

<?php
include 'config.php';


$sql1 = "CREATE TABLE IF NOT EXISTS University (
    university_id INT(6) AUTO_INCREMENT PRIMARY KEY,
    university_name VARCHAR(100) DEFAULT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 ";
$result1 = mysqli_query($dbConn,$sql1);
 if(!$result1)
 die('Error with creating the university table.');
?>

</body>
</html>
