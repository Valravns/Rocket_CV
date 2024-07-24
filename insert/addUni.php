<?php
include '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uniName = $_POST['uniName'];
    $uniGrade = $_POST['uniGrade'];

    $sql = "INSERT INTO University (university_name, accreditation) VALUES (?, ?)";
    $stmt = $dbConn->prepare($sql);
    $stmt->bind_param('ss', $uniName, $uniGrade);

    if ($stmt->execute()) {
        $newUniId = $stmt->insert_id;
        $response = [
            "status" => "success",
            "message" => "The university has been successfully added!",
            "newUniId" => $newUniId
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