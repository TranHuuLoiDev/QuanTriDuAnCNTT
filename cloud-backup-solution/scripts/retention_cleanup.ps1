param(
    [int]$RetentionDays = 30,
    [switch]$Execute
)

$ErrorActionPreference = "Stop"

$ProjectDir = "C:\xampp\htdocs\QuanTriDuAnCNTT"
$Rclone = Join-Path $ProjectDir "cloud-backup-solution\scripts\rclone.exe"
$RemotePath = "mygdrive:Backup_QuanTriDuAn"

$Cutoff = (Get-Date).AddDays(-$RetentionDays)

Write-Host "============================================"
Write-Host "CLOUD BACKUP RETENTION POLICY"
Write-Host "Retention : $RetentionDays days"
Write-Host "Cutoff    : $Cutoff"
Write-Host "Mode      :" $(if ($Execute) { "EXECUTE" } else { "DRY RUN" })
Write-Host "============================================"

$Files = & $Rclone lsf $RemotePath --files-only

if ($LASTEXITCODE -ne 0) {
    throw "Unable to read Google Drive backup directory."
}

$Backups = @()

foreach ($File in $Files) {

    if ($File -match '^full_backup_(\d{8})_(\d{6})\.zip$') {

        $TimestampText = "$($Matches[1])_$($Matches[2])"

        $BackupDate = [datetime]::ParseExact(
            $TimestampText,
            "yyyyMMdd_HHmmss",
            $null
        )

        if ($BackupDate -lt $Cutoff) {

            $Backups += [PSCustomObject]@{
                Zip  = $File
                Hash = "$File.sha256"
                Date = $BackupDate
            }
        }
    }
}

if ($Backups.Count -eq 0) {

    Write-Host ""
    Write-Host "[OK] No expired backups found."
    exit 0
}

Write-Host ""

foreach ($Backup in $Backups) {

    Write-Host "[EXPIRED]" $Backup.Zip
    Write-Host "          Date:" $Backup.Date

    if ($Execute) {

        & $Rclone deletefile "$RemotePath/$($Backup.Zip)"

        if ($LASTEXITCODE -ne 0) {
            throw "Failed deleting $($Backup.Zip)"
        }

        & $Rclone deletefile "$RemotePath/$($Backup.Hash)"

        if ($LASTEXITCODE -ne 0) {
            throw "Failed deleting $($Backup.Hash)"
        }

        Write-Host "[DELETED] $($Backup.Zip)"
        Write-Host "[DELETED] $($Backup.Hash)"
    }
}

if (!$Execute) {

    Write-Host ""
    Write-Host "DRY RUN ONLY - Nothing was deleted."
    Write-Host "Use -Execute to apply retention policy."
}

Write-Host ""
Write-Host "[SUCCESS] Retention check completed."