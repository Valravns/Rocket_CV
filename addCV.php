<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first'];
    $middleName = $_POST['middle'];
    $lastName = $_POST['last'];
    $dob = $_POST['dob'];
    $university = $_POST['option'];
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
        $stmt->bind_result($personId);
        $stmt->fetch();
        $stmt->close();
        $dbConn->close();
        header("Location: showCV.php?id_person=$personId&id_university=$university&match=1");
        exit();
    } else {
        $addPersonSql = "INSERT INTO Person (first_name, middle_name, last_name, date_of_birth) VALUES (?, ?, ?, ?)";
        $stmt = $dbConn->prepare($addPersonSql);
        $stmt->bind_param('ssss', $firstName, $middleName, $lastName, $dob);

        if ($stmt->execute()) {
            $newPersonId = $stmt->insert_id;
            $stmt->close();
            foreach($skills as $skill) {
                $addPersonSkillsSql = "INSERT INTO PersonSkills (id_person, id_skill) VALUES (?, ?)";
                $stmt = $dbConn->prepare($addPersonSkillsSql);
                $stmt->bind_param('ii', $newPersonId, $skill);
                if(!$stmt->execute()) {
                    $response = [
                        "status" => "error",
                        "message" => "Error with adding the person skills: " . $stmt->error
                    ];
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit();
                }
            }
            $stmt->close();
            $addCVSql = "INSERT INTO CV (id_person, id_university) VALUES (?,?)";
            $stmt = $dbConn->prepare($addCVSql);
            $stmt->bind_param('ii', $newPersonId, $university);

            if ($stmt->execute()) {
                $stmt->close();
                $dbConn->close();
                header("Location: showCV.php?id_person=$newPersonId&id_university=$university");
                exit();
            }
            else {
                $response = [
                    "status" => "error",
                    "message" => "Error with adding the CV: " . $stmt->error
                ];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }
        } else {
            $response = [
                "status" => "error",
                "message" => "Error with adding the Person: " . $stmt->error
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }

    }
}
?>