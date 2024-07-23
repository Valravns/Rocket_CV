<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rocket CV</title>
    <link rel="stylesheet" href="indexCSS.css"> 

    
</head>
<body>
    <div class="header">
        <nav>
            <ul>
                <li><a href="index.php"><b>Submit CV</b></a></li>
                <li><a href="Inqueries.php"><b>Inquiries</b></a></li>
            </ul>
        </nav>
    </div>
    <?php
        include 'config.php';
        if(isset($_GET['match'])) {
            ?><div class="alert"><p><b>This person already exists and has a CV.</b></p></div><?php
        }
        if (isset($_GET['id_person']) && isset($_GET['id_university'])) {
            $personId = $_GET['id_person'];
            $universityId = $_GET['id_university'];
            $cvSql = "SELECT Person.first_name, Person.middle_name, Person.last_name, Person.date_of_birth,
               University.university_name,
                GROUP_CONCAT(Skills.skill SEPARATOR ', ') AS skills 
                FROM CV
                JOIN Person ON CV.id_person = Person.person_id 
                JOIN University ON CV.id_university = University.university_id 
                JOIN PersonSkills ON Person.person_id = PersonSkills.id_person 
                JOIN Skills ON PersonSkills.id_skill = Skills.skill_id 
                WHERE CV.id_person = ?
                GROUP BY Person.person_id";
            $stmt = $dbConn->prepare($cvSql);
            $stmt->bind_param('i', $personId);
            $stmt->execute();
            $cvResult = $stmt->get_result();
            if($cvResult->num_rows > 0) {
                echo "<div class='cvPrint'>";
                while ($row = $cvResult->fetch_assoc()) {
                    echo "<table border='3' cellspacing='0' cellpadding='5'>";
                        echo "<tr><th colspan='2'>CV Info</th></tr>";
                        echo "<tr><th>First name</th><td>" . $row["first_name"] . "</td></tr>";
                        echo "<tr><th>Middle name</th><td>" . $row["middle_name"] . "</td></tr>";
                        echo "<tr><th>Last name</th><td>" . $row["last_name"] . "</td></tr>";
                        echo "<tr><th>Date of birth</th><td>" . $row["date_of_birth"] . "</td></tr>";
                        echo "<tr><th>University</th><td>" . $row["university_name"] . "</td></tr>";
                        echo "<tr><th>Skills</th><td>" . $row["skills"] . "</td></tr>";
                    echo "</table></a>";
                }
                echo "</div>";
            }
        }
    ?>

</body>
</html>