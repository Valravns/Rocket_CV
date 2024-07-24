<?php
include '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uniName = $_POST['uniName'];

    $sql = "SELECT university_id FROM University 
        WHERE university_name = ?";
    $stmt = $dbConn->prepare($sql);
    $stmt->bind_param('s', $uniName);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response = [
            "status" => "exists",
            "message" => "The university already exists!"
        ];
    } else {
        $response = [
            "status" => "does not exist",
            "message" => "The university does not exist!"
        ];
    }

    $stmt->close();
    $dbConn->close();

    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>