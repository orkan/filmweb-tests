@echo off
setlocal

rem Grab some predefined phpunit switches
call env.inc.bat

rem -----------------------------------------------------------
set COMMAND=phpunit %PHPUNIT_COVERAGE_WHITELIST% %PHPUNIT_COVERAGE%
rem -----------------------------------------------------------

rem Copy COMMAND to clipboard
call clip.inc.bat "%COMMAND%"

rem clear coverag dir
rd /s /q %PHPUNIT_COVERAGE_DIR%

cmd /c %COMMAND%

echo.
pause
