@echo off

set "TASK_NAME=AutoCloudBackup_OnlineBookStore"
set "BACKUP_SCRIPT=C:\xampp\htdocs\QuanTriDuAnCNTT\cloud-backup-solution\scripts\backup_full.bat"

echo ============================================================
echo CLOUD BACKUP - TASK SCHEDULER SETUP
echo ============================================================

schtasks /create ^
 /tn "%TASK_NAME%" ^
 /tr "\"%BACKUP_SCRIPT%\"" ^
 /sc DAILY ^
 /st 00:00 ^
 /f

if errorlevel 1 (
    echo.
    echo [ERROR] Unable to create scheduled task.
    echo Try running this script as Administrator.
    exit /b 1
)

echo.
echo [SUCCESS] Scheduled task created.
echo Task : %TASK_NAME%
echo Time : 00:00 daily
echo.

schtasks /query /tn "%TASK_NAME%" /fo LIST /v

exit /b 0