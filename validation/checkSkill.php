<?php
include '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $skillName = $_POST['skillName'];

    $sql = "SELECT skill_id FROM Skills 
        WHERE skill = ?";
    $stmt = $dbConn->prepare($sql);
    $stmt->bind_param('s', $skillName);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response = [
            "status" => "exists",
            "message" => "The skill already exists!"
        ];
    } else {
        $response = [
            "status" => "does not exist",
            "message" => "The skill does not exist!"
        ];
    }

    $stmt->close();
    $dbConn->close();

    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>