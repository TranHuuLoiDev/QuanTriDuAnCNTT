$ErrorActionPreference = "Stop"

# ============================================================
# CLOUD BACKUP - ONLINE BOOK STORE
# MySQL + Uploads -> ZIP -> SHA256 -> Google Drive
# ============================================================

$DbName = "online_book_store_db"
$DbUser = "root"

$ProjectDir = "C:\xampp\htdocs\QuanTriDuAnCNTT"
$BackupRoot = Join-Path $ProjectDir "cloud-backup-solution"

$WorkDir = Join-Path $BackupRoot "temp"
$LogDir = Join-Path $BackupRoot "logs"
$ScriptDir = Join-Path $BackupRoot "scripts"

$UploadsDir = Join-Path $ProjectDir "Uploads"

$MysqlDump = "C:\xampp\mysql\bin\mysqldump.exe"
$Rclone = Join-Path $ScriptDir "rclone.exe"

$RemotePath = "mygdrive:Backup_QuanTriDuAn"

$Timestamp = Get-Date -Format "yyyyMMdd_HHmmss"

$PackageDir = Join-Path $WorkDir "package_$Timestamp"
$SqlFile = Join-Path $PackageDir "database.sql"

$ZipName = "full_backup_$Timestamp.zip"
$ZipFile = Join-Path $WorkDir $ZipName

$HashName = "$ZipName.sha256"
$HashFile = Join-Path $WorkDir $HashName

$LogFile = Join-Path $LogDir "backup_$Timestamp.log"

$Stopwatch = [System.Diagnostics.Stopwatch]::StartNew()


function Write-Log {
    param([string]$Message)

    $Line = "[$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')] $Message"

    Write-Host $Line
    Add-Content -LiteralPath $LogFile -Value $Line -Encoding UTF8
}


try {

    # --------------------------------------------------------
    # 1. PREPARE DIRECTORIES
    # --------------------------------------------------------

    New-Item -ItemType Directory -Force -Path $WorkDir | Out-Null
    New-Item -ItemType Directory -Force -Path $LogDir | Out-Null

    if (Test-Path $PackageDir) {
        Remove-Item $PackageDir -Recurse -Force
    }

    New-Item -ItemType Directory -Force -Path $PackageDir | Out-Null


    Write-Log "============================================================"
    Write-Log "CLOUD BACKUP STARTED"
    Write-Log "Database : $DbName"
    Write-Log "Remote   : $RemotePath"
    Write-Log "Timestamp: $Timestamp"
    Write-Log "============================================================"


    # --------------------------------------------------------
    # 2. CHECK REQUIREMENTS
    # --------------------------------------------------------

    Write-Log "[CHECK] Checking required components..."

    if (!(Test-Path $MysqlDump)) {
        throw "mysqldump.exe not found: $MysqlDump"
    }

    if (!(Test-Path $Rclone)) {
        throw "rclone.exe not found: $Rclone"
    }

    if (!(Test-Path $UploadsDir)) {
        throw "Uploads directory not found: $UploadsDir"
    }

    Write-Log "[OK] Required components found."


    # --------------------------------------------------------
    # 3. DATABASE BACKUP
    # --------------------------------------------------------

    Write-Log "[1/5] Exporting MySQL database..."

    $DumpOutput = & $MysqlDump `
        "-u$DbUser" `
        "--single-transaction" `
        "--routines" `
        "--triggers" `
        "--events" `
        "--default-character-set=utf8mb4" `
        $DbName 2>&1

    if ($LASTEXITCODE -ne 0) {
        throw "mysqldump failed: $DumpOutput"
    }

    $DumpOutput | Out-File `
        -LiteralPath $SqlFile `
        -Encoding utf8


    if (!(Test-Path $SqlFile)) {
        throw "database.sql was not created."
    }

    $SqlSize = (Get-Item $SqlFile).Length

    if ($SqlSize -le 0) {
        throw "database.sql is empty."
    }

    Write-Log "[OK] Database exported. Size: $SqlSize bytes"


    # --------------------------------------------------------
    # 4. COPY UPLOADS
    # --------------------------------------------------------

    Write-Log "[2/5] Copying Uploads..."

    $PackageUploads = Join-Path $PackageDir "Uploads"

    Copy-Item `
        -LiteralPath $UploadsDir `
        -Destination $PackageUploads `
        -Recurse `
        -Force


    $SourceCount = (
        Get-ChildItem $UploadsDir -Recurse -File
    ).Count

    $BackupCount = (
        Get-ChildItem $PackageUploads -Recurse -File
    ).Count


    Write-Log "Source Uploads files : $SourceCount"
    Write-Log "Backup Uploads files : $BackupCount"


    if ($SourceCount -ne $BackupCount) {
        throw "Uploads copy verification failed."
    }

    Write-Log "[OK] Uploads copied successfully."


    # --------------------------------------------------------
    # 5. CREATE ZIP
    # --------------------------------------------------------

    Write-Log "[3/5] Creating ZIP archive..."

    if (Test-Path $ZipFile) {
        Remove-Item $ZipFile -Force
    }

    Compress-Archive `
        -Path "$PackageDir\*" `
        -DestinationPath $ZipFile `
        -Force


    if (!(Test-Path $ZipFile)) {
        throw "ZIP archive was not created."
    }


    $ZipSize = (Get-Item $ZipFile).Length

    if ($ZipSize -le 0) {
        throw "ZIP archive is empty."
    }

    Write-Log "[OK] ZIP created. Size: $ZipSize bytes"


    # --------------------------------------------------------
    # 6. SHA-256
    # --------------------------------------------------------

    Write-Log "[4/5] Generating SHA-256 checksum..."

    $Hash = (
        Get-FileHash `
            -LiteralPath $ZipFile `
            -Algorithm SHA256
    ).Hash


    $Hash |
        Out-File `
            -LiteralPath $HashFile `
            -Encoding ascii `
            -NoNewline


    if (!(Test-Path $HashFile)) {
        throw "SHA-256 file was not created."
    }


    Write-Log "SHA256: $Hash"
    Write-Log "[OK] SHA-256 generated successfully."


    # --------------------------------------------------------
    # 7. UPLOAD CLOUD
    # --------------------------------------------------------

    Write-Log "[5/5] Uploading backup to Google Drive..."


    & $Rclone copyto `
        $ZipFile `
        "$RemotePath/$ZipName"

    if ($LASTEXITCODE -ne 0) {
        throw "Failed to upload ZIP to Google Drive."
    }


    & $Rclone copyto `
        $HashFile `
        "$RemotePath/$HashName"

    if ($LASTEXITCODE -ne 0) {

        # ZIP đã upload nhưng hash lỗi -> xóa ZIP để tránh backup không hoàn chỉnh

        & $Rclone deletefile "$RemotePath/$ZipName" 2>$null

        throw "Checksum upload failed. Incomplete Cloud ZIP removed."
    }


    Write-Log "[OK] Backup uploaded successfully."


    # --------------------------------------------------------
    # 8. CLOUD VERIFICATION
    # --------------------------------------------------------

    Write-Log "[VERIFY] Checking Cloud objects..."

    $CloudFiles = & $Rclone lsf `
        $RemotePath `
        --files-only

    if ($LASTEXITCODE -ne 0) {
        throw "Unable to verify Cloud backup."
    }


    if ($CloudFiles -notcontains $ZipName) {
        throw "Cloud ZIP verification failed."
    }

    if ($CloudFiles -notcontains $HashName) {
        throw "Cloud SHA-256 verification failed."
    }

    Write-Log "[OK] Cloud objects verified."


    # --------------------------------------------------------
    # 9. SUCCESS
    # --------------------------------------------------------

    $Stopwatch.Stop()

    $Duration = [Math]::Round(
        $Stopwatch.Elapsed.TotalSeconds,
        2
    )


    Write-Log "============================================================"
    Write-Log "[SUCCESS] CLOUD BACKUP COMPLETED"
    Write-Log "Backup   : $ZipName"
    Write-Log "Files    : $SourceCount"
    Write-Log "Size     : $ZipSize bytes"
    Write-Log "Duration : $Duration seconds"
    Write-Log "============================================================"


    Write-Host ""
    Write-Host "============================================================"
    Write-Host "[SUCCESS] CLOUD BACKUP COMPLETED"
    Write-Host "============================================================"
    Write-Host "Backup   : $ZipName"
    Write-Host "Cloud    : $RemotePath"
    Write-Host "Files    : $SourceCount"
    Write-Host "Duration : $Duration seconds"
    Write-Host "Log      : $LogFile"
    Write-Host "============================================================"


    Remove-Item $PackageDir -Recurse -Force
    Remove-Item $ZipFile -Force
    Remove-Item $HashFile -Force

    exit 0
}

catch {

    $Stopwatch.Stop()

    $Duration = [Math]::Round(
        $Stopwatch.Elapsed.TotalSeconds,
        2
    )

    $Message = $_.Exception.Message


    Write-Host ""
    Write-Host "============================================================"
    Write-Host "[ERROR] CLOUD BACKUP FAILED"
    Write-Host $Message
    Write-Host "Stopped after $Duration seconds."
    Write-Host "============================================================"


    if (Test-Path $LogDir) {

        Add-Content `
            -LiteralPath $LogFile `
            -Value "[$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')] [ERROR] $Message"

        Add-Content `
            -LiteralPath $LogFile `
            -Value "[$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')] Backup stopped after $Duration seconds."
    }


    exit 1
}