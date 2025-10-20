<?php
header("Content-Type: application/json");
include("../config/db.php");

$sql = "SELECT s.TenSach, t.SoLuongTon, t.ViTriKho 
        FROM tonkho t 
        JOIN sach s ON s.MaSach = t.MaSach";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
