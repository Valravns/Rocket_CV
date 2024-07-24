<?php
include '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $skillName = $_POST['skillName'];

    $sql = "INSERT INTO Skills (skill) VALUES (?)";
    $stmt = $dbConn->prepare($sql);
    $stmt->bind_param('s', $skillName);

    if ($stmt->execute()) {
        $newSkillId = $stmt->insert_id;
        $response = [
            "status" => "success",
            "message" => "The skill has been successfully added!",
            "newSkillId" => $newSkillId
        ];
    } else {
        $response = [
            "status" => "error",
            "message" => "Error with adding the university: " . $stmt->error
        ];
    }
    $stmt->close();
    $dbConn->close();
    
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>