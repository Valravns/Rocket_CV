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
            <a style="margin-bottom: 5vh;"><b>Show number of applicants grouped by age with a list of skills they have and have applied in the selected period.</a>
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
            $currentDate = date('y-m-d');

            $cvSql = "SELECT TIMESTAMPDIFF(YEAR, Person.date_of_birth, '$currentDate') AS age,
                COUNT(DISTINCT Person.person_id) as candidates,
                 GROUP_CONCAT(DISTINCT Skills.skill SEPARATOR ', ') AS skills 
                FROM Person
                JOIN CV ON Person.person_id = CV.id_person
                JOIN University ON CV.id_university = University.university_id 
                JOIN PersonSkills ON Person.person_id = PersonSkills.id_person 
                JOIN Skills ON PersonSkills.id_skill = Skills.skill_id 
                WHERE CV.date_of_applying BETWEEN ? AND ?
                GROUP BY age
                ORDER BY age DESC";

            if ($stmt = $dbConn->prepare($cvSql)) {
                $stmt->bind_param('ss', $startDate, $endDate);
                $stmt->execute();
                $cvResult = $stmt->get_result();

                if ($cvResult->num_rows > 0) {
                    ?><script>console.log("SUCCESS");</script><?php
                    echo "<div class='cvPrint'>";
                    echo "<table border='3' style='margin-top: -10vh;'>";
                    echo "<tr><th>Age</th><th>Number of candidates</th><th>Skills</th></tr>";
                    while ($row = $cvResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["age"] . "</td>";
                        echo "<td>" . $row["candidates"] . "</td>";
                        echo "<td>" . $row["skills"] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table></a>";
                    echo "</div>";
                }
                $stmt->close();
            }
        }
    ?>

</body>
</html>