@echo off
setlocal

rem Grab some predefined phpunit switches
call env.inc.bat

rem ------ config ----------
set TESTSUITE=methods
rem ------------------------

echo.
echo PHPUnit TestSuite: %TESTSUITE%
echo =================

rem -----------------------------------------------------------
set COMMAND=phpunit --testdox --testsuite %TESTSUITE%
rem -----------------------------------------------------------

rem Copy COMMAND to clipboard
call clip.inc.bat "%COMMAND%"

echo.
rem /k - keep cmd window open
cmd /k %COMMAND%
echo.
