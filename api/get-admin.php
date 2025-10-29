<?php
header('Content-Type: application/json');
include "../db_conn.php";

try {
    // Truy vấn danh sách admin
    $sql = "SELECT id, full_name, email FROM admin ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "count" => count($admins),
        "data" => $admins
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
