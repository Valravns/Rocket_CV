<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first'];
    $middleName = $_POST['middle'];
    $lastName = $_POST['last'];
    $dob = $_POST['dob'];
    $universityId = $_POST['option'];
    $skills = $_POST['optiont'];


    $personCheckSql = "SELECT person_id FROM Person
    WHERE first_name = ? 
    AND middle_name = ?
     AND last_name = ?
      AND date_of_birth = ?";
    $stmt = $dbConn->prepare($personCheckSql);
    $stmt->bind_param('ssss', $firstName, $middleName, $lastName, $dob);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        echo "<script>alert('This person already exists and has a CV.'); window.location.href = 'index.php';</script>";

        $cvSql = "SELECT * FROM CV 
            JOIN Person ON id_person = person_id
            JOIN University ON id_university=university_id
            JOIN PersonSkills ON id_person_skills = person_skills_id
            JOIN Skills ON id_skill = skill_id";
        $cvResult = mysqli_query($dbConn, $cvSql);
        echo "<table border = '3'>";
        echo "<tr><th>First name</th><th>Middle name</th><th>Last name</th><th>Date of birth</th><th>University</th><th>Skills</th></tr>";
        while ($row = mysqli_fetch_assoc($cvResult)) {
            echo "<tr>";
            echo "<td>" . $row["first_name"] . "</td>";
            echo "<td>" . $row["middle_name"] . "</td>";
            echo "<td>" . $row["last_name"] . "</td>";
            echo "<td>" . $row["date_of_birth"] . "</td>";
            echo "<td>" . $row["university_name"] . "</td>";
            echo "<td>" . $row["skill"] . "</td>";
            echo "</tr>";
        }
        echo "</table></a>";
        echo "</div>";
    }
}
?>