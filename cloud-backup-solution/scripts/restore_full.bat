@echo off
setlocal

set "PS_SCRIPT=%~dp0restore_full.ps1"

if "%~1"=="" (
    powershell.exe -NoProfile -ExecutionPolicy Bypass ^
        -File "%PS_SCRIPT%"
    exit /b %ERRORLEVEL%
)

if /I "%~3"=="--force" (
    powershell.exe -NoProfile -ExecutionPolicy Bypass ^
        -File "%PS_SCRIPT%" ^
        -TargetDb "%~1" ^
        -TargetUploads "%~2" ^
        -Force
) else (
    powershell.exe -NoProfile -ExecutionPolicy Bypass ^
        -File "%PS_SCRIPT%" ^
        -TargetDb "%~1" ^
        -TargetUploads "%~2"
)

exit /b %ERRORLEVEL%