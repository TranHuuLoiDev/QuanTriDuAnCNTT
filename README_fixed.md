# Cloud Backup & Disaster Recovery Solution

## 1. Tổng quan

Giải pháp Cloud Backup & Disaster Recovery được xây dựng cho hệ thống Website Bán Sách Online Book Store.

Mục tiêu của giải pháp là tự động sao lưu và hỗ trợ khôi phục:

- Cơ sở dữ liệu MySQL `online_book_store_db`
- Thư mục dữ liệu người dùng `Uploads`
- Các file ảnh bìa và tài liệu PDF của hệ thống

Backup được đóng gói thành file ZIP, tạo mã kiểm tra SHA-256 và lưu trữ trên Google Drive thông qua Rclone.

Hệ thống đồng thời hỗ trợ:

- Backup tự động
- Restore từ backup mới nhất
- Kiểm tra tính toàn vẹn dữ liệu
- Logging
- Retention Policy
- Windows Task Scheduler
- Đo thời gian phục hồi
- Xử lý lỗi theo cơ chế fail-safe

---

## 2. Kiến trúc giải pháp

```text
                        GOOGLE DRIVE
                             ▲
                             │
                           Rclone
                             │
                ┌────────────┴────────────┐
                │                         │
        backup_full.ps1           restore_full.ps1
                ▲                         ▲
                │                         │
        backup_full.bat           restore_full.bat
                ▲
                │
        Windows Task Scheduler
```

### Luồng sao lưu

```text
MySQL Database
      │
      ├── mysqldump
      │
Uploads Folder
      │
      ▼
Temporary Backup Package
      │
      ▼
ZIP Archive
      │
      ▼
SHA-256
      │
      ▼
Rclone
      │
      ▼
Google Drive
```

### Luồng khôi phục

```text
Google Drive
      │
      ▼
Tìm backup mới nhất
      │
      ▼
Download ZIP + SHA-256
      │
      ▼
Kiểm tra SHA-256
      │
      ├── Sai → Dừng Restore
      │
      └── Đúng
            │
            ▼
         Extract
            │
      ┌─────┴─────┐
      ▼           ▼
   MySQL       Uploads
      │           │
      └─────┬─────┘
            ▼
       Verification
```

---

## 3. Công nghệ sử dụng

| Thành phần | Công nghệ |
|---|---|
| Web Server | XAMPP |
| Database | MySQL |
| Backup Database | mysqldump |
| Automation | PowerShell |
| Launcher | Windows Batch |
| Cloud Storage | Google Drive |
| Cloud Transfer | Rclone |
| Integrity Check | SHA-256 |
| Scheduling | Windows Task Scheduler |
| File Packaging | ZIP / Compress-Archive |

---

## 4. Cấu trúc thư mục

```text
cloud-backup-solution/
│
├── README.md
├── .gitignore
│
├── docs/
│
├── logs/
│
├── scripts/
│   ├── backup_full.bat
│   ├── backup_full.ps1
│   ├── restore_full.bat
│   ├── restore_full.ps1
│   ├── retention_cleanup.ps1
│   └── setup_scheduler.bat
│
└── temp/
```

Các file backup, log runtime, dữ liệu test, SQL dump, Rclone binary và Rclone credentials không được lưu trên GitHub.

---

## 5. Cấu hình Rclone

Rclone được sử dụng để kết nối hệ thống local với Google Drive.

Remote mặc định:

```text
mygdrive:
```

Thư mục lưu backup:

```text
mygdrive:Backup_QuanTriDuAn
```

Có thể kiểm tra kết nối bằng:

```powershell
rclone lsd mygdrive:
```

hoặc:

```powershell
rclone ls mygdrive:Backup_QuanTriDuAn
```

### Lưu ý bảo mật

File `rclone.conf` có thể chứa token xác thực nên tuyệt đối không được commit lên GitHub.

`rclone.exe` cũng không được lưu trong repository vì đây là binary của bên thứ ba.

Sau khi cài đặt Rclone, đặt `rclone.exe` vào:

```text
cloud-backup-solution/scripts/
```

---

## 6. Backup Workflow

Script chính:

```text
scripts/backup_full.ps1
```

Launcher:

```text
scripts/backup_full.bat
```

Chạy thủ công:

```powershell
cmd /c ".\cloud-backup-solution\scripts\backup_full.bat"
```

Quy trình gồm:

1. Kiểm tra `mysqldump.exe`, Rclone và thư mục Uploads.
2. Export database MySQL.
3. Copy toàn bộ Uploads.
4. Kiểm tra số lượng file.
5. Tạo ZIP.
6. Sinh SHA-256.
7. Upload ZIP và SHA-256 lên Google Drive.
8. Kiểm tra file trên Cloud.
9. Ghi log kết quả.

Tên backup:

```text
full_backup_YYYYMMDD_HHMMSS.zip
```

Checksum:

```text
full_backup_YYYYMMDD_HHMMSS.zip.sha256
```

Ví dụ:

```text
full_backup_20260909_215727.zip
full_backup_20260909_215727.zip.sha256
```

---

## 7. Restore Workflow

Script chính:

```text
scripts/restore_full.ps1
```

Launcher:

```text
scripts/restore_full.bat
```

Restore sẽ tự động:

1. Kết nối Google Drive.
2. Tìm bản backup mới nhất.
3. Download ZIP và SHA-256.
4. Kiểm tra SHA-256.
5. Giải nén backup.
6. Restore MySQL.
7. Restore Uploads.
8. Kiểm tra số lượng file.
9. Ghi log và thời gian phục hồi.

Chạy:

```powershell
cmd /c ".\cloud-backup-solution\scripts\restore_full.bat"
```

Có thể truyền database và thư mục Uploads riêng để thực hiện kiểm thử trong sandbox mà không ảnh hưởng dữ liệu production.

---

## 8. Kiểm tra tính toàn vẹn SHA-256

Mỗi file ZIP đều có một SHA-256 tương ứng.

Ví dụ:

```text
full_backup_20260909_215727.zip
full_backup_20260909_215727.zip.sha256
```

Trước khi Restore, hệ thống tính lại SHA-256 của ZIP và so sánh với giá trị đã lưu.

Nếu hai giá trị không giống nhau:

```text
SHA-256 MISMATCH
```

Restore sẽ bị hủy trước khi database hoặc Uploads bị thay đổi.

---

## 9. Retention Policy

Script:

```text
scripts/retention_cleanup.ps1
```

Retention mặc định:

```text
30 ngày
```

Kiểm tra an toàn bằng Dry Run:

```powershell
powershell.exe -NoProfile -ExecutionPolicy Bypass `
-File .\cloud-backup-solution\scripts\retention_cleanup.ps1
```

Dry Run chỉ liệt kê backup hết hạn và không xóa dữ liệu.

Việc xóa thật chỉ được thực hiện khi sử dụng tùy chọn:

```powershell
-Execute
```

Qua kiểm thử hiện tại, các backup đều nhỏ hơn 30 ngày nên chưa có backup nào bị xóa.

---

## 10. Windows Task Scheduler

Script:

```text
scripts/setup_scheduler.bat
```

Task:

```text
AutoCloudBackup_OnlineBookStore
```

Lịch mặc định:

```text
00:00 mỗi ngày
```

Có thể kiểm tra bằng:

```powershell
schtasks /query /tn "AutoCloudBackup_OnlineBookStore" /fo LIST /v
```

Trong kiểm thử thực tế:

```text
Schedule Type : Daily
Start Time    : 12:00:00 AM
Last Result   : 0
```

`Last Result = 0` cho biết lần chạy gần nhất hoàn thành thành công.

Task Scheduler đã tạo thành công backup:

```text
full_backup_20260909_214724.zip
```

trên Google Drive.

---

## 11. RPO và RTO

### RPO

Backup tự động được cấu hình chạy mỗi ngày lúc:

```text
00:00
```

Với lịch này, RPO thiết kế tối đa là khoảng:

```text
24 giờ
```

Khoảng thời gian này có thể giảm bằng cách tăng tần suất backup trong môi trường triển khai thực tế.

### RTO

Trong thử nghiệm khôi phục sandbox ngày 09/09/2026:

```text
Backup: full_backup_20260908_170320.zip
Database: online_book_store_db_restore_test
Uploads: 13/13 files
Measured recovery automation duration: 24.62 seconds
```

Thời gian này bao gồm các bước tự động như tìm backup, download từ Cloud, kiểm tra SHA-256, giải nén và phục hồi dữ liệu trong môi trường thử nghiệm.

Đây là số liệu của lần thử nghiệm cụ thể, không được xem là SLA cố định cho mọi môi trường.

---

## 12. Kết quả kiểm thử

| Test Case | Kịch bản | Kết quả |
|---|---|---|
| TC-NORMAL-01 | Backup MySQL + Uploads | PASS |
| TC-NORMAL-02 | Upload ZIP + SHA-256 lên Google Drive | PASS |
| TC-NORMAL-03 | Restore MySQL + Uploads | PASS |
| TC-NORMAL-04 | Kiểm tra 13/13 file sau Restore | PASS |
| TC-AUTO-01 | Backup bằng Windows Task Scheduler | PASS |
| TC-FAIL-01 | MySQL Server bị dừng | PASS |
| TC-FAIL-02 | SHA-256 không khớp | PASS |
| TC-FAIL-03 | Cloud Remote không khả dụng | PASS |

### TC-FAIL-01 - MySQL unavailable

Kết quả:

```text
mysqldump connection error
Exit Code = 1
Backup stopped
No incomplete backup uploaded to Cloud
```

Database sau khi MySQL được khởi động lại vẫn giữ nguyên dữ liệu.

### TC-FAIL-02 - SHA-256 mismatch

Checksum giả được sử dụng để mô phỏng backup bị thay đổi.

Kết quả:

```text
Expected SHA256 != Actual SHA256
Restore cancelled
Exit Code = 1
```

Database test không được tạo và dữ liệu Uploads hiện tại không bị thay đổi.

### TC-FAIL-03 - Cloud unavailable

Một remote không tồn tại được sử dụng để mô phỏng lỗi Cloud.

Kết quả:

```text
Rclone connection error
Restore stopped at step 1/7
Exit Code = 1
```

Database và file hiện tại không bị thay đổi.

---

## 13. Cơ chế Fail-Safe

Các script được thiết kế theo nguyên tắc:

```text
ERROR
  │
  ▼
Ghi log
  │
  ▼
Dừng workflow
  │
  ▼
Exit Code = 1
```

Nếu lỗi xảy ra, hệ thống không tiếp tục các thao tác có thể làm thay đổi dữ liệu.

Đặc biệt Restore chỉ bắt đầu thay đổi database sau khi:

```text
Download thành công
        +
SHA-256 hợp lệ
        +
Giải nén thành công
```

---

## 14. Logging

Log được lưu tại:

```text
cloud-backup-solution/logs/
```

Ví dụ:

```text
backup_YYYYMMDD_HHMMSS.log
restore_YYYYMMDD_HHMMSS.log
```

Log bao gồm:

- Thời điểm chạy
- Các bước xử lý
- Lỗi
- Backup được sử dụng
- Số lượng file
- Thời gian thực thi
- Kết quả SUCCESS hoặc ERROR

Các log runtime không được commit lên GitHub.

---

## 15. Bảo mật

Giải pháp áp dụng các nguyên tắc:

- Không lưu OAuth token trên GitHub.
- Không commit `rclone.conf`.
- Không commit database dump.
- Không commit file backup.
- Kiểm tra SHA-256 trước khi Restore.
- Có bước xác nhận khi Restore dữ liệu thật.
- Hỗ trợ sandbox để kiểm thử không ảnh hưởng production.
- Không tiếp tục workflow khi phát hiện lỗi.

---

## 16. Hạn chế hiện tại

Giải pháp hiện được triển khai trên môi trường Windows/XAMPP phục vụ mục đích học tập và thử nghiệm.

Task Scheduler hiện chạy theo tài khoản Windows của người dùng.

Trong môi trường production có thể cải tiến bằng:

- Service Account hoặc tài khoản chuyên dụng cho backup.
- Mã hóa backup trước khi upload.
- Backup incremental/differential.
- Nhiều Cloud Storage provider.
- Notification qua Email/Telegram khi backup thất bại.
- Dashboard theo dõi backup.
- Tăng tần suất backup để giảm RPO.
- Phân quyền và quản lý key tập trung.

---

## 17. Kết luận

Giải pháp đã triển khai thành công quy trình Backup và Disaster Recovery cho Website Bán Sách.

Hệ thống có khả năng:

- Sao lưu database và file.
- Lưu nhiều phiên bản trên Cloud.
- Xác minh tính toàn vẹn bằng SHA-256.
- Tự động sao lưu theo lịch.
- Khôi phục dữ liệu từ Cloud.
- Đo thời gian phục hồi.
- Quản lý retention.
- Ghi log.
- Phát hiện và xử lý các tình huống lỗi.

Các thử nghiệm normal case và fault injection đều cho kết quả PASS trong môi trường thử nghiệm.
