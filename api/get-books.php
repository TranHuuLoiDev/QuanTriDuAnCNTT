<?php
header('Content-Type: application/json');
include "../db_conn.php";

try {
    $sql = "SELECT 
                books.id,
                books.title,
                authors.name AS author,
                categories.name AS category,
                books.description,
                books.cover,
                books.file
            FROM books
            INNER JOIN authors ON books.author_id = authors.id
            INNER JOIN categories ON books.category_id = categories.id
            ORDER BY books.id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "count" => count($books),
        "data" => $books
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
