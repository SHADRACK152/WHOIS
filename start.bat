@echo off
setlocal

cd /d "%~dp0"

where php >nul 2>nul
if errorlevel 1 (
  echo PHP was not found in PATH.
  echo Install PHP or add it to PATH, then run this file again.
  pause
  exit /b 1
)

echo Starting WHOIS PHP Backend on http://localhost:8000 ...
start "WHOIS PHP Server" php -S localhost:8000 -t public
timeout /t 2 /nobreak >nul
start "" http://localhost:8000/

endlocal