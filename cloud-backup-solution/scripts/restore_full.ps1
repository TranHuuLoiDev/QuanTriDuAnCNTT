param(
    [string]$TargetDb = "online_book_store_db",

    [string]$TargetUploads = "C:\xampp\htdocs\QuanTriDuAnCNTT\Uploads",

    [string]$RemotePath = "mygdrive:Backup_QuanTriDuAn",

    [switch]$Force
)

$ErrorActionPreference = "Stop"

# ============================================================
# CLOUD DISASTER RECOVERY - ONLINE BOOK STORE
# Google Drive -> SHA256 -> MySQL + Uploads
# ============================================================

$DbUser = "root"

$ProjectDir = "C:\xampp\htdocs\QuanTriDuAnCNTT"
$BackupRoot = Join-Path $ProjectDir "cloud-backup-solution"

$ScriptDir = Join-Path $BackupRoot "scripts"
$RestoreDir = Join-Path $BackupRoot "restore_temp"
$LogDir = Join-Path $BackupRoot "logs"

$Mysql = "C:\xampp\mysql\bin\mysql.exe"
$Rclone = Join-Path $ScriptDir "rclone.exe"


$Timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$LogFile = Join-Path $LogDir "restore_$Timestamp.log"

$Stopwatch = [System.Diagnostics.Stopwatch]::StartNew()


function Write-Log {
    param([string]$Message)

    $Line = "[$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')] $Message"

    Write-Host $Line
    Add-Content -LiteralPath $LogFile -Value $Line -Encoding UTF8
}


try {

    # --------------------------------------------------------
    # 1. PREPARE
    # --------------------------------------------------------

    New-Item -ItemType Directory -Force -Path $LogDir | Out-Null

    if (Test-Path $RestoreDir) {
        Remove-Item $RestoreDir -Recurse -Force
    }

    New-Item -ItemType Directory -Force -Path $RestoreDir | Out-Null


    Write-Log "============================================================"
    Write-Log "CLOUD DISASTER RECOVERY STARTED"
    Write-Log "Target Database: $TargetDb"
    Write-Log "Target Uploads : $TargetUploads"
    Write-Log "Cloud Remote   : $RemotePath"
    Write-Log "============================================================"


    # --------------------------------------------------------
    # 2. CHECK REQUIREMENTS
    # --------------------------------------------------------

    Write-Log "[CHECK] Checking required components..."

    if (!(Test-Path $Mysql)) {
        throw "mysql.exe not found: $Mysql"
    }

    if (!(Test-Path $Rclone)) {
        throw "rclone.exe not found: $Rclone"
    }

    Write-Log "[OK] Required components found."


    # --------------------------------------------------------
    # 3. FIND LATEST CLOUD BACKUP
    # --------------------------------------------------------

    Write-Log "[1/7] Searching latest backup on Google Drive..."

    $Output = & $Rclone lsf `
        $RemotePath `
        --files-only `
        --include "full_backup_*.zip" 2>&1

    if ($LASTEXITCODE -ne 0) {
        throw "Unable to read backup list from Google Drive: $Output"
    }

    $BackupFiles = $Output |
        ForEach-Object { $_.ToString().Trim() } |
        Where-Object {
            $_ -match '^full_backup_\d{8}_\d{6}\.zip$'
        }

    if (!$BackupFiles) {
        throw "No valid full backup ZIP found on Google Drive."
    }

    $LatestZip = $BackupFiles |
        Sort-Object -Descending |
        Select-Object -First 1

    $LatestHash = "$LatestZip.sha256"

    Write-Log "[OK] Latest backup found: $LatestZip"


    # --------------------------------------------------------
    # 4. DOWNLOAD BACKUP
    # --------------------------------------------------------

    Write-Log "[2/7] Downloading ZIP and SHA-256..."

    $ZipFile = Join-Path $RestoreDir $LatestZip
    $HashFile = Join-Path $RestoreDir $LatestHash

    & $Rclone copyto `
        "$RemotePath/$LatestZip" `
        "$ZipFile"

    if ($LASTEXITCODE -ne 0) {
        throw "Failed to download backup ZIP."
    }


    & $Rclone copyto `
        "$RemotePath/$LatestHash" `
        "$HashFile"

    if ($LASTEXITCODE -ne 0) {
        throw "Failed to download SHA-256 checksum."
    }


    if (!(Test-Path $ZipFile)) {
        throw "Downloaded ZIP file does not exist."
    }

    if (!(Test-Path $HashFile)) {
        throw "Downloaded checksum file does not exist."
    }

    Write-Log "[OK] Backup downloaded successfully."


    # --------------------------------------------------------
    # 5. VERIFY SHA256
    # --------------------------------------------------------

    Write-Log "[3/7] Verifying SHA-256 integrity..."

    $ExpectedHash = (Get-Content `
        -LiteralPath $HashFile `
        -Raw).Trim().ToUpperInvariant()

    if ([string]::IsNullOrWhiteSpace($ExpectedHash)) {
        throw "Checksum file is empty."
    }


    $ActualHash = (
        Get-FileHash `
            -LiteralPath $ZipFile `
            -Algorithm SHA256
    ).Hash.ToUpperInvariant()


    Write-Log "Expected SHA256: $ExpectedHash"
    Write-Log "Actual SHA256  : $ActualHash"


    if ($ExpectedHash -ne $ActualHash) {
        throw "SHA-256 mismatch. Restore cancelled for safety."
    }

    Write-Log "[OK] SHA-256 MATCH - Backup integrity verified."


    # --------------------------------------------------------
    # 6. EXTRACT
    # --------------------------------------------------------

    Write-Log "[4/7] Extracting backup..."

    $UnpackDir = Join-Path $RestoreDir "unpacked"

    Expand-Archive `
        -LiteralPath $ZipFile `
        -DestinationPath $UnpackDir `
        -Force


    $SqlFile = Join-Path $UnpackDir "database.sql"
    $SourceUploads = Join-Path $UnpackDir "Uploads"


    if (!(Test-Path $SqlFile)) {
        throw "database.sql is missing from backup."
    }

    if (!(Test-Path $SourceUploads)) {
        throw "Uploads folder is missing from backup."
    }

    Write-Log "[OK] Backup extracted successfully."


    # --------------------------------------------------------
    # 7. CONFIRM RESTORE
    # --------------------------------------------------------

    if (!$Force) {

        Write-Host ""
        Write-Host "============================================================"
        Write-Host "WARNING - RESTORE WILL REPLACE TARGET DATA"
        Write-Host "Database : $TargetDb"
        Write-Host "Uploads  : $TargetUploads"
        Write-Host "============================================================"

        $Confirm = Read-Host "Type RESTORE to continue"

        if ($Confirm -ne "RESTORE") {
            throw "Restore cancelled by user."
        }
    }


    # --------------------------------------------------------
    # 8. RESTORE MYSQL
    # --------------------------------------------------------

    Write-Log "[5/7] Restoring MySQL database..."

    & $Mysql `
        "-u$DbUser" `
        -e `
        "DROP DATABASE IF EXISTS $TargetDb; CREATE DATABASE $TargetDb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

    if ($LASTEXITCODE -ne 0) {
        throw "Unable to recreate target database."
    }


    $SqlForMysql = $SqlFile.Replace("\", "/")

    & $Mysql `
        "-u$DbUser" `
        $TargetDb `
        -e `
        "source $SqlForMysql"

    if ($LASTEXITCODE -ne 0) {
        throw "Unable to import database.sql."
    }

    Write-Log "[OK] Database restored successfully."


    # --------------------------------------------------------
    # 9. RESTORE UPLOADS
    # --------------------------------------------------------

    Write-Log "[6/7] Restoring Uploads..."

    if (Test-Path $TargetUploads) {
        Remove-Item $TargetUploads -Recurse -Force
    }

    New-Item `
        -ItemType Directory `
        -Force `
        -Path $TargetUploads |
        Out-Null


    & robocopy `
        $SourceUploads `
        $TargetUploads `
        /MIR `
        /R:1 `
        /W:1 `
        /NFL `
        /NDL `
        /NJH `
        /NJS `
        /NP |
        Out-Null


    # Robocopy 0-7 = success
    if ($LASTEXITCODE -ge 8) {
        throw "Unable to restore Uploads folder. Robocopy code: $LASTEXITCODE"
    }

    Write-Log "[OK] Uploads restored successfully."


    # --------------------------------------------------------
    # 10. VERIFY RESTORE
    # --------------------------------------------------------

    Write-Log "[7/7] Verifying restored files..."

    $SourceCount = (
        Get-ChildItem `
            -LiteralPath $SourceUploads `
            -Recurse `
            -File
    ).Count


    $TargetCount = (
        Get-ChildItem `
            -LiteralPath $TargetUploads `
            -Recurse `
            -File
    ).Count


    Write-Log "Backup file count  : $SourceCount"
    Write-Log "Restore file count : $TargetCount"


    if ($SourceCount -ne $TargetCount) {
        throw "Restored Uploads file count does not match backup."
    }

    Write-Log "[OK] File verification successful."


    # --------------------------------------------------------
    # SUCCESS
    # --------------------------------------------------------

    $Stopwatch.Stop()

    $RtoSeconds = [Math]::Round(
        $Stopwatch.Elapsed.TotalSeconds,
        2
    )


    Write-Log "============================================================"
    Write-Log "[SUCCESS] DISASTER RECOVERY COMPLETED"
    Write-Log "Backup used : $LatestZip"
    Write-Log "Database    : $TargetDb"
    Write-Log "Uploads     : $TargetUploads"
    Write-Log "RTO         : $RtoSeconds seconds"
    Write-Log "============================================================"


    Write-Host ""
    Write-Host "============================================================"
    Write-Host "[SUCCESS] DISASTER RECOVERY COMPLETED"
    Write-Host "============================================================"
    Write-Host "Backup used : $LatestZip"
    Write-Host "Database    : $TargetDb"
    Write-Host "Uploads     : $TargetUploads"
    Write-Host "RTO         : $RtoSeconds seconds"
    Write-Host "Log         : $LogFile"
    Write-Host "============================================================"

    exit 0
}

catch {

    $Stopwatch.Stop()

    $RtoSeconds = [Math]::Round(
        $Stopwatch.Elapsed.TotalSeconds,
        2
    )

    $Message = $_.Exception.Message

    Write-Host ""
    Write-Host "============================================================"
    Write-Host "[ERROR] $Message"
    Write-Host "Restore stopped after $RtoSeconds seconds."
    Write-Host "============================================================"

    if (Test-Path $LogDir) {

        Add-Content `
            -LiteralPath $LogFile `
            -Value "[$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')] [ERROR] $Message"

        Add-Content `
            -LiteralPath $LogFile `
            -Value "[$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')] Restore stopped after $RtoSeconds seconds."
    }

    exit 1
}