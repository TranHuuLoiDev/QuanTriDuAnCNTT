<?php
header("Content-Type: application/json");
include("../config/db.php");

if (!isset($_GET['MaPX'])) {
    echo json_encode(["error" => "Thiếu MaPX"]);
    exit;
}

$mapx = intval($_GET['MaPX']);

$sql = "SELECT c.MaSach, s.TenSach, c.SoLuong, c.DonGia, c.ThanhTien
        FROM ct_phieuxuat c
        JOIN sach s ON s.MaSach = c.MaSach
        WHERE c.MaPX = $mapx";

$result = $conn->query($sql);
if ($result->num_rows === 0) {
    echo json_encode(["error" => "Không tìm thấy chi tiết phiếu xuất"]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(["MaPX" => $mapx, "ChiTiet" => $data]);
?>
