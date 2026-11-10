@echo off
chcp 65001 >nul
title SIPKA IDB Bali - Dev Server

set PHP="C:\Users\MyBook Hype AMD\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
set MARIADB="C:\Program Files\MariaDB 13.0\bin\mariadbd.exe"
set MYSQL="C:\Program Files\MariaDB 13.0\bin\mysql.exe"
set MARIADB_INI="C:\Program Files\MariaDB 13.0\data\my.ini"
set PROJECT="C:\Users\MyBook Hype AMD\Downloads\peminjaman kelas\idb-peminjaman-kelas"

echo ============================================
echo   SIPKA IDB Bali - Menjalankan Server Dev
echo ============================================
echo.

REM [1/3] Cek & nyalakan MariaDB (port 3306)
netstat -an | findstr ":3306" >nul
if %errorlevel% neq 0 (
    echo [1/3] Menyalakan MariaDB...
    start "MariaDB" /min %MARIADB% --defaults-file=%MARIADB_INI%
    timeout /t 4 /nobreak >nul
) else (
    echo [1/3] MariaDB sudah berjalan.
)

REM [2/3] Cek & nyalakan Reverb WebSocket (port 8081)
netstat -an | findstr ":8081" >nul
if %errorlevel% neq 0 (
    echo [2/3] Menyalakan Reverb (WebSocket realtime)...
    start "Reverb" /min cmd /c "cd /d %PROJECT% && %PHP% artisan reverb:start --host=127.0.0.1 --port=8081"
    timeout /t 3 /nobreak >nul
) else (
    echo [2/3] Reverb sudah berjalan.
)

REM [3/3] Cek & nyalakan Laravel (port 8000)
netstat -an | findstr ":8000" >nul
if %errorlevel% neq 0 (
    echo [3/3] Menyalakan server Laravel...
    start "SIPKA Laravel" /min cmd /c "cd /d %PROJECT% && %PHP% artisan serve --host=127.0.0.1 --port=8000"
    timeout /t 4 /nobreak >nul
) else (
    echo [3/3] Server Laravel sudah berjalan.
)

echo.
echo Membuka browser ke http://127.0.0.1:8000/login ...
start http://127.0.0.1:8000/login
echo.
echo Selesai! Tutup jendela ini kapan saja (server tetap berjalan di jendela lain).
pause
