<?php
header("Content-Type: application/json");
include("../config/db.php");

$sql = "SELECT s.TenSach, t.SoLuongTon
        FROM tonkho t
        JOIN sach s ON s.MaSach = t.MaSach
        WHERE t.SoLuongTon < 10";

$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
