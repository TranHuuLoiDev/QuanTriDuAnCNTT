Thành viên : 3 
Tên : Trần Hữu Lợi
Tên : Huỳnh Minh Quang
Tên : Lương Quốc An


# Cloud Backup & Disaster Recovery Solution

1. Tổng quan
2. Kiến trúc giải pháp
3. Công nghệ sử dụng
4. Cấu trúc thư mục
5. Cấu hình Rclone
6. Backup workflow
7. Restore workflow
8. Retention Policy
9. Windows Task Scheduler
10. RPO/RTO
11. Test Results
12. Security
13. Hướng dẫn chạy

| Test                   | Kết quả                 |
| ---------------------- | ----------------------- |
| Database + file backup | PASS                    |
| SHA-256 verification   | PASS                    |
| Database restore       | PASS                    |
| 13/13 Uploads restore  | PASS                    |
| Scheduler automation   | PASS                    |
| MySQL unavailable      | PASS – exit 1           |
| Corrupted checksum     | PASS – restore rejected |
| Cloud unavailable      | PASS – restore rejected |
