<?php
header('Content-Type: application/json');
include "../db_conn.php";

try {
    $stmt = $conn->query("SELECT * FROM authors ORDER BY id DESC");
    $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "count" => count($authors),
        "data" => $authors
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
