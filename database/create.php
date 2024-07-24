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
        university_name VARCHAR(100) DEFAULT NULL,
        accreditation VARCHAR(20) DEFAULT NULL
    ) ENGINE=INNODB DEFAULT CHARSET=utf8 ";
    $result1 = mysqli_query($dbConn,$sql1);
    if(!$result1)
        die('Error with creating the university table.');

    $sql2 = "CREATE TABLE IF NOT EXISTS Skills (
        skill_id INT(6) AUTO_INCREMENT PRIMARY KEY,
        skill VARCHAR(50) DEFAULT NULL
    ) ENGINE=INNODB DEFAULT CHARSET=utf8 ";
    $result2 = mysqli_query($dbConn,$sql2);
    if(!$result2)
        die('Error with creating the skills table.');

    $sql3 = "CREATE TABLE IF NOT EXISTS Person (
        person_id INT(6) AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(20) NOT NULL,
        middle_name VARCHAR(20) NOT NULL,
        last_name VARCHAR(20) NOT NULL,
        date_of_birth DATE NOT NULL
    ) ENGINE=INNODB DEFAULT CHARSET=utf8 ";
    $result3 = mysqli_query($dbConn,$sql3);
    if(!$result3)
        die('Error with creating the person table.');

    $sql4 = "CREATE TABLE IF NOT EXISTS PersonSkills (
        person_skills_id INT(6) AUTO_INCREMENT PRIMARY KEY,
        id_person INT(6) DEFAULT NULL, FOREIGN KEY(id_person) References Person(person_id),
        id_skill INT(6) DEFAULT NULL, FOREIGN KEY(id_skill) References Skills(skill_id)
    ) ENGINE=INNODB DEFAULT CHARSET=utf8 ";
    $result4 = mysqli_query($dbConn,$sql4);
    if(!$result4)
        die('Error with creating the person skills table.');

    $sql5 = "CREATE TABLE IF NOT EXISTS CV (
        cv_id INT(6) AUTO_INCREMENT PRIMARY KEY,
        id_person INT(6), FOREIGN KEY (id_person) REFERENCES Person(person_id),
        id_university INT(6), FOREIGN KEY (id_university) REFERENCES University(university_id),
        date_of_applying DATE NOT NULL
    ) ENGINE=INNODB DEFAULT CHARSET=utf8";
    $result5 = mysqli_query($dbConn, $sql5);
    if (!$result5)
        die('Error creating CVs table.');

?>

</body>
</html>
