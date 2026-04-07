@echo off
title Human Rights Website Server

echo ============================================
echo   Human Rights ^& Social Work Website
echo ============================================
echo.

echo [Step 1] Starting MongoDB...
net start MongoDB >nul 2>&1
if %errorlevel%==0 (
    echo  MongoDB started successfully.
) else (
    echo  MongoDB is already running.
)
echo.

echo [Step 2] Starting PHP Web Server on port 8080...
echo  Server will start in a moment...
echo.

echo [Step 3] Open these URLs in your browser:
echo.
echo   Home      --^>  http://localhost:8080
echo   About     --^>  http://localhost:8080/about
echo   Blog      --^>  http://localhost:8080/blog
echo   Contact   --^>  http://localhost:8080/contact
echo   Admin     --^>  http://localhost:8080/admin/login
echo.
echo   Admin Email    : admin@humanrights.org
echo   Admin Password : Admin@1234
echo.
echo ============================================
echo   Press Ctrl+C to stop the server
echo ============================================
echo.

C:\xampp\php\php.exe -S localhost:8080 -t C:\xampp\htdocs\demo C:\xampp\htdocs\demo\router.php

pause
