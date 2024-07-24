<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rocket CV</title>
    <link rel="stylesheet" href="../indexCSS.css"> 

    
</head>
<body>
    <div class="header">
        <nav>
            <ul>
                <li><a href="../index.php"><b>Submit CV</b></a></li>
                <li><a href="cvInq.php"><b>Show CV's</b></a></li>
                <li><a href="skillSelectionInq.php"><b>Candidates skills</b></a></li>
            </ul>
        </nav>
    </div>
    <div class="form-contents">
        <form method="post" action="#">
            <a style="margin-bottom: 5vh;"><b>Show all CV's of candidates born in a certain period.</a>
            <label>Choose start date: </label>
            <input type="date" name="startDate" required /> 
            <label>Choose end date: </label>
            <input type="date" name="endDate" required />
            <input type="submit" name="submPeriod" value="Show"/>
        </form>
    </div>

    <?php
        include '../database/config.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];

            $cvSql = "SELECT Person.first_name, Person.middle_name, Person.last_name, Person.date_of_birth,
               University.university_name,
                GROUP_CONCAT(Skills.skill SEPARATOR ', ') AS skills 
                FROM CV
                JOIN Person ON CV.id_person = Person.person_id 
                JOIN University ON CV.id_university = University.university_id 
                JOIN PersonSkills ON Person.person_id = PersonSkills.id_person 
                JOIN Skills ON PersonSkills.id_skill = Skills.skill_id 
                WHERE Person.date_of_birth BETWEEN ? AND ?
                GROUP BY Person.person_id";

            if ($stmt = $dbConn->prepare($cvSql)) {
                $stmt->bind_param('ss', $startDate, $endDate);
                $stmt->execute();

                $cvResult = $stmt->get_result();
                if ($cvResult->num_rows > 0) {
                    echo "<div class='cvPrint'>";
                    echo "<table border='3' style='margin-top: -15vh;'>";
                    echo "<tr><th>First name</th><th>Middle name</th><th>Last name</th><th>Date of birth</th><th>University</th><th>Skills</th></tr>";
                    while ($row = $cvResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["first_name"] . "</td>";
                        echo "<td>" . $row["middle_name"] . "</td>";
                        echo "<td>" . $row["last_name"] . "</td>";
                        echo "<td>" . $row["date_of_birth"] . "</td>";
                        echo "<td>" . $row["university_name"] . "</td>";
                        echo "<td>" . $row["skills"] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "</div>";
                } else {
                    ?><div class="alert" style="margin-top: -20vh; width: 40%;"><p><b>There are no candidates born in this period.</b></p></div><?php
                }
                $stmt->close();
            }
        }
    ?>
</body>
</html>