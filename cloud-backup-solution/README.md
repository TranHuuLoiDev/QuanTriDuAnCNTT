\# Cloud Backup \& Disaster Recovery Solution



\## 1. Tá»•ng quan



Giáº£i phÃ¡p Cloud Backup \& Disaster Recovery Ä‘Æ°á»£c xÃ¢y dá»±ng cho há»‡ thá»‘ng

Website BÃ¡n SÃ¡ch Online Book Store.



Má»¥c tiÃªu cá»§a giáº£i phÃ¡p lÃ  tá»± Ä‘á»™ng sao lÆ°u vÃ  há»— trá»£ khÃ´i phá»¥c:



\- CÆ¡ sá»Ÿ dá»¯ liá»‡u MySQL `online\_book\_store\_db`

\- ThÆ° má»¥c dá»¯ liá»‡u ngÆ°á»i dÃ¹ng `Uploads`

\- CÃ¡c file áº£nh bÃ¬a vÃ  tÃ i liá»‡u PDF cá»§a há»‡ thá»‘ng



Backup Ä‘Æ°á»£c Ä‘Ã³ng gÃ³i thÃ nh file ZIP, táº¡o mÃ£ kiá»ƒm tra SHA-256 vÃ  lÆ°u trá»¯

trÃªn Google Drive thÃ´ng qua Rclone.



Há»‡ thá»‘ng Ä‘á»“ng thá»i há»— trá»£:



\- Backup tá»± Ä‘á»™ng

\- Restore tá»« backup má»›i nháº¥t

\- Kiá»ƒm tra tÃ­nh toÃ n váº¹n dá»¯ liá»‡u

\- Logging

\- Retention Policy

\- Windows Task Scheduler

\- Äo thá»i gian phá»¥c há»“i

\- Xá»­ lÃ½ lá»—i theo cÆ¡ cháº¿ fail-safe



\---



\## 2. Kiáº¿n trÃºc giáº£i phÃ¡p



```text

&#x20;                       GOOGLE DRIVE

&#x20;                            â–²

&#x20;                            â”‚

&#x20;                          Rclone

&#x20;                            â”‚

&#x20;               â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”´â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”

&#x20;               â”‚                         â”‚

&#x20;       backup\_full.ps1           restore\_full.ps1

&#x20;               â–²                         â–²

&#x20;               â”‚                         â”‚

&#x20;       backup\_full.bat           restore\_full.bat

&#x20;               â–²

&#x20;               â”‚

&#x20;       Windows Task Scheduler





Luá»“ng sao lÆ°u:




MySQL Database

&#x20;     â”‚

&#x20;     â”œâ”€â”€ mysqldump

&#x20;     â”‚

Uploads Folder

&#x20;     â”‚

&#x20;     â–¼

Temporary Backup Package

&#x20;     â”‚

&#x20;     â–¼

ZIP Archive

&#x20;     â”‚

&#x20;     â–¼

SHA-256

&#x20;     â”‚

&#x20;     â–¼

Rclone

&#x20;     â”‚

&#x20;     â–¼

Google Drive

Luá»“ng khÃ´i phá»¥c:



Google Drive

&#x20;     â”‚

&#x20;     â–¼

TÃ¬m backup má»›i nháº¥t

&#x20;     â”‚

&#x20;     â–¼

Download ZIP + SHA-256

&#x20;     â”‚

&#x20;     â–¼

Kiá»ƒm tra SHA-256

&#x20;     â”‚

&#x20;     â”œâ”€â”€ Sai â†’ Dá»«ng Restore

&#x20;     â”‚

&#x20;     â””â”€â”€ ÄÃºng

&#x20;           â”‚

&#x20;           â–¼

&#x20;        Extract

&#x20;           â”‚

&#x20;     â”Œâ”€â”€â”€â”€â”€â”´â”€â”€â”€â”€â”€â”

&#x20;     â–¼           â–¼

&#x20;  MySQL       Uploads

&#x20;     â”‚           â”‚

&#x20;     â””â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”˜

&#x20;           â–¼

&#x20;      Verification



3\. CÃ´ng nghá»‡ sá»­ dá»¥ng



| ThÃ nh pháº§n      | CÃ´ng nghá»‡              |

| --------------- | ---------------------- |

| Web Server      | XAMPP                  |

| Database        | MySQL                  |

| Backup Database | mysqldump              |

| Automation      | PowerShell             |

| Launcher        | Windows Batch          |

| Cloud Storage   | Google Drive           |

| Cloud Transfer  | Rclone                 |

| Integrity Check | SHA-256                |

| Scheduling      | Windows Task Scheduler |

| File Packaging  | ZIP / Compress-Archive |



4\. Cáº¥u trÃºc thÆ° má»¥c



cloud-backup-solution/

â”‚

â”œâ”€â”€ README.md

â”œâ”€â”€ .gitignore

â”‚

â”œâ”€â”€ docs/

â”‚

â”œâ”€â”€ logs/

â”‚

â”œâ”€â”€ scripts/

â”‚   â”œâ”€â”€ backup\_full.bat

â”‚   â”œâ”€â”€ backup\_full.ps1

â”‚   â”œâ”€â”€ restore\_full.bat

â”‚   â”œâ”€â”€ restore\_full.ps1

â”‚   â”œâ”€â”€ retention\_cleanup.ps1

â”‚   â””â”€â”€ setup\_scheduler.bat

â”‚

â””â”€â”€ temp/

CÃ¡c file backup, log runtime, dá»¯ liá»‡u test, SQL dump, Rclone binary vÃ 

Rclone credentials khÃ´ng Ä‘Æ°á»£c lÆ°u trÃªn GitHub.



5\. Cáº¥u hÃ¬nh Rclone



Rclone Ä‘Æ°á»£c sá»­ dá»¥ng Ä‘á»ƒ káº¿t ná»‘i há»‡ thá»‘ng local vá»›i Google Drive.



Remote máº·c Ä‘á»‹nh:

mygdrive:



Remote máº·c Ä‘á»‹nh:



mygdrive:



ThÆ° má»¥c lÆ°u backup:



mygdrive:Backup\_QuanTriDuAn



CÃ³ thá»ƒ kiá»ƒm tra káº¿t ná»‘i báº±ng:



rclone lsd mygdrive:



Hoáº·c:



rclone ls mygdrive:Backup\_QuanTriDuAn

LÆ°u Ã½ báº£o máº­t



File rclone.conf cÃ³ thá»ƒ chá»©a token xÃ¡c thá»±c nÃªn tuyá»‡t Ä‘á»‘i khÃ´ng Ä‘Æ°á»£c

commit lÃªn GitHub.



rclone.exe cÅ©ng khÃ´ng Ä‘Æ°á»£c lÆ°u trong repository vÃ¬ Ä‘Ã¢y lÃ  binary cá»§a

bÃªn thá»© ba.



Sau khi cÃ i Ä‘áº·t Rclone, Ä‘áº·t rclone.exe vÃ o:



cloud-backup-solution/scripts/

6\. Backup Workflow



Script chÃ­nh:



scripts/backup\_full.ps1



Launcher:



scripts/backup\_full.bat



Cháº¡y thá»§ cÃ´ng:



cmd /c ".\\cloud-backup-solution\\scripts\\backup\_full.bat"



Quy trÃ¬nh gá»“m:



Kiá»ƒm tra mysqldump.exe, Rclone vÃ  thÆ° má»¥c Uploads.

Export database MySQL.

Copy toÃ n bá»™ Uploads.

Kiá»ƒm tra sá»‘ lÆ°á»£ng file.

Táº¡o ZIP.

Sinh SHA-256.

Upload ZIP vÃ  SHA-256 lÃªn Google Drive.

Kiá»ƒm tra file trÃªn Cloud.

Ghi log káº¿t quáº£.



TÃªn backup:



full\_backup\_YYYYMMDD\_HHMMSS.zip



Checksum:



full\_backup\_YYYYMMDD\_HHMMSS.zip.sha256



VÃ­ dá»¥:



full\_backup\_20260909\_215727.zip

full\_backup\_20260909\_215727.zip.sha256

7\. Restore Workflow



Script chÃ­nh:



scripts/restore\_full.ps1



Launcher:



scripts/restore\_full.bat



Restore sáº½ tá»± Ä‘á»™ng:



Káº¿t ná»‘i Google Drive.

TÃ¬m báº£n backup má»›i nháº¥t.

Download ZIP vÃ  SHA-256.

Kiá»ƒm tra SHA-256.

Giáº£i nÃ©n backup.

Restore MySQL.

Restore Uploads.

Kiá»ƒm tra sá»‘ lÆ°á»£ng file.

Ghi log vÃ  thá»i gian phá»¥c há»“i.



Cháº¡y:



cmd /c ".\\cloud-backup-solution\\scripts\\restore\_full.bat"



CÃ³ thá»ƒ truyá»n database vÃ  thÆ° má»¥c Uploads riÃªng Ä‘á»ƒ thá»±c hiá»‡n kiá»ƒm thá»­

trong sandbox mÃ  khÃ´ng áº£nh hÆ°á»Ÿng dá»¯ liá»‡u production.



8\. Kiá»ƒm tra tÃ­nh toÃ n váº¹n SHA-256



Má»—i file ZIP Ä‘á»u cÃ³ má»™t SHA-256 tÆ°Æ¡ng á»©ng.



VÃ­ dá»¥:



full\_backup\_20260909\_215727.zip

full\_backup\_20260909\_215727.zip.sha256



TrÆ°á»›c khi Restore, há»‡ thá»‘ng tÃ­nh láº¡i SHA-256 cá»§a ZIP vÃ  so sÃ¡nh vá»›i giÃ¡

trá»‹ Ä‘Ã£ lÆ°u.



Náº¿u hai giÃ¡ trá»‹ khÃ´ng giá»‘ng nhau:



SHA-256 MISMATCH



Restore sáº½ bá»‹ há»§y trÆ°á»›c khi database hoáº·c Uploads bá»‹ thay Ä‘á»•i.



9\. Retention Policy



Script:



scripts/retention\_cleanup.ps1



Retention máº·c Ä‘á»‹nh:



30 ngÃ y



Kiá»ƒm tra an toÃ n báº±ng Dry Run:



powershell.exe -NoProfile -ExecutionPolicy Bypass `

\-File .\\cloud-backup-solution\\scripts\\retention\_cleanup.ps1



Dry Run chá»‰ liá»‡t kÃª backup háº¿t háº¡n vÃ  khÃ´ng xÃ³a dá»¯ liá»‡u.



Viá»‡c xÃ³a tháº­t chá»‰ Ä‘Æ°á»£c thá»±c hiá»‡n khi sá»­ dá»¥ng tÃ¹y chá»n:



\-Execute



Qua kiá»ƒm thá»­ hiá»‡n táº¡i, cÃ¡c backup Ä‘á»u nhá» hÆ¡n 30 ngÃ y nÃªn chÆ°a cÃ³ backup

nÃ o bá»‹ xÃ³a.



10\. Windows Task Scheduler



Script:



scripts/setup\_scheduler.bat



Task:



AutoCloudBackup\_OnlineBookStore



Lá»‹ch máº·c Ä‘á»‹nh:



00:00 má»—i ngÃ y



CÃ³ thá»ƒ kiá»ƒm tra báº±ng:



schtasks /query /tn "AutoCloudBackup\_OnlineBookStore" /fo LIST /v



Trong kiá»ƒm thá»­ thá»±c táº¿:



Schedule Type : Daily

Start Time    : 12:00:00 AM

Last Result   : 0



Last Result = 0 cho biáº¿t láº§n cháº¡y gáº§n nháº¥t hoÃ n thÃ nh thÃ nh cÃ´ng.



Task Scheduler Ä‘Ã£ táº¡o thÃ nh cÃ´ng backup:



full\_backup\_20260909\_214724.zip



trÃªn Google Drive.



11\. RPO vÃ  RTO

RPO



Backup tá»± Ä‘á»™ng Ä‘Æ°á»£c cáº¥u hÃ¬nh cháº¡y má»—i ngÃ y lÃºc:



00:00



Vá»›i lá»‹ch nÃ y, RPO thiáº¿t káº¿ tá»‘i Ä‘a lÃ  khoáº£ng:



24 giá»



Khoáº£ng thá»i gian nÃ y cÃ³ thá»ƒ giáº£m báº±ng cÃ¡ch tÄƒng táº§n suáº¥t backup trong

mÃ´i trÆ°á»ng triá»ƒn khai thá»±c táº¿.



RTO



Trong thá»­ nghiá»‡m khÃ´i phá»¥c sandbox ngÃ y 09/09/2026:



Backup:

full\_backup\_20260908\_170320.zip



Database:

online\_book\_store\_db\_restore\_test



Uploads:

13/13 files



Measured recovery automation duration:

24.62 seconds



Thá»i gian nÃ y bao gá»“m cÃ¡c bÆ°á»›c tá»± Ä‘á»™ng nhÆ° tÃ¬m backup, download tá»« Cloud,

kiá»ƒm tra SHA-256, giáº£i nÃ©n vÃ  phá»¥c há»“i dá»¯ liá»‡u trong mÃ´i trÆ°á»ng thá»­ nghiá»‡m.



ÄÃ¢y lÃ  sá»‘ liá»‡u cá»§a láº§n thá»­ nghiá»‡m cá»¥ thá»ƒ, khÃ´ng Ä‘Æ°á»£c xem lÃ  SLA cá»‘ Ä‘á»‹nh

cho má»i mÃ´i trÆ°á»ng.



12\. Káº¿t quáº£ kiá»ƒm thá»­

Test Case	Ká»‹ch báº£n	Káº¿t quáº£

TC-NORMAL-01	Backup MySQL + Uploads	PASS

TC-NORMAL-02	Upload ZIP + SHA-256 lÃªn Google Drive	PASS

TC-NORMAL-03	Restore MySQL + Uploads	PASS

TC-NORMAL-04	Kiá»ƒm tra 13/13 file sau Restore	PASS

TC-AUTO-01	Backup báº±ng Windows Task Scheduler	PASS

TC-FAIL-01	MySQL Server bá»‹ dá»«ng	PASS

TC-FAIL-02	SHA-256 khÃ´ng khá»›p	PASS

TC-FAIL-03	Cloud Remote khÃ´ng kháº£ dá»¥ng	PASS

TC-FAIL-01 - MySQL unavailable



Káº¿t quáº£:



mysqldump connection error

Exit Code = 1

Backup stopped

No incomplete backup uploaded to Cloud



Database sau khi MySQL Ä‘Æ°á»£c khá»Ÿi Ä‘á»™ng láº¡i váº«n giá»¯ nguyÃªn dá»¯ liá»‡u.



TC-FAIL-02 - SHA-256 mismatch



Checksum giáº£ Ä‘Æ°á»£c sá»­ dá»¥ng Ä‘á»ƒ mÃ´ phá»ng backup bá»‹ thay Ä‘á»•i.



Káº¿t quáº£:



Expected SHA256 != Actual SHA256

Restore cancelled

Exit Code = 1



Database test khÃ´ng Ä‘Æ°á»£c táº¡o vÃ  dá»¯ liá»‡u Uploads hiá»‡n táº¡i khÃ´ng bá»‹ thay Ä‘á»•i.



TC-FAIL-03 - Cloud unavailable



Má»™t remote khÃ´ng tá»“n táº¡i Ä‘Æ°á»£c sá»­ dá»¥ng Ä‘á»ƒ mÃ´ phá»ng lá»—i Cloud.



Káº¿t quáº£:



Rclone connection error

Restore stopped at step 1/7

Exit Code = 1



Database vÃ  file hiá»‡n táº¡i khÃ´ng bá»‹ thay Ä‘á»•i.



13\. CÆ¡ cháº¿ Fail-Safe



CÃ¡c script Ä‘Æ°á»£c thiáº¿t káº¿ theo nguyÃªn táº¯c:



ERROR

&#x20; â”‚

&#x20; â–¼

Ghi log

&#x20; â”‚

&#x20; â–¼

Dá»«ng workflow

&#x20; â”‚

&#x20; â–¼

Exit Code = 1



Náº¿u lá»—i xáº£y ra, há»‡ thá»‘ng khÃ´ng tiáº¿p tá»¥c cÃ¡c thao tÃ¡c cÃ³ thá»ƒ lÃ m thay Ä‘á»•i

dá»¯ liá»‡u.



Äáº·c biá»‡t Restore chá»‰ báº¯t Ä‘áº§u thay Ä‘á»•i database sau khi:



Download thÃ nh cÃ´ng

&#x20;       +

SHA-256 há»£p lá»‡

&#x20;       +

Giáº£i nÃ©n thÃ nh cÃ´ng

14\. Logging



Log Ä‘Æ°á»£c lÆ°u táº¡i:



cloud-backup-solution/logs/



VÃ­ dá»¥:



backup\_YYYYMMDD\_HHMMSS.log

restore\_YYYYMMDD\_HHMMSS.log



Log bao gá»“m:



Thá»i Ä‘iá»ƒm cháº¡y

CÃ¡c bÆ°á»›c xá»­ lÃ½

Lá»—i

Backup Ä‘Æ°á»£c sá»­ dá»¥ng

Sá»‘ lÆ°á»£ng file

Thá»i gian thá»±c thi

Káº¿t quáº£ SUCCESS hoáº·c ERROR



CÃ¡c log runtime khÃ´ng Ä‘Æ°á»£c commit lÃªn GitHub.



15\. Báº£o máº­t



Giáº£i phÃ¡p Ã¡p dá»¥ng cÃ¡c nguyÃªn táº¯c:



KhÃ´ng lÆ°u OAuth token trÃªn GitHub.

KhÃ´ng commit rclone.conf.

KhÃ´ng commit database dump.

KhÃ´ng commit file backup.

Kiá»ƒm tra SHA-256 trÆ°á»›c khi Restore.

CÃ³ bÆ°á»›c xÃ¡c nháº­n khi Restore dá»¯ liá»‡u tháº­t.

Há»— trá»£ sandbox Ä‘á»ƒ kiá»ƒm thá»­ khÃ´ng áº£nh hÆ°á»Ÿng production.

KhÃ´ng tiáº¿p tá»¥c workflow khi phÃ¡t hiá»‡n lá»—i.

16\. Háº¡n cháº¿ hiá»‡n táº¡i



Giáº£i phÃ¡p hiá»‡n Ä‘Æ°á»£c triá»ƒn khai trÃªn mÃ´i trÆ°á»ng Windows/XAMPP phá»¥c vá»¥

má»¥c Ä‘Ã­ch há»c táº­p vÃ  thá»­ nghiá»‡m.



Task Scheduler hiá»‡n cháº¡y theo tÃ i khoáº£n Windows cá»§a ngÆ°á»i dÃ¹ng.



Trong mÃ´i trÆ°á»ng production cÃ³ thá»ƒ cáº£i tiáº¿n báº±ng:



Service Account hoáº·c tÃ i khoáº£n chuyÃªn dá»¥ng cho backup.

MÃ£ hÃ³a backup trÆ°á»›c khi upload.

Backup incremental/differential.

Nhiá»u Cloud Storage provider.

Notification qua Email/Telegram khi backup tháº¥t báº¡i.

Dashboard theo dÃµi backup.

TÄƒng táº§n suáº¥t backup Ä‘á»ƒ giáº£m RPO.

PhÃ¢n quyá»n vÃ  quáº£n lÃ½ key táº­p trung.

17\. Káº¿t luáº­n



Giáº£i phÃ¡p Ä‘Ã£ triá»ƒn khai thÃ nh cÃ´ng quy trÃ¬nh Backup vÃ  Disaster Recovery

cho Website BÃ¡n SÃ¡ch.



Há»‡ thá»‘ng cÃ³ kháº£ nÄƒng:



Sao lÆ°u database vÃ  file.

LÆ°u nhiá»u phiÃªn báº£n trÃªn Cloud.

XÃ¡c minh tÃ­nh toÃ n váº¹n báº±ng SHA-256.

Tá»± Ä‘á»™ng sao lÆ°u theo lá»‹ch.

KhÃ´i phá»¥c dá»¯ liá»‡u tá»« Cloud.

Äo thá»i gian phá»¥c há»“i.

Quáº£n lÃ½ retention.

Ghi log.

PhÃ¡t hiá»‡n vÃ  xá»­ lÃ½ cÃ¡c tÃ¬nh huá»‘ng lá»—i.



CÃ¡c thá»­ nghiá»‡m normal case vÃ  fault injection Ä‘á»u cho káº¿t quáº£ PASS trong

mÃ´i trÆ°á»ng thá»­ nghiá»‡m.
