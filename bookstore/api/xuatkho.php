<?php
header("Content-Type: application/json");
include("../config/db.php");

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    echo json_encode(["error" => "Chỉ hỗ trợ phương thức POST"]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);

if (
    !isset($input['LyDo'], $input['MaNV'], $input['ChiTiet']) ||
    !is_array($input['ChiTiet']) || count($input['ChiTiet']) == 0
) {
    echo json_encode([
        "error" => "Thiếu dữ liệu đầu vào hoặc danh sách ChiTiet trống",
        "received" => $input
    ]);
    exit;
}

// Tạo phiếu xuất
$lydo = $conn->real_escape_string($input['LyDo']);
$manv = $conn->real_escape_string($input['MaNV']);
$ngayxuat = date('Y-m-d');

$sql_px = "INSERT INTO phieuxuat (NgayXuat, LyDo, MaNV) VALUES ('$ngayxuat', '$lydo', '$manv')";
if (!$conn->query($sql_px)) {
    echo json_encode(["error" => "Không thể tạo phiếu xuất: " . $conn->error]);
    exit;
}

$mapx = $conn->insert_id; // Lấy mã phiếu xuất vừa tạo

// Lặp qua danh sách chi tiết
foreach ($input['ChiTiet'] as $item) {
    if (!isset($item['MaSach'], $item['SoLuong'], $item['DonGia'])) {
        echo json_encode(["error" => "Thiếu thông tin sách trong ChiTiet"]);
        exit;
    }

    $masach = intval($item['MaSach']);
    $soluong = intval($item['SoLuong']);
    $dongia = floatval($item['DonGia']);

    $sql_ct = "INSERT INTO ct_phieuxuat (MaPX, MaSach, SoLuong, DonGia)
               VALUES ($mapx, $masach, $soluong, $dongia)";
    if (!$conn->query($sql_ct)) {
        echo json_encode(["error" => "Lỗi khi thêm chi tiết phiếu xuất: " . $conn->error]);
        exit;
    }

    // Cập nhật tồn kho
    $sql_update = "UPDATE tonkho SET SoLuongTon = SoLuongTon - $soluong WHERE MaSach = $masach";
    $conn->query($sql_update);
}

echo json_encode(["message" => "Tạo phiếu xuất thành công", "MaPX" => $mapx]);
?>
