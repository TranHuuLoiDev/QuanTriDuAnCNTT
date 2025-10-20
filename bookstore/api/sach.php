<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");
include("../config/db.php"); // đường dẫn tới file db.php, chỉnh nếu cần

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Hỗ trợ ?id= hoặc ?MaSach= hoặc không truyền => lấy tất cả
    $id = null;
    if (isset($_GET['id'])) $id = intval($_GET['id']);
    if (isset($_GET['MaSach'])) $id = intval($_GET['MaSach']);

    if ($id) {
        $sql = "SELECT MaSach, TenSach, TacGia, NXB, NamXB, TheLoai, SoTrang, NgonNgu, DinhDang, GiaBan FROM sach WHERE MaSach = $id LIMIT 1";
        $res = $conn->query($sql);
        if ($res && $res->num_rows) {
            echo json_encode($res->fetch_assoc(), JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["error" => "Không tìm thấy sách"], JSON_UNESCAPED_UNICODE);
        }
    } else {
        $sql = "SELECT MaSach, TenSach, TacGia, NXB, NamXB, TheLoai, SoTrang, NgonNgu, DinhDang, GiaBan FROM sach ORDER BY MaSach ASC";
        $res = $conn->query($sql);
        $data = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) $data[] = $row;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    $conn->close();
    exit;
}

// Nếu bạn cần POST/PUT/DELETE có thể thêm ở đây (không bắt buộc để hiển thị)
echo json_encode(["error" => "Phương thức không hỗ trợ trên endpoint này"], JSON_UNESCAPED_UNICODE);
$conn->close();
?>
