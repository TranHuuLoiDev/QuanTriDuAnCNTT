@echo off
setlocal

set "PS_SCRIPT=%~dp0backup_full.ps1"

powershell.exe -NoProfile -ExecutionPolicy Bypass ^
    -File "%PS_SCRIPT%"

exit /b %ERRORLEVEL%