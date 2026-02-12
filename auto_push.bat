@echo off
title AUTO GITHUB PUSHER (JANGAN DITUTUP)
echo Monitoring perubahan file... Tekan Ctrl+C untuk berhenti.
echo -------------------------------------------------------

:loop
:: 1. Tunggu 3 detik sebelum cek lagi (biar CPU tidak jebol)
timeout /t 3 >nul

:: 2. Coba add semua file
git add .

:: 3. Coba commit. 
:: >nul artinya menyembunyikan pesan jika tidak ada perubahan
git commit -m "Auto Update: %date% %time%" >nul 2>&1

:: 4. Cek apakah commit berhasil (artinya ada perubahan)
if %errorlevel% equ 0 (
    echo [!] Perubahan terdeteksi! Mengirim ke GitHub...
    git push origin main
    echo [v] Berhasil dikirim pada %time%
    echo -------------------------------------------------------
)

:: 5. Ulangi proses
goto loop