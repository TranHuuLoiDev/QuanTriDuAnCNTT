<?php
header("Content-Type: application/json");
include("../config/db.php");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT l.MaLo, s.TenSach, l.NgayNhap, l.SoLuongNhap, n.TenNCC, l.DonGiaNhap 
                FROM lohang l
                JOIN sach s ON s.MaSach = l.MaSach
                JOIN nhacungcap n ON n.MaNCC = l.NhaCungCap";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        if (!isset($input['MaSach'], $input['NgayNhap'], $input['SoLuongNhap'], $input['NhaCungCap'], $input['DonGiaNhap'])) {
            echo json_encode(["error" => "Thiếu dữ liệu đầu vào"]);
            exit;
        }

        $masach = intval($input['MaSach']);
        $ngaynhap = $conn->real_escape_string($input['NgayNhap']);
        $soluong = intval($input['SoLuongNhap']);
        $ncc = intval($input['NhaCungCap']);
        $dongia = floatval($input['DonGiaNhap']);

        $sql = "INSERT INTO lohang (MaSach, NgayNhap, SoLuongNhap, NhaCungCap, DonGiaNhap)
                VALUES ($masach, '$ngaynhap', $soluong, $ncc, $dongia)";
        echo $conn->query($sql)
            ? json_encode(["message" => "Thêm lô hàng thành công"])
            : json_encode(["error" => $conn->error]);
        break;
}
?>
